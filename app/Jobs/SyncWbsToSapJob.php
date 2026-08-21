<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\WbsElement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncWbsToSapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function handle(): void
    {
        $project = Project::find($this->projectId);
        if (!$project) return;

        WbsElement::where('project_id', $this->projectId)->update(['sent_to_sap' => true]);

        foreach (User::all() as $u) {
            Notification::create([
                'user_id' => $u->id,
                'title' => 'WBS Sent to SAP (Async)',
                'message' => 'Struktur WBS untuk proyek segment ' . $project->segment . ' telah sukses disinkronkan ke SAP secara asynchronous.',
                'type' => 'project',
            ]);
        }

        \App\Helpers\AuditLogger::log('Sync WBS to SAP Job', $project, [], ['sent_to_sap' => true]);
    }
}
