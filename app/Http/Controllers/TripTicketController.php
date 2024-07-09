<?php

namespace App\Http\Controllers;

use App\TripTicket;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class TripTicketController extends Controller
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
     * @param  \App\TripTicket  $tripTicket
     * @return \Illuminate\Http\Response
     */
    public function show(TripTicket $tripTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TripTicket  $tripTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(TripTicket $tripTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TripTicket  $tripTicket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TripTicket $tripTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TripTicket  $tripTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(TripTicket $tripTicket)
    {
        //
    }
    
    public function getPendingDrByStore($store_id)
    {
        $store_channel = DB::table('stores')->where('id',$store_id)->value('channel_id');
        
        $created_trips = DB::table('trip_tickets')
            ->where('stores_id',$store_id)->where('is_backload',0)
            ->where('trip_type','OUT')
            ->select('ref_number')->get()->toArray();
            
        if($store_channel == 2){
            return  DB::table('ebs_pull')->where('stores_id',$store_id)->where('status','PENDING')
                ->whereNotIn('order_number', array_column($created_trips, 'ref_number'))
                ->select('order_number as dr_number', DB::raw("SUM(shipped_quantity) as quantity"))
                ->groupBy('order_number')->distinct()
                ->get();
        }

        return  DB::table('ebs_pull')->where('stores_id',$store_id)->where('status','PENDING')
            ->whereNotIn('dr_number',array_column($created_trips, 'ref_number'))
            ->select('dr_number',DB::raw("SUM(shipped_quantity) as quantity"))
            ->groupBy('dr_number')->distinct()
            ->get();
        
    }
    
    public function getReceivingOutboundByStore($store_id,$trip_number)
    {
        return DB::table('trip_tickets')
            ->where('stores_id',$store_id)
            ->where('trip_number',$trip_number)
            // ->whereNull('updated_at')
            ->where('is_received',0)
            ->where('trip_type','OUT')->get();
    }
    
    public function getReleasingInboundByStore($store_id,$trip_number)
    {
        return DB::table('trip_tickets')
            ->where('stores_id',$store_id)
            ->where('trip_number',$trip_number)
            // ->whereNull('updated_at')
            ->where('is_released',0)
            ->where('trip_type','IN')->get();
    }

    public function getPendingSTSByStore($store_id)
    {

        $created_trips = DB::table('trip_tickets')
            ->where('stores_id',$store_id)->where('is_backload',0)
            ->where('trip_type','OUT')
            ->select('ref_number')->get()->toArray();

        return DB::table('pos_pull_headers')
            ->leftJoin('pos_pull','pos_pull_headers.id','=','pos_pull.pos_pull_header_id')
            ->where('pos_pull_headers.stores_id_destination',$store_id)
            //->where('pos_pull_headers.scheduled_at',date('Y-m-d',strtotime("-1 days")))
            ->where('pos_pull_headers.transport_types_id',1)
            ->where('pos_pull_headers.status','FOR RECEIVING')
            ->whereNotIn('pos_pull_headers.st_document_number',array_column($created_trips, 'ref_number'))
            ->select('pos_pull_headers.st_document_number',DB::raw("SUM(pos_pull.quantity) as quantity"))
            ->groupBy('pos_pull_headers.st_document_number')->distinct()
            ->get();
    }

    public function getPendingInboundSTSByStore($store_id)
    {

        $created_trips = DB::table('trip_tickets')
            ->where('stores_id',$store_id)->where('is_backload',0)
            ->where('trip_type','IN')
            ->select('ref_number')->get()->toArray();

        return DB::table('pos_pull_headers')
            ->leftJoin('pos_pull','pos_pull_headers.id','=','pos_pull.pos_pull_header_id')
            ->where('pos_pull_headers.stores_id',$store_id)
            // ->where('scheduled_at',date('Y-m-d'))
            ->where('pos_pull_headers.transport_types_id',1)
            ->where('pos_pull_headers.status','FOR RECEIVING')
            ->whereNotIn('pos_pull_headers.st_document_number',array_column($created_trips, 'ref_number'))
            ->select('pos_pull_headers.st_document_number',DB::raw("SUM(pos_pull.quantity) as quantity"))
            ->groupBy('pos_pull_headers.st_document_number')->distinct()
            ->get();
    }

    public function getPendingInboundSTWRByStore($store_id)
    {

        $created_trips = DB::table('trip_tickets')
            ->where('stores_id',$store_id)->where('is_backload',0)
            ->where('trip_type','IN')
            ->select('ref_number')->get()->toArray();

        return DB::table('pullout')
            ->where('stores_id',$store_id)
            // ->where('schedule_date',date('Y-m-d'))
            ->where('transport_types_id',1)
            ->where('status','FOR RECEIVING')
            ->whereNotIn('st_document_number',array_column($created_trips, 'ref_number'))
            ->select('st_document_number','transaction_type',DB::raw("SUM(quantity) as quantity"))
            ->groupBy('st_document_number','transaction_type')->distinct()
            ->get();
    }
    
    
}
