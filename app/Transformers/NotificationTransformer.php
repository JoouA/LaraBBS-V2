<?php
namespace App\Transformers;

use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $databaseNotification)
    {
        return [
            'id' => $databaseNotification->id,
            'type' => $databaseNotification->type,
            'data' => $databaseNotification->data,
            'read_at' => $databaseNotification->read_at ? $databaseNotification->read_at->toDateTimeString() : null,
            'created_at' => $databaseNotification->created_at->toDateTimeString(),
            'updated_at' => $databaseNotification->updated_at->toDateTimeString(),
        ];
    }
}