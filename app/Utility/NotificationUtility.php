<?php

namespace App\Utility;

use App\Mail\InvoiceEmailManager;
use App\Models\User;
use Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use App\Models\FirebaseNotification;

class NotificationUtility
{
    public static function sendOrderPlacedNotification($order, $request = null)
    {
        //sends email to customer with the invoice pdf attached
        $array['view'] = 'emails.invoice';
        $array['subject'] = translate('Bestellungsbestätigung Mytreety') . ' - ' . $order->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['order'] = $order;
        $adminEmail = env('ADMIN_EMAIL');

        //sends email to vendor with the invoice pdf attached
        $array_vendor['view'] = 'emails.invoice_vendor';
        $array_vendor['subject'] = translate('Sie haben eine Bestellung! (You have an order!)') . ' - ' . $order->code;
        $array_vendor['from'] = env('MAIL_FROM_ADDRESS');
        $array_vendor['order'] = $order;

        try {
            // customer
             Mail::to($order->user->email)->bcc($adminEmail,'New Customer Order Confirmation')->queue(new InvoiceEmailManager($array));
            // vendor
             Mail::to($order->orderDetails->first()->product->user->email)->cc($adminEmail,'New Vendor Order Confirmation')->queue(new InvoiceEmailManager($array_vendor));


        } catch (\Exception $e) {
               dd($e);
        }


        //sends Notifications to user
        self::sendNotification($order, 'placed');
        if ($request != null && get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order placed !";
            $request->text = "An order {$order->code} has been placed";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            self::sendFirebaseNotification($request);
        }
    }
    public static function sendGifCardPlacedNotification($order, $request = null)
    {
        //sends email to customer with the invoice pdf attached
        $array['view'] = 'emails.invoice';
        $array['subject'] = translate('Bestellungsbestätigung Mytreety') . ' - ' . $order->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['order'] = $order;
        $adminEmail = 'hello@mytreety.com';

        //sends email to vendor with the invoice pdf attached
        $array_vendor['view'] = 'emails.invoice_vendor';
        $array_vendor['subject'] = translate('Sie haben eine Bestellung! (You have an order!)') . ' - ' . $order->code;
        $array_vendor['from'] = env('MAIL_FROM_ADDRESS');
        $array_vendor['order'] = $order;

        try {
            // customer
            // Mail::to($order->user->email)->bcc($adminEmail,'Order confirmation')->queue(new InvoiceEmailManager($array));
            // vendor
            // Mail::to($order->orderDetails->first()->product->user->email)->cc($adminEmail,'Order confirmation')->queue(new InvoiceEmailManager($array_vendor));


        } catch (\Exception $e) {
            //   dd($e);
        }


        //sends Notifications to user
        self::sendNotification($order, 'placed');
        if ($request != null && get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order placed !";
            $request->text = "An order {$order->code} has been placed";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            self::sendFirebaseNotification($request);
        }
    }

    public static function sendNotification($order, $order_status)
    {
        if ($order->seller_id == \App\Models\User::where('user_type', 'admin')->first()->id) {
            $users = User::findMany([$order->user->id, $order->seller_id]);
        } else {
            $users = User::findMany([$order->user->id, $order->seller_id, \App\Models\User::where('user_type', 'admin')->first()->id]);
        }

        $order_notification = array();
        $order_notification['order_id'] = $order->id;
        $order_notification['order_code'] = $order->code;
        $order_notification['user_id'] = $order->user_id;
        $order_notification['seller_id'] = $order->seller_id;
        $order_notification['status'] = $order_status;

        Notification::send($users, new OrderNotification($order_notification));
    }

    public static function sendFirebaseNotification($req)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'to' => $req->device_token,
            'notification' => [
                'body' => $req->text,
                'title' => $req->title,
                'sound' => 'default' /*Default sound*/
            ],
            'data' => [
                'item_type' => $req->type,
                'item_type_id' => $req->id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ]
        );

        //$fields = json_encode($arrayToSend);
        $headers = array(
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        curl_close($ch);

        $firebase_notification = new FirebaseNotification;
        $firebase_notification->title = $req->title;
        $firebase_notification->text = $req->text;
        $firebase_notification->item_type = $req->type;
        $firebase_notification->item_type_id = $req->id;
        $firebase_notification->receiver_id = $req->user_id;

        $firebase_notification->save();
    }
}
