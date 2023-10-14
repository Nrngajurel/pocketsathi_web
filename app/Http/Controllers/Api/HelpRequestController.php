<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use App\Http\Requests\StoreHelpRequestRequest;
use App\Http\Requests\UpdateHelpRequestRequest;

class HelpRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function myHelpRequest()
    {
        return response()->json(HelpRequest::with('applies')->where('requester_id', auth()->id())->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeHelpRequest(StoreHelpRequestRequest $request)
    {
        $data = $request->validated();
        $data['requester_id'] = auth()->id();
        $helpRequest = HelpRequest::create($data);
        $helpRequest->addStatus('pending');

        // send notification to nearby
        $helpRequest->notifyNearby();


        return response()->json([
            'message' => 'Successfully Requested',
            'data' => $helpRequest
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(HelpRequest $helpRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HelpRequest $helpRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHelpRequestRequest $request, HelpRequest $helpRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HelpRequest $helpRequest)
    {
        //
    }
    public function complete(HelpRequest $helpRequest)
    {
        if ($helpRequest->current_status == 'complete') {
            return response()->json([
                'error' => 'Already Completed'
            ]);
        }
        if ($helpRequest->current_status == 'cancel') {
            return response()->json([
                'error' => 'Cannot Complete Cancelled request'
            ]);
        }
        $helpRequest->completed();
        return response()->json([
            'message' => 'Successfully Completed',
            'data' => $helpRequest
        ]);
    }
    public function cancel(HelpRequest $helpRequest)
    {
        if ($helpRequest->current_status == 'cancel') {
            return response()->json([
                'error' => 'Already Cancelled'
            ]);
        }
        if ($helpRequest->current_status == 'complete') {
            return response()->json([
                'error' => 'Cannot Cancel Completed request'
            ]);
        }
        $helpRequest->cancel();
        return response()->json([
            'message' => 'Successfully Cancelled',
            'data' => $helpRequest
        ]);
    }

    // function haversine($lat1, $lon1, $lat2, $lon2)
    // {
    //     $radLat1 = deg2rad($lat1);
    //     $radLon1 = deg2rad($lon1);
    //     $radLat2 = deg2rad($lat2);
    //     $radLon2 = deg2rad($lon2);

    //     $dlat = $radLat2 - $radLat1;
    //     $dlon = $radLon2 - $radLon1;

    //     $a = sin($dlat / 2) * sin($dlat / 2) + cos($radLat1) * cos($radLat2) * sin($dlon / 2) * sin($dlon / 2);
    //     $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    //     $radius = 6371; // Earth's radius in kilometers
    //     $distance = $radius * $c;

    //     return $distance;
    // }
}