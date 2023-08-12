<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Http\Requests\StoreApplyRequest;
use App\Http\Requests\UpdateApplyRequest;
use App\Models\HelpRequest;

class ApplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplyRequest $request)
    {
        $data = $request->validated();
        $data['applier_id']= auth()->id();

        $apply = HelpRequest::findOrFail($request->help_request_id)->applies()->create($data);
        $apply->addStatus('pending');

        return response()->json([
            'message'=> 'Successfully Requested',
            'data'=> $apply
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Apply $apply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apply $apply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplyRequest $request, Apply $apply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apply $apply)
    {
        //
    }


    public function accept($helpRequest, $apply)
    {

         $apply = Apply::where('help_request_id', $helpRequest)->findOrFail($apply);
        if ($apply->current_status == 'accept') {
            return response()->json([
                'error' => 'Already acceptd'
            ]);
        }
        if ($apply->current_status == 'reject') {
            return response()->json([
                'error' => 'Cannot Complete Rejected request'
            ]);
        }
        $apply->accept();
        return response()->json([
            'message' => 'Successfully acceptd',
            'data' => $apply
        ]);
    }
    public function reject($helpRequest, $apply)
    {
         $apply = Apply::where('help_request_id', $helpRequest)->findOrFail($apply);
        if ($apply->current_status == 'reject') {
            return response()->json([
                'error' => 'Already Reject'
            ]);
        }
        if ($apply->current_status == 'accept') {
            return response()->json([
                'error' => 'Cannot reject acceptd request'
            ]);
        }
        $helpRequest->cancel();
        return response()->json([
            'message' => 'Successfully Reject',
            'data' => $apply
        ]);
    }
}
