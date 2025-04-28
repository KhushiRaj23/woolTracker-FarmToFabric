<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity($model, 'created');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity($model, $action)
    {
        if (auth()->guard('admin')->check()) {
            $user = auth()->guard('admin')->user();
            $modelName = class_basename($model);
            
            ActivityLog::create([
                'user_id' => $user->id,
                'user_type' => get_class($user),
                'description' => "{$user->name} {$action} {$modelName} #{$model->id}",
                'type' => $action
            ]);
        }
    }
} 