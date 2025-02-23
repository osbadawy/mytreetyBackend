<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\RedeemCodeRequest;
use App\Http\Requests\StoreGiftCardRequest;
use App\Models\CombinedOrder;
use App\Models\GiftCard;
use App\Traits\PaymentTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PayPalHttp\IOException;
use Stripe\Exception\ApiErrorException;

class GiftCardController extends Controller
{
    use PaymentTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        //Get all redeemed gift card for a customer
        $gift_cards = GiftCard::where('email', $request->user()->email)->where('is_used', 1)->paginate(5);

        return response()->json(['data' => $gift_cards], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sent(Request $request): JsonResponse
    {
        //Get all sent gift card for a customer
        $gift_cards = GiftCard::where('user_id', $request->user()->id)->paginate(5);

        return response()->json(['data' => $gift_cards], 200);
    }


    /**
     * @param RedeemCodeRequest $request
     * @return JsonResponse
     */
    public function RedeemCode(RedeemCodeRequest $request): JsonResponse
    {
        $code = $request->code;
        $user = $request->user();

        //Check if redeem code exist
        $coupon = GiftCard::where('code', $code)->where('is_used', '!=', 1)->first();
        if (!$coupon) {
            return response()->json(['message' => translate('Gift Card does not exist!')], 400);
        }

        //Add balance to user
        $this->addBalance($user, $coupon);

        //Set coupon status to used
        $coupon->is_used = 1;
        $coupon->save();

        return response()->json(['message' => translate('Successfully redeemed')], 200);
    }

    /**
     * @throws IOException
     * @throws ApiErrorException
     */
    public function store(StoreGiftCardRequest $request): JsonResponse
    {
        $user_id = $request->user()->id;
        $amount = $request->amount;

        //Create Combined Order
        $combined_order = $this->createCombinedOrder($user_id, $amount);

        //Create new gift card
        $this->createNewGiftCard($request, $user_id, $combined_order);

        //Get payment link for the gift card
        $return_url = env('FRONTEND_URL', 'https://mytreety.com');
        $success_url = "$return_url/thank-you";
        $cancel_url = "$return_url/cancel";
        $payment_url = $this->getPaymentUrl($request->payment_option, $combined_order, $success_url, $cancel_url);


        return response()->json(['url' => $payment_url, 'message' => translate('Your request has been placed successfully'), 'success' => true, 'status' => 200], 200);
    }

    /**
     * @param $user
     * @param $coupon
     * @return void
     */
    public function addBalance($user, $coupon): void
    {
        $user->balance = $user->balance + $coupon->amount;
        $user->save();
    }

    /**
     * @param Request $request
     * @param $user_id
     * @param CombinedOrder $combined_order
     * @return void
     */
    public function createNewGiftCard(Request $request, $user_id, CombinedOrder $combined_order): void
    {
        $gift_card = new GiftCard;
        $gift_card->desgin = $request->desgin;
        $gift_card->amount = $request->amount;
        $gift_card->email = $request->email;
        $gift_card->delivary_date = $request->delivary_date;
        $gift_card->signature = $request->signature;
        $gift_card->subject = $request->subject;
        $gift_card->message = $request->message;
        $gift_card->user_id = $user_id;
        $gift_card->code = $this->createGiftcardCode();
        $gift_card->is_used = 0;
        $gift_card->combined_order_id = $combined_order->id;
        $gift_card->save();
    }

    /**
     * @param $user_id
     * @param $total_price
     * @return CombinedOrder
     */
    public function createCombinedOrder($user_id, $total_price): CombinedOrder
    {
        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user_id;
        $combined_order->type_id = 2;
        $combined_order->grand_total = $total_price;
        $combined_order->save();
        return $combined_order;
    }

    public function createGiftcardCode()
    {
        // Set the character pool for the code
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Set the length of the code
        $code_length = 16;

        // Get the collection of existing gift card codes
        $giftcards = GiftCard::all();

        // Initialize a variable to store the generated code
        $code = '';

        // Generate a code
        for ($j = 0; $j < $code_length; $j++) {
            $code .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        // Check if the generated code already exists in the collection of gift card codes
        while ($giftcards->contains('code', $code)) {
            $code = '';
            for ($j = 0; $j < $code_length; $j++) {
                $code .= $characters[mt_rand(0, strlen($characters) - 1)];
            }
        }

        // return the generated code
        return $code;
    }
}
