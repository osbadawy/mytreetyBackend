<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCharityInvoiceRequest;
use App\Http\Requests\ListCharityInvoiceRequest;
use App\Http\Resources\V2\CharityCollection;
use App\Models\Charity;
use App\Models\CharityInvoice;
use App\Models\Order;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharityController extends Controller
{


    /**
     * @return CharityCollection
     */
    public function index(): CharityCollection
    {
        //Get all verified charities
        return new CharityCollection(Charity::where('verification_status', 1)->paginate(10));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        //Check if user is charity
        if (!Auth::user()->charity) {
            return response()->json(['message' => translate('You are not a charity')], 403);
        }

        $data = null;
        $pending_donation = round(Auth::user()->charity->left_earned, 2);
        $total_donation = round(Auth::user()->charity->total_earned, 2);

        //Calculate received donation
        $received_donation = $this->calculateReceivedDonation($total_donation, $pending_donation);

        //Get recent invoices
        $recent_invoices = $this->getRecentInvoices();

        //Calculate recent invoices
        $orderCountByMonth = $this->getOrderCountByMonth();

        //Prepare return object
        $data['pending_donation'] = $pending_donation . '€';
        $data['received_donation'] = abs($received_donation) . '€';
        $data['total_donation'] = $total_donation . '€';
        $data['donationsCountByMonth'] = json_decode($orderCountByMonth);
        $data['recent_invoices'] = $recent_invoices;


        return response()->json(['data' => $data], 200);
    }

    /**
     * @param float $total_donation
     * @param float $pending_donation
     * @return float
     */
    public function calculateReceivedDonation(float $total_donation, float $pending_donation): float
    {
        return $total_donation - $pending_donation;
    }

    /**
     * @return Collection
     */
    public function getRecentInvoices(): Collection
    {
        return CharityInvoice::where('charity_id', Auth::user()->charity->id)->orderBy('created_at', 'desc')->take(10)->get();
    }

    /**
     * @return string
     */
    public function getOrderCountByMonth(): string
    {
        return Order::where('charity', Auth::user()->id)->selectRaw('DATE_FORMAT(created_at, "%b") as month, COUNT(*) as total')
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

    /**
     * @param CreateCharityInvoiceRequest $request
     * @return JsonResponse
     *
     */
    public function charity_invoices_store(CreateCharityInvoiceRequest $request): JsonResponse
    {
        //Create new charity invoice
        ;
        $invoice = new CharityInvoice;
        $invoice->code = $this->generateInvoiceCode();
        $invoice->charity_id = Auth::user()->charity->id;
        $invoice->details = $request->details;
        $invoice->file = $request->attachments;
        $invoice->save();

        return response()->json(['message' => translate('Invoice Uploaded')], 200);
    }

    /**
     * @return string
     */
    public function generateInvoiceCode(): string
    {
        return max(900000, (CharityInvoice::latest()->first() != null ? CharityInvoice::latest()->first()->code + 1 : 0)) . date('s');
    }

    /**
     * @param ListCharityInvoiceRequest $request
     * @return JsonResponse
     */
    public function charity_invoices_index(ListCharityInvoiceRequest $request): JsonResponse
    {
        //Set sorting setting or default
        $request->order ? $order = $request->order : $order = 'desc';
        $request->sort ? $sort = $request->sort : $sort = 'created_at';

        //Get charity invoices
        $invoices = CharityInvoice::where('charity_id', Auth::user()->charity->id)->orderBy($sort, $order)->paginate(10);

        return response()->json(['data' => $invoices], 200);
    }
}
