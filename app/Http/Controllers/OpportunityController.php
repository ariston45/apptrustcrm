<?php

namespace App\Http\Controllers;

use App\Models\Act_activity_type;
use App\Models\Cst_customer;
use App\Models\Cst_personal;
use App\Models\Opr_opportunity;
use App\Models\Opr_value_product;
use App\Models\Opr_value;
use App\Models\Prd_principle;
use App\Models\Prd_product;
use App\Models\Prs_contact;
use App\Models\Prs_lead;
use App\Models\Opr_stage_status;
use App\Models\Prs_accessrule;
use App\Models\Prs_lead_value;
use App\Models\Prs_qualification;
use App\Models\Prs_salesperson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;

class OpportunityController extends Controller
{
	/* Tags:... */
	public function viewOpportunities()
	{
		return view('contents.page_opportunity.opportunity');
	}
	/* Tags:... */
	public function viewOpportunityDetail(Request $request)
	{
		$id_oppor = $request->id;
		$user = Auth::user();
		$users = User::join('user_structures','users.id','=','user_structures.usr_user_id')
		->leftJoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->select('id','name','level','uts_team_name')
		->whereNotIn('level',['ADM','AGM'])
		->get();
		$opportunity = Opr_opportunity::join('prs_leads','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
		->where('opr_id',$id_oppor)->select('opr_id','opr_status','lds_id','lds_title','lds_status','lds_describe','lds_customer','opr_status','opr_notes')->first();
		$id_lead = $opportunity->lds_id;
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
		$lead_contacts = Prs_contact::join('cst_personals', 'prs_contacts.plc_attendant_id','=', 'cst_personals.cnt_id')
		->leftjoin('cst_customers','cst_customers.cst_id','=','cst_personals.cnt_cst_id')
		->select('plc_id', 'cnt_id', 'plc_lead_id', 'plc_attendant_id', 'plc_attendant_rule', 'plc_customer_id', 'cnt_fullname', 'cnt_company_position','cst_name')
		->where('plc_lead_id',$id_lead)
		->get();
		$cst_ids= explode(',',$opportunity->lds_customer);
		$lead_customer = Cst_customer::join('cst_institutions','cst_customers.cst_institution','=', 'cst_institutions.ins_id')
		->whereIn('cst_id', $cst_ids)
		->select('cst_id','ins_id', 'ins_name', 'cst_name', 'view_option')
		->get();
		$opportunity_customer = array();
		foreach ($lead_customer as $key => $value) {
			$opportunity_customer[$key] = $value->cst_name;
		}
		$all_contacts = Cst_personal::join('cst_customers','cst_personals.cnt_cst_id','=','cst_customers.cst_id')
		->whereIn('cnt_cst_id', $cst_ids)
		->select('cnt_id','cnt_fullname','cst_name')
		->get();
		$institution_names = Str::title($lead_customer->first()->ins_name);
		// $status = Prs_lead_statuses::where('pls_code_name','!=','dead_end')->get();
		$status = Opr_stage_status::where('oss_id','!=',7)->get();
		// $status = Prs_lead_statuses::where('pls_code_name','!=','dead_end')->get();
		$lead_value = Prs_lead_value::where('lvs_lead_id',$id_lead)->first();
		if ($lead_value == null) {
			$lead_value_set_null = [
				'lvs_base_value' => 0,
				'lvs_target_value' => 0
			];
			$lead_value = (object) $lead_value_set_null;
		}
		$user_marketing = $users->whereIn('level',['STF','MGR','MGR.PAS']);
		$user_tech = $users->whereIn('level',['STF.TCH','MGR.TCH']);
		$sales = Prs_accessrule::join('users','users.id','=','prs_accessrules.slm_user')
		->join('user_structures','prs_accessrules.slm_user','=','user_structures.usr_user_id')
		->leftJoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->where('slm_lead',$id_lead)
		->select('users.id as userid','name','username', 'slm_rules','uts_team_name')->get();
		$sales_selected = $sales->where('slm_rules','master')->first();
		$sales_selected = $sales->where('slm_rules','master')->first();
		if ($sales_selected == null) {
			$sales_dataset_null = ['name' => null,'userid'=>null];
			$sales_selected = (object) $sales_dataset_null;
		}
		###
		$team_selected = $sales->where('slm_rules', 'colaborator');
		$team_member_id = array();
		$team_member_name = array();
		foreach ($team_selected as $key => $value) {
			$team_member_id[$key] = $value->userid;
			$team_member_name[$key] = $value->name;
		}
		$member_name= implode(',',$team_member_name);
		$tech_selected = $sales->where('slm_rules', 'technical');
		$team_tech_id = array();
		$team_tech_name = array();
		foreach ($tech_selected as $key => $value) {
			$team_tech_id[$key] = $value->userid;
			$team_tech_name[$key] = $value->name;
		}
		$tech_name= implode(',',$team_tech_name);
		###
		$opr_product = Opr_value_product::where('opr_value_products.por_opr_id',$id_oppor)
		->select('por_id','por_principle_name','por_product_name','por_note','por_quantity','por_unit_value','por_total_value')
		->get();
		// echo $opr_product;
		// die();
		// $opr_product = Opr_value_product::join('prd_products','opr_value_products.por_product_name','=','prd_products.psp_id')
		// ->join('prd_principles','prd_products.psp_product_id','=','prd_principles.prd_id')
		// ->where('opr_value_products.por_opr_id',$id_oppor)
		// ->select('psp_subproduct_name','prd_name')
		// ->get();
		$products = array();
		$principle = new StdClass();
		if ($opr_product->count() == null) {
			$principle->prd_name = null;
		}else{
			foreach ($opr_product as $key => $value) {
				$products[$key] = $value->psp_subproduct_name;
			}
			$principle = $opr_product->first();
		}
		$allproduct = Prd_principle::get();

		###
		$activity_type = Act_activity_type::get();
		return view('contents.page_opportunity.opportunity_detail',compact(
			'id_oppor','id_lead','user','users','status','opportunity','sales','member_name','team_member_id','tech_name','team_tech_id','sales_selected', 'team_selected',
			'user_marketing','user_tech','institution_names', 'lead_customer', 'lead_value','opportunity_value','opportunity_customer','opr_product',
			'all_contacts','lead_contacts','activity_type','products','principle','allproduct'
		));
	}
	/* Tags:... */
	public function formNewOpportunity(Request $request)
	{
		$id_cst = $request->id;
		$user = Auth::user();
		$company = Cst_customer::join('cst_institutions','cst_institutions.ins_id','=','cst_customers.cst_institution')
    ->leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->select('cst_id','cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes','ins_name')
    ->get();
    $user = Auth::user();
		$user_all = User::whereIn('level',['MKT','MGR.PAS','MGR','AGM','TCK'])->get();
    $personal= Cst_personal::where('cnt_cst_id',$id_cst)->get();
		$check_opp = Opr_opportunity::join('prs_leads','opr_opportunities.opr_lead_id','=','prs_leads.lds_id')->where('lds_customer',$id_cst)->select('lds_id')->get();
		$lds_id = array();
		foreach ($check_opp as $key => $value) {
			$lds_id[$key] = $value->lds_id;
		}
    $project =Prs_lead::where('lds_customer',$id_cst)->where('lds_status','3')->whereNotIn('lds_id',$lds_id)->get();
		$principles = Prd_principle::get();
		return view('contents.page_opportunity.form_create_opportunity',compact('company','id_cst','project','user_all','user','personal','company','principles'));
	}
	/* Tags:... */
	public function formNewOpportunityCst(Request $request)
	{
		$id_cst = $request->id;
		$user = Auth::user();
		$company = Cst_customer::join('cst_institutions','cst_institutions.ins_id','=','cst_customers.cst_institution')
    ->leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->where('cst_id', $id_cst)
    ->select('cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes','ins_name')
    ->first();
    $user = Auth::user();
		$user_all = User::whereIn('level',['MKT','MGR.PAS','MGR','AGM','TCK'])->get();
    $personal= Cst_personal::where('cnt_cst_id',$id_cst)->get();
		$check_opp = Opr_opportunity::join('prs_leads','opr_opportunities.opr_lead_id','=','prs_leads.lds_id')->where('lds_customer',$id_cst)->select('lds_id')->get();
		$lds_id = array();
		foreach ($check_opp as $key => $value) {
			$lds_id[$key] = $value->lds_id;
		}
    $project =Prs_lead::where('lds_customer',$id_cst)->where('lds_status','3')->whereNotIn('lds_id',$lds_id)->get();
		$principles = Prd_principle::get();
		return view('contents.page_opportunity.form_create_opportunity_cst',compact('id_cst','project','user_all','user','personal','company','principles'));
	}
	/* Tags:... */
	public function storeNewOpportunity(Request $request)
	{
		$opr_id = genIdOpportunity();
		$est_closing = date('Y-m-d h:i:s', strtotime($request->est_closing_date));
		$lead = Prs_lead::where('lds_id',$request->lead_id)->select('lds_title')->first();
		$data_opr = [
			'opr_id' => $opr_id,
			'opr_lead_id' => $request->lead_id,
			'opr_title' => $lead->lds_title,
			'opr_status' => null,
			'opr_estimate_closing' => $est_closing,
			'opr_notes' => $request->opportunities_notes
		];
		foreach ($request->product as $key => $value) {
			$data_opr_prd[$key] = [
				"por_opportunity_id" => $opr_id,
				"por_principle_name" => $request->product_principle,
				"por_product_name" => $value,
				"por_quantity" => null,
				"por_unit_value" => null,
				"por_total_value" => null
			];
		}
		$actionStoreOpportunity = Opr_opportunity::insert($data_opr);
		$actionStoreProduct = Opr_value_product::insert($data_opr_prd);
		$result = [
			'param'=>true,
			'id_opr' => $opr_id
		];
		return $result;
	}
	/* Tags:... */
	public function storeOpportunity_A(Request $request)
	{
		$opr_id = genIdOpportunity();
		$est_closing = date('Y-m-d h:i:s', strtotime($request->est_closing_date));
		$data_opr = [
			'opr_id' => $opr_id,
			'opr_lead_id' => $request->lead_id,
			'opr_title' => $request->lead_title,
			'opr_status' => null,
			'opr_estimate_closing' => $est_closing,
			'opr_notes' => $request->opportunities_notes
		];
		foreach ($request->product as $key => $value) {
			$data_opr_prd[$key] = [
				"por_opportunity_id" => $opr_id,
				"por_principle_name" => $request->product_principle,
				"por_product_name" => $value,
				"por_quantity" => null,
				"por_unit_value" => null,
				"por_total_value" => null
			];
		}
		$actionStoreOpportunity = Opr_opportunity::insert($data_opr);
		$actionStoreProduct = Opr_value_product::insert($data_opr_prd);
		$result = [
			'param'=>true,
			'id_opr' => $opr_id
		];
		return $result;
	}
	/* Tags:... */
	public function storeNewOpportunityCst(Request $request)
	{
		$opr_id = genIdOpportunity();
		$est_closing = date('Y-m-d h:i:s', strtotime($request->est_closing_date));
		$lead = Prs_lead::where('lds_id',$request->lead_id)->select('lds_title')->first();
		$data_opr = [
			'opr_id' => $opr_id,
			'opr_lead_id' => $request->lead_id,
			'opr_title' => $lead->lds_title,
			'opr_status' => null,
			'opr_estimate_closing' => $est_closing,
			'opr_notes' => $request->opportunities_notes
		];
		foreach ($request->product as $key => $value) {
			$data_opr_prd[$key] = [
				"por_opportunity_id" => $opr_id,
				"por_principle_name" => $request->product_principle,
				"por_product_name" => $value,
				"por_quantity" => null,
				"por_unit_value" => null,
				"por_total_value" => null
			];
		}
		$actionStoreOpportunity = Opr_opportunity::insert($data_opr);
		$actionStoreProduct = Opr_value_product::insert($data_opr_prd);
		$result = [
			'param'=>true,
			'id_opr' => $opr_id
		];
		return $result;
	}
	/* Tags:... */
	public function updateProductOpportunity(Request $request)
	{
		foreach ($request->product as $key => $value) {
			$data[$key] = [
				"por_opportunity_id" => $request->oppor_id,
				"por_principle_name" => $request->product_principle,
				"por_product_name" => $value,
				"por_quantity" => null,
				"por_unit_value" => null,
				"por_total_value" => null
			];
		}
		$checkproduct = Opr_value_product::where('por_opportunity_id',$request->oppor_id)->get();
		if ($checkproduct->count() == 0) {
			$actionStoreProduct = Opr_value_product::insert($data);
		}else {
			$actionDeleteProduct = Opr_value_product::where('por_opportunity_id',$request->oppor_id)->delete();
			$actionStoreProduct = Opr_value_product::insert($data);
		}
		$principle = Prd_principle::where('prd_id',$request->product_principle)->first();
		$products = Prd_product::whereIn('psp_id',$request->product)->get();
		foreach ($products as $key => $value) {
			$x[$key] = $value->psp_subproduct_name;
		}
		$result = [
			'param'=>true,
			'prin   ciple' => $principle->prd_name,
			'product' => implode(', ',$x)
		];
		return $result;
	}
	/* Tags:... */
	public function listProductPrinciple(Request $request)
	{
		$product = Prd_product::where('psp_product_id',$request->id)->get();
		$data = array();
		foreach ($product as $key => $value) {
			$data[$key] = [
				'id' => $value->psp_id,
				'title' => $value->psp_subproduct_name
			];
		}
		$result = [
			'param'=>true,
			'product' =>$data
		];
		return $result;
	}
	/* Tags:... */
	public function storeUpdateStatusOpr(Request $request)
	{
		$updating_lead = Opr_opportunity::where('opr_id',$request->id)->update(['opr_status'=>$request->status]);
		$result = [
			'param'=>true,
			'new_status' => $request->status
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprNotes(Request $request)
	{
		$actionStoreNotes = Opr_opportunity::where('opr_id',$request->id)->update(['opr_notes'=>$request->notes]);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValue(Request $request)
	{
		$user = Auth::user();
		$value =  Str::remove('.',Str::substr($request->opportunity_value,3));
		$x = Str::replace(',', '.', $value);
		$checkBaseValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		if ($checkBaseValue == null) {
			$data = [
				"ovs_opr_id" => $request->id,
				"opr_value_dpp" => $x,
				"opr_value_hpp" => null,
				"opr_tax" => null,
				"opr_other" => null,
				"opr_revenue" =>null
			];
			$actionStore = Opr_value::insert($data);
		}else{
			$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['opr_value_dpp' => $x]);
		}
		$result = [
			'param' => true,
			'currency' => fcurrency($x)
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValueHpp(Request $request)
	{
		$user = Auth::user();
		$value =  Str::remove('.',Str::substr($request->hpp_value,3));
		$x = Str::replace(',', '.', $value);
		$checkBaseValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		if ($checkBaseValue == null) {
			$data = [
				"ovs_opr_id" => $request->id,
				"opr_value_dpp" => null,
				"opr_value_hpp" => $x,
				"opr_tax" => null,
				"opr_other" => null,
				"opr_revenue" =>null
			];
			$actionStore = Opr_value::insert($data);
		}else{
			$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['opr_value_hpp' => $x]);
		}
		$result = [
			'param' => true,
			'currency' => fcurrency($x)
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValueTax(Request $request)
	{
		$user = Auth::user();
		$value =  Str::remove('.',Str::substr($request->tax_value,3));
		$x = Str::replace(',', '.', $value);
		$checkBaseValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		if ($checkBaseValue == null) {
			$data = [
				"ovs_opr_id" => $request->id,
				"opr_value_dpp" => null,
				"opr_value_hpp" => null,
				"opr_tax" => $x,
				"opr_other" => null,
				"opr_revenue" =>null
			];
			$actionStore = Opr_value::insert($data);
		}else{
			$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['opr_tax' => $x]);
		}
		$result = [
			'param' => true,
			'currency' => fcurrency($x)
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValueOther(Request $request)
	{
		$user = Auth::user();
		$value =  Str::remove('.',Str::substr($request->other_value,3));
		$x = Str::replace(',', '.', $value);
		$checkBaseValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		if ($checkBaseValue == null) {
			$data = [
				"ovs_opr_id" => $request->id,
				"opr_value_dpp" => null,
				"opr_value_hpp" => null,
				"opr_tax" => null,
				"opr_other" => $x,
				"opr_revenue" =>null
			];
			$actionStore = Opr_value::insert($data);
		}else{
			$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['opr_other' => $x]);
		}
		$result = [
			'param' => true,
			'currency' => fcurrency($x)
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValueRevenue(Request $request)
	{
		$user = Auth::user();
		$value =  Str::remove('.',Str::substr($request->revenue_value,3));
		$x = Str::replace(',', '.', $value);
		$checkBaseValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		if ($checkBaseValue == null) {
			$data = [
				"ovs_opr_id" => $request->id,
				"opr_value_dpp" => null,
				"opr_value_hpp" => null,
				"opr_tax" => null,
				"opr_other" => null,
				"opr_revenue" =>$x
			];
			$actionStore = Opr_value::insert($data);
		}else{
			$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['opr_revenue' => $x]);
		}
		$result = [
			'param' => true,
			'currency' => fcurrency($x)
		];
		return $result;
	}
	/* Tags:... */
	public function sourceCustomerProject(Request $request)
	{
		$check_opp = Opr_opportunity::join('prs_leads','opr_opportunities.opr_lead_id','=','prs_leads.lds_id')->where('lds_customer',$request->id)->select('lds_id')->get();
		$lds_id = array();
		foreach ($check_opp as $key => $value) {
			$lds_id[$key] = $value->lds_id;
		}
    $project =Prs_lead::where('lds_customer',$request->id)->where('lds_status','3')->whereNotIn('lds_id',$lds_id)->get();
		
    $data = array();
    foreach ($project as $key => $value) {
      $data[$key] = [
        'id' => $value->lds_id,
        'title' => $value->lds_title
      ];
    }
    $result = [
      'param'=>true,
      'data' => $data
    ];
    return $result;
	}
}
