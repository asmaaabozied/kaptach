<?php

namespace App\Jobs;

use App\Helpers\Utilities;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $folder;

    protected $image;

    protected $sizes;

    public $tries = 1;
    //public $timeout = 200;

    /**
     * Create a new job instance.
     *
     * @param $folder
     * @param $image
     * @param array $sizes
     */
    public function __construct($folder, $image, $sizes = [])
    {
        $this->folder = $folder;
        $this->image = $image;
        $this->sizes = $sizes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Utilities $utils)
    {
        $utils->resizeImage($this->folder, $this->image, $this->sizes);
    }
}
