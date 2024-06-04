<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDO;
use CRUDBooster;

class POSPullController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $client;
    private $w3p_id;
    private $w3p_key;

    public function __construct()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new \SoapClient("http://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "http://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 

        $this->w3p_id = config('w3p.w3p_id');
        $this->w3p_key = config('w3p.w3p_key');
    }
    
    public function posPull($datefrom, $dateto)
    {
        $data['st_details'] = DB::connection('webpos')->table('inv_adjust')
            ->leftJoin('inv_adjust_product', 'inv_adjust.frecno', '=', 'inv_adjust_product.frecno')
            ->leftJoin('mst_product', 'inv_adjust_product.fproductid', '=', 'mst_product.fproductid')
            ->leftJoin('mst_warehouse AS whf', 'inv_adjust.fsiteid', '=', 'whf.fsiteid')
            ->leftJoin('mst_warehouse AS whd', 'inv_adjust.fdst_siteid', '=', 'whd.fsiteid')
            ->select(
               "DATE_FORMAT('inv_adjust.ftrxdate','%Y-%m-%d') as ST_DATE",
                'inv_adjust.fdocument_no as ST_NUMBER',
                'inv_adjust.freference_code as DR_NUMBER',
                'inv_adjust.fmemo as MEMO',
                'whf.fname as WH_FROM',
                'whd.fname as WH_TO',
                'inv_adjust_product.fproductid as ITEM_CODE',
                'mst_product.fname as ITEM_DESCRIPTION',
                'floor(inv_adjust_product.fqty) as QUANTITY',
                'inv_adjust_product.flotno as SERIAL',
                'inv_adjust.fstatus_flag as ST_STATUS_ID')
            ->whereBetween('inv_adjust.ftrxdate', [$datefrom, $dateto])
            ->where('inv_adjust.fdoctype', 101)
            ->where('inv_adjust.fcompanyid','=','whf.fcompanyid')
            ->where('inv_adjust.fcompanyid','=','whd.fcompanyid')
            ->where('whf.fname','DIGITS WAREHOUSE')
            ->where('inv_adjust.fstatus_flag', 6)
            ->where('whf.factive_flag', 1)
            ->where('inv_adjust.fcompanyid', 'BC-17052282')
            ->where('mst_product.fcompanyid', 'BC-17052282')
            ->get();

        foreach ($data['st_details'] as $key => $value) {
            DB::table('pos_pull')->insert((array)$value);
        }

        dd($data);
    }

    public function getProduct($item)
    {   
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <fproductid>".$item."</fproductid>
                        <fthirdpartyid></fthirdpartyid>
                        <fkeyword></fkeyword>
                    </filter>
                </data>
            </root>";

        $result = $this->client->call("GET_PRODUCT",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
        
    }

    public function checkSTNumber($document_no)
    {
        $result = self::getStockTransfer($document_no);
        return $result;
        // if($result['data']['record']['fstatus_flag'] == 'POSTED'){
        //     CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#: '.$document_no.' is posted!','success')->send();
        // }
        // else{
        //     CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#: '.$document_no.' is not posted!','warning')->send();
        // }
    }

    public function getStockTransfer($document_no)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <fdocument_no>".$document_no."</fdocument_no>
                        <ffrom>".date('Ymd')."</ffrom>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_TRANSFER",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
    
    public function getPOSItem($itemcode)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <fproductid>".$itemcode."</fproductid>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_PRODUCT",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
    
    public function getStockTransferByRef($dr_number)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <freference_code>".$dr_number."</freference_code>
                        <ffrom>".date('Ymd')."</ffrom>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_TRANSFER",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
    
    public function getStockTransferByRefManual($dr_number,$date)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <freference_code>".$dr_number."</freference_code>
                        <ffrom>".$date."</ffrom>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_TRANSFER",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function getStockAdjustment($dr_number)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <freference_code>".$dr_number."</freference_code>
                        <fsiteid>DIGITSWAREHOUSE</fsiteid>
                        <ffrom>".date('Ymd')."</ffrom>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_ADJUSTMENT",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
    
    public function getStockAdjustmentManual($dr_number,$date)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <freference_code>".$dr_number."</freference_code>
                        <fsiteid>DIGITSWAREHOUSE</fsiteid>
                        <ffrom>".$date."</ffrom>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_ADJUSTMENT",$parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function getStockStatus($item, $warehouse)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>".$this->w3p_id."</fw3p_id>
                    <fw3p_key>".$this->w3p_key."</fw3p_key>
                </id>

                <data>
                    <filter>
                        <fproductid>".$item."</fproductid>
                        <fsiteid>".$warehouse."</fsiteid>
                        <fby_lot>1</fby_lot>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_STATUS", $parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);

    }

    public function getSI($document_no)
    {
        return DB::connection('webpos')->table('inv_adjust')
            ->join('inv_adjust_product', 'inv_adjust.frecno', '=','inv_adjust_product.frecno')
            ->join('mst_product', 'inv_adjust_product.fproductid', '=', 'mst_product.fproductid')
            ->join('mst_warehouse as mst', 'inv_adjust.fsiteid', '=', 'mst.fsiteid')
            ->join('mst_warehouse as msd', 'inv_adjust.fdst_siteid', '=', 'msd.fsiteid')
            ->join('mst_salesoffice', 'inv_adjust.fofficeid', '=', 'mst_salesoffice.fofficeid')
            ->select('inv_adjust.ftrxdate AS SI_DATE',	
                'inv_adjust.fdocument_no AS SI_NUMBER',	
                'inv_adjust.freference_code AS BEA_DR_NUMBER',	
                'inv_adjust.fmemo  AS MEMO',	
                'mst.fname AS TRANSACTING_BRANCH',	
                'msd.fname AS ADJUSTED_WAREHOUSE',		
                'inv_adjust_product.fproductid  AS ITEM_CODE',	
                'mst_product.fname AS ITEM_DESCRIPTION',	
                'inv_adjust_product.fqty AS QTY',	
                'inv_adjust_product.flotno AS SERIAL',
                'inv_adjust.fstatus_flag AS STATUS') //6-posted
            ->whereColumn('inv_adjust.fcompanyid','=','mst_warehouse.fcompanyid')
            ->whereColumn('mst_salesoffice.fcompanyid','=','mst_warehouse.fcompanyid')
            ->where('inv_adjust.fdoctype', 0)
            ->where('inv_adjust.fcompanyid', 'BC-17052282')
            ->where('mst_product.fcompanyid', 'BC-17052282')
            ->where('inv_adjust.freference_code', $document_no)
            ->get();
    }

    public function getST($document_no)
    {
        return DB::connection('webpos')->table('inv_adjust')
            ->leftJoin('inv_adjust_product', 'inv_adjust.frecno', '=','inv_adjust_product.frecno')
            ->leftJoin('mst_product', 'inv_adjust_product.fproductid', '=', 'mst_product.fproductid')
            ->leftJoin('mst_warehouse as mst', 'inv_adjust.fsiteid', '=', 'mst.fsiteid')
            ->leftJoin('mst_warehouse as msd', 'inv_adjust.fdst_siteid', '=', 'msd.fsiteid')
            ->join('mst_salesoffice', 'inv_adjust.fofficeid', '=', 'mst_salesoffice.fofficeid')
            ->select('inv_adjust.ftrxdate AS ST_DATE',	
                'inv_adjust.fdocument_no AS ST_NUMBER',	
                'inv_adjust.freference_code AS BEA_DR_NUMBER',	
                'inv_adjust.fmemo  AS MEMO',	
                'mst.fname AS FROM_WH',	
                'msd.fname AS TO_WH',		
                'inv_adjust_product.fproductid  AS ITEM_CODE',	
                'mst_product.fname AS ITEM_DESCRIPTION',	
                'inv_adjust_product.fqty AS QTY',	
                'inv_adjust_product.flotno AS SERIAL',
                'inv_adjust.fstatus_flag AS STATUS') //6-posted
            ->whereColumn('inv_adjust.fcompanyid','=','mst.fcompanyid')
            ->whereColumn('inv_adjust.fcompanyid','=','msd.fcompanyid')
            ->where('inv_adjust.fdoctype', 101)
            ->where('inv_adjust.fcompanyid', 'BC-17052282')
            ->where('mst_product.fcompanyid', 'BC-17052282')
            ->where('inv_adjust.freference_code', $document_no)
            ->get();
    }

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
}
