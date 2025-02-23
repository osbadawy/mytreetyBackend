<?php

namespace App\Traits;

use App\Models\Upload;
use Auth;

trait ApiUpload

{

    public function ApiUpload($extension, $path)
    {
        $upload = new Upload;
        $upload->extension = $extension;
        $upload->file_name = $path;
        $upload->user_id = Auth::user()->id;
        $upload->save();
    }
}
