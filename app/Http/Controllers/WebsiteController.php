<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function header(Request $request)
	{
		return view('backend.website_settings.header');
	}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function footer(Request $request)
	{
		$lang = $request->lang;
		return view('backend.website_settings.footer', compact('lang'));
	}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function pages(Request $request)
	{
		return view('backend.website_settings.pages.index');
	}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function appearance(Request $request)
	{
		return view('backend.website_settings.appearance');
	}
}
