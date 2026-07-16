<?php

namespace App\Observers;

use App\Models\AktivitasLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $this->write('membuat', $model);
    }

    public function updated(Model $model): void
    {
        $this->write('mengubah', $model, $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->write('menghapus', $model);
    }

    private function write(string $action, Model $model, array $changes = []): void
    {
        AktivitasLog::query()->create([
            'user_id' => auth()->id(),
            'aksi' => $action,
            'modul' => class_basename($model),
            'deskripsi' => ucfirst($action).' data '.$model->getKey(),
            'ip_address' => request()?->ip(),
            'metadata' => [
                'record_id' => $model->getKey(),
                'changes' => $this->withoutSensitiveValues($changes),
            ],
        ]);
    }

    private function withoutSensitiveValues(array $changes): array
    {
        unset($changes['password'], $changes['remember_token']);

        return $changes;
    }
}
