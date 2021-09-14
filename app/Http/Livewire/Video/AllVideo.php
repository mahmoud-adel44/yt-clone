<?php

namespace App\Http\Livewire\Video;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class AllVideo extends Component
{
//    use AuthorizesRequests;
    use WithPagination , AuthorizesRequests;

    public $channel;
    protected $paginationTheme = 'bootstrap';

//    public $videos;
    public function mount(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function render()
    {
        return view('livewire.video.all-video')
            ->with('videos', $this->channel->videos()->paginate(3))
            ->extends('layouts.app');
    }

    public function delete(Video $video): RedirectResponse
    {
        $this->authorize('delete' , $video);
        $deleted = Storage::disk('videos')->deleteDirectory($video->uid);
        if ($deleted){
            $video->delete();
        }
        return back();
    }
}
