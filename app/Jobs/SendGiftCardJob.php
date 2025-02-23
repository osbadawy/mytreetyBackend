<?php

namespace App\Jobs;

use App\Mail\GiftCardMail;
use App\Models\GiftCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendGiftCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $giftcard_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($giftcard_id)
    {
        $this->giftcard_id= $giftcard_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $giftcard=GiftCard::where('id',$this->giftcard_id)->where('is_used',0)->where('is_paid',1)->first();

        if($giftcard){
            $details=
            [
                'desgin'=>$giftcard->desgin,
                'code'=> $giftcard->code,
                'signature'=>$giftcard->signature,
                'subject'=>$giftcard->subject,
                'message'=>$giftcard->message,
                'sender_name' => $giftcard->user->name
            ];

            Mail::to($giftcard->email)->send(new GiftCardMail($details));
        }
    }
}
