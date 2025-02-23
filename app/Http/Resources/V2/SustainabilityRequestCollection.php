<?php

namespace App\Http\Resources\V2;

use App\Models\Product;
use App\Models\Sustainability;
use DB;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SustainabilityRequestCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection->map(function ($data) {

                $product_requests = [];
                $product_requests['icons'] = [];
                foreach ($data as $key => $req) {
                    $icon = Sustainability::find($req->sustainability_id);
                    $product = Product::withTrashed()->where('id', $req->product_id)->first();
                    $status = translate('Verified');
                    $product_sustainability = DB::table('product_sustainability')->where('product_id', $req->product_id)->where('sustainability_id', $req->sustainability_id)->first();
                    $product_requests['status'] = translate('Pending');
                    if ($product_sustainability) {
                        $is_verified = $product_sustainability->is_verified;

                        if ($is_verified == 0) {
                            $status = translate('Pending');
                        }
                        if ($is_verified == 2) {
                            $status = translate('Denied');
                        }
                        $product_requests['icons'][] = [
                            'id' => (int)$icon->id,
                            'name' => $icon->getTranslation('name'),
                            'verified' => (int)$is_verified,
                            'image' => uploaded_asset($icon->getTranslation('image'))
                        ];
                    } else {
                        $status = translate('Declined');
                    }
                    if ($product) {

                        $product_requests['product_name'] = $product->name;
                        $product_requests['thumbnail_img'] = $product->thumbnail_img;
                        $product_requests['status'] = $status;
                    }

                }


                return $product_requests;
            })
        ];
    }

    public function with($request): array
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
