<?php

namespace App\Http\Controllers;

use App\StatusWorkflow;
use Illuminate\Http\Request;
use DB;

class StatusWorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StatusWorkflow  $statusWorkflow
     * @return \Illuminate\Http\Response
     */
    public function show(StatusWorkflow $statusWorkflow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StatusWorkflow  $statusWorkflow
     * @return \Illuminate\Http\Response
     */
    public function edit(StatusWorkflow $statusWorkflow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StatusWorkflow  $statusWorkflow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StatusWorkflow $statusWorkflow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StatusWorkflow  $statusWorkflow
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatusWorkflow $statusWorkflow)
    {
        //
    }

    public function getSTWNextStatus($channel, $transport_type, $customer_type, $current_step)
    {
        return StatusWorkflow::where([
            'channel_id' => $channel,
            'transaction_type' => 'STW',
            'transport_types_id' => $transport_type,
            'customer_type' => (empty($customer_type)) ? NULL : $customer_type,
            'current_step' => $current_step,
            'status' => 'ACTIVE'
        ])->first();
    }

    public function getOnlineSTWNextStatus($channel, $customer_type, $current_step)
    {
        return StatusWorkflow::where([
            'channel_id' => $channel,
            'transaction_type' => 'STW',
            'transport_types_id' => ($customer_type == 'FBD') ? NULL : 1,
            'customer_type' => (empty($customer_type)) ? NULL : $customer_type,
            'current_step' => $current_step,
            'status' => 'ACTIVE'
        ])->first();
    }

    public function getRMANextStatus($channel, $transport_type, $customer_type, $current_step)
    {
        return StatusWorkflow::where([
            'channel_id' => $channel,
            'transaction_type' => 'RMA',
            'transport_types_id' => $transport_type,
            'customer_type' => (empty($customer_type)) ? NULL : $customer_type,
            'current_step' => $current_step,
            'status' => 'ACTIVE'
        ])->first();
    }

    public function getOnlineRMANextStatus($channel, $customer_type, $current_step)
    {
        return StatusWorkflow::where([
            'channel_id' => $channel,
            'transaction_type' => 'RMA',
            'transport_types_id' => ($customer_type == 'FBD') ? NULL : 1,
            'customer_type' => (empty($customer_type)) ? NULL : $customer_type,
            'current_step' => $current_step,
            'status' => 'ACTIVE'
        ])->first();
    }
}
