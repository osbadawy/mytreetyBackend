<?php

namespace App\Http\Controllers;

use App\Jobs\CSVSync;
use App\Jobs\XMLSync;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use Auth;
use App\Models\ProductsExport;
use App\Models\Upload;
use PDF;
use Excel;
use Illuminate\Support\Str;
use JoggApp\GoogleTranslate\GoogleTranslateFacade;
use Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductBulkUploadController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->user_type == 'seller') {
            if (Auth::user()->seller->verification_status) {
                return view('frontend.user.seller.product_bulk_upload.index');
            } else {
                flash('Your shop is not verified yet!')->warning();
                return back();
            }
        } elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.product.bulk_upload.index');
        }
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new ProductsExport, 'mytreety_products.xlsx');
    }

    public function pdf_download_category()
    {
        $categories = Category::all();

        return PDF::loadView('backend.downloads.category', [
            'categories' => $categories,
        ], [], [])->download('category.pdf');
    }


    public function pdf_download_seller()
    {
        $users = User::where('user_type', 'seller')->get();

        return PDF::loadView('backend.downloads.user', [
            'users' => $users,
        ], [], [])->download('user.pdf');
    }

    public function bulk_upload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bulk_file' => 'required|mimes:csv,xlsx',

        ]);

        if ($request->hasFile('bulk_file')) {

            $user_id = $request->user()->id;
            $file = request()->file('bulk_file');

            //Move Uploaded File
            $destinationPath = 'public/uploads/excel';
            $filename=$file->getClientOriginalName();
            $file->move($destinationPath, $filename);


            dispatch(new CSVSync($filename, $user_id));

        }

        flash(translate('CSV is syncing in the background'))->success();
        return back();
    }

    public function bulk_upload_xml(Request $request)
    {

        $validated = $request->validate([
            'bulk_file' => 'required|mimes:xml',

        ]);

        $xmlString = file_get_contents($request->bulk_file);
        $xmlObject = simplexml_load_string($xmlString);

        $json = json_encode($xmlObject);
        $products = json_decode($json, true)['InventoryItem'];
        // dd($products['InventoryItem']);

        if (count($products) > 0) {
            $user_id=Auth::user()->id;

            dispatch(new XMLSync($products, $user_id));


            flash(translate('XML is syncing in the background'))->success();

            return back();
        }
    }

    public function bulk_upload_auto_xml(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'xml_file' => 'required|url',

        ]);

        $seller = Auth::user()->seller;
        $seller->xml_file = $request->xml_file;
        $seller->save();


        flash(translate('XML will be automatically imported everyday at 12:00 AM'))->success();

        return back();

    }


//    public function SellerSync($seller_id)
//    {
//
//        $seller=Seller::where('id',$seller_id)->first();
//        $file=$seller->xml_file;
//        $products=[];
//
//
//        try {
//            $xmlString = file_get_contents($file);
//            $xmlObject = simplexml_load_string($xmlString);
//
//            $json = json_encode($xmlObject);
//
//            $products = json_decode($json, true)['InventoryItem'];
//
//            dispatch(new XMLSync($products, $seller->user_id));
//        }  catch (\Exception $e) {
//            dd($e);
//        }
//
//
//    }


    public function downloadThumbnail($url)
    {
        try {
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            $filename = 'uploads/products/' . Str::random(5) . '.' . $extension;
            $fullpath = 'public/' . $filename;
            $file = file_get_contents(str_replace(array(' '), '%20', $url));
            file_put_contents($fullpath, $file);

            $upload = new Upload;
            $upload->extension = strtolower($extension);

            $upload->file_original_name = $filename;
            $upload->file_name = $filename;
            $upload->user_id = Auth::user()->id;
            $upload->type = "image";
            $upload->file_size = filesize(base_path($fullpath));
            $upload->save();

            if (env('FILESYSTEM_DRIVER') == 's3') {
                $s3 = Storage::disk('s3');
                $s3->put($filename, file_get_contents(base_path($fullpath)));
                unlink(base_path($fullpath));
            }

            return $upload->id;
        } catch (\Exception $e) {
            // dd($e);
        }
        return null;
    }
}
