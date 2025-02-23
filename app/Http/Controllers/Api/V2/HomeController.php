<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\NewsletterSubscribeRequest;
use App\Mail\ContactEmail;
use App\Mail\SubscribeEmail;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Mail;
use NZTim\Mailchimp\MailchimpFacade;

class HomeController extends Controller
{


    /**
     * @param NewsletterSubscribeRequest $request
     * @return JsonResponse
     */
    public function subscribe(NewsletterSubscribeRequest $request): JsonResponse
    {


        $listID = env('MAILCHIMP_LIST_ID', 'f6a3fd1b4b');

        $email = $request->email;

        //Check if the email is already a subscriber
        $subscriber = Subscriber::where('email', $email)->first();
        if ($subscriber) {
            return response()->json(['message' => translate('You are  already a subscriber'), 'success' => false,], 400);
        }

        //Send the email to Mailchimp
        $this->SubscribeMailchimp($listID, $email);

        //Save the email to the database
        $this->saveTheEmailToTheDatabase($email);

        //Send confirm subscribe email
        Mail::to($request->email)->send(new SubscribeEmail($email));

        return response()->json([
            'message' => 'You have subscribed successfully', 'success' => true
        ]);

    }



    /**
     * @param string $listID
     * @param $email
     * @return JsonResponse
     */
    public function SubscribeMailchimp(string $listID, $email): JsonResponse
    {
        try {
            MailchimpFacade::subscribe($listID, $email, $merge = [], $confirm = false);
            return response()->json([
                'message' => 'Subscribed!', 'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Please enter valid email!', 'success' => false
            ], 400);
        }
    }

    /**
     * @param $email
     * @return void
     */
    public function saveTheEmailToTheDatabase($email): void
    {
        $subscriber = new Subscriber();
        $subscriber->email = $email;
        $subscriber->save();
    }

    /**
     * @param ContactFormRequest $request
     * @return JsonResponse
     */
    public function contactPost(ContactFormRequest $request): JsonResponse
    {
        //Set receiver email
        $to_email = env('CONTACT_EMAIL', 'hello@mytreety.com');

        //Send email to receiver
        $this->sendEmail($request, $to_email);

        return response()->json(['message' => translate('Thank you, Message sent successfully'), 'success' => false, 'status' => 200], 200);
    }

    /**
     * @param ContactFormRequest $request
     * @param $to_email
     * @return void
     */
    public function sendEmail(ContactFormRequest $request, $to_email): void
    {
        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'body' => $request->message,
            'subject' => $request->subject
        ];

        \Mail::to($to_email)->send(new ContactEmail($details));
    }
}
