<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class DistriController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function scanItemSearch(Request $request)
		{
			$data = array();
			$item_serials = array();
            $data['status_no'] = 0;
			$data['message'] ='No item found!';
			$qty = 1;
			
			$items = app(ItemsController::class)->getItem($request->item_code);

			if($items && $qty >= 0 && $request->channel == 6) { //con
                $col_sub = $request->subinventory.'_reserve_qty';
                $column = app(ItemsController::class)->decrementItemColumn($request->item_code, $col_sub, 1);
                
                if($column->$col_sub < 0){
                    echo json_encode($data);
                    exit;
                }
                
				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->digits_code;
				$return_data['bea_item'] = $items->bea_item_id;
				$return_data['upc_code'] = $items->upc_code;
				$return_data['item_description'] = $items->item_description;
				$return_data['has_serial'] = $items->has_serial;
				$return_data['item_serial'] = $item_serials;
				$return_data['price'] = $items->store_cost;
				$data['items'] = $return_data;	
			}
            elseif($items && $qty >= 0 && $request->channel != 6){ //out
                $data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->digits_code;
				$return_data['bea_item'] = $items->bea_item_id;
				$return_data['upc_code'] = $items->upc_code;
				$return_data['item_description'] = $items->item_description;
				$return_data['has_serial'] = $items->has_serial;
				$return_data['item_serial'] = $item_serials;
				$return_data['price'] = $items->store_cost;
				$data['items'] = $return_data;
            }
			            
            echo json_encode($data);
            exit;
		}

		public function incrementReserveQty(Request $request)
		{
			$data = array();
			$data['status_no'] = 0;
			$data['status_no'] = app(ItemsController::class)->incrementItem($request->item_code,$request->subinventory.'_reserve_qty',$request->qty);
			echo json_encode($data);
            exit;
		}

		public function decrementReserveQty(Request $request)
		{
			$data = array();
			$data['status_no'] = 0;
			$data['status_no'] = app(ItemsController::class)->decrementItem($request->item_code,$request->subinventory.'_reserve_qty',$request->qty);
			echo json_encode($data);
            exit;
		}
}
