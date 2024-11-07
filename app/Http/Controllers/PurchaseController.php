<?php

namespace App\Http\Controllers;

use App\Models\Opr_stage_status;
use App\Models\Opr_value;
use App\Models\Opr_value_other;
use App\Models\Opr_value_product;
use Illuminate\Http\Request;
#Controller
use App\Http\Controllers\CustomerController;
use App\Models\Act_activity;
use App\Models\Act_activity_type;
#Models
use App\Models\Addr_city;
use App\Models\Addr_subdistrict;
use App\Models\Addr_district;
use App\Models\Addr_province;
use App\Models\Cst_bussiness_field;
use App\Models\Cst_customer;
use App\Models\Cst_personal;
use App\Models\Opr_opportunity;
use App\Models\Ord_purchase;
use App\Models\Prd_principle;
use App\Models\Prd_subproduct;
use App\Models\Prs_contact;
use App\Models\Prs_lead;
use App\Models\Prs_lead_status;
use App\Models\Prs_lead_statuses;
use App\Models\Prs_product_offer;
use App\Models\Prs_lead_value;
use App\Models\Prs_qualification;
use App\Models\Prs_accessrule;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
#Helpers
use Str;
use DB;
use DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
	/* Tags:... */
	public function PurchaseDataView(Request $request)
	{
		return view('contents.page_purchase.purchase');
	}
	/* Tags:... */
	public function sourceDataPurchased(Request $request)
	{
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->join('ord_purchases','opr_opportunities.opr_id','=','ord_purchases.pur_oppr_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name','pur_id')
			// ->whereIn('lds_id',$lead_ids)
			->get();
		}elseif(checkRule(array('MGR'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->join('ord_purchases','opr_opportunities.opr_id','=','ord_purchases.pur_oppr_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name','pur_id')
			->whereIn('lds_id',$lead_ids)
			->get();
		}elseif(checkRule(array('STF'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->join('ord_purchases','opr_opportunities.opr_id','=','ord_purchases.pur_oppr_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name','pur_id')
			->whereIn('lds_id',$lead_ids)
			->get();
		}elseif(checkRule(array('MGR.TCH'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['technical'])->select('slm_lead')->get();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name')
			->whereIn('lds_id',$lead_ids)
			->get();
		}elseif(checkRule(array('STF.TCH'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['technical'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name')
			->whereIn('lds_id',$lead_ids)
			->get();
		}
		return DataTables::of($lead_data)
		->addIndexColumn()
		->addColumn('empty_str', function ($k) {
			return '';
		})
		->addColumn('menu', function ($lead_data) {
			return '
			<div style="text-align:center;">
			<button type="button" class="badge bg-cyan" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="ri-list-settings-line"></i></button>
			<div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 38px);">
			<a class="dropdown-item" href="'.url('purchased/detail').'/'.$lead_data->pur_id.'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Purchase</a>
      </div>
			</div>
			';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('title', function ($lead_data) {
			return $lead_data->lds_title;
		})
		->addColumn('customer', function ($lead_data) {
			return $lead_data->cst_name;
		})
		->addColumn('status', function ($lead_data) {
			if ($lead_data->oss_status_name == null || $lead_data->oss_status_name == "" ) {
				return "-";
			}else{
				return "<strong>".$lead_data->oss_status_name."</strong>";
			}
		})
		->addColumn('salesperson', function ($lead_data) {
			if ($lead_data->name == null || $lead_data->name == "" ) {
				return "-";
			}else{
				return $lead_data->name;
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu'])
		->make('true');
	}
	/* Tags:... */
	public function sourceDataPurchase(Request $request)
	{
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name')
			->get();
		}elseif (checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_id = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name')
			->whereIn('lds_id',$lead_id)
			->get();
		} elseif (checkRule(array('MGR.TCH'))) {
			# code...
			$lead_data = Prs_accessrule::whereIn('slm_rules',['technical'])->select('slm_lead')->get();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				# code...
				$lds_idr[$key] = $value->slm_lead;
			}
			$lds_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name')
			->whereIn('lds_id',$lds_ids)
			->get();
		}elseif(checkRule(array('STF'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$ids = array();
			foreach ($lead_data as $key => $value) {
				$ids[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name')
			->whereIn('lds_id',$lead_ids)
			->get();
		}elseif(checkRule(array('STF.TCH'))){
			$lead_user_master = Prs_accessrule::where('slm_rules','technical')->where('slm_user',$user->id)->select('slm_lead')->get();
			$ids = array();
			foreach ($lead_user_master as $key => $value) {
				$ids[$key] = $value->slm_lead;
			}
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name')
			->whereIn('lds_id',$lead_ids)
			->get();
		}
		return DataTables::of($lead_data)
		->addIndexColumn()
		->addColumn('empty_str', function ($k) {
			return '';
		})
		->addColumn('menu', function ($lead_data) {
			return '
			<div style="text-align:center;">
			<button type="button" class="badge bg-cyan" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="ri-list-settings-line"></i></button>
			<div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 38px);">
			<a class="dropdown-item" href="'.url('leads/detail-lead').'/'.$lead_data->lds_id.'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Lead</a>
      </div>
			</div>
			';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('title', function ($lead_data) {
			return $lead_data->lds_title;
		})
		->addColumn('customer', function ($lead_data) {
			return $lead_data->cst_name;
		})
		->addColumn('status', function ($lead_data) {
			if ($lead_data->pls_code_name == 'prospecting') {
				return '<span class="badge bg-blue-lt">Prospecting</span>';
			}elseif ($lead_data->pls_code_name == 'qualifying') {
				return '<span class="badge bg-azure-lt">Qualifying</span>';
			}else{
				return '<span class="badge bg-green-lt">Opportunity</span>';
			}
		})
		->addColumn('salesperson', function ($lead_data) {
			if ($lead_data->name == "" || $lead_data->name == null) {
				return '<i>-</i>';
			}else{
				return '<style="text-align:center;">'.$lead_data->name.'</style>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu'])
		->make('true');
	}
	/* Tags:... */
	public function storeDataPurchase_a(Request $request)
	{
		$user = auth()->user();
		$date_now = date('Y-m-d');
		$checkPurchase = Ord_purchase::where('pur_oppr_id', $user->id)->first();
		if ($checkPurchase != null) {
			Ord_purchase::where('pur_id',$checkPurchase->pur_id)->delete();
		}
		$id = genIdPurchase();
		$data_purchase = [
			"pur_id" => $id,
			"pur_oppr_id" => $request->id,
			"pur_invoice" => $request->number_invoice,
			"pur_date" => $request->date_purchase,
			"pur_note" => $request->note_purchase,
			"created_by" => $user->id,
		];
		$actionStore = Ord_purchase::insert($data_purchase);
		$actionUpdate = Opr_opportunity::where('opr_id',$request->id)->update(['opr_close_status'=>'WIN','opr_close_date'=> $date_now]);
		$result = [
			'param'=>true,
			'po_id' => $id, 
		];
		return $result;
	}
	/* Tags:... */
	public function storeDataPurchase_b(Request $request)
	{
		$user = auth()->user();
		$date_now = date('Y-m-d');
		$checkPurchase = Ord_purchase::where('pur_oppr_id', $request->opportunity)->first();
		if ($checkPurchase != null) {
			Ord_purchase::where('pur_id',$checkPurchase->pur_id)->delete();
		}
		$id = genIdPurchase();
		$data_purchase = [
			"pur_id" => $id,
			"pur_oppr_id" => $request->opportunity,
			"pur_invoice" => $request->number_invoice,
			"pur_date" => $request->date_purchase,
			"pur_note" => $request->note_purchase,
			"created_by" => $user->id,
		];
		$actionUpdate = Opr_opportunity::where('opr_id',$request->opportunity)->update(['opr_close_status'=>'WIN','opr_close_date'=> $date_now]);
		$actionStore = Ord_purchase::insert($data_purchase);
		$result = [
			'param'=>true,
			'po_id' => $id, 
		];
		return $result;
	}
	/* Tags:... */
	public function actionCheckPurchase(Request $request)
	{
		$data_purchase = Ord_purchase::where('pur_oppr_id',$request->opr_id)->first();
		if ($data_purchase == null) {
			$param = false;
			$id = null;
		}else{
			$param = true;
			$id = $data_purchase->pur_id;
		}
		$result = [
			'param'=>$param,
			'id' =>$id,
		];
		return $result;
	}
	/* Tags:... */
	public function detailPurchase(Request $request)
	{
		$user = Auth::user();
		$id_purchase = $request->id;
		$purchase_data = Ord_purchase::where('pur_id',$id_purchase)->first();
		if ($purchase_data == null) {
			echo 'test'; die();
		}
		$id_oppor = $purchase_data->pur_oppr_id;
		$allproduct = Prd_principle::get();
		$opportunity = Opr_opportunity::join('prs_leads','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
		->where('opr_id',$id_oppor)->select('opr_id','opr_status','lds_id','lds_title','lds_status','lds_describe','lds_customer','opr_status','opr_notes','opr_estimate_closing')->first();
		$closing = date('d M/Y',strtotime($opportunity->opr_estimate_closing));
		// $id_lead = $opportunity->lds_id;
		$opportunity_value = Opr_value::where('ovs_opr_id',$id_oppor)->first();
		if ($opportunity_value == null) {
			$opportunity_value = (object) [
				"opr_value_dpp" => null,
				"opr_value_hpp" => null,
				"opr_tax" => null,
				"opr_other" => null,
				"opr_revenue" =>null
			];
		}
		$cst_ids= explode(',',$opportunity->lds_customer);
		$lead_customer = Cst_customer::join('cst_institutions','cst_customers.cst_institution','=', 'cst_institutions.ins_id')
		->whereIn('cst_id', $cst_ids)
		->select('cst_id','ins_id', 'ins_name', 'cst_name', 'view_option')
		->get();
		$opportunity_customer = array();
		foreach ($lead_customer as $key => $value) {
			$opportunity_customer[$key] = $value->cst_name;
		}
		$institution_names = Str::title($lead_customer->first()->ins_name);
		$opr_value = Opr_value::where('ovs_opr_id',$id_purchase)->first();
		$opr_product_src = Opr_value_product::where('opr_value_products.por_opr_id',$id_purchase)
		->select('por_id','por_principle_name','por_product_name','por_note','por_quantity','por_unit_value','por_total_value')
		->get();
		$prd_num = 1 ;
		$opr_product = array();
		foreach ($opr_product_src as $key => $value) {
			$opr_product[$key] = [
				"id" => $value->por_id,
				"no" => $prd_num,
				"principle" => $value->por_principle_name,
				"product" => $value->por_product_name,
				"note" => $value->por_note,
				"quantity" =>  $value->por_quantity,
				"unit" => $value->por_unit_value,
				"total" => $value->por_total_value,
			];
			$prd_num++;
		}
		$otr_num = 1 ;
		$opr_other = array();
		$opr_other_src = Opr_value_other::where('ots_opr_id',$id_purchase)->get();
		foreach ($opr_other_src as $key => $value) {
			$opr_other[$key] = [
				"id" => $value->ots_id,
				"no" => $otr_num,
				"note" => $value->ots_name,
				"coast" =>  $value->ots_value,
			];
			$otr_num++;
		}
		if (count($opr_other) == 0) {
			$other_value = 0;
			$tab_row = 1;
		}else{
			$other_value = $opr_value->ovs_value_other_cost;
			$tab_row = 1 + count($opr_other);
		}
		$purchase_data = Ord_purchase::where("pur_id",$id_purchase)->first();
		return view('contents.page_purchase.purchase_detail',compact(
			'id_oppor','id_purchase','opr_product','opr_other','opr_value','other_value','tab_row','allproduct','purchase_data',
			'opportunity','opportunity_customer','institution_names','opportunity_value'
		));
	}
	/* Tags:... */
	public function actionCheckInvoice(Request $request)
	{
		$purchase_data = Ord_purchase::where('pur_id',$request->id)->first();
		$result = [
			'param'=>true,
			'number' => $purchase_data->pur_invoice,
		];
		return $result;
	}
	/* Tags:... */
	public function storeInvoiceNumber(Request $request)
	{
		$actionStore = Ord_purchase::where('pur_id',$request->id)->update(['pur_invoice'=> $request->number_invoice]);
		$result = [
			'param'=>true,
			'number' => $request->number_invoice,
		];
		return $result;
	}
	/* Tags:... */
	public function actionGetDatePurchase(Request $request)
	{
		$purchase_data = Ord_purchase::where('pur_id',$request->id)->first();
		$result = [
			'param'=>true,
			'date' => $purchase_data->pur_date,
		];
		return $result;
	}
	/* Tags:... */
	public function storeDatePurchase(Request $request)
	{
		$actionStore = Ord_purchase::where('pur_id',$request->id)->update(['pur_date'=> $request->date_purchase]);
		$result = [
			'param'=>true,
			'date' => $request->date_purchase,
		];
		return $result;
	}
	/* Tags:... */
	public function actionCheckOpr(Request $request)
	{
		$actionStore = Ord_purchase::where('pur_id',$request->id)->first();
		$result = [
			'param'=>true,
			'opr' => $actionStore->pur_oppr_id,
		];
		return $result;
	}
}
