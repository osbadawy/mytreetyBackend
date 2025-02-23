<?php

namespace App\Jobs;

use App\Models\ProductsImport;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class CSVSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id;
    private $file;
    public $timeout = 12000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file,$user_id)
    {
        $this->file = $file;
        $this->user_id= $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename="$this->user_id/$this->file";
        $file=Storage::disk('local')->path("/uploads/excel/$filename");

        $import = new ProductsImport($this->user_id);

        Excel::queueImport($import, $file);


    }

    public function deleteFile($filename)
    {
        if(Storage::exists("upload/excel/$filename")){
            dd('yes');
            Storage::delete("upload/excel/$filename");
        }else{
            dd('File does not exist.');
        }
    }
}
