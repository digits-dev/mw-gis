<?php

namespace App\Http\Controllers;

use Session;
use DB;
use CRUDBooster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Pullout;
use App\Serials;
use App\CodeCounter;

class OnlineFBVController extends \crocodicstudio\crudbooster\controllers\CBController {

	
    public function cbInit() {
    }
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

    public function scanFBVItemSearch(Request $request)
    {
        $data = array();
        $data['status_no'] = 0;
        $data['message'] ='No item found!';
        $qty = 1;

        $store_reserve = $request->store_code;
        
        $items = DB::table('items')
            ->where('digits_code', $request->item_code)
            ->first();

        if(!empty($items) && $items->$store_reserve > 0) {
            
            DB::table('items')
                ->where('digits_code', $request->item_code)
                ->decrement($store_reserve);

            $data['status_no'] = 1;
            $data['message'] ='Item found!';
            $return_data['digits_code'] = $items->digits_code;
            $return_data['bea_item'] = $items->bea_item_id;
            $return_data['upc_code'] = $items->upc_code;
            $return_data['item_description'] = $items->item_description;
            $return_data['has_serial'] = $items->has_serial;
            $return_data['price'] = $items->store_cost;
            $data['items'] = $return_data;	
        }
                    
        echo json_encode($data);
        exit;
    }

    public function scanFBVItemSearchNoReserve(Request $request)
    {
        $data = array();
        $data['status_no'] = 0;
        $data['message'] ='No item found!';
        $qty = 1;
        
        $items = DB::table('items')
            ->where('digits_code', $request->item_code)
            ->first();

        if(!empty($items)) {

            $data['status_no'] = 1;
            $data['message'] ='Item found!';
            $return_data['digits_code'] = $items->digits_code;
            $return_data['bea_item'] = $items->bea_item_id;
            $return_data['upc_code'] = $items->upc_code;
            $return_data['item_description'] = $items->item_description;
            $return_data['has_serial'] = $items->has_serial;
            $return_data['price'] = $items->store_cost;
            $data['items'] = $return_data;	
        }
                    
        echo json_encode($data);
        exit;
    }

    public function cancelReserveQty(Request $request)
    {
        $data = array();
        $data['status_no'] = 0;
        $updateOnhand = DB::table('items')
            ->where('digits_code', $request->item_code)
            ->increment($request->reserve,$request->qty);

        $data['status_no'] = $updateOnhand;
        echo json_encode($data);
        exit;
    }

    public function resetReserveQty(Request $request)
    {
        $data = array();
        $data['status_no'] = 0;
        $updateOnhand = DB::table('items')
            ->where('digits_code', $request->item_code)
            ->decrement($request->reserve,$request->qty);

        $data['status_no'] = $updateOnhand;
        echo json_encode($data);
        exit;
    }

    public function saveCreateSTWOnline(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'transport_type' => 'required',
            'digits_code' => 'required',
            'transfer_from' => 'required',
            'transfer_to' => 'required',
            'reason' => 'required',
            'pullout_date' => 'required',
        ],
        [
            'transport_type.required' => 'You have to choose transport by.',
            'digits_code.required' => 'You have to add pullout items.',
            'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
            'reason.required' => 'You have to choose pullout reason.',
            'pullout_date.required' => 'You have to set pullout date.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        
        $stDetails = array();
        $posItemDetails = array();
        $record = false;
        /*
        foreach ($request->digits_code as $key_item => $value_item) {
            $serial = $value_item.'_serial_number';
            
            if(!empty($request->$serial)){
                
                foreach ($request->$serial as $key_serial => $value_serial) {
                    
                    $posItemDetails[$value_item.'-'.$key_serial] = [
                        'item_code' => $value_item,
                        'quantity' => 1,
                        'serial_number' => $value_serial,
                        'item_price' => $request->price[$key_item]
                    ];
                }
                
            }
            else{
                
                $posItemDetails[$value_item.'-'.$key_item] = [
                    'item_code' => $value_item,
                    'quantity' => $request->st_quantity[$key_item],
                    'item_price' => $request->price[$key_item]
                ];
                
            }
        }
        */
        $code_counter = CodeCounter::where('id', 1)->value('pullout_refcode');
        $st_number = 'REF-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);

        if(empty($st_number)){
            //back to old form
            // CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created. '.$postedST['data']['record']['errors']['error'] ,'danger')->send();
            CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created.','danger')->send();
        }

        //save ST
        foreach ($request->digits_code as $key_item => $value_item) {
            $serial = $value_item.'_serial_number';
            $item_serials = array();
            if(!is_null($request->$serial)){
                foreach ($request->$serial as $key_serial => $value_serial) {
                    array_push($item_serials, $value_serial);
                }
                
                $stDetails = [
                    'item_code' => $value_item,
                    'item_description' => $request->item_description[$key_item],
                    'quantity' => $request->st_quantity[$key_item],
                    'serial' => implode(",",(array)$item_serials),
                    'wh_from' => $request->transfer_from,
                    'wh_to' => $request->transfer_to,
                    'stores_id' => $request->stores_id,
                    'channel_id' => CRUDBooster::myChannel(),
                    'memo' => $request->memo,
                    'pullout_date' => $request->pullout_date,
                    'reason_id' => $request->reason,
                    'transport_types_id' => $request->transport_type,
                    'hand_carrier' => $request->hand_carrier,
                    'transaction_type' => 'STW',
                    'st_document_number' => $st_number,
                    'sor_number' => NULL,
                    'st_status_id' => 2,
                    'has_serial' => 1,
                    'status' => 'PENDING', 
                    'step' => 2,
                    'created_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
            }
            else{
                $stDetails = [
                    'item_code' => $value_item,
                    'item_description' => $request->item_description[$key_item],
                    'quantity' => $request->st_quantity[$key_item],
                    'wh_from' => $request->transfer_from,
                    'wh_to' => $request->transfer_to,
                    'stores_id' => $request->stores_id,
                    'channel_id' => CRUDBooster::myChannel(),
                    'memo' => $request->memo,
                    'pullout_date' => $request->pullout_date,
                    'reason_id' => $request->reason,
                    'transport_types_id' => $request->transport_type,
                    'hand_carrier' => $request->hand_carrier,
                    'transaction_type' => 'STW',
                    'st_document_number' => $st_number,
                    'sor_number' => NULL,
                    'st_status_id' => 2,
                    'status' => 'PENDING',
                    'step' => 2,
                    'created_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            $pullout_id = Pullout::insertGetId($stDetails);
            $record = true;
            $getSerial = self::getSerialById($pullout_id);

            if(!is_null($getSerial)){
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        Serials::insert([
                            'pullout_id' => $pullout_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            
        }

        if($record && !empty($st_number))
        {
            CodeCounter::where('id', 1)->increment('pullout_refcode');
            // CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout/print').'/'.$st_number,'','')->send();
            CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Pullout request has been created!','success')->send();
        }
        else{
            CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created','danger')->send();
        }
    }

    public function saveCreateRMAOnline(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'transport_type' => 'required',
            'digits_code' => 'required',
            'transfer_from' => 'required',
            'transfer_to' => 'required',
            'reason' => 'required',
            'pullout_date' => 'required',
            // 'problems' => 'required',
        ],
        [
            'transport_type.required' => 'You have to choose transport by.',
            'digits_code.required' => 'You have to add pullout items.',
            'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
            'reason.required' => 'You have to choose pullout reason.',
            'pullout_date.required' => 'You have to set pullout date.',
            // 'problems.required' => 'You have to choose item problem.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $stDetails = array();
        $posItemDetails = array();
        $record = false;
        /*
        foreach ($request->digits_code as $key_item => $value_item) {
            $serial = $value_item.'_serial_number';
            
            if(!empty($request->$serial)){
                
                foreach ($request->$serial as $key_serial => $value_serial) {
                    
                    $posItemDetails[$value_item.'-'.$key_serial] = [
                        'item_code' => $value_item,
                        'quantity' => 1,
                        'serial_number' => $value_serial,
                        'item_price' => $request->price[$key_item]
                    ];
                }
                
            }
            else{
                
                $posItemDetails[$value_item.'-'.$key_item] = [
                    'item_code' => $value_item,
                    'quantity' => $request->st_quantity[$key_item],
                    'item_price' => $request->price[$key_item]
                ];
                
            }
        }
        */
        $code_counter = CodeCounter::where('id', 1)->value('pullout_refcode');
        $st_number = 'REF-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);

        if(empty($st_number)){
            //back to old form
            // CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created. '.$postedST['data']['record']['errors']['error'] ,'danger')->send();
            CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created.','danger')->send();
        }
            
        //save ST
        foreach ($request->digits_code as $key_item => $value_item) {
            $serial = $value_item.'_serial_number';
            $item_problems = $value_item.'problems';
            $item_serials = array();
            if(!is_null($request->$serial)){
                foreach ($request->$serial as $key_serial => $value_serial) {
                    array_push($item_serials, $value_serial);
                }
                
                $stDetails = [
                    'item_code' => $value_item,
                    'item_description' => $request->item_description[$key_item],
                    'quantity' => $request->st_quantity[$key_item],
                    'serial' => implode(",",(array)$item_serials),
                    'wh_from' => $request->transfer_from,
                    'wh_to' => $request->transfer_to,
                    'stores_id' => $request->stores_id,
                    'channel_id' => CRUDBooster::myChannel(),
                    'memo' => $request->memo,
                    'pullout_date' => $request->pullout_date,
                    'reason_id' => $request->reason,
                    // 'purchase_date' => $request->purchase_date[$key_item],
					'problems' => implode(",",$request->$item_problems),
					'problem_detail' => $request->problem_detail[$key_item],
                    'transport_types_id' => $request->transport_type,
                    'hand_carrier' => $request->hand_carrier,
                    'transaction_type' => 'RMA',
                    'st_document_number' => $st_number,
                    'sor_number' => NULL,
                    'st_status_id' => 2,
                    'has_serial' => 1,
                    'status' => 'PENDING',
                    'step' => 2,
                    'created_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
            }
            else{
                $stDetails = [
                    'item_code' => $value_item,
                    'item_description' => $request->item_description[$key_item],
                    'quantity' => $request->st_quantity[$key_item],
                    'wh_from' => $request->transfer_from,
                    'wh_to' => $request->transfer_to,
                    'stores_id' => $request->stores_id,
                    'channel_id' => CRUDBooster::myChannel(),
                    'memo' => $request->memo,
                    'pullout_date' => $request->pullout_date,
                    'reason_id' => $request->reason,
                    // 'purchase_date' => $request->purchase_date[$key_item],
					'problems' => implode(",",$request->$item_problems),
					'problem_detail' => $request->problem_detail[$key_item],
                    'transaction_type' => 'RMA',
                    'transport_types_id' => $request->transport_type,
                    'hand_carrier' => $request->hand_carrier,
                    'st_document_number' => $st_number,
                    'sor_number' => NULL, 
                    'st_status_id' => 2,
                    'status' => 'PENDING', 
                    'step' => 2,
                    'created_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            $pullout_id = Pullout::insertGetId($stDetails);
            $record = true;
            $getSerial = self::getSerialById($pullout_id);

            if(!is_null($getSerial)){
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        Serials::insert([
                            'pullout_id' => $pullout_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            
        }

        if($record && !empty($st_number)){
            CodeCounter::where('id', 1)->increment('pullout_refcode');
            CRUDBooster::insertLog(trans("crudbooster.str_created", ['ref_number' =>$st_number]));
            // CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout/print').'/'.$st_number,'','')->send();
            CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Pullout request has been created!','success')->send();
        }
        else{
            CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created','danger')->send();
        }
    }

    public static function getSerialById($pullout_id)
    {
        return Pullout::where('id',$pullout_id)
            ->select('serial')->first();
    }
}
