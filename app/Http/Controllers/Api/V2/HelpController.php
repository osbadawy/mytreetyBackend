<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\faq;
use App\Models\guide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class HelpController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function vendor_help(Request $request): JsonResponse
    {
        $data = null;

        //Get all vendor FAQ
        $faqs = faq::where('type', 1)->get();
        $this->setTranslation($faqs);

        //Get all vendor guides
        $guides = guide::where('type', 1)->get();
        $this->setTranslation($guides);

        //Prepare return object
        $data['guides'] = $guides;
        $data['faqs'] = $faqs;

        return response()->json(['data' => $data], 200);

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function charity_help(Request $request): JsonResponse
    {
        $data = null;

        //Get all charity FAQ
        $faqs = faq::where('type', 2)->get();
        $this->setTranslation($faqs);

        //Get all charity guides
        $guides = guide::where('type', 2)->get();
        $this->setTranslation($guides);

        //Prepare return object
        $data['guides'] = $guides;
        $data['faqs'] = $faqs;

        return response()->json(['data' => $data], 200);

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function customer_help(Request $request): JsonResponse
    {
        $data = null;

        //Get all customer FAQ
        $ordersFaqs = faq::where('type', 3)->get();
        $paymentFaqs = faq::where('type', 4)->get();
        $refundFaqs = faq::where('type', 5)->get();
        $accountFaqs = faq::where('type', 6)->get();
        $shippingFaqs = faq::where('type', 7)->get();
        $socialFaqs = faq::where('type', 8)->get();
        $partnerFaqs = faq::where('type', 9)->get();

        //Get translations
        $this->getTranslations($ordersFaqs, $paymentFaqs, $refundFaqs, $accountFaqs, $shippingFaqs, $socialFaqs, $partnerFaqs);


        //Prepare object
        $data['Orders'] = $ordersFaqs;
        $data['Payment'] = $paymentFaqs;
        $data['Returns & Refund'] = $refundFaqs;
        $data['Account'] = $accountFaqs;
        $data['Shipping & Delivery'] = $shippingFaqs;
        $data['Social & Environmental Impact'] = $socialFaqs;
        $data['Partner with Mytreety'] = $partnerFaqs;

        return response()->json(['data' => $data], 200);

    }

    /**
     * @param $collection
     * @return void
     */
    public function setTranslation($collection): void
    {
        foreach ($collection as $key => $item) {
            $item->title = $item->getTranslation('title');
            $item->sub_title = $item->getTranslation('sub_title');
        }
    }

    /**
     * @param $ordersFaqs
     * @param $paymentFaqs
     * @param $refundFaqs
     * @param $accountFaqs
     * @param $shippingFaqs
     * @param $socialFaqs
     * @param $partnerFaqs
     * @return void
     */
    public function getTranslations($ordersFaqs, $paymentFaqs, $refundFaqs, $accountFaqs, $shippingFaqs, $socialFaqs, $partnerFaqs): void
    {
        $this->setTranslation($ordersFaqs);
        $this->setTranslation($paymentFaqs);
        $this->setTranslation($refundFaqs);
        $this->setTranslation($accountFaqs);
        $this->setTranslation($shippingFaqs);
        $this->setTranslation($socialFaqs);
        $this->setTranslation($partnerFaqs);
    }
}
