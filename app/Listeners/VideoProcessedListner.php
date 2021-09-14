<?php

namespace App\Listeners;

use App\Events\VideoProcessedEvent;
use App\Jobs\ConveteVideoForSreaming;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

//use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class VideoProcessedListner
{


    public function __construct()
    {


//        $result = Artisan::call('queue:work');
//        Log::info( $result . ' video was deleted from videos-temp folder');
//        dump($result);



    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return object|VideoProcessedEvent
     */
    public function handle(VideoProcessedEvent $event)
    {

        return $event;
    }
}
