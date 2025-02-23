<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Charity;
use App\Models\Seller;
use App\Models\Upload;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Storage;

class ProfileController extends Controller
{

    /**
     * @param $password
     * @param $user
     * @return void
     */
    public function savePassword($password, $user): void
    {
        $user->password = Hash::make($password);
        $user->update();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = User::find($request->user()->id);

        //Update first name
        if ($request->name != "") $user->name = $request->name;

        //Update last name
        if ($request->last_name != "") $user->last_name = $request->last_name;

        //Update display_name
        if ($request->display_name != "") $user->displayname = $request->display_name;

        //Update Avatar
        if ($user->avatar_original) $user->avatar_original = $request->avatar_original;

        $user->save();

        //Update Password
        if ($request->current_password != "" && $request->new_password != "") return $this->changeUserPassword($user, $request);

        return response()->json([
            'result' => true,
            'message' => translate("Profile information updated")
        ]);
    }

    /**
     * @param $user
     * @param Request $request
     * @return JsonResponse
     */
    public function changeUserPassword($user, Request $request): JsonResponse
    {
        $hashedPassword = $user->password;

        if (!\Hash::check($request->current_password, $hashedPassword)) {
            return response()->json(['success' => false, 'message' => 'Old password doesnt matched'], 400);
        }
        if (\Hash::check($request->new_password, $hashedPassword)) {
            return response()->json(['success' => false, 'message' => 'New password can not be the old password!'], 400);
        }

        $this->savePassword($request->new_password, $user);

        return response()->json(['success' => true, 'message' => 'Password Changed'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function charity_update(Request $request): JsonResponse
    {

        $charity = Charity::where('user_id', $request->user()->id)->first();
        $data = [];

        //Check if charity exist
        if (!$charity) return response()->json(['result' => false, 'message' => translate('Charity not found'),], 400);


        if ($charity->verification_info == null || $charity->verification_info == "[]") {
            //If charity has no verification info create new

            // Create legal Name
            if ($request->legal_name) {
                $item = $this->createItemObject('text', 'Legal Name of Charity (As per Legal Registration)', $request->legal_name);
                $data[] = $item;
            }
            // Create charity name
            if ($request->charity_name) {
                $item = $this->createItemObject('text', 'Charity Name (shown to customer)', $request->charity_name);
                $data[] = $item;
            }
            // Create charity website
            if ($request->website) {
                $item = $this->createItemObject('text', 'Website / e-store', $request->website);
                $data[] = $item;
            }
            // Create charity email
            if ($request->email) {

                $item = $this->createItemObject('text', 'Charity Email Address', $request->email);
                $data[] = $item;
            }
            // Create registration_number
            if ($request->registration_number) {
                $item = $this->createItemObject('text', 'Company registration number', $request->registration_number);
                $data[] = $item;
            }
            // Create tax number
            if ($request->tax_number) {
                $item = $this->createItemObject('text', 'Tax number', $request->tax_number);
                $data[] = $item;
            }
            // Create vat id
            if ($request->vat_id) {
                $item = $this->createItemObject('text', 'German/EU VAT ID or OSS number', $request->vat_id);
                $data[] = $item;
            }
            // Create person name
            if ($request->person_name) {
                $item = $this->createItemObject('text', 'Person in charge full name', $request->person_name);
                $data[] = $item;
            }
            // Create person email
            if ($request->person_email) {
                $item = $this->createItemObject('text', 'Person in charge Email', $request->person_email);
                $data[] = $item;
            }
            // Create person phone
            if ($request->person_phone) {
                $item = $this->createItemObject('text', 'Person in charge phone number', $request->person_phone);
                $data[] = $item;
            }
            // Create description
            if ($request->description) {
                $item = $this->createItemObject('text', 'Chairty Description (shown to customer)', $request->person_phone);
                $data[] = $item;
            }
            $charity->verification_info = json_encode($data);
        } else {
            //If charity has verification info update it

            $verification_info = json_decode($charity->verification_info);$verification_info = json_decode($charity->verification_info);
            $labels = [
              'Person in charge full name',
              'Charity Name (shown to customer)',
              'Charity Email Address',
              'Company registration number',
              'Tax number',
              'German/EU VAT ID or OSS number',
              'Person in charge full name',
              'Person in charge Email',
              'Chairty Description (shown to customer)'
            ];

            // Add missing labels to verification_info array
            $verification_info = array_reduce($labels, function ($acc, $label) use ($verification_info) {
              if (!array_search($label, $verification_info)) {
                $acc[] = (object) [
                  'type' => 'text',
                  'label' => $label,
                  'value' => null
                ];
              }
              return $acc;
            }, $verification_info);

            // Update verification_info array with input data
            $verification_info = array_map(function ($item) use ($request) {
              switch ($item->label) {
                case 'Person in charge full name':
                  $item->value = $request->person_name ?? $item->value;
                  break;
                case 'Charity Name (shown to customer)':
                  $item->value = $request->charity_name ?? $item->value;
                  break;
                case 'Charity Email Address':
                  $item->value = $request->email ?? $item->value;
                  break;
                case 'Company registration number':
                  $item->value = $request->registration_number ?? $item->value;
                  break;
                case 'Tax number':
                  $item->value = $request->tax_number ?? $item->value;
                  break;
                case 'German/EU VAT ID or OSS number':
                  $item->value = $request->vat_id ?? $item->value;
                  break;
                case 'Person in charge Email':
                  $item->value = $request->person_email ?? $item->value;
                  break;
                case 'Person in charge phone number':
                  $item->value = $request->person_phone ?? $item->value;
                  break;
                case 'Chairty Description (shown to customer)':
                  $item->value = $request->description ?? $item->value;
                  break;
              }
              return $item;
            }, $verification_info);

            // Update charity object with updated verification_info
            $charity->verification_info = json_encode($verification_info);

        }

        // Update charity name
        if ($request->charity_name) $charity->name = $request->charity_name;

        // Update charity description
        if ($request->description) $charity->operations = $request->description;

        // Update charity bank account name
        if ($request->bank_acc_name) $charity->bank_acc_name=$request->bank_acc_name;

        // Update charity bank account number
        if ($request->bank_acc_no) $charity->bank_acc_no=$request->bank_acc_no;

        // Update charity bank account iban
        if ($request->bank_iban) $charity->bank_iban=$request->bank_iban;

        // Update charity bank bank name
        if ($request->bank_name) $charity->bank_name=$request->bank_name;

        // Update charity paypal account
        if ($request->paypal_account) $charity->paypal_account=$request->paypal_account;

        $charity->save();

        $user = Auth::user();

        // Update charity logo
        if ($request->logo) $user->avatar_original = $request->logo;

        // Update charity website
        if ($request->website) $user->url = $request->website;

        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Profile Updated'),
        ], 201);
    }

    /**
     * @param string $text
     * @param string $label
     * @param $value
     * @return array
     */
    public function createItemObject(string $text, string $label, $value): array
    {
        $item = array();
        $item['type'] = $text;
        $item['label'] = $label;
        $item['value'] = $value;
        return $item;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function vendor_update(Request $request): JsonResponse
    {

        $seller = Seller::where('user_id', $request->user()->id)->first();
        $data = [];

        //Check if vendor exist
        if (!$seller) return response()->json(['result' => false, 'message' => translate('Vendor not found'),], 400);

        $verification_info = json_decode($seller->verification_info);


        if ($verification_info == null || $seller->verification_info == "[]") {
            //If vendor has no verification info create new

            //Create orders email
            if ($request->orders_email) {
                $item = $this->createItemObject('text', 'Email (to receive order confirmations)', $request->orders_email);
                $data[] = $item;
            }
            //Create vendor website
            if ($request->website) {
                $item = $this->createItemObject('text', 'Website / e-store', $request->website);
                $data[] = $item;
            }
            //Create vendor registration_number
            if ($request->registration_number) {
                $item = $this->createItemObject('text', 'Company registration number', $request->registration_number);
                $data[] = $item;
            }
            //Create vendor tax_number
            if ($request->tax_number) {
                $item = $this->createItemObject('text', 'Tax number', $request->tax_number);
                $data[] = $item;
            }
            //Create vendor vat_id
            if ($request->vat_id) {
                $item = $this->createItemObject('text', 'German/EU VAT ID or OSS number', $request->vat_id);
                $data[] = $item;
            }
            //Create vendor person_name
            if ($request->person_name) {
                $item = $this->createItemObject('text', 'Person in charge full name', $request->person_name);
                $data[] = $item;
            }
            //Create vendor person_email
            if ($request->person_email) {
                $item = $this->createItemObject('text', 'Person in charge Email', $request->person_email);
                $data[] = $item;
            }
            //Create vendor person_phone
            if ($request->person_phone) {
                $item = $this->createItemObject('text', 'Person in charge phone number', $request->person_phone);
                $data[] = $item;
            }

            $seller->verification_info = json_encode($data);
        } else {

            //If vendor has verification info update it

            //Update orders_email
            if ($request->orders_email) $verification_info[0]->value = $request->orders_email;

            //Update website
            if ($request->website) $verification_info[1]->value = $request->website;

            //Update registration_number
            if ($request->registration_number) $verification_info[2]->value = $request->registration_number;

            //Update tax_number
            if ($request->tax_number) $verification_info[3]->value = $request->tax_number;

            //Update vat_id
            if ($request->vat_id) $verification_info[4]->value = $request->vat_id;

            //Update person_name
            if ($request->person_name) $verification_info[5]->value = $request->person_name;

            //Update person_email
            if ($request->person_email) $verification_info[6]->value = $request->person_email;

            //Update person_phone
            if ($request->person_phone) $verification_info[7]->value = $request->person_phone;


            $seller->verification_info = $verification_info;
        }

        //Update bank name
        if ($request->bank_name) $seller->bank_name = $request->bank_name;

        //Update bank name
        if ($request->bank_acc_name) $seller->bank_acc_name = $request->bank_acc_name;

        //Update bank_acc_no
        if ($request->bank_acc_no) $seller->bank_acc_no = $request->bank_acc_no;

        //Update bank_iban
        if ($request->bank_iban) $seller->bank_iban = $request->bank_iban;

        //Update paypal_account
        if ($request->paypal_account) $seller->paypal_account = $request->paypal_account;

        //Update banner
        if ($request->banner) $seller->banner = $request->banner;

        $seller->save();


        $user = Auth::user();

        //Update banner
        if ($request->logo) $user->avatar_original = $request->logo;

        //Update website
        if ($request->website) $user->url = $request->website;

        //Update country
        if ($request->country) $user->country = $request->country;

        //Update city
        if ($request->city) $user->city = $request->city;

        //Update zipcode
        if ($request->zipcode) $user->postal_code = $request->zipcode;

        //Update address
        if ($request->address) $user->address = $request->address;

        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Profile Updated'),
        ], 201);
    }
}
