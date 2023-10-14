<?php

namespace App\Models;

use App\Traits\Statusable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class HelpRequest extends Model
{
    use HasFactory, Statusable;
    protected $guarded = [];
    protected $appends = ['current_status'];



    public function applies()
    {
        return $this->hasMany(Apply::class);
    }
    public function scopeNearby($query, $lat, $lon, $radius)
    {
        return $query
            ->selectRaw('*, (6371 * acos(cos(radians(?)) * cos(radians(help_requests.lat)) * cos(radians(help_requests.long) - radians(?)) + sin(radians(?)) * sin(radians(help_requests.lat)))) AS distance', [$lat, $lon, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }

    public function notifyNearby()
    {
        $firebase = (new Factory)
            ->withServiceAccount(public_path('pocketsathi-firebase-adminsdk-9s4u4-01c9a943c8.json'));
        $messaging = $firebase->createMessaging();
        $nearByUser = User::whereNotNull('fcm_token')->nearby($this->lat, $this->long, 15)->get();
        $nearByUser->each(function ($user) use ($firebase, $messaging) {
            // send notification to user by firebase
            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification([
                    'title' => 'Help Request Nearby',
                    'body' => 'A help request is nearby. Can you assist?'
                ]);

            $messaging->send($message);
        });
    }
}