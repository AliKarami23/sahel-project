<?php

namespace App\Jobs;

use App\Models\Image;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClearInactiveMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function handle()
    {
        $this->clearInactiveMedia(Image::class);
        $this->clearInactiveMedia(Video::class);
    }

    protected function clearInactiveMedia($modelClass)
    {
        $inactiveMedia = $modelClass::where('Status', 'Inactive')
            ->where('updated_at', '<=', now()->subDays(3)->toDateTimeString())
            ->get();

        foreach ($inactiveMedia as $mediaItem) {
            $this->deleteMedia($mediaItem);
        }
    }

    protected function deleteMedia($model)
    {
        $media = $model->getMedia('videos'); // Replace 'videos' with your media collection name

        foreach ($media as $mediaItem) {
            $mediaItem->delete();
        }

        $model->delete();
    }

}
