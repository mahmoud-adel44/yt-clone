<?php

namespace App\Http\Livewire\Video;

use App\Events\VideoProcessedEvent;
use App\Jobs\ConveteVideoForSreaming;
use App\Jobs\CreateThumbnailFromVideo;
use Illuminate\Support\Facades\Artisan;
use Livewire\WithFileUploads;
use App\Models\Channel;
use App\Models\Video;
use Livewire\Component;
use Illuminate\Support\Facades\Redis;

class CreateVideo extends Component
{

    use WithFileUploads;

    public Channel $channel;
    public Video $video;
    public $videoFile;
    protected $rules = [
        'videoFile' => 'required|mimes:mkv|max:1228800'
    ];

    public function mount(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function render()
    {
        return view('livewire.video.create-video')
            ->extends('layouts.app');
    }

    public function fileCompleted()
    {
        // validation
        $this->validate();
        //save the file
        $path = $this->videoFile->store('videos-temp');

        //create video record in sb
        $this->video = $this->channel->videos()->create([
            'title' => 'untitled',
            'description' => 'none',
            'uid' => uniqid(true),
            'visibility' => 'private',
            'path' => explode('/', $path)[1]
        ]);


        // Dispatch the job
        event(new VideoProcessedEvent($this->video));


//        CreateThumbnailFromVideo::dispatch($this->video);
//        ConveteVideoForSreaming::dispatch($this->video);


        //redirect to edit route
        return redirect()->route('video.edit', [
            'channel' => $this->channel,
            'video' => $this->video,
        ]);
    }
}
