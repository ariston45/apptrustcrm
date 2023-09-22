<?php

namespace App\Http\Controllers;

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

class LeadController extends Controller
{
	public function LeadDataView()
	{
		return view('contents.page_lead.lead');
	}
	/* Tags:... */
	public function formCreateLead(Request $request)
	{
		$user = Auth::user();
		$customer = Cst_customer::get();
		$sales = User::where('level','MKT')->orWhere('level','MGR')->orWhere('level','MGR.PAS')->get();
		return view('contents.page_lead.form_create_lead',compact('customer','sales','user'));
	}
	public function storeLead(Request $request)
	{
		$user_id = Auth::user()->id;
		$lead_id = genIdLead();
		$product_offer_detail = Prd_subproduct::where('psp_id',$request->product_interest)->select('psp_estimate_value')->first();
		$product_offer_total_value = $product_offer_detail->psp_estimate_value * $request->product_quantity;
		$incheckCst = CustomerController::recheckCustomerCompany($request->institution_name);
		$cst_id = $incheckCst['cst_id'];
		$cst_string_id = $incheckCst['cst_string_id'];
		$data_lead = [
			'lds_id' => $lead_id,
			'lds_title' => $request->lead_title,
			'lds_describe' => $request->lead_describe,
			'lds_status' => $request->lead_status,
			'created_by' => $user_id,
		];
		$res_leads = Prs_lead::insert($data_lead);
		$data_lead_customer = [
			'plc_lead_id' => $lead_id,
			'plc_personal_id' => $request->idperson,
			'plc_company_id' => $cst_id,
			'plc_lead_rule' => 'PIC',
			'created_by' => $user_id
		];
		$res_lead_customer = Prs_contact::insert($data_lead_customer);
		$data_lead_product = [
			'pof_lead_id' => $lead_id,
			'pof_product_id' => $request->product_interest,
			'pof_quantity' => $request->product_quantity,
			'pof_unit_value' => $product_offer_detail->psp_estimate_value,
			'pof_total_value' => $product_offer_total_value,
			'created_by' => $user_id
		];
		$res_lead_product = Prs_product_offer::insert($data_lead_product);
		$data_lead_values = [
			'lvs_lead_id' => $lead_id,
			'lvs_base_value' => $product_offer_total_value,
			'lvs_target_value' => $request->target_value,
			'created_by' => $user_id
		];
		$res_lead_values = Prs_lead_value::insert($data_lead_values);
		return true;
	}
	/* Tags:... */
	public function storeLeadI(Request $request)
	{
		$user = Auth::user();
		$id = genIdLead();
		$user = Auth::user();
		$base_val =  Str::remove('.',Str::substr($request->base_value,3));
		$base_x = Str::replace(',', '.', $base_val);
		$target_val =  Str::remove('.',Str::substr($request->target_value,3));
		$target_x = Str::replace(',', '.', $target_val);
		$data_lead = [
			"lds_id" => $id,
			"lds_title" => $request->lead_title,
			"lds_status" => 1,
			"lds_customer" => $request->customer
		];
		$data_lead_value = [
			"lvs_lead_id" => $id,
			"lvs_base_value" => $base_x,
			"lvs_target_value" => $target_x,
		];
		$person_lead = array();
		$person_to_store = array();
		$new_id_person = genIdPerson();
		foreach ($request->customer_pic_contact as $key => $value) {
			if (is_numeric($value) == false) {
				$person_to_store[$key] = [
					"cnt_id" => $new_id_person,
					"cnt_cst_id" => $request->customer,
					"cnt_fullname" => $value,
					"cnt_nickname" => null,
					"cnt_company_position" => null,
					"cnt_notes" => null,
					"view_option" => 'PUBLIC',
					"created_by" => $user->id
				];
				$person_lead[$key] = [
					"plc_lead_id" => $id,
					"plc_attendant_id" => $new_id_person,
					"plc_customer_id" => $request->customer,
					"created_by" => $user->id
				];
				$new_id_person++;
			}else{
				$person_lead[$key] = [
					"plc_lead_id" => $id,
					"plc_attendant_id" => $value,
					"plc_customer_id" => $request->customer,
					"created_by" => $user->id
				];
			}
		}
		$assign_lead = [
			"slm_user" => $request->assign_sales,
			"slm_rules" => 'head',
			"slm_lead" => $id,
			"created_by" => $user->id
		];
		$assign_team = array();
		foreach ($request->assign_team as $key => $value) {
			$assign_team = [
				"slm_user" => $value,
				"slm_rules" => 'colaborator',
				"slm_lead" => $id,
				"created_by" => $user->id
			];
		}
		$actionStore_SalesTeam = Prs_accessrule::insert($assign_team);
		$actionStore_Sales = Prs_accessrule::insert($assign_lead);
		$actionStore_PersonLead = Prs_contact::insert($person_lead);
		$actionStore_Person = Cst_personal::insert($person_to_store);
		$actionStore_LeadValue = Prs_lead_value::insert($data_lead_value);
		$actionStore_Lead = Prs_lead::insert($data_lead);
		$result = [
			'param'=>true,
			'idlead' => $id
		];
		return $result;
	}
	public function storeLeadVerII(Request $request)
	{
		$user = Auth::user();
		$id = genIdLead();
		$user = Auth::user();
		$base_val =  Str::remove('.',Str::substr($request->base_value,3));
		$base_x = Str::replace(',', '.', $base_val);
		$target_val =  Str::remove('.',Str::substr($request->target_value,3));
		$target_x = Str::replace(',', '.', $target_val);
		$data_lead = [
			"lds_id" => $id,
			"lds_title" => $request->lead_title,
			"lds_status" => 1,
			"lds_customer" => $request->idcustomer
		];
		$data_lead_value = [
			"lvs_lead_id" => $id,
			"lvs_base_value" => $base_x,
			"lvs_target_value" => $target_x,
		];
		$person_lead = array();
		$person_to_store = array();
		$new_id_person = genIdPerson();
		foreach ($request->customer_pic_contact as $key => $value) {
			if (is_numeric($value) == false) {
				$person_to_store[$key] = [
					"cnt_id" => $new_id_person,
					"cnt_cst_id" => $request->idcustomer,
					"cnt_fullname" => $value,
					"cnt_nickname" => null,
					"cnt_company_position" => null,
					"cnt_notes" => null,
					"view_option" => 'PUBLIC',
					"created_by" => $user->id
				];
				$person_lead[$key] = [
					"plc_lead_id" => $id,
					"plc_attendant_id" => $new_id_person,
					"plc_customer_id" => $request->idcustomer,
					"created_by" => $user->id
				];
				$new_id_person++;
			}else{
				$person_lead[$key] = [
					"plc_lead_id" => $id,
					"plc_attendant_id" => $value,
					"plc_customer_id" => $request->idcustomer,
					"created_by" => $user->id
				];
			}
		}

		$assign_lead = [
			"slm_user" => $request->assign_sales,
			"slm_rules" => 'head',
			"slm_lead" => $id,
			"created_by" => $user->id
		];
		$assign_team = array();
		foreach ($request->assign_team as $key => $value) {
			$assign_team = [
				"slm_user" => $value,
				"slm_rules" => 'colaborator',
				"slm_lead" => $id,
				"created_by" => $user->id
			];
		}
		$actionStore_SalesTeam = Prs_accessrule::insert($assign_team);
		$actionStore_Sales = Prs_accessrule::insert($assign_lead);
		$actionStore_PersonLead = Prs_contact::insert($person_lead);
		$actionStore_Person = Cst_personal::insert($person_to_store);
		$actionStore_LeadValue = Prs_lead_value::insert($data_lead_value);
		$actionStore_Lead = Prs_lead::insert($data_lead);
		$result = [
			'param'=>true,
			'idlead' => $id
		];
		return $result;
	}
	###
	public function storeLeadFollowUp(Request $request)
	{
		$user = Auth::user();
		$today =Carbon::now()->locale('id-ID');
		$act_task_times = date('Y-m-d h:i:s',strtotime($request->start_date));
		$act_task_times_due = date('Y-m-d h:i:s', strtotime($request->due_date));
		if ($request->todo_status == "done") {
			$act_run_status = "finished";
		}else if($request->todo_status == "running"){
			$act_run_status = "running";
		}else {
			if ($act_task_times < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		$todo = Act_activity_type::where('aat_type_code',$request->action)->select('aat_id', 'aat_type_name')->first();
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
		}else{
			$act_user_teams = null;
		}
		$data = [
			'act_id' => genIdActivity(),
			'act_lead_id' => $request->lead_id,
			'act_todo_type_id' => $todo->aat_type_name,
			'act_run_status' => $act_run_status,
			'act_lead_status' => $request->lead_status_id, 
			// 'act_todo_priority' => null,
			'act_todo_describe' => $request->activity_summary,
			'act_todo_result' => null,
			'act_task_times' => $act_task_times,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::insert($data);
		return true;
	}
	###
	public function LeadDataDetailView(Request $request)
	{
		$user = Auth::user();
		$users = User::join('user_structures','users.id','=','user_structures.usr_user_id')
		->leftJoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->select('id','name','level','uts_team_name')
		->whereNotIn('level',['ADM','AGM'])
		->get();
		$id_lead = $request->id;
		$lead = Prs_lead::leftjoin('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
		->where('lds_id',$id_lead)
		->select('lds_id','lds_title','lds_status','lds_describe','lds_customer','pls_status_name')
		->first();
		$lead_contacts = Prs_contact::join('cst_personals', 'prs_contacts.plc_attendant_id','=', 'cst_personals.cnt_id')
		->leftjoin('cst_customers','cst_customers.cst_id','=','cst_personals.cnt_cst_id')
		->select('plc_id', 'plc_lead_id', 'plc_attendant_id', 'plc_attendant_rule', 'plc_customer_id','cnt_id', 'cnt_fullname', 'cnt_company_position','cst_name')
		->where('plc_lead_id',$id_lead)
		->get();
		$cst_ids= explode(',',$lead->lds_customer);
		$lead_customer = Cst_customer::join('cst_institutions','cst_customers.cst_institution','=', 'cst_institutions.ins_id')
		->whereIn('cst_id', $cst_ids)
		->select('cst_id','ins_id', 'ins_name', 'cst_name', 'view_option')
		->get();
		$all_contacts = Cst_personal::join('cst_customers','cst_personals.cnt_cst_id','=','cst_customers.cst_id')
		->whereIn('cnt_cst_id', $cst_ids)
		->select('cnt_id','cnt_fullname','cst_name')
		->get();
		$institution_names = str::title($lead_customer->first()->ins_name);
		$status = Prs_lead_statuses::where('pls_code_name','!=','dead_end')->get();
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
		$qualifying = Prs_qualification::where('lqs_lead_id', $id_lead)->get();
		$ident_need = array('param' => null, 'textdata' => null);
		$ident_budget = array('param' => null, 'textdata' => null);
		foreach ($qualifying as $key => $value) {
			if ($value->lqs_type_identification == 'identification_needs') {
				$ident_need = [
					'param' => $value->lqs_parameter,
					'textdata' => $value->lqs_describe
				];
			}elseif ($value->lqs_type_identification == 'identification_budgets') {
				$ident_budget = [
					'param' => $value->lqs_parameter,
					'textdata' => $value->lqs_describe
				];
			}else {
			}
		}
		###
		$principle = Prd_principle::get();
		$activity_type = Act_activity_type::get();
		$checkOppor = Opr_opportunity::where('opr_lead_id',$id_lead)->select('opr_id')->first();
		return view('contents.page_lead.lead_detail',
			compact(
				'user','users','id_lead','status','lead','sales','member_name','team_member_id','tech_name','team_tech_id','sales_selected', 'team_selected',
				'user_marketing','user_tech','institution_names', 'lead_customer', 'lead_value',
				'ident_need','ident_budget','all_contacts','lead_contacts','activity_type','principle','checkOppor'
			)
		);
	}
	####
	public function storeUpdateStatusLead(Request $request)
	{
		$updating_lead = Prs_lead::where('lds_id',$request->id)->update(['lds_status'=>$request->status]);
		$result = [
			'param'=>true,
			'new_status' => $request->status
		];
		return $result;
		
	}
	####
	public function storeUpdateBaseValue(Request $request)
	{
		$user = Auth::user();
		$value =  Str::remove('.',Str::substr($request->base_value,3));
		$x = Str::replace(',', '.', $value);
		$checkBaseValue = Prs_lead_value::where('lvs_lead_id',$request->id)->first();
		if ($checkBaseValue == null) {
			$data = [
				"lvs_lead_id" => $request->id,
				"lvs_base_value" => $x,
				"created_by" => $user->id
			];
			$actionStore = Prs_lead_value::insert($data);
		}else{
			$actionUpdate = Prs_lead_value::where('lvs_lead_id',$request->id)->update(['lvs_base_value' => $x]);
		}
		$result = [
			'param' => true,
			'currency' => fcurrency($x)
		];
		return $result;
	}
	####
	public function storeUpdateTargetValue(Request $request)
	{
		$user = Auth::user();
		$value =  Str::remove('.', Str::substr($request->target_value, 3));
		$x = Str::replace(',', '.', $value);
		// echo $x;
		// die();
		$checkBaseValue = Prs_lead_value::where('lvs_lead_id',$request->id)->first();
		if ($checkBaseValue == null) {
			$data = [
				"lvs_lead_id" => $request->id,
				"lvs_target_value" => $x,
				"created_by" => $user->id
			];
			$actionStore = Prs_lead_value::insert($data);
		}else{
			$actionUpdate = Prs_lead_value::where('lvs_lead_id',$request->id)->update(['lvs_target_value' => $x]);
		}
		$result = [
			'param' => true,
			'currency' => fcurrency($x)
		];
		return $result;
	}
	####
	public function storeChangeSales(Request $request)
	{
		$user_selected = User::where('id', $request->select_sales)->select('id','name')->first();
		if ($user_selected == null) {
			$result = [
			'param' => false,
			'user' => null,
		];
		return $result;
		}
		$checkData = Prs_accessrule::where('slm_lead',$request->id)->where('slm_rules','master')->first();
		if ($checkData == null) {
			$data = [
				'slm_user' => $request->select_sales,
				'slm_lead' => $request->id,
				'slm_rules' => 'master',
				'created_by' => null
			];
			$actionAddSales = Prs_accessrule::insert($data);
		}else{
			$actionChangeSales = Prs_accessrule::where('slm_lead',$request->id)->where('slm_rules','master')->update(['slm_user'=>$request->select_sales]);
		}
		$result = [
			'param' => true,
			'user' => $user_selected->name,
		];
		return $result;
	}
	####
	public function storeSelectTeam(Request $request)
	{
		if (!$request->select_teams) {
			$ids = array();
		}else{
			$ids = $request->select_teams;
		}
		$actionDeleteId = Prs_accessrule::where('slm_lead',$request->id)->where('slm_rules','colaborator')->delete();
		$data = array();
		foreach ($ids as $key => $value) {
			$data[$key] = [
				'slm_user' => $value,
				'slm_lead' => $request->id,
				'slm_rules' => 'colaborator'
			];
		}
		$actionStoreSales = Prs_accessrule::insert($data);
		$data_user = User::whereIn('id',$ids)->get();
		$str_user ='';
		foreach ($data_user as $key => $value) {
			if ($key == 0) {
				$str_user.= $value->name;
			}else {
				$str_user.= ', '.$value->name;
			}
		}
		$result = [
			'param' =>true,
			'user_member' => $str_user
		];
		return $result;
	}
	/* Tags:... */
	public function storeSelectTech(Request $request)
	{
		if (!$request->select_tech) {
			$ids = array();
		}else{
			$ids = $request->select_tech;
		}
		$actionDeleteId = Prs_accessrule::where('slm_lead',$request->id)->where('slm_rules','technical')->delete();
		$data = array();
		foreach ($ids as $key => $value) {
			$data[$key] = [
				'slm_user' => $value,
				'slm_lead' => $request->id,
				'slm_rules' => 'technical'
			];
		}
		$actionStoreSales = Prs_accessrule::insert($data);
		$data_user = User::whereIn('id',$ids)->get();
		$str_user ='';
		foreach ($data_user as $key => $value) {
			if ($key == 0) {
				$str_user.= $value->name;
			}else {
				$str_user.= ', '.$value->name;
			}
		}
		$result = [
			'param' =>true,
			'user_tech' => $str_user
		];
		return $result;
	}
	####
	public function storeIdentificationQualification(Request $request)
	{

		$user = Auth::user();
		if ($request->param == 'true') {
			$p = 'true';
		}else{
			$p = 'false';
		}
		$check_set_in = Prs_qualification::where('lqs_lead_id',$request->id)->where('lqs_type_identification',$request->rule)->first();
		if (!isset($check_set_in)) {
			$data = [
				"lqs_lead_id" => $request->id,
				"lqs_type_identification" => $request->rule,
				"lqs_parameter" => $request->param,
				"lqs_describe" => $request->textdata,
				"created_by" => $user->id
			];
			$create_set_in = Prs_qualification::insert($data);
		}else{
			$update_set_in = Prs_qualification::where('lqs_id',$check_set_in->lqs_id)->update(["lqs_parameter" => $p, "lqs_describe" => $request->textdata]);
		}
		return true;
	}
	#####
	public function storeContactLead(Request $request)
	{
		$user = Auth::user();
		if (is_numeric($request->contact)) {
			$person_id = $request->contact;
			$checkContact = Prs_contact::join('cst_personals','prs_contacts.plc_attendant_id','=','cst_personals.cnt_id')
			->where('plc_attendant_id',$person_id )
			->where('plc_lead_id',$request->id)
			->count();
			if ($checkContact == 0) {
				$person = Cst_personal::where('cnt_id', $person_id)->first();
				$data_person_lead = [
					'plc_lead_id' => $request->id,
					'plc_attendant_id' => $person->cnt_id,
					'plc_attendant_rule' => $person->cnt_company_position,
					'plc_customer_id' => $person->cnt_cst_id,
					'created_by' => $user->id
				];
			}else{
				$result = [
					'param'=>false,
				];
				return $result;
			}
			// die('test');
		}else{
			$person_id = genIdPerson();
			$data_person = [
				"cnt_id" => $person_id,
				"cnt_cst_id" => $request->customer,
				"cnt_fullname" => $request->contact,
				"cnt_nickname" => null,
				"cnt_company_position" => $request->position,
				"cnt_notes" => null,
				"view_option" => "PUBLIC",
				"created_by" => $user->id
			];
			$data_person_lead = [
				'plc_lead_id' => $request->id,
				'plc_attendant_id' => $person_id,
				'plc_attendant_rule' => $request->position,
				'plc_customer_id' => $request->customer,
				'created_by' =>$user->id
			];
			$actionCreatePerson = Cst_personal::insert($data_person);
		}
		$actionCreatePersonLead = Prs_contact::insert($data_person_lead);
		// $person = Cst_personal::join('cst_customers', 'cst_personals.cnt_cst_id','=', 'cst_customers.cst_id')
		// ->where('cnt_id', $person_id)
		// ->select('cnt_id','cst_id','cnt_fullname','cst_name')
		// ->first();
		$person_contact = Prs_contact::join('cst_personals','prs_contacts.plc_attendant_id','=','cst_personals.cnt_id')
		->join('cst_customers','prs_contacts.plc_customer_id','=','cst_customers.cst_id')
		->where('plc_attendant_id',$person_id )
		->where('plc_lead_id',$request->id)
		->select('plc_id','plc_attendant_id','cst_name','cnt_fullname')
		->first();
		$result = [
			'param' => true,
			'contact_id' => $person_contact->plc_id,
			'personal_id' => $person_contact->plc_attendant_id,
			'name_cst' => $person_contact->cst_name,
			'name_cnt' => $person_contact->cnt_fullname
		];
		return $result;
	}
	####
	public function actionRemoveContact(Request $request){
		$actionRemove = Prs_contact::where('plc_id',$request->id)->delete();
		$text = 'contact-'.$request->id;
		$result = [
			'param' => true,
			'id_card' => $text,
		];
		return $result;
	}
	/* Tags:... */
	public function actionGetCstProject(Request $request)
	{
		$user = Auth::user();
		$lead = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
		->where('lds_customer',$request->id)
		// ->where('slm_user',$user->id)
		->select('lds_id','lds_title')
		->get();
		$data = array();
    foreach ($lead as $key => $value) {
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
