<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\ProductReviewCollection;
use App\Http\Resources\V2\ReviewCollection;
use App\Models\ExternalReview;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Review;
use App\Traits\AmazonScrapperTrait;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use AmazonScrapperTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        $product = Product::find($request->product_id);
        $user = $request->user();


        //Check if product exist
        if (!$product) return response()->json(['result' => false, 'message' => translate('Product Not Found')]);

        //Check every product in the order is reviewable
        $reviewable = $this->isReviewable($product, $request, false);

        //Return if not $reviewable
        if (!$reviewable) return response()->json(['result' => false, 'message' => translate('You cannot review this product')], 400);

        //Store Review to db
        $review = $this->createReview($request, $user);

        //Update product rating
        $this->updateProductRating($product);

        //Update vendor rating
        if ($product->added_by == 'seller') $this->updateVendorRating($product, $review);

        return response()->json(['result' => true, 'message' => translate('Review  Submitted')]);
    }

    /**
     * @param $product
     * @param Request $request
     * @param bool $reviewable
     * @return bool
     */
    public function isReviewable($product, Request $request, bool $reviewable): bool
    {
        foreach ($product->orderDetails as $key => $orderDetail) {
            if ($orderDetail->order != null && $orderDetail->order->user_id == $request->user()->id  && Review::where('user_id', $request->user()->id)->where('product_id', $product->id)->first() == null) {
                $reviewable = true;
            }
        }
        return $reviewable;
    }

    /**
     * @param Request $request
     * @param $user
     * @return Review
     */
    public function createReview(Request $request, $user): Review
    {
        $review = new Review;
        $review->product_id = $request->product_id;
        $review->user_id = $user->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->viewed = 0;
        $review->save();
        return $review;
    }

    /**
     * @param $product
     * @return void
     */
    public function updateProductRating($product): void
    {
        $count = Review::where('product_id', $product->id)->where('status', 1)->count();
        if ($count > 0) {
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating') / $count;
        } else {
            $product->rating = 0;
        }
        $product->save();
    }


    /**
     * @param $product
     * @param Review $review
     * @return void
     */
    public function updateVendorRating($product, Review $review): void
    {
        $seller = $product->user->seller;
        $seller->rating = (($seller->rating * $seller->num_of_reviews) + $review->rating) / ($seller->num_of_reviews + 1);
        $seller->num_of_reviews += 1;
        $seller->save();
    }

    /**
     * @param Request $request
     * @return ReviewCollection
     */
    public function customerReviews(Request $request): ReviewCollection
    {
        //Get all customer reviews
        $reviews = Review::where('user_id', $request->user()->id)->where('status', 1)->orderBy('updated_at', 'desc')->paginate(10);
        return new ReviewCollection($reviews);
    }

    /**
     * @param Request $request
     */
    public function customerPendingReviews(Request $request)
    {
        $user = $request->user();
        $products = [];

        // Get all the products that the user has ordered
        $products = Product::whereHas('orderDetails.order.user', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->orderByDesc('id')->paginate(5);


        $pagination = [
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage()
        ];

        // Remove products that the user has already reviewed
        $products->getCollection()->transform(function ($product) use ($user) {
            $review = Review::where('user_id', $user->id)->where('product_id', $product->id)->exists();
            if ($review) {
                return null;
            }
            $orderCreatedAt = null;
            $quantity=null;
            $variation=null;
            $delivery_status=null;
            foreach ($product->orderDetails as $orderDetail) {
                if ($orderDetail->order->user_id == $user->id) {
                    $orderCreatedAt = $orderDetail->order->created_at;
                    $quantity = $orderDetail->quantity;
                    $variation = $orderDetail->variation;
                    $delivery_status = $orderDetail->delivery_status;

                    break;
                }
            }
            if (is_null($orderCreatedAt)) {
                return null;
            }
            $product->order_created_at = date('d-m-Y h:i A', strtotime($orderCreatedAt));
            $product->quantity = $quantity ;
            $product->variation = $variation ;
            $product->delivery_status = $delivery_status ;

            return $product;
        });

        // Filter out null values
        $products = $products->filter();

        return new ProductReviewCollection($products->values(),$pagination);
    }


    public function importReviews(Request $request)
    {
        $product_link = $request->product_link;
        $product_id = $request->product_id;
        $source = $request->source;
        $data = [];

        if ($source == 'amazon') {
            $data = $this->getAmazonReviews($product_link, $product_id);
        }


        if (count($data) > 0) {
            $asin = $data['asin'];
            $original_url = $data['original_url'];
            $external_url = $data['external_url'];
            $rating_counts = $data['rating_counts'];
            $overall_rating = $data['overall_rating'];
            $reviews_array = $data['reviews_array'];

            $external_review = ExternalReview::firstOrNew(['source' => $source, 'product_id' => $product_id]);
            $external_review->user_id = Auth::user()->id;
            $external_review->product_id = $product_id;
            $external_review->external_product_id = $asin;
            $external_review->original_product_url = $original_url;
            $external_review->external_product_url = $external_url;
            $external_review->source = $source;
            $external_review->reviews_counts = json_encode($rating_counts);
            $external_review->overall_rating = $overall_rating / count($reviews_array);
            $external_review->reviews = json_encode($reviews_array);
            $external_review->status = 'active';
            $external_review->total_count = count($reviews_array);
            $external_review->save();
        }


        return response()->json([
            'success' => true,
            'message' => translate('Reviews imported successfully')
        ], 200);
    }

    /**
     * @param $id
     * @return array
     */
    public function details($id): array
    {
        $products = [];
        $items = OrderDetail::where('order_id', $id)->get();
        foreach ($items as $key => $item) {
            //Check if the product exist
            if ($item->product) {
                //Set thumbnail image
                $thumbnail_image = $item->product->thumbnail_img;
                if (!$thumbnail_image || is_numeric($thumbnail_image)) $thumbnail_image = 'https://mytreety.s3.eu-central-1.amazonaws.com/products/default.png';

                //Get sustainabilities icons
                $sustainabilities = $this->getSustainabilities($item->product->sustainabilities);

                //Get products
                $products = $this->prepareProductsObject($item, $products, $key, $thumbnail_image, $sustainabilities);
            }
        }

        return $products;
    }


    /**
     * @param $orders
     * @param array $products_ids
     * @param array $products
     * @param $user
     * @return array
     */
    public function getPendingReviewProducts($orders, array $products_ids, array $products, $user): array
    {
        foreach ($orders as $key => $order) {
            $details = $order->orderDetails;
            foreach ($details as $key => $detail) {
                $products_ids[] = $detail->product_id;
                $products[] = $this->details($order->id);
            }
        }


        foreach ($products_ids as $key => $product_id) {
            $review = Review::where('user_id', $user->id)->where('product_id', $product_id)->first();
            $product_exist = Product::where('id', $product_id)->count();
            if ($review || $product_exist == 0) {
                unset($products[$key]);
            }
        }
        return $products;
    }

    public function delete($id, Request $request)
    {
        $review = Review::where('id', $id)->where('user_id', $request->user()->id)->first();

        if ($review) {
            $review->delete();
            return response()->json([
                'result' => true,
                'message' => 'Deleted'
            ], 200);
        }
        else{
            return response()->json([
                'result' => false,
                'message' => 'Review Not Found'
            ], 400);
        }
    }

    /**
     * @param $product_sustainabilities
     * @return array
     */
    public function getSustainabilities($product_sustainabilities): array
    {
        $sustainabilities = [];
        if ($product_sustainabilities) {

            foreach ($product_sustainabilities as $sustainability) {
                $sustainabilities[] = [
                    'id' => $sustainability->id,
                    'name' => $sustainability->getTranslation('name'),
                    'image' => uploaded_asset($sustainability->getTranslation('image'))
                ];
            }
        }
        return $sustainabilities;
    }

    /**
     * @param $item
     * @param array $products
     * @param $key
     * @param string $thumbnail_image
     * @param array $sustainabilities
     * @return array
     */
    public function prepareProductsObject($item, array $products, $key, string $thumbnail_image, array $sustainabilities): array
    {
        $products[$key]['id'] = $item->product->id;
        $products[$key]['name'] = $item->product->name;
        $products[$key]['slug'] = $item->product->slug;
        $products[$key]['qty'] = $item->quantity;
        $products[$key]['delivery_status'] = $item->delivery_status;
        $products[$key]['image'] = $thumbnail_image;
        $products[$key]['product_name'] = $item->product->name;
        $products[$key]['est_shipping_days'] = $item->product->est_shipping_days;
        $products[$key]['variation'] = $item->variation;
        $products[$key]['price'] = format_price($item->price);
        $products[$key]['sustainabilities'] = $sustainabilities;
        $products[$key]['date'] = date('d-m-Y h:i A', strtotime($item->created_at));
        return $products;
    }
}
