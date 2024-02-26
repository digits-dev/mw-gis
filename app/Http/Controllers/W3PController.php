<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class W3PController extends Controller
{
    private $client;

    public function __construct()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
    }

    public function getAccount()
    {   
        ini_set("soap.wsdl_cache_enabled", "0");
        
        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>
                <data>
                    <filter>
                        <fkeyword></fkeyword>
                    </filter>
                </data>
            </root>";

        $result = $client->call("GET_ACCOUNT",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
		
        
    }
    
    public function getProduct($item)
    {   
        ini_set("soap.wsdl_cache_enabled", "0");
        //18090382
        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        //17052282-live
        //17020882-demo
        /**
         * BC-17012382
         * BC-17020882
         */
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>
                <data>
                    <filter>
                        <fproductid>".$item."</fproductid>
                        <fthirdpartyid></fthirdpartyid>
                        <fkeyword></fkeyword>
                    </filter>
                </data>
            </root>";

        $result = $client->call("GET_PRODUCT",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
		
        
    }

    public function getStockAdjustment()
    {   
        ini_set("soap.wsdl_cache_enabled", "0");
        //stacc.alliancewebpos.com:1026
        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        /*
        $client = new \SoapClient("https://bc.alliancewebpos.com:1026/appserv/soap/main.wsdl", 
            array("location" => "https://bc.alliancewebpos.com:1026/appserv/soap/CSoapServer.php")); 
        */
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>
                <data>
                    <filter>
                        <fdocument_no></fdocument_no>
                        <freference_code></freference_code>
                        <fsiteid></fsiteid>
                        <fmemo></fmemo>
                        <fposted_by></fposted_by>
                        <ffrom>20200701</ffrom>
                        <fto>20200702</fto>
                        <ftrxtype></ftrxtype>
                    </filter>
                </data>
            </root>";

        $result = $client->call("GET_STOCK_ADJUSTMENT",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
		
        
    }

    public function getPOSItems() {
        $data = [];
        $data['result'] = DB::connection('webpos')->table('mst_product')->select('fproductid','fname')->limit(5)->get();
        dd($data);
    }

    public function createStockAdjustment()
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
            //<fdoctype>1</fdoctype> OUT
            //<fdoctype>0</fdoctype> IN
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdoctype>0</fdoctype>
                        <freference_code>MEMO-12345678A</freference_code>
                        <ftrxdate>20201027</ftrxdate>
                        <fsiteid>BTBPIAZZA</fsiteid>
                        <fthirdparty_siteid/>
                        <fmemo/>
                        <fcreated_date>20201027164444</fcreated_date>
                        <product>
                            <fproductid>80006574</fproductid>
                            <fthirdparty_productid/>
                            <fqty>2</fqty>
                            <fuom>PCS</fuom>
                            <flotno>12345678</flotno>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        
                        
                    </record>
                </data>

            </root>";
        
        $result = $client->call("SAVE_STOCK_ADJUSTMENT",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function cancelStockAdjustment($documentNumber)
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdocument_no>".$documentNumber."</fdocument_no>
                    </record>
                </data>

            </root>";
        $result = $client->call("VOID_STOCK_ADJUSTMENT",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function createStockTransfer()
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <freference_code/>
                        <fdoctype>101</fdoctype>
                        <ftrxdate>20210121</ftrxdate>
                        <fsiteid>NEWWAREHOUSE</fsiteid>
                        <fdst_siteid>GDWCENTURY</fdst_siteid>
                        <fofficeid>DIGITSWAREHOUSE</fofficeid>
                        <fstatus_flag>0</fstatus_flag>
                        <fmemo/>
                        <fcreated_date>20210122080000</fcreated_date>
                        <product>
                            <fproductid>80006574</fproductid>
                            <fthirdparty_productid/>
                            <fqty>1</fqty>
                            <fuom>PCS</fuom>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        
                    </record>
                </data>

            </root>";

        $result = $client->call("SAVE_STOCK_TRANSFER",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function createStockTransferTest()
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        //date('YmdHis') => 20210215115300
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>
                <data>
                    <record>
                        <freference_code>FHI".date('YmdHis')."</freference_code>
                        <ftrxdate>".date('Ymd')."</ftrxdate>
                        <fsiteid>GDWCENTURY</fsiteid>
                        <fthirdparty_siteid/>
                        <fdst_siteid>TDWCENTURY</fdst_siteid>
                        <fmemo/>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>
                        <product>
                            <fproductid>80006574</fproductid>
                            <fthirdparty_productid/>
                            <fqty>1</fqty>
                            <fuom>PCS</fuom>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        <product>
                            <fproductid>80006575</fproductid>
                            <fthirdparty_productid/>
                            <fqty>1</fqty>
                            <fuom>PCS</fuom>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        
                    </record>
                </data>
            </root>";

        $result = $client->call("SAVE_STOCK_TRANSFER",$parameter);
        \Log::info(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function createStockTransferTestStore()
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        //date('YmdHis') => 20210215115300
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>
                <data>
                    <record>
                        <freference_code>MIK".date('YmdHis')."</freference_code>
                        <ftrxdate>".date('Ymd')."</ftrxdate>
                        <fofficeid>GDWCENTURY</fofficeid>
                        <fsiteid>GDWCENTURY</fsiteid>
                        <fthirdparty_siteid/>
                        <fdst_siteid>TDWCENTURY</fdst_siteid>
                        <fmemo/>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>
                        <product>
                            <fproductid>80006574</fproductid>
                            <fthirdparty_productid/>
                            <fqty>1</fqty>
                            <fuom>PCS</fuom>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        <product>
                            <fproductid>80006575</fproductid>
                            <fthirdparty_productid/>
                            <fqty>1</fqty>
                            <fuom>PCS</fuom>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        
                        
                    </record>
                </data>
            </root>";

        $result = $client->call("SAVE_STOCK_TRANSFER",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function createStockTransferTestNewStore()
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        //date('YmdHis') => 20210215115300
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>
                <data>
                    <record>
                        <freference_code>FHI".date('YmdHis')."</freference_code>
                        <ftrxdate>".date('Ymd')."</ftrxdate>
                        <fofficeid>GDWCENTURY</fofficeid>
                        <fsiteid>GDWCENTURY</fsiteid>
                        <fthirdparty_siteid/>
                        <fdst_siteid>TDWCENTURY</fdst_siteid>
                        <fmemo/>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>
                        <product>
                            <fproductid>80006574</fproductid>
                            <fthirdparty_productid/>
                            <fqty>1</fqty>
                            <fuom>PCS</fuom>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        <product>
                            <fproductid>80006575</fproductid>
                            <fthirdparty_productid/>
                            <fqty>1</fqty>
                            <fuom>PCS</fuom>
                            <fuomqty>1.000000</fuomqty>
                            <fextprice>2590</fextprice>
                        </product>
                        
                    </record>
                </data>
            </root>";

        $result = $client->call("SAVE_STOCK_TRANSFER",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function cancelStockTransfer($documentNumber)
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdocument_no>".$documentNumber."</fdocument_no>
                    </record>
                </data>

            </root>";
        $result = $client->call("VOID_STOCK_TRANSFER",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function getStockTransfer($datefrom, $dateto) 
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <filter>
                        <ffrom>".$datefrom."</ffrom>
                        <fto>".$dateto."</fto>
                                  
                    </filter>
                </data>

            </root>";

        $result = $client->call("GET_STOCK_TRANSFER",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function getStockTransferNext($datefrom, $dateto, $new_batchid, $last_key) 
    {
        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient("https://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "https://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17020882</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <filter>
                        <ffrom>".$datefrom."</ffrom>
                        <fto>".$dateto."</fto>
                        <fnew_batchid>".$new_batchid."</fnew_batchid>
                        <flast_key>".$last_key."</flast_key>          
                    </filter>
                </data>

            </root>";

        $result = $client->call("GET_STOCK_TRANSFER",$parameter);
        dd(json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function getStockStatus($item, $warehouse)
    {
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <filter>
                        <fproductid>".$item."</fproductid>
                        <fsiteid>".$warehouse."</fsiteid>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_STATUS", $parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);

    }

    public function getStockStatusByWarehouse($warehouse)
    {
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <filter>
                        <fsiteid>".$warehouse."</fsiteid>
                    </filter>
                </data>

            </root>";

        $result = $this->client->call("GET_STOCK_STATUS", $parameter);
        return json_decode(json_encode(simplexml_load_string($result)), true);

    }

    public function callRunInteface()
    {
        return DB::connection('oracle')->sentence('EXEC PROCESS_TRANSACTION_BPG');
    }
}
