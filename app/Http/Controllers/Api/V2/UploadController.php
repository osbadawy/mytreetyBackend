<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSignedUrlRequest;
use Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;
use Str;

class UploadController extends Controller
{

    /**
     * @param CreateSignedUrlRequest $request
     * @return JsonResponse
     */
    public function CreateSignedUrl(CreateSignedUrlRequest $request): JsonResponse
    {

        $extension = 'jpg';
        $uuid = Str::uuid()->toString();
        $cmdType = 'PutObject';
        $s3 = Storage::disk('s3');
        $bucket = Config::get('filesystems.disks.s3.bucket');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+10 minutes";
        $folder = null;


        //set filename
        $request->filename ? $filename = $request->filename : $filename = null;


        //set filename
        if (($pos = strpos($filename, ".")) !== FALSE) {
            $extension = substr($filename, $pos + 1);
        }


        //set folder
        if (!empty($request->folder)) {
            $folder = $request->folder;
        }


        // create  key
        $key = "$folder/$uuid.$extension";
        $params = [
            'Bucket' => $bucket,
            'Key' => $key,
        ];

        //create Resigned Url
        $cmd = $client->getCommand($cmdType, $params);
        $request = $client->createPresignedRequest($cmd, $expiry);
        $resignedUrl = (string)$request->getUri();

        return response()->json(['url' => $resignedUrl], 201);
    }
}
