<?php

namespace App\Traits;

use App\Models\Status;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Statusable
{
    public function statuses(): MorphMany
    {
        return $this->morphMany(Status::class, 'statusable');
    }
    // latest of may status

    public function getCurrentStatusAttribute(): ?string
    {
        $latestStatus = $this->statuses()->latest()->first();

        return $latestStatus ? $latestStatus->status : null;
    }

    public function approve()
    {
        $this->addStatus('approve');
    }
    public function accept()
    {
        $this->addStatus('accept');
    }
    public function reject()
    {
        $this->addStatus('reject');
    }
    public function completed()
    {
        $this->addStatus('complete');
    }
    public function cancel()
    {
        $this->addStatus('cancel');
    }
    public function addStatus(string $status, $user_id = null): Status
    {
        return $this->statuses()->create([
            'status' => $status,
            'user_id' => $user_id ?? auth()->id(),
        ]);
    }
}