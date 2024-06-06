<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Delivery;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin/login');
});
Route::get('/php-info', function () {
    return phpinfo();
});

Route::get('/admin/get_pending_dr/{store_id}','TripTicketController@getPendingDrByStore');
Route::get('/admin/get_pending_sts/{store_id}','TripTicketController@getPendingSTSByStore');
Route::get('/admin/get_pending_stwr/{store_id}','TripTicketController@getPendingInboundSTWRByStore');
Route::get('/admin/get_pending_inbound_sts/{store_id}','TripTicketController@getPendingInboundSTSByStore');
Route::get('/admin/get_receiving_outbound_trips/{store_id}/{trip_number}','TripTicketController@getReceivingOutboundByStore');
Route::get('/admin/get_releasing_inbound_trips/{store_id}/{trip_number}','TripTicketController@getReleasingInboundByStore');

//wrs
Route::get('/admin/wrs/receive_stw','AdminWrsController@getReceiveSTW');
Route::get('/admin/wrs/receive_others','AdminWrsController@getReceiveOther');
Route::post('/admin/received_stw','AdminWrsController@saveReceiveSTW')->name('saveReceiveSTW');
Route::post('/admin/received_others','AdminWrsController@saveReceiveOthers')->name('saveReceiveOthers');
Route::get('/admin/wrs/details/{wrs_number}','AdminWrsController@getWrsDetail');
Route::get('/admin/wrs/print/{wrs_number}','AdminWrsController@getWrsPrint');

Route::get('/admin/wrs/stw/{st_number}','AdminWrsController@getSTWDetails')->name('getSTWDetails');
Route::get('/admin/wrs/waive-items/{item_code}','AdminWrsController@searchItem');
Route::post('/admin/wrs/stw_reset_serial','AdminWrsController@resetSerialCounter')->name('stwResetSerialCounter');

//end-wrd

Route::post('/admin/create_outbound_trip_ticket','AdminTripTicketsController@saveOutboundTripTicket')->name('saveOutboundTripTicket');
Route::post('/admin/create_inbound_trip_ticket','AdminTripTicketsController@saveInboundTripTicket')->name('saveInboundTripTicket');
Route::post('/admin/update_trip_ticket','AdminTripTicketsController@updateTripTicket')->name('updateTripTicket');

Route::post('/admin/receive_outbound_trip_ticket','AdminTripTicketsController@saveReceiveTripTicket')->name('saveReceiveTripTicket');
Route::post('/admin/release_inbound_trip_ticket','AdminTripTicketsController@saveReleaseTripTicket')->name('saveReleaseTripTicket');

Route::get('/admin/trip_tickets/receive_outbound_trip_ticket','AdminTripTicketsController@getReceiveTripTicket');
Route::get('/admin/trip_tickets/release_inbound_trip_ticket','AdminTripTicketsController@getReleaseTripTicket');

Route::get('/admin/trip_tickets/details/{trip_number}','AdminTripTicketsController@getDetail');
Route::get('/admin/trip_tickets/store-details/{trip_number}','AdminTripTicketsController@getTripStoreDetail');
Route::get('/admin/trip_tickets/create-trip-ticket-outbound','AdminTripTicketsController@getTripTicketOutbound');
Route::get('/admin/trip_tickets/create-trip-ticket-inbound','AdminTripTicketsController@getTripTicketInbound');
Route::get('/admin/trip_tickets/update-trip/{trip_number}', 'AdminTripTicketsController@getUpdateTripTicket');
Route::get('/admin/trip_tickets/print-trip/{trip_number}', 'AdminTripTicketsController@getPrintTripTicket');
Route::get('/admin/trip_tickets/export-trip-tickets','AdminTripTicketsController@exportTripTicket')->name('trip-ticket.export');

//BEA
Route::get('/admin/close_trip/{p_delivery_id}','EBSPushController@closeTrip')->name('ebspush.closeTrip');
Route::get('/admin/accepted_date/{p_delivery_id}','EBSPushController@acceptedDate')->name('ebspush.acceptedDate');
Route::get('/admin/doo_receiving/{dr_number}','EBSPushController@dooReceiving')->name('ebspush.dooReceiving');

Route::get('/admin/sales_order_pull/{datefrom}/{dateto}','EBSPullController@salesOrderPullManual')->name('ebspull.salesOrder');
Route::get('/admin/sales_order_pull_admin/{datefrom}/{dateto}','EBSPullController@salesOrderPullAdminManual')->name('ebspull.salesOrderAdmin');
Route::get('/admin/sales_order_pull','EBSPullController@salesOrderPull')->name('ebspull.salesOrderV2');
Route::get('/admin/move_order_pull','EBSPullController@moveOrderPull')->name('ebspull.moveOrderV2');

Route::get('/admin/wrr_pull/{datefrom}/{dateto}','EBSPullController@getWrrManual')->name('ebspull.getWrrManual');
Route::get('/admin/distri_moveorder_pull/{datefrom}/{dateto}','EBSPullController@moveOrderPullDistriManual')->name('ebspull.moveOrderPullDistriManual');

Route::get('/admin/bea_autocreate_item','ItemsController@pushBEAItemCreation')->name('ebspull.autocreateitems');
Route::get('/admin/bea_autocreate_item/{datefrom}/{dateto}','ItemsController@pushBEAItemCreationManual')->name('ebspull.manualautocreateitems');

//POS
Route::get('/admin/get_product/{item}','POSPullController@getProduct');
Route::get('/admin/st_detail_pull/{datefrom}/{dateto}','POSPullController@posPull')->name('pospull.posPull');

//Route::get('/admin/check_product/{item}/{dr_number}','POSPushController@checkItem');
Route::get('/admin/post_si/{dr_number}','POSPushController@postSI');
Route::get('/admin/post_st/{dr_number}','POSPushController@postST');

Route::get('/admin/check_st/{document_no}','POSPullController@checkSTNumber');
// Route::get('/admin/doAllPhaseI','AdminBeaTransactionsController@doAllPhaseI');
// Route::get('/admin/doAllPhaseII','AdminBeaTransactionsController@doAllPhaseII');

//W3P
// Route::get('/admin/get_account','W3PController@getAccount');
Route::get('/admin/get_stock_adjustment_dr/{dr_number}/{date}','POSPullController@getStockAdjustmentManual');

Route::get('/admin/get_stock_adjustment/{dr_number}','POSPullController@getStockAdjustment');

// Route::get('/admin/create_stock_adjustment','W3PController@createStockAdjustment');
// Route::get('/admin/cancel_stock_adjustment/{documentNumber}','W3PController@cancelStockAdjustment');
// Route::get('/admin/get_stock_transfer/{datefrom}/{dateto}','W3PController@getStockTransfer'); 
Route::get('/admin/get_st_dr/{dr_number}/{date}','POSPullController@getStockTransferByRefManual');
// Route::get('/admin/create_stock_transfer','W3PController@createStockTransfer');
// Route::get('/admin/create_stock_transfer_test','W3PController@createStockTransferTest');
// Route::get('/admin/create_stock_transfer_test_store','W3PController@createStockTransferTestStore');
// Route::get('/admin/create_stock_transfer_test_newstore','W3PController@createStockTransferTestNewStore');
// Route::get('/admin/cancel_stock_transfer/{documentNumber}','W3PController@cancelStockTransfer');
// Route::get('/admin/get_webpos_items','W3PController@getPOSItems');
Route::get('/admin/get_stock_status/{item}/{warehouse}','POSPullController@getStockStatus');
// Route::get('/admin/get_stock_status/{warehouse}','W3PController@getStockStatusByWarehouse');

Route::get('/admin/get_items_created','ItemsController@getItemsCreatedAPI');
Route::get('/admin/get_items_updated','ItemsController@getItemsUpdatedAPI');
Route::get('/admin/bea_items_created','ItemsController@pushBEAItemCreation');
Route::get('/admin/mw_update_bea_item_id','ItemsController@middlewareUpdateBEAItem');
Route::get('/admin/pos_items_created','ItemsController@pushPOSItemCreation');

Route::get('/admin/users/users-upload','AdminCmsUsersController@uploadUsers');
Route::get('/admin/users/download-users-template','AdminCmsUsersController@uploadUsersTemplate');
Route::post('/admin/users/upload-users','AdminCmsUsersController@usersUpload')->name('upload.users');

// Route::get('/admin/run_interface','W3PController@callRunInteface');
Route::post('/admin/bea_transactions/move_order_pull/with-time','EBSPullController@moveOrderPullManualTime')->name('ebspull.runMoveOrderPullWithTime');
Route::post('/admin/bea_transactions/sales_order_pull/with-time','EBSPullController@salesOrderPullManualTime')->name('ebspull.runSalesOrderPullWithTime');
Route::get('/admin/bea_transactions/move_order_pull/{datefrom}/{dateto}','EBSPullController@moveOrderPullManual')->name('ebspull.runMoveOrderPull');
Route::get('/admin/bea_transactions/move_order_pull_onl/{datefrom}/{dateto}','EBSPullController@moveOrderPullOnlManual')->name('ebspull.runMoveOrderPullOnl');
Route::get('/admin/bea_transactions/move_order_pull','AdminBeaTransactionsController@getMoveOrder')->name('ebspull.getMoveOrderPull');
Route::get('/admin/bea_transactions/move_order_pull_rma/{datefrom}/{dateto}','EBSPullController@moveOrderPullRmaManual')->name('ebspull.runMoveOrderPullRma');

//Receiving
Route::get('/admin/delivery_receiving/{dr_number}','AdminDeliveryReceivingController@getReceiving')->name('dr.receiving');
Route::get('/admin/delivery_receiving/details/{dr_number}','AdminDeliveryReceivingController@getDetail')->name('dr-receiving.detail');
Route::post('/admin/search_items','AdminDeliveryReceivingController@itemSearch')->name('itemSearch');
Route::post('/admin/search_serial','AdminDeliveryReceivingController@serialSearch')->name('serialSearch');
Route::post('/admin/dr_reset_serial','AdminDeliveryReceivingController@resetSerialCounter')->name('drResetSerialCounter');
Route::post('/admin/st_receiving','AdminDeliveryReceivingController@saveReceivingST')->name('saveReceivingST');

//Store ST Creation
Route::get('/admin/store_transfers/create','AdminStoreTransferController@getScan')->name('st.scanning');
Route::get('/admin/store_transfers/details/{st_number}','AdminStoreTransferController@getDetail')->name('st.detail');
Route::get('/admin/store_transfers/schedule/{st_number}','AdminStoreTransferController@getSchedule')->name('st.schedule');
Route::get('/admin/store_transfers/print/{st_number}','AdminStoreTransferController@getPrint')->name('st.print');
Route::get('/admin/store_transfers/void-st/{st_number}','AdminStoreTransferController@voidStockTransfer')->name('st.void');

Route::post('/admin/st_creation_online','AdminStoreTransferController@saveCreateOnlineST')->name('saveCreateOnlineST');

//Store GIS ST Creation
Route::get('/admin/store_transfers/create-gis-sts','AdminStoreTransferGisController@getGisScan')->name('st.gis.scanning');
Route::post('/admin/search_scan_items_gis','AdminStoreTransferGisController@scanItemSearchGis')->name('scanItemSearchGis');
Route::post('/admin/st_gis_creation','AdminStoreTransferGisController@saveCreateGisST')->name('saveCreateGisST');
Route::get('/admin/store_transfers_gis/gis-details/{ref_number}','AdminStoreTransferGisController@getDetail')->name('st.gis-detail');
Route::get('/admin/store_transfers_gis/void-st-gis/{id}','AdminStoreTransferGisController@getVoidStGis');
Route::get('/admin/store_transfers/schedule-gis/{id}','AdminStoreTransferGisController@getScheduleGis')->name('st.schedule.gis');
Route::post('/admin/schedule_transfer_gis','AdminStoreTransferGisController@saveScheduleGis')->name('saveScheduleTransferGis');
Route::get('/admin/store_transfers_gis/print-gis/{id}','AdminStoreTransferGisController@getPrintGis')->name('st.print.gis');
//Store GIS ST Approval
Route::get('/admin/gis_pull_approval/getRequestApproval/{id}','AdminGisPullApprovalController@getRequestApproval')->name('st.gis.approval');
//Store GIS ST For Receiving
Route::get('/admin/st_gis_receiving/getStForReceiving/{id}','AdminStGisReceivingController@getStForReceiving')->name('st.gis.receiving');

 //ST Approval
Route::get('/admin/transfer_approval/review/{st_number}','AdminStoreTransferApprovalController@getApproval')->name('st-approval.review');
Route::get('/admin/transfer_approval/details/{st_number}','AdminStoreTransferApprovalController@getDetail')->name('st-approval.detail');
Route::post('/admin/st_approval','AdminStoreTransferApprovalController@saveReviewST')->name('saveReviewST');

Route::post('/admin/st_creation','AdminStoreTransferController@saveCreateST')->name('saveCreateST');
Route::post('/admin/search_scan_items','AdminStoreTransferController@scanItemSearch')->name('scanItemSearch');
Route::post('/admin/schedule_transfer','AdminStoreTransferController@saveSchedule')->name('saveScheduleTransfer');
Route::post('/admin/st_search_serial','AdminStoreTransferController@scanSerialSearch')->name('scanSerialSearch');

Route::get('/admin/get_transit_warehouse/{good_warehouse}','AdminStoresController@getTransitWarehouse')->name('getTransitWarehouse');
Route::get('/admin/get_rma_warehouse/{good_warehouse}','AdminStoresController@getRMAWarehouse')->name('getRMAWarehouse');

//ST Receiving
Route::get('/admin/transfer_receiving/{st_number}','AdminStoreTransferReceivingController@getReceiving')->name('st-receiving.receive');
Route::get('/admin/transfer_receiving/details/{st_number}','AdminStoreTransferReceivingController@getDetail')->name('st-receiving.detail');
Route::post('/admin/receiving_sts','AdminStoreTransferReceivingController@saveSTSReceiving')->name('saveSTSReceiving');
Route::post('/admin/receiving_online_sts','AdminStoreTransferReceivingController@saveSTSOnlineReceiving')->name('saveSTSOnlineReceiving');
Route::post('/admin/rcv_search_serial','AdminStoreTransferReceivingController@serialSearch')->name('rcvSerialSearch');
Route::post('/admin/rcv_search_items','AdminStoreTransferReceivingController@itemSearch')->name('receiveItemSearch');
Route::post('/admin/sts_reset_serial','AdminStoreTransferReceivingController@resetSerialCounter')->name('stsResetSerialCounter');
//

//Deliveries
Route::get('/admin/deliveries/details/{dr_number}','AdminDeliveryController@getDetail')->name('dr.detail');
Route::get('/admin/deliveries/export-dr-serialized','AdminDeliveryController@exportDeliverySerialized')->name('dr.export-delivery-serialized');
Route::get('/admin/deliveries/export-dr','AdminDeliveryController@exportDelivery')->name('dr.export-delivery');
Route::get('/admin/deliveries/receive_dr/{dr_number}','AdminDeliveryController@drReceivingST')->name('dr.receive-dr');

Route::get('/admin/deliveries/push_dotr_interface/{dr_number}','AdminDeliveryController@dotrPushInterface')->name('dr.push-dotr-interface'); //2022-04-05
Route::get('/admin/deliveries/stockout_dr/{st_number}/{customer_name}','AdminDeliveryController@drStockout')->name('dr.stockout-dr'); //2021-11-03
Route::get('/admin/deliveries/edit_stockout_dr/{dr_number}','AdminDeliveryController@getdrStockout')->name('dr.stockout-view'); //2021-11-03
Route::post('/admin/deliveries/edit_stockout_dr','AdminDeliveryController@saveDRStockout')->name('saveDRStockout'); //2021-11-03


Route::get('/admin/sts_history/export-sts-serialized','AdminStoreTransferHistoryController@exportSTSSerialized')->name('sts.export-sts-serialized');
Route::get('/admin/sts_history/export-sts','AdminStoreTransferHistoryController@exportSTS')->name('sts.export-sts');
Route::get('/admin/sts_history/print/{st_number}','AdminStoreTransferHistoryController@getPrint')->name('st-history.print');

Route::get('/admin/pullout_history/export-stw-serialized','AdminPulloutHistoryController@exportSTWSerialized')->name('stw.export-stw-serialized');
Route::get('/admin/pullout_history/export-stw','AdminPulloutHistoryController@exportSTW')->name('stw.export-stw');

Route::get('/admin/pullout_history/export-str-serialized','AdminPulloutHistoryController@exportSTRSerialized')->name('str.export-str-serialized');
Route::get('/admin/pullout_history/export-str','AdminPulloutHistoryController@exportSTR')->name('str.export-str');

Route::get('/admin/pullout_history/export-stwr-serialized','AdminPulloutHistoryController@exportSTWRSerialized')->name('stwr.export-stwr-serialized');
Route::get('/admin/pullout_history/export-stwr','AdminPulloutHistoryController@exportSTWR')->name('stwr.export-stwr');


//Pullout
Route::get('/admin/store_pullout/rma/create','AdminPulloutController@getRMA')->name('pullout.rma');
Route::get('/admin/store_pullout/stw/create','AdminPulloutController@getSTW')->name('pullout.stw');
Route::get('/admin/store_pullout/stw-marketing/create','AdminPulloutController@getSTWMarketing')->name('pullout.stw-marketing'); //2021-03-01
Route::get('/admin/store_pullout/details/{st_number}','AdminPulloutController@getDetail')->name('pullout.detail');
Route::get('/admin/store_pullout/schedule/{st_number}','AdminPulloutController@getSchedule')->name('pullout.schedule');
Route::get('/admin/store_pullout/print/{st_number}','AdminPulloutController@getPrint')->name('pullout.print');
Route::get('/admin/store_pullout/void-st/{st_number}','AdminPulloutController@voidStockTransfer')->name('pullout.void');
Route::get('/admin/store_pullout/received-st/{st_number}','AdminPulloutReceivingController@createReceivedST')->name('pullout.createReceivedST');
Route::get('/admin/store_pullout/create-st','AdminPulloutReceivingController@createST')->name('pullout.createST');

// Route::get('/admin/pullout_transactions/details/{st_number}','AdminPulloutTransactionsController@getDetail')->name('pullout-transactions.detail');

//Pullout Online
Route::get('/admin/store_pullout/rma-online/create','AdminPulloutController@getRMAOnline')->name('pullout.rma-online');
Route::get('/admin/store_pullout/stw-online/create','AdminPulloutController@getSTWOnline')->name('pullout.stw-online');
// Route::post('/admin/stw_online_creation','AdminPulloutController@saveCreateSTWOnline')->name('saveCreateSTWOnline');
// Route::post('/admin/rma_online_creation','AdminPulloutController@saveCreateRMAOnline')->name('saveCreateRMAOnline');
Route::get('/admin/store_pullout/rma-gis/create','AdminPulloutController@getRMAGis')->name('pullout.rma-online');

//Pullout Approval
Route::get('/admin/pullout_approval/review/{st_number}','AdminPulloutApprovalController@getApproval')->name('pullout-approval.review');
Route::get('/admin/pullout_approval/details/{st_number}','AdminPulloutApprovalController@getDetail')->name('pullout-approval.detail');
Route::post('/admin/pullout_review','AdminPulloutApprovalController@saveReviewPullout')->name('saveReviewPullout');
// Route::post('/admin/pullout_marketing_review','AdminPulloutApprovalController@saveReviewMarketingPullout')->name('saveReviewMarketingPullout'); //2021-03-01

Route::get('/admin/stw_approval/review/{st_number}','AdminStwApprovalController@getApproval')->name('stw-approval.review');
Route::get('/admin/stw_approval/details/{st_number}','AdminStwApprovalController@getDetail')->name('stw-approval.detail');
Route::post('/admin/stw_review','AdminStwApprovalController@saveReviewPullout')->name('saveReviewStw');

//2021-09-29
//pullout ecom
Route::post('/admin/cancelReserveQty','OnlineFBDController@cancelReserveQty')->name('cancelReserveQty');
Route::post('/admin/resetReserveQty','OnlineFBDController@resetReserveQty')->name('resetReserveQty');

Route::post('/admin/cancelFBVReserveQty','OnlineFBVController@cancelReserveQty')->name('cancelFBVReserveQty');
Route::post('/admin/resetFBVReserveQty','OnlineFBVController@resetReserveQty')->name('resetFBVReserveQty');

Route::post('/admin/pullout_scan_fbd_items','OnlineFBDController@scanFBDItemSearch')->name('scanFBDPulloutItem');
Route::post('/admin/pullout_scan_fbv_items','OnlineFBVController@scanFBVItemSearch')->name('scanFBVPulloutItem');
// MW GIS PULLOUT
Route::post('/admin/pullout_scan_gis_mw_items','OnlineFBVController@scanGisMwItemSearch')->name('scanGisMwPulloutItem');

//saving
Route::post('/admin/stw_fbd_creation','OnlineFBDController@saveCreateSTWOnline')->name('saveCreateSTWFBD');
Route::post('/admin/rma_fbd_creation','OnlineFBDController@saveCreateRMAOnline')->name('saveCreateRMAFBD');
Route::post('/admin/stw_fbv_creation','OnlineFBVController@saveCreateSTWOnline')->name('saveCreateSTWFBV');
Route::post('/admin/rma_fbv_creation','OnlineFBVController@saveCreateRMAOnline')->name('saveCreateRMAFBV');
//SAVING MW GIS PULLOUT
Route::post('/admin/rma_gis_mw_creation','AdminPulloutController@saveCreateRMAGisRma')->name('saveCreateRMAGisMw');

Route::get('/admin/sts_history/print-picklist/{id}','AdminStoreTransferHistoryController@printPicklist')->name('printSTSPicklist');
Route::get('/admin/pullout_history/print-picklist/{id}','AdminPulloutHistoryController@printPicklist')->name('printSTWPicklist');

Route::get('/admin/sts_picklist/print-picklist/{st_number}','AdminStsPicklistController@getPrint')->name('sts-picklist.print');
Route::get('/admin/sts_picklist/details/{st_number}','AdminStsPicklistController@getDetail')->name('sts-picklist.detail');
Route::get('/admin/sts_pick_confirm/pick-confirm/{st_number}','AdminStsPickConfirmController@pickConfirm')->name('sts-pickconfirm.confirm');
Route::get('/admin/sts_pick_confirm/details/{st_number}','AdminStsPickConfirmController@getDetail')->name('sts-pickconfirm.detail');

Route::get('/admin/pullout_picklist/print-picklist/{st_number}','AdminPulloutPicklistController@getPrint')->name('stw-picklist.print');
Route::get('/admin/pullout_picklist/details/{st_number}','AdminPulloutPicklistController@getDetail')->name('pullout-picklist.detail');
Route::get('/admin/pullout_pick_confirm/pick-confirm/{st_number}','AdminPulloutPickConfirmController@getpickConfirm')->name('getstw-pickconfirm.confirm');
Route::get('/admin/pullout_pick_confirm/details/{st_number}','AdminPulloutPickConfirmController@getDetail')->name('pullout-pickconfirm.detail');
Route::post('/admin/pullout_pick_confirm/pick-confirmed','AdminPulloutPickConfirmController@saveSTWOnlinePickConfirm')->name('saveSTWOnlinePickConfirm');
Route::post('/admin/pickconfirm_online_sts','AdminStoreTransferReceivingController@saveSTSOnlinePickConfirm')->name('saveSTSOnlinePickConfirm');

Route::get('/admin/getRetailOnhand','EBSPullController@getRetailOnhand');
Route::get('/admin/getLazadaOnhand','EBSPullController@getLazadaOnhand');
Route::get('/admin/getShopeeOnhand','EBSPullController@getShopeeOnhand');
Route::get('/admin/getDistriOnhand','EBSPullController@getDistriOnhand');

Route::get('/admin/getReceivedDOTTransactions','EBSPullController@getReceivedDOTTransactions');
Route::get('/admin/getReceivedSORTransactions','EBSPullController@getReceivedSORTransactions');
Route::get('/admin/getReceivedSORFranchiseTransactions','EBSPullController@getReceivedSORFranchiseTransactions');
Route::get('/admin/getReceivedDistriSORTransactions','EBSPullController@getReceivedDistriSORTransactions');
//end 2021-09-29


Route::get('/admin/sts_history/details/{st_number}','AdminStoreTransferHistoryController@getDetail')->name('sts-history.detail');
Route::get('/admin/pullout_history/details/{st_number}','AdminPulloutHistoryController@getDetail')->name('pullout-history.detail');

Route::post('/admin/stw_creation','AdminPulloutController@saveCreateSTW')->name('saveCreateSTW');
Route::post('/admin/rma_creation','AdminPulloutController@saveCreateRMA')->name('saveCreateRMA');
Route::post('/admin/stw_marketing_creation','AdminPulloutController@saveCreateMarketingSTW')->name('saveCreateMarketingSTW'); //2021-03-01
Route::post('/admin/schedule_pullout','AdminPulloutController@saveSchedule')->name('saveSchedulePullout');
Route::post('/admin/pullout_scan_items','AdminPulloutController@scanItemSearch')->name('scanPulloutItem');
Route::post('/admin/pullout_scan_serial','AdminPulloutController@scanSerialSearch')->name('scanPulloutSerial');

//distri
Route::post('/admin/pullout_scan_distri_items','DistriController@scanItemSearch')->name('scanDistriPulloutItem');
Route::post('/admin/distri_incrementReserveQty','DistriController@incrementReserveQty')->name('distriIncrementReserveQty');
Route::post('/admin/distri_decrementReserveQty','DistriController@decrementReserveQty')->name('distriDecrementReserveQty');
Route::post('/admin/stw_distri_creation','AdminPulloutController@saveCreateSTWDistri')->name('saveCreateSTWDistri');
Route::post('/admin/rma_distri_creation','AdminPulloutController@saveCreateRMADistri')->name('saveCreateRMADistri');


//Pullout Receiving
Route::post('/admin/pullout_receive_items','AdminPulloutReceivingController@itemSearch')->name('receivePulloutItem');
Route::post('/admin/pullout_receive_serial','AdminPulloutReceivingController@serialSearch')->name('pulloutSerialSearch');
Route::get('/admin/pullout_receiving/{st_number}','AdminPulloutReceivingController@getReceiving')->name('pullout.receiving');
Route::get('/admin/pullout_receiving/online/{st_number}','AdminPulloutReceivingController@getReceivingOnline')->name('pullout.receivingOnline');
Route::get('/admin/pullout_receiving/details/{st_number}','AdminPulloutReceivingController@getDetail')->name('pullout.receivingdetail');
Route::post('/admin/receiving_pullout_stw','AdminPulloutReceivingController@savePulloutSTWReceiving')->name('savePulloutSTWReceiving');
Route::post('/admin/receiving_pullout_rma','AdminPulloutReceivingController@savePulloutRMAReceiving')->name('savePulloutRMAReceiving');

Route::post('/admin/stwr_reset_serial','AdminPulloutReceivingController@resetSerialCounter')->name('stwrResetSerialCounter');

Route::get('/admin/pullout-received/{datefrom}/{dateto}','AdminPulloutReceivingController@closeReceiving')->name('pullout.closeReceiving');
Route::get('/admin/updateSOR','AdminPulloutController@updateSORNumber')->name('pullout.updateSOR');


//Store Submaster
Route::get('/admin/stores/import','AdminStoresController@getStoreImportTemplate')->name('stores.upload-template');
Route::get('/admin/stores/import/download-template','AdminStoresController@downloadStoreImportTemplate')->name('stores.download-template');
Route::post('/admin/stores/import/create','AdminStoresController@storeImport')->name('stores.upload');


Route::resource('/admin/reports', ReportsController::class);
Route::get('/admin/reports/generatePurchaseOrderReport/{date_from}/{date_to}','ReportsController@generatePurchaseOrderReport')->name('generatePurchaseOrderReport');

// EDITED BY LEWIE
Route::get('/admin/generateIntransitReport/{date_from}/{date_to}/{org}', 'ReportsController@generateIntransitReport');


//new dr receiving routes
Route::post('/admin/delivery_receiving/check_dr','AdminDeliveryReceivingController@checkDeliveryStatus')->name('checkDR');
Route::post('/admin/delivery_receiving/check_pos_si','AdminDeliveryReceivingController@checkPOSStockAdjustment')->name('checkSI');
Route::post('/admin/delivery_receiving/check_pos_st','AdminDeliveryReceivingController@checkPOSStockTransfer')->name('checkST');
Route::post('/admin/delivery_receiving/create_pos_si','AdminDeliveryReceivingController@createPOSAdj')->name('createPOSAdjDR');
Route::post('/admin/delivery_receiving/create_pos_st','AdminDeliveryReceivingController@createPOSSto')->name('createPOSStoDR');
Route::post('/admin/delivery_receiving/create_bea_dot','AdminDeliveryReceivingController@createBEADOT')->name('createDRBEA');
Route::post('/admin/delivery_receiving/get_pos_item','AdminDeliveryReceivingController@getPOSItemDetails')->name('getPOSItem');
Route::post('/admin/delivery_receiving/get_bea_item','AdminDeliveryReceivingController@getBEAItemDetails')->name('getBEAItem');

//Get Stores Options
Route::get('/get-child-options/{parentId}', 'StoreOptions@getValues');