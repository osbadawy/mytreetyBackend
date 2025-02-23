<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommissionHistoryRequest;
use App\Http\Requests\UpdateTrackingCodeRequest;
use App\Http\Requests\VendorRankedProductsRequest;
use App\Http\Requests\VendorReviewsIndexRequest;
use App\Http\Requests\VendorUnRankedProductsRequest;
use App\Http\Resources\V2\OrderMiniCollection;
use App\Http\Resources\V2\OrderResource;
use App\Http\Resources\V2\ProductCollection;
use App\Mail\TrackingIDEmail;
use App\Models\CommissionHistory;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Auth;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mail;

class VendorController extends Controller
{


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $data = null;

        //Get vendor's total products
        $products = Product::where('user_id', Auth::user()->id)->latest()->take(10)->get();


        //Get vendor's total products count
        $total_products=Product::where('user_id', Auth::user()->id)->count();

        //Get vendor's total paid orders
        $seller_orders = Order::where('seller_id', Auth::user()->id)->where('payment_status', 'paid')->get();

        //Get vendor's delivered orders
        $total_sold = $seller_orders->where('delivery_status', 'delivered')->count();

        //Get vendor's total earn
        $total_earn = $this->getTotalEarn();

        //Get vendor's total orders count
        $total_orders = $seller_orders->count();

        //Get vendor's canceled and pending orders count
        $canceled_orders = $seller_orders->where('delivery_status', 'cancelled')->count();
        $pending_orders = $seller_orders->where('delivery_status', 'pending')->count();


        //Get vendor's total orders count by month
        $orderCountByMonth = $this->getOrderCountByMonth();

        //Prepare dashboard object
        $data['total_products'] = $total_products;
        $data['total_sold'] = $total_sold;
        $data['total_earning'] = format_price($total_earn);
        $data['successful_orders'] = $total_sold;
        $data['orders']['total_orders'] = $total_orders;
        $data['orders']['pending_orders'] = $pending_orders;
        $data['orders']['canceled_orders'] = $canceled_orders;
        $data['orders']['successful_orders'] = $total_sold;
        $data['sold_amount'] = json_decode($orderCountByMonth);
        $data['products'] = new ProductCollection($products);


        return response()->json(['data' => $data, 'success' => true, 'status' => 200]);
    }

    /**
     * @return mixed
     */
    public function getTotalEarn()
    {
        $total_earn = 0;
        $orderDetails = \App\Models\OrderDetail::where('seller_id', Auth::user()->id)->get();
        foreach ($orderDetails as $key => $orderDetail) {
            if ($orderDetail->order != null && $orderDetail->order->payment_status == 'paid') {
                $total_earn += $orderDetail->price;
            }
        }
        return $total_earn;
    }

    /**
     * @return string
     */
    public function getOrderCountByMonth(): string
    {
        return Order::where('seller_id', Auth::user()->id)->where('payment_status', 'paid')->selectRaw('DATE_FORMAT(created_at, "%b") as month, COUNT(*) as total')
            ->whereBetween('created_at', [date('Y') . "-01-01 00:00:00", date('Y') + 1 . '-01-01 00:00:00'])
            ->groupBy('month')
            ->orderByRaw('MONTH(created_at)')
            ->getQuery()
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->month => $row->total];
            })
            ->toJson();
    }

    public function vendorRankedProducts(VendorRankedProductsRequest $request): ProductCollection
    {

        // Initialize the order and sort variables
        $orderBy = $request->order ?? 'desc';
        $sortBy = $this->getSortBy($request);

        $query = Product::query()->where('user_id', $request->user()->id)->orderBy($sortBy, $orderBy);

        // If collection IDs are provided, filter the products by the collection IDs
        if ($request->collection_ids) {
            $collection_ids = explode(',', $request->collection_ids);
            $query->whereIn('collection_id', $collection_ids);
        } else {
            // Otherwise, filter the products by collection ID greater than 0
            $query->where('collection_id', '>', 0);
        }

        if ($request->has('search')) {
            $sort_search = $request->search;
            $query->where('name', 'like', '%' . $sort_search . '%');
        }

        // Execute the query and paginate the results
        $products = $query->paginate(20);

        //fix active and inactive products
        foreach ($products as $key => $product) {
            $product->published = 0;
            // If the product has a category and collection ID, set the published status to 1
            if ($product->category_id != null && $product->collection_id != null) $product->published = 1;

            // If the product has collection_id null set it to 0
            if ($product->collection_id == null) $product->published = 0;


            // Save the updated product
            $product->save();
        }


        return new ProductCollection($products);
    }

    /**
     * @param Request $request
     * @return mixed|string
     */
    public function getSortBy(Request $request)
    {
        $sort = $request->sort ?? 'created_at';

        // Map the sort values to the corresponding column names
        $sortMap = [
            'sustainability_ranking' => 'sustainability_rank',
            'active' => 'published',
            'date' => 'created_at',
        ];

        // If the sort value is in the map, use the mapped value
        if (isset($sortMap[$sort])) {
            $sort = $sortMap[$sort];
        }
        return $sort;
    }

    /**
     * @param VendorUnRankedProductsRequest $request
     * @return ProductCollection
     */
    public function vendorUnrankedProducts(VendorUnRankedProductsRequest $request): ProductCollection
    {
        // Initialize the order and sort variables
        $orderBy = $request->order ?? 'desc';
        $sortBy = $this->getSortBy($request);

        $user_id = $request->user()->id;

        $query = Product::where('user_id', $user_id)->orderBy($sortBy, $orderBy)->where('collection_id', 0);

        if ($request->has('search')) {
            $sort_search = $request->search;
            $query->where('name', 'like', '%' . $sort_search . '%');
        }


        $products = $query->paginate(20);

        return new ProductCollection($products);
    }

    /**
     * @param CommissionHistoryRequest $request
     * @return JsonResponse
     */
    public function commissionHistory(CommissionHistoryRequest $request): JsonResponse
    {

        // Set default values for the order and sort variables
        $order = $request->order ?? 'desc';
        $sort = $request->sort ?? 'created_at';

        // Initialize the query builder object
        $query = CommissionHistory::where('seller_id', '=', $request->user()->id)->orderBy($sort, $order);

        // If date range parameters are provided, filter the commission history by the date range
        if ($request->date_range_from && $request->date_range_to) {
            $query->whereBetween('created_at', [$request->date_range_from, $request->date_range_to]);
        }

        //Filter by payment status
        $this->filterByPaymentStatus($request->payment_status, $query);

        // Execute the query and paginate the results
        $commission_history = $query->paginate(10);

        // Iterate over the commission history
        foreach ($commission_history as $key => $history) {
            // If the history has an associated order, add the relevant data to the history object
            if ($history->order) {
                $history->order_code = $history->order->code;
                $history->admin_commission = '€' . $history->admin_commission;
                $history->seller_earning = '€' . $history->seller_earning;
                $history->download_url = route('api.vendor.invoice.download', $history->order->code);
            } else {
                $history->order_code = null;
            }
        }

        return response()->json(['data' => $commission_history, 'success' => true, 'status' => 200]);
    }

    /**
     * @param $payment_status
     * @param $query
     * @return void
     */
    public function filterByPaymentStatus($payment_status, $query): void
    {
        // If payment status parameter is provided, filter the commission history by payment status using a switch statement
        if ($payment_status) {
            switch ($payment_status) {
                case 'paid':
                    $query->where('payment_status', 1);
                    break;
                case 'unpaid':
                    $query->where('payment_status', 0);
                    break;
            }
        }
    }

    /**
     * @param Request $request
     * @return OrderMiniCollection
     */
    public function vendorOrders(Request $request): OrderMiniCollection
    {

        //Get all vendor paid orders
        $orders = Order::orderBy('id', 'desc')->where('seller_id', Auth::user()->id)->where('payment_status', 'paid')->distinct();

        //Filter orders
        $orders = $this->filterOrders($request, $orders);

        //Set all orders status to view
        $this->setOrdersAsViewed($orders);

        return new OrderMiniCollection($orders->latest()->paginate(20));
    }

    /**
     * @param Request $request
     * @param BuilderAlias $orders
     * @return BuilderAlias
     */
    public function filterOrders(Request $request, BuilderAlias $orders): BuilderAlias
    {
        // Filter orders by delivery status if provided
        $delivery_status = $request->delivery_status;
        $orders = $delivery_status ? $orders->where('delivery_status', $delivery_status) : $orders;

        // Filter orders by search term if provided
        $sort_search = $request->search;
        return $sort_search ? $orders->where('code', 'like', '%' . $sort_search . '%') : $orders;
    }

    /**
     * @param BuilderAlias $orders
     * @return void
     */
    public function setOrdersAsViewed(BuilderAlias $orders): void
    {
        foreach ($orders as $key => $value) {
            $order = Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }
    }

    /**
     * @param Request $request
     * @return OrderResource|JsonResponse
     */
    public function orderDetails(Request $request)
    {
        //Get vendor order details
        $order = Order::where('code', $request->order_code)->where('seller_id', $request->user()->id)->first();

        //Return if order not found
        if (!$order) return response()->json(['message' => 'Order not found', 'success' => false, 'status' => 404], 404);

        return new OrderResource($order);
    }

    /**
     * @param UpdateTrackingCodeRequest $request
     * @return JsonResponse
     */
    public function updateTrackingCode(UpdateTrackingCodeRequest $request): JsonResponse
    {

        // Find the order by code and seller ID
        $order = Order::where('code', $request->order_code)
            ->where('seller_id', $request->user()->id)
            ->first();

        // Return if order not found
        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
                'success' => false,
                'status' => 404
            ], 404);
        }

        // Update the tracking information for the order
        $order->tracking_code = $request->tracking_code;
        $order->tracking_carrier = $request->tracking_carrier;
        $order->save();

        // Prepare the email details
        $details = [
            'tracking_code' => $order->tracking_code,
            'tracking_carrier' => $order->tracking_carrier
        ];

        // Send the email to the customer
        try {
            Mail::to($order->user->email)->send(new TrackingIDEmail($details));
        } catch (\Exception $e) {
            // Log the error or do something else here
        }

        // Return success response
        return response()->json([
            'message' => 'Tracking info updated',
            'success' => true,
            'status' => 200
        ], 200);

    }

    /**
     * @param VendorReviewsIndexRequest $request
     * @return JsonResponse
     */
    public function vendorReviews(VendorReviewsIndexRequest $request): JsonResponse
    {

        $order = $request->order ?? 'desc';
        $sort = $request->sort ?? 'created_at';
        $sortColumn = $sort === 'product_name' ? 'created_at' : $sort;

        //Get vendor reviews
        $products = Product::where('user_id', $request->user()->id)->get()->pluck('id');
        $reviews = Review::whereIn('product_id', $products)->when($request->stars, function ($query) use ($request) {
            return $query->whereIn('rating', explode(',', $request->stars));
        })->orderBy($sortColumn, $order)->when($request->date_range_from && $request->date_range_to, function ($query) use ($request) {
            return $query->whereBetween('created_at', [$request->date_range_from, $request->date_range_to]);
        })->paginate(9);

        foreach ($reviews as $key => $value) {
            $review = $this->setReviewAsSeen($value);

            $value->product_name = $review->product->name;
            $value->product_image = $review->product->thumbnail_img;
        }

        return response()->json(['data' => $reviews, 'success' => true, 'status' => 200], 200);

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function reviewDetails($id): JsonResponse
    {

        //Get review details
        $review = Review::findOrfail($id);

        //Return if review not found
        if ($review->product->user_id != Auth::user()->id) return response()->json(['message' => 'Review not found', 'success' => false, 'status' => 404], 404);

        return response()->json(['data' => $review, 'success' => true, 'status' => 200], 200);
    }

    /**
     * @param $value
     * @return Review|Review[]|Collection|Model|null
     */
    public function setReviewAsSeen($value)
    {
        $review = Review::find($value->id);
        $review->viewed = 1;
        $review->save();
        return $review;
    }
}
