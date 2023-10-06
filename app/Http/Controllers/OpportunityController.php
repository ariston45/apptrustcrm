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
use App\Models\Opr_value_other;
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
		$allproduct = Prd_principle::get();
		$activity_type = Act_activity_type::get();
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
		$opr_value = Opr_value::where('ovs_opr_id',$id_oppor)->first();
		$opr_product_src = Opr_value_product::where('opr_value_products.por_opr_id',$id_oppor)
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
		$opr_other_src = Opr_value_other::where('ots_opr_id',$id_oppor)->get();
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
		return view('contents.page_opportunity.opportunity_detail',compact(
			'id_oppor','id_lead','user','users','status','opportunity','sales','member_name','team_member_id','tech_name','team_tech_id','sales_selected', 'team_selected',
			'user_marketing','user_tech','institution_names', 'lead_customer', 'lead_value','opportunity_value','opportunity_customer','opr_product','opr_other','opr_value','other_value','tab_row',
			'all_contacts','lead_contacts','activity_type','allproduct'
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
	/* Tags: store new product */
	public function storeProductOpportunity(Request $request)
	{
		$val_unit = Str::remove('.',Str::substr($request->unit_value,3));
		$por_id = genIdProductList();
		#
		$unit_value = Str::replace(',', '.', $val_unit);
		$val_total = $unit_value * $request->quantity;
		$value_total =  Str::replace(',', '.', $val_total);
		$data_product = [
			"por_id" => $por_id,
			"por_opr_id" => $request->oppor_id,
			"por_principle_name" => $request->alt_principle,
			"por_product_name" => $request->alt_product,
			"por_note" => $request->product_note,
			"por_quantity" => $request->quantity,
			"por_unit_value" => $unit_value,
			"por_total_value" => $value_total
		];
		$actionStoreProduct = Opr_value_product::insert($data_product);
		# updating hpp
		$valPrdRevenue = Opr_value_product::where('por_opr_id',$request->oppor_id)->sum('por_total_value');
		$actionUpdateSubtototal = Opr_value::where('ovs_opr_id',$request->oppor_id)->update(['ovs_value_subtotal' => $valPrdRevenue ]);
		# updating tax value
		$valOpportunity_1 = Opr_value::where('ovs_opr_id',$request->oppor_id)->select('ovs_value_subtotal','ovs_rate_tax')->first();
		$getPrdTax = ($valOpportunity_1->ovs_rate_tax / 100) * $valOpportunity_1->ovs_value_subtotal;
		$actionUpdateRevenue = Opr_value::where('ovs_opr_id',$request->oppor_id)->update(['ovs_value_tax' => $getPrdTax ]);
		#
		$valOpportunity_2 = Opr_value::where('ovs_opr_id',$request->oppor_id)->select('ovs_value_subtotal','ovs_value_other_cost','ovs_value_tax')->first();
		$valPrdRevenueTotal = $valOpportunity_2->ovs_value_subtotal + $valOpportunity_2->ovs_value_other_cost + $valOpportunity_2->ovs_value_tax ;
		$actionUpdateTotal = Opr_value::where('ovs_opr_id',$request->oppor_id)->update(['ovs_value_total' => $valPrdRevenueTotal ]);
		$rowNumber = Opr_value_product::where('por_opr_id',$request->oppor_id)->count();
		$newRowData ='';
		$newRowData.='<tr><td class="text-center">'.$rowNumber.'</td><td><span class="strong">'.$request->alt_principle.'</span></td><td><div class="strong">'.$request->alt_product.'</div>';
		if ($request->product_note == null || $request->product_note =="") {
			$newRowData.= '<span class="text-muted">-</span></td>';
		} else {
			$newRowData.= '<span class="text-muted">'.$request->product_note.'</span></td>';
		}
		$newRowData.='<td class="text-center">'.$request->quantity.'</td><td class="text-end">'.rupiahFormat( $unit_value ).'</td><td class="text-end">'.rupiahFormat( $value_total ).'</td>
		<td class="text-center" style="vertical-align: middle;"><button class="badge bg-blue-lt" onclick="actionUpdateProduct('.$por_id.')"><i class="ri-edit-2-line"></i></button></td></tr>';
		$result = [
			'param' => true,
			'new_row' => $newRowData,
			'val_product' => rupiahFormat($value_total),
			'val_subtotal' => rupiahFormat($valOpportunity_1->ovs_value_subtotal),
			'val_tax' => rupiahFormat($getPrdTax),
			'val_total' => rupiahFormat($valPrdRevenueTotal)
		];
		return $result;
	}
	/* Tags:... */
	public function updateProductOpportunity(Request $request)
	{
		$val_unit = Str::remove('.',Str::substr($request->unit_value,3));
		// $por_id = genIdProductList();
		#
		$unit_value = Str::replace(',', '.', $val_unit);
		$val_total = $unit_value * $request->quantity;
		$value_total =  Str::replace(',', '.', $val_total);
		$data_product = [
			"por_opr_id" => $request->oppor_id,
			"por_principle_name" => $request->alt_principle,
			"por_product_name" => $request->alt_product,
			"por_note" => $request->product_note,
			"por_quantity" => $request->quantity,
			"por_unit_value" => $unit_value,
			"por_total_value" => $value_total
		];
		$actionStoreProduct = Opr_value_product::where('por_id',$request->prd_id)->update($data_product);
		# updating hpp
		$valPrdRevenue = Opr_value_product::where('por_opr_id',$request->oppor_id)->sum('por_total_value');
		$actionUpdateSubtototal = Opr_value::where('ovs_opr_id',$request->oppor_id)->update(['ovs_value_subtotal' => $valPrdRevenue ]);
		# updating tax value
		$valOpportunity_1 = Opr_value::where('ovs_opr_id',$request->oppor_id)->select('ovs_value_subtotal','ovs_rate_tax')->first();
		$getPrdTax = ($valOpportunity_1->ovs_rate_tax / 100) * $valOpportunity_1->ovs_value_subtotal;
		$actionUpdateRevenue = Opr_value::where('ovs_opr_id',$request->oppor_id)->update(['ovs_value_tax' => $getPrdTax ]);
		#
		$valOpportunity_2 = Opr_value::where('ovs_opr_id',$request->oppor_id)->select('ovs_value_subtotal','ovs_value_other_cost','ovs_value_tax')->first();
		$valPrdRevenueTotal = $valOpportunity_2->ovs_value_subtotal + $valOpportunity_2->ovs_value_other_cost + $valOpportunity_2->ovs_value_tax ;
		$actionUpdateTotal = Opr_value::where('ovs_opr_id',$request->oppor_id)->update(['ovs_value_total' => $valPrdRevenueTotal ]);
		$rowNumber = Opr_value_product::where('por_opr_id',$request->oppor_id)->count();
		
		$result = [
			'param' => true,
			'val_product' => rupiahFormat($value_total),
			'val_subtotal' => rupiahFormat($valOpportunity_1->ovs_value_subtotal),
			'val_tax' => rupiahFormat($getPrdTax),
			'val_total' => rupiahFormat($valPrdRevenueTotal)
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
	/* Tags:... */
	public function sourceProductOppor(Request $request)
	{
		$product_opr = Opr_value_product::where('por_id',$request->idProduct)->first();
		$principle = Prd_principle::where('prd_name',$product_opr->por_principle_name)
		->select('prd_id')
		->first();
		
		if ($principle == null) {
			$id_principle = null;
		} else {
			$id_principle = $principle->prd_id;
		}
		$product = Prd_product::where('psp_subproduct_name',$product_opr->por_product_name)
		->select('psp_id')
		->first();
		if ($product == null) {
			$id_product = null;
		} else {
			$id_product = $product->psp_id;
		}
		$products = Prd_product::where('psp_product_id',$principle->prd_id)
		->select('psp_id','psp_subproduct_name')
		->get();
		if ($products->count() == 0) {
			$prd_ar[0] = [
				'id' => null,
				'title' => null
			];
		}else{
			foreach ($products as $key => $value) {
				$prd_ar[$key] = [
					'id' => $value->psp_id,
					'title' => $value->psp_subproduct_name
				];
			}
		}
		$result = [
			'param'=>true,
			'prd_id' => $product_opr->por_id,
			'principle' => $product_opr->por_principle_name,
			'principle_id' => $id_principle,
			'product' => $product_opr->psp_subproduct_name,
			'product_id' => $id_product,
			'note' => $product_opr->por_note, 
			'quantity' => $product_opr->por_quantity,
			'unit' => $product_opr->por_unit_value,
			'products' => $prd_ar
		];
		return $result;
	}
}
