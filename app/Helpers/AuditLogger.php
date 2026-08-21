<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log(string $action, $model = null, array $oldValues = [], array $newValues = [])
    {
        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model ? $model->getKey() : null,
                'old_values' => !empty($oldValues) ? json_encode($oldValues) : null,
                'new_values' => !empty($newValues) ? json_encode($newValues) : null,
                'ip_address' => Request::ip(),
            ]);
        } catch (\Exception $e) {
            // Silently fail or log to error log to prevent breaking core transaction
            \Illuminate\Support\Facades\Log::error('Audit log failed: ' . $e->getMessage());
        }
    }
}
