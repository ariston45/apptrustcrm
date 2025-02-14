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
use App\Models\Ord_purchase;
use App\Models\Prs_accessrule;
use App\Models\Prs_lead_value;
use App\Models\Cst_institution;
use App\Models\Prs_qualification;
use App\Models\Prs_salesperson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;
use DB;

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
		->where('opr_id',$id_oppor)
		->select('opr_id','opr_status','lds_id','lds_title','lds_status','lds_describe','lds_customer', 'lds_subcustomer','opr_status','opr_notes','opr_estimate_closing')
		->first();
		if ($opportunity == null) {
			return view('errors.404');
		}
		$closing = date('d M/Y',strtotime($opportunity->opr_estimate_closing));
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
		->leftjoin('cst_institutions','cst_institutions.ins_id','=','cst_personals.cnt_cst_id')
		// ->select('plc_id', 'cnt_id', 'plc_lead_id', 'plc_attendant_id', 'plc_attendant_rule', 'plc_customer_id', 'cnt_fullname', 'cnt_company_position','cst_name')
		->where('plc_lead_id',$id_lead)
		->get();
		$lead_customer = Cst_institution::where('ins_id', $opportunity->lds_customer)->first();
		$lead_subcustomer = Cst_customer::where('cst_id', $opportunity->lds_subcustomer)->first();
		$all_contacts = Cst_personal::leftjoin('cst_institutions','cst_personals.cnt_cst_id','=', 'cst_institutions.ins_id')
		->where('cnt_cst_id', $opportunity->lds_customer)
		->select('cnt_id','cnt_fullname','ins_name', 'cnt_company_position')
		->get();
		$institution_names = Str::title($lead_customer->ins_name);
		
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
		$purchase_data = Ord_purchase::where("pur_oppr_id",$id_oppor)->first();
		return view('contents.page_opportunity.opportunity_detail',compact(
			'id_oppor','id_lead','user','users','status','opportunity','sales','member_name','team_member_id','tech_name','team_tech_id','sales_selected', 'team_selected',
			'user_marketing','user_tech','institution_names', 'lead_customer', 'lead_value','opportunity_value','opr_product','opr_other','opr_value','other_value','tab_row','lead_subcustomer',
			'all_contacts','lead_contacts','activity_type','allproduct','closing','purchase_data'
		));
	}
	/* Tags:... */
	public function help_viewOpportunityDetail(Request $request)
	{
		$opr = Opr_opportunity::where('opr_lead_id',$request->id)->first();
		if($opr == true){
			return redirect('opportunities/detail-opportunity/'.$opr->opr_id);
		}else{
			return redirect('leads/detail-lead/'.$request->id);
		}
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
		$date_now = date('Y-m-d H:i:s');
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
		$actionStoreOpportunity = Opr_opportunity::insert($data_opr);
		$data_value = [
			'ovs_opr_id' => $opr_id,
			'ovs_value_subtotal' => null,
			'ovs_rate_tax' => $request->est_tax_rate,
			'ovs_value_tax' => null,
			'ovs_value_other_cost' => null,
			'ovs_value_total' =>null,
		];
		$actionStoreOpportunity = Opr_value::insert($data_value);
		$actionUpdateLead = Prs_lead::where('lds_id',$request->lead_id)->update(['lds_stage_opr'=>'true','lds_close_date'=>$date_now]);
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
		$date_now = date('Y-m-d H:i:s');
		$data_opr = [
			'opr_id' => $opr_id,
			'opr_lead_id' => $request->lead_id,
			'opr_title' => $request->lead_title,
			'opr_status' => null,
			'opr_estimate_closing' => $est_closing,
			'opr_notes' => $request->opportunities_notes
		];
		$data_opr_value = [
			"ovs_opr_id" => $opr_id,
			"ovs_rate_tax" => 11
		];
		$actionStoreOpportunity = Opr_opportunity::insert($data_opr);
		$actionStoreProduct = Opr_value::insert($data_opr_value);
		$actionUpdateLead = Prs_lead::where('lds_id',$request->lead_id)->update(['lds_stage_opr'=>'true','lds_close_date'=>$date_now]);
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
		$por_id = $request->prd_id;
		$val_unit = Str::remove('.',Str::substr($request->unit_value,3));
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
		$actionStoreProduct = Opr_value_product::where('por_id',$por_id)->update($data_product);
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

		if ($data_product['por_note'] == null) {
			$notes = "-";
		}else{
			$notes = $data_product['por_note'];
		}
		
		$result = [
			'param' => true,
			'init_prd' => $por_id,
			'init_prd_priciple' => $data_product['por_principle_name'],
			'init_prd_product' => $data_product['por_product_name'],
			'init_prd_note' => $notes,
			'init_prd_quantity' => $data_product['por_quantity'],
			'init_prd_unit' => rupiahFormat($data_product['por_unit_value']),
			'init_prd_total' => rupiahFormat($data_product['por_total_value']),
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
		Opr_opportunity::where('opr_id',$request->id)->update(['opr_close_status'=> null]);
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
			'note' => $request->notes,
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValue(Request $request)
	{
		$user = Auth::user();
		$value = asNumber($request->opportunity_value);
		$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['ovs_value_subtotal'=>$value]);
		$dataOprValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		$dataTax = $dataOprValue->ovs_value_subtotal * $dataOprValue->ovs_rate_tax / 100 ;
		$dataTotal = $dataOprValue->ovs_value_subtotal + $dataOprValue->ovs_value_other_cost + $dataTax;
		$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['ovs_value_tax'=>$dataTax,'ovs_value_total'=>$dataTotal]);
		$result = [
			'param' => true,
			'val_subtotal' => rupiahFormat($value),
			'val_tax' => rupiahFormat($dataTax),
			'val_total' => rupiahFormat($dataTotal)
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValueTax(Request $request)
	{
		$user = Auth::user();
		$tax_value = asNumber($request->tax_value);
		$tax_rate = $request->tax_rate;
		$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['ovs_rate_tax'=>$tax_rate]);
		$dataOprValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		$dataTax = ($dataOprValue->ovs_value_subtotal * $dataOprValue->ovs_rate_tax) / 100 ;
		if ($dataTax != $tax_value) {
			$tax_result = $tax_value;
		}else{
			$tax_result = $dataTax;
		}
		$dataTotal = $dataOprValue->ovs_value_subtotal + $dataOprValue->ovs_value_other_cost + $tax_result;
		$actionUpdate = Opr_value::where('ovs_opr_id',$request->id)->update(['ovs_value_tax'=>$tax_result,'ovs_value_total'=>$dataTotal]);
		$result = [
			'param' => true,
			'val_rate' => $tax_rate,
			'val_tax' => rupiahFormat($tax_result),
			'val_total' => rupiahFormat($dataTotal)
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValueOther(Request $request)
	{
		$data = array();
		$id = genIdOprValOther();
		foreach ($request->other_note as $key => $value) {
			$data[$key] = [
				'ots_id' => $id,
				'ots_opr_id' => $request->id,
				'ots_name' => $value,
				'ots_value' => asNumber($request->other_value[$key])
			];
			$data_value[$key] = asNumber($request->other_value[$key]);
			$id++;
		}
		$actionDelete = Opr_value_other::where('ots_opr_id',$request->id)->delete();
		$actionStoreOther = Opr_value_other::insert($data);
		$dataOprValue = Opr_value::where('ovs_opr_id',$request->id)->first();
		$other_value = array_sum($data_value);
		$dataTotal = $dataOprValue->ovs_value_subtotal + $dataOprValue->ovs_value_tax + $other_value;
		$actionStoreValueOpr = Opr_value::where('ovs_opr_id',$request->id)->update(['ovs_value_other_cost' => $other_value,'ovs_value_total'=>$dataTotal]);
		$tab_row = 1 + count($data_value);
		$data_other ='';
		$data_other.= '<tr>
			<td colspan="4" class="strong text-end"><i>Other Cost</i></td>
			<td class="text-end"></td>
			<td rowspan="'.$tab_row.'" class="text-end" style="vertical-align: middle;"><span id="opr_other_cost"></span>'.rupiahFormat($other_value).'</td>
			<td rowspan="'.$tab_row.'" class="text-center" style="vertical-align: middle;">
				<button class="badge bg-blue-lt" onclick="actionChangeValOther()"><i class="ri-edit-2-line"></i></button>
			</td>
		</tr>';
		foreach ($data as $key => $value) {
			# code...
			$data_other.='<tr>
				<td colspan="4" class="text-muted text-end"><i><span id="opr_other_note_'.$value['ots_id'].'"></span>'.$value['ots_name'].'</i></td>
				<td class="text-end"><span id="opr_other_cost_'.$value['ots_id'].'"></span>'.rupiahFormat($value['ots_value']).'</td>
			</tr>';
		}
		$result = [
			'param' => true,
			'val_other' => $other_value,
			'val_total' => rupiahFormat($dataTotal),
			'item_other' => $data_other
		];
		return $result;
	}
	/* Tags:... */
	public function storeOprValueRevenue(Request $request)
	{
		$value = asNumber($request->revenue_value);
		$actionStore = Opr_value::where('ovs_opr_id',$request->id)->update(['ovs_value_total'=>$value]);
		$result = [
			'param'=>true,
			'val_total' => rupiahFormat($value
			)
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
		->select('prd_id','prd_name')
		->first();
		
		if ($principle == null) {
			$id_principle = $product_opr->por_principle_name;
			$name_principle = $product_opr->por_principle_name;
			$products = Prd_product::select('psp_id','psp_subproduct_name')->get();
		} else {
			$id_principle = $principle->prd_id;
			$name_principle = $principle->prd_name;
			$products = Prd_product::where('psp_product_id',$principle->prd_id)
			->select('psp_id','psp_subproduct_name')
			->get();
		}
		$product = Prd_product::where('psp_subproduct_name',$product_opr->por_product_name)
		->select('psp_id','psp_subproduct_name')
		->first();
		if ($product == null) {
			$id_product = $product_opr->por_product_name;
			$name_product = $product_opr->por_product_name;
		} else {
			$id_product = $product->psp_id;
			$name_product = $product->psp_subproduct_name;
		}

		
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
			'principle' => $name_principle,
			'principle_id' => $id_principle,
			'product' => $name_product,
			'product_id' => $id_product,
			'note' => $product_opr->por_note, 
			'quantity' => $product_opr->por_quantity,
			'unit' => $product_opr->por_unit_value,
			'products' => $prd_ar
		];
		return $result;
	}
	/* Tags:... */
	public function sourceProductValue(Request $request)
	{
		// $valPrdRevenue = Opr_value_product::where('por_opr_id',$request->idOpportunity)->sum('por_total_value');
		$valPrdRevenue = Opr_value::where('ovs_opr_id',$request->idOpportunity)->first();
		$result = [
			'param'=>true,
			'value' => $valPrdRevenue->ovs_value_subtotal
		];
		return $result;
	}
	public function sourceTaxValue(Request $request)
	{
		// $valPrdRevenue = Opr_value_product::where('por_opr_id',$request->idOpportunity)->sum('por_total_value');
		$valPrdRevenue = Opr_value::where('ovs_opr_id',$request->idOpportunity)->first();
		$result = [
			'param'=>true,
			'tax_rate' => $valPrdRevenue->ovs_rate_tax,
			'tax_value' => $valPrdRevenue->ovs_value_tax
		];
		return $result;
	}
	/* Tags:... */
	public function sourceTriggerTaxValue(Request $request)
	{
		$valOpr = Opr_value::where('ovs_opr_id',$request->idOpportunity)->first();
		$value = ($valOpr->ovs_value_subtotal*$request->tax_rate)/100;
		$result = [
			'param'=>true,
			'value' => $value
		];
		return $result;
	}
	/* Tags:... */
	public function sourceOtherValueData(Request $request)
	{
		$oprOtherValue = Opr_value_other::where('ots_opr_id',$request->idOpportunity)->get();
		$data = '';
		if ($oprOtherValue->count() > 0) {
			foreach ($oprOtherValue as $key => $value) {
				$randomIndex = quickRandom(6);
				$data.='<tr>
				<td><input type="text" class="form-control pb-1 pt-1 col-auto" name="other_note[]" value="'.$value->ots_name.'" placeholder="Input description"></td>
				<td><input type="text" id="input-opportunity-other-'.$randomIndex.'" class="form-control pb-1 pt-1" name="other_value[]" placeholder="Input value" 
					oninput="fcurrencyInput(\'input-opportunity-other-'.$randomIndex.'\')" value="'.rupiahFormat($value->ots_value).'" style="margin-right: 4px;">
				</td>
				<td style="text-align: center;">
					<button type="button" class="btn btn-sm btn-ghost-danger" onclick="actionDelRowForm4(this)"><i class="ri-delete-bin-line"></i></button>
				</td></tr>';
			}
		}
		$result = [
			'param'=>true,
			'data' => $data
		];
		return $result;
	}
	/* Tags:... */
	public function sourceTotalValue(Request $request)
	{
		$opr_value = Opr_value::where('ovs_opr_id',$request->idOpportunity)->first();
		$result = [
			'param'=>true,
			'val_total' => $opr_value->ovs_value_total
		];
		return $result;
	}
	/* Tags:... */
	public function sourceOpporNotes(Request $request)
	{
		$notes = Opr_opportunity::where('opr_id',$request->idOpportunity)->first();
		$result = [
			'param'=>true,
			'note' => $notes->opr_notes,
		];
		return $result;
	}
	/* Tags:... */
	public function checkWinOpportunity(Request $request)
	{
		$user = auth()->user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_lead::join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->join('ord_purchases','opr_opportunities.opr_id','=','ord_purchases')
			->where('opr_close_status',null)
			->select('opr_id','lds_title')
			->get();
		}elseif(checkRule(array('MGR'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->where('opr_close_status',null)
			->whereIn('lds_id',$lead_ids)
			->select('opr_id','lds_title')
			->get();
		}elseif(checkRule(array('STF'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->where('opr_close_status',null)
			->whereIn('lds_id',$lead_ids)
			->select('opr_id','lds_title')
			->get();
		}
		if (isset($lead_data)) {
			foreach ($lead_data as $key => $value) {
				$data[$key] = [
					'id' => $value->opr_id,
					'title' => $value->lds_title
				];
			}
		}else{
			$data = array();
		}
		$result = [
			'param'=>true,
			'data' => $data,
		];
		return $result;
	}
}
