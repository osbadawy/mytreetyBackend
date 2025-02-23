<?php

namespace App\Http\Controllers;

use App\Models\Charity;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Auth;
use Cache;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CharityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $sort_search = null;
        $approved = null;
        $charities = Charity::paginate(20);

        return view('backend.charity.index', compact('charities', 'sort_search', 'approved'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $charity = Charity::findOrFail(decrypt($id));
        return view('backend.charity.edit', compact('charity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $charity = Charity::findOrFail($id);
        $charity->name = $request->name;
        $charity->save();

        $user = $charity->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if (strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            if ($charity->save()) {
                flash(translate('Charity has been updated successfully'))->success();
                return redirect()->route('charities.index');
            }
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function ban($id): RedirectResponse
    {
        $charity = Charity::findOrFail($id);

        if ($charity->user->banned == 1) {
            $charity->user->banned = 0;
            flash(translate('Charity has been unbanned successfully'))->success();
        } else {
            $charity->user->banned = 1;
            flash(translate('Charity has been banned successfully'))->success();
        }

        $charity->user->save();
        return back();
    }

    public function updateApproved(Request $request): int
    {
        $charity = Charity::findOrFail($request->id);
        $charity->verification_status = $request->status;
        if ($charity->save()) {
            Cache::forget('verified_sellers_id');
            return 1;
        }
        return 0;
    }

    public function profile_modal(Request $request)
    {
        $charity = Charity::findOrFail($request->id);
        return view('backend.charity.profile_modal', compact('charity'));
    }

    public function show_verification_request($id)
    {
        $charity = Charity::findOrFail($id);
        return view('backend.charity.verification', compact('charity'));
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id): RedirectResponse
    {
        $charity = Charity::findOrFail($id);


        User::destroy($charity->user->id);

        if (Charity::destroy($id)) {
            flash(translate('Charity has been deleted successfully'))->success();
            return redirect()->route('charities.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function approve_charity($id): RedirectResponse
    {
        $seller = Charity::findOrFail($id);
        $seller->verification_status = 1;
        if ($seller->save()) {
            Cache::forget('verified_charities_id');
            flash(translate('Charity has been approved successfully'))->success();
            return redirect()->route('charities.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function reject_charity($id): RedirectResponse
    {
        $seller = Charity::findOrFail($id);
        $seller->verification_status = 0;
        $seller->verification_info = null;
        if ($seller->save()) {
            Cache::forget('verified_charities_id');
            flash(translate('Charity verification request has been rejected successfully'))->success();
            return redirect()->route('charites.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }


}
