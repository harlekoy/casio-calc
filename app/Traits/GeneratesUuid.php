<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Automatically generate an ordered UUID when creating a model.
 */
trait GeneratesUuid
{
    /**
     * Boot the GeneratesUuid trait.
     */
    protected static function bootGeneratesUuid(): void
    {
        static::creating(function ($model) {
            if (! $model->uuid) {
                do {
                    $uuid = Str::orderedUuid();
                    $model->uuid = $uuid;
                } while (self::where('uuid', $uuid)->exists());
            }
        });
    }
}
