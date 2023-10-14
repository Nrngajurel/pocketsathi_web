<?php

use App\Models\HelpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    // $helpRequest = HelpRequest::first();
    // $helpRequest->notifyNearby();

    // send firebase notification to ctyuTOYKSD-M_BofnBOaAQ:APA91bFMiDSRbRcHxNCjNfJeveBFrlnl9AcUuu2HTua8w7CXClq8iDGqKYRdRSQI0Hz0Ueued8GxfASsqKD2haFqOP4lwKbA3s0w1jm9Pw7EdMqJkLyU_E1FxmBAHfWdnxWA-zrEpd2R token

    User::whereNotNull('fcm_token')->get()->each(function ($user) {
        try {
            $firebase = (new Factory)
                ->withServiceAccount(public_path('pocketsathi-firebase-adminsdk-9s4u4-01c9a943c8.json'));
            $messaging = $firebase->createMessaging();

            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification([
                    'title' => 'Help Request Nearby',
                    'body' => 'A help request is nearby. Can you assist?'
                ]);

            $messaging->send($message);

            dump('sent to ' . $user->name);

        } catch (\Throwable $th) {
            dump('error sending to ' . $user->name);
            //throw $th;
        }

    });
    // $user = User::first();

    // $nearByRequests = HelpRequest::nearby($user->lat, $user->long, 10)->get();
    // $all_request = HelpRequest::all();
    // dd($nearByRequests, $user->lat, $user->long, $all_request);

    // return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});