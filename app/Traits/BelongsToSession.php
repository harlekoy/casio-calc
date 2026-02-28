<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Scope queries and auto-fill session_id based on the current session.
 */
trait BelongsToSession
{
    /**
     * Boot the BelongsToSession trait.
     */
    protected static function bootBelongsToSession(): void
    {
        static::addGlobalScope('current_session', function (Builder $builder) {
            if (! Request::hasMacro('sessionId')) {
                $table = (new static)->getTable();
                $builder->where("{$table}.session_id", '');

                return;
            }

            if ($sessionId = Request::sessionId()) {
                $table = (new static)->getTable();
                $builder->where("{$table}.session_id", $sessionId);
            }
        });

        static::creating(function ($model) {
            if (! Request::hasMacro('sessionId')) {
                return;
            }

            $sessionId = Request::sessionId();

            if ($sessionId && ! $model->session_id) {
                $model->session_id = $sessionId;
            }
        });
    }
}
