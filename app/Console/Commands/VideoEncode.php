<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoEncode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video-encode:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Video Encoding...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $low = (new x264('aac'))->setKiloBitrate(500);
        $high = (new x264('aac'))->setKiloBitrate(1000);

        FFMpeg::fromDisk('videos-temp')
            ->open('file.mkv')
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
                $this->info("progress={$progress}");
            })
            ->toDisk('videos-temp')
            ->save('/test/file.m3u8');

    }
}
