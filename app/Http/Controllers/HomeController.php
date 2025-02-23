<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Models\Subscriber;
use NZTim\Mailchimp\Mailchimp;
use NZTim\Mailchimp\MailchimpFacade;

class HomeController extends Controller
{

    /**
     * Show the application home.
     *
     */
    public function index(Request $request)
    {
        return redirect('/admin');

    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function unsubscribe(Request $request)
    {
        $email=\Crypt::decryptString($request->user);
        Subscriber::where('email', $email)->delete();

        $listID='f6a3fd1b4b';

        MailchimpFacade::unsubscribe($listID, $email);

        flash(translate('You have unsubscribed successfully'))->success();

        return redirect('/');

    }



}
