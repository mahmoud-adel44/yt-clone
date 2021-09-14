<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConveteVideoForSreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $destination = '/' . $this->video->uid . '/' . $this->video->uid . '.m3u8';
        $low = (new x264('aac'))->setKiloBitrate(500);
        $high = (new x264('aac'))->setKiloBitrate(1000);

        FFMpeg::fromDisk('videos-temp')
            ->open($this->video->path)
            ->exportForHLS()
//            ->addFormat($low , function($filter){
//                $filter->resize(640 , 480);
//            })
//            ->addFormat($high , function($filter){
//                $filter->resize(1200 , 720);
//            })

            ->addFormat($low)
            ->addFormat($high)
            ->onProgress(function ($progress) {
                $this->video->update([
                    'processing_percentage' => $progress
                ]);
            })
            ->toDisk('videos')
            ->save($destination);

        $this->video->update([
            'processed' => true,
            'processed_file' => $this->video->uid .'.m3u8',
        ]);

        //delete the video temp
        $result = Storage::disk('videos-temp')->delete($this->video->path);
        Log::info($this->video->path . ' video was deleted from videos-temp folder');

    }
}
