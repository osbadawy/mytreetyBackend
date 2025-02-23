<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Requests\CreateAddressRequest;
use App\Http\Resources\V2\AddressCollection;
use App\Http\Resources\V2\CitiesCollection;
use App\Http\Resources\V2\CountriesCollection;
use App\Http\Resources\V2\StatesCollection;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    /**
     * @param Request $request
     * @return AddressCollection
     */
    public function addresses(Request $request): AddressCollection
    {
        return new AddressCollection(Address::where('user_id', $request->user()->id)->get());
    }


    /**
     * @param CreateAddressRequest $request
     * @return JsonResponse
     */
    public function createShippingAddress(CreateAddressRequest $request): JsonResponse
    {

        //Save address is db
        $this->saveAddress($request);

        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been added successfully')
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function saveAddress(Request $request): void
    {
        $address = new Address;
        $address->user_id = $request->user()->id;
        $address->name = $request->name;
        $address->full_name = "$request->first_name $request->last_name";
        $address->address = $request->address;
        $address->country_id = $request->country_id;
        $address->state_id = $request->state_id;
        $address->city_id = $request->city_id;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        if ($request->set_default) {
            $request->set_default = 1;
        }
        $address->save();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateShippingAddress(Request $request): JsonResponse
    {
        //Get address
        $address = Address::where('user_id', $request->user()->id)->where('id', $request->id)->first();
        if (!$address) {
            return $this->returnIfAddressedFound();
        }

        //Update Address
        $this->updateAddress($request, $address);

        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been updated successfully')
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfAddressedFound(): JsonResponse
    {
        return response()->json([
            'result' => false,
            'message' => translate('Something went wrong!')
        ], 400);
    }

    /**
     * @param Request $request
     * @param $address
     * @return void
     */
    public function updateAddress(Request $request, $address): void
    {
        $address->full_name = "$request->first_name $request->last_name";
        $address->name = $request->name;
        $address->address = $request->address;
        $address->country_id = $request->country_id;
        $address->state_id = $request->state_id;
        $address->city_id = $request->city_id;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteShippingAddress($id, Request $request): JsonResponse
    {
        //Get address
        $address = Address::where('id', $id)->where('user_id', $request->user()->id)->first();
        if (!$address) {
            return $this->returnIfAddressedFound();
        }
        //Delete Address
        $address->delete();
        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been deleted')
        ]);
    }

    public function makeShippingAddressDefault($id, Request $request): JsonResponse
    {
        //Get address
        $address = Address::where('user_id', $request->user()->id)->where('id', $id)->first();
        if (!$address) {
            return $this->returnIfAddressedFound();
        }

        //Make all user addresses non default first
        Address::where('user_id', $request->user()->id)->update(['set_default' => 0]);


        //Set new address to default
        $address->set_default = 1;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Default shipping information has been updated')
        ]);
    }

    /**
     * @param $id
     * @return StatesCollection
     */
    public function getStates($id): StatesCollection
    {
        //Return all active states by country id
        return new StatesCollection(State::where('status', 1)->where('country_id', $id)->get());
    }

    /**
     * @param $id
     * @return CitiesCollection
     */
    public function getCities($id): CitiesCollection
    {
        //Return all active states by country id
        return new CitiesCollection(City::where('status', 1)->where('state_id', $id)->get());
    }

    /**
     * @param Request $request
     * @return CountriesCollection
     */
    public function getCountries(Request $request): CountriesCollection
    {
        //Get all active countries
        $country_query = Country::where('status', 1);

        //If request need to filter countries by name
        if ($request->name != "") {
            $country_query->where('name', 'like', '%' . $request->name . '%');
        }
        $countries = $country_query->get();

        return new CountriesCollection($countries);
    }
}
