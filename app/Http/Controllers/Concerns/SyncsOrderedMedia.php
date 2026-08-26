<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;

trait SyncsOrderedMedia
{
    protected function syncMediaWithOrder(Model $model, array $mediaIds): void
    {
        $syncData = [];

        foreach ($mediaIds as $index => $mediaId) {
            $syncData[$mediaId] = ['display_order' => $index];
        }

        $model->media()->sync($syncData);
    }
}
