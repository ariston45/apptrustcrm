<?php

namespace App\Http\Controllers;

use App\Models\Act_activity;
use App\Models\Act_activity_access;
use App\Models\Act_activity_type;
use App\Models\Cst_contact_email;
use App\Models\Cst_contact_phone;
use App\Models\Cst_customer;
use App\Models\Cst_location;
use App\Models\Cst_personal;
use App\Models\Ord_purchase;
use App\Models\Prs_accessrule;
use App\Models\Prs_lead;
use App\Models\User;
use App\Models\User_structure;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use DB;

class ManagementController extends Controller
{
	/* Tags:... */
	public function viewManagingUsers(Request $request)
	{
		$team = checkTeamMgr(3);
		// $users = User::whereIn('id',$team)->get();
		// foreach ($users as $key => $value) {
		// 	$lead[$key] = Prs_accessrule::where('slm_user',$value->id)
		// 	->where('slm_rules','master')
		// 	->get();
		// 	foreach ($lead[$key] as $skey => $svalue) {
		// 		$lead_id[$value->id][$skey] = $svalue->slm_lead;
		// 	}
		// 	$data[$key] = [
		// 		'id' => $value->id,
		// 		'name' => $value->name,
		// 		'lead' => count($lead[$key]),
		// 	]; 
		// }
		// dd($data);
		// die();
		return view('contents.page_management.all_users');
	}

	
	/* Tags:... */
	public function viewManagingUsersDetail(Request $request)
	{
    $auth = Auth::user();
		$user = User_structure::join('users','user_structures.usr_user_id','=','users.id')
		->join('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->where('id',$request->id)
		->first();
		$id = $user->id;
		return view('contents.page_management.mgn_user_info', compact('id','user'));
	}
	/* Tags:... */
	public function viewActivitiesUser(Request $request)
	{
		$auth = Auth::user();
		$user = User_structure::join('users','user_structures.usr_user_id','=','users.id')
		->join('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->where('id',$request->id)
		->first();
		$id = $request->id;
    $company = Cst_customer::join('cst_institutions','cst_institutions.ins_id','=','cst_customers.cst_institution')
    ->leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->where('cst_id', $id)
    ->select('cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes','ins_name')
    ->first();
		$user = Auth::user();
		$users = User::get();
		$status_activity = Act_activity::get();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			# code...
			$all_activities = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
      ->where('act_cst', $id)
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['colaborator', 'manager'])
			->where('slm_user', $user->id)
			->select('slm_lead')
			->get()
			->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_ids = array_unique($lds_idr);
			$all_activities = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
			->whereIn('act_activities.act_lead_id', $lds_ids)
			->where('act_label_category', 'ACTIVITY')
			->select('act_id', 'aat_type_code')
			->get();
		} elseif(checkRule(array('MGR.TCH'))) {
			$act_access = Act_activity_access::whereIn('acs_user_id',$id)
			->select('acs_act_id')
			->get();
			$act_idr = array();
			foreach ($act_access as $key => $value) {
				$act_idr[$key] = $value->acs_act_id;
			}
			$act_ids = array_unique($act_idr);
			$all_activities = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
      ->where('act_cst', $id)
			->whereIn('act_activities.act_id',$act_ids)
			->select('act_id','aat_type_code')
			->get();
		} 
		$cnt_todo = $all_activities->where('aat_type_code','act_todo')->count();
		$cnt_phone = $all_activities->where('aat_type_code','act_phone')->count();
		$cnt_email = $all_activities->where('aat_type_code','act_email')->count();
		$cnt_visit = $all_activities->where('aat_type_code','act_visit')->count();
		$cnt_poc = $all_activities->where('aat_type_code','act_poc')->count();
		$cnt_webinar = $all_activities->where('aat_type_code','act_webinar')->count();
		$cnt_video_call = $all_activities->where('aat_type_code','act_video_call')->count();
		$cnt_total = $cnt_todo+$cnt_phone+$cnt_email+$cnt_visit+$cnt_poc+$cnt_webinar+$cnt_video_call;
		$activity_type = Act_activity_type::get();
		$user_all = User::whereIn('level',['MKT','MGR.PAS','MGR','AGM','TCK'])->get();
		$customer_all = Cst_customer::select('cst_id','cst_name')->get();
		return view('contents.page_management.mgn_user_activity', compact('id','user',
			'id','activity_type','cnt_todo','cnt_phone','cnt_email','cnt_visit','cnt_poc','cnt_webinar','cnt_video_call','cnt_total','user_all','user','customer_all','company'
		));
	}
	public function viewLeadsUser(Request $request)
	{
		$auth = Auth::user();
		$user = User_structure::join('users','user_structures.usr_user_id','=','users.id')
		->join('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->where('id',$request->id)
		->first();
		$id = $user->id;
		return view('contents.page_management.mgn_user_lead', compact('id','user'));
	}
	public function viewOpportunitiesUser(Request $request)
	{
		$auth = Auth::user();
		$user = User_structure::join('users','user_structures.usr_user_id','=','users.id')
		->join('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->where('id',$request->id)
		->first();
		$id = $user->id;
		return view('contents.page_management.mgn_user_opportunities', compact('id','user'));
	}
	/* Tags:... */
	public function viewPurchasesUser(Request $request)
	{
		$auth = Auth::user();
		$user = User_structure::join('users','user_structures.usr_user_id','=','users.id')
		->join('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->where('id',$request->id)
		->first();
		$id = $user->id;
		return view('contents.page_management.mgn_user_purchases', compact('id','user'));
	}
	public function sourceDataManagementUser(Request $request)
	{
		$user = Auth::user();
		$user_ids_team = checkTeamMgr($user->id);
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$colect_data = User_structure::join('users','user_structures.usr_user_id','=','users.id')
			->join('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
			->leftJoin(DB::raw('(SELECT aa.slm_user,COUNT(aa.slm_id) AS count_prs FROM prs_accessrules aa WHERE aa.slm_rules = "master" GROUP BY aa.slm_user ) leads'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','leads.slm_user');				
				})
			->leftJoin(DB::raw('(SELECT aa.slm_user,COUNT(bb.opr_id) as count_opr FROM prs_accessrules aa JOIN opr_opportunities bb ON aa.slm_lead = bb.opr_lead_id 
				WHERE aa.slm_rules="master" AND bb.opr_close_status IS NULL GROUP BY aa.slm_user) opportunities'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','opportunities.slm_user');				
				})
			->leftJoin(DB::raw('(SELECT aa.slm_user,COUNT( cc.pur_id ) AS count_pur FROM prs_accessrules aa JOIN opr_opportunities bb ON aa.slm_lead = bb.opr_lead_id
				JOIN ord_purchases cc ON bb.opr_id = cc.pur_oppr_id WHERE aa.slm_rules = "master" GROUP BY aa.slm_user) purchases'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','purchases.slm_user');				
				})
			->leftJoin(DB::raw('(SELECT aa.acs_user_id,COUNT( aa.acs_id ) AS count_act FROM act_activity_access aa JOIN act_activities bb ON aa.acs_act_id = bb.act_id 
				WHERE aa.acs_rules = "assignee" AND bb.act_label_category = "ACTIVITY" GROUP BY aa.acs_user_id) activities'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','activities.acs_user_id');				
				})
			->leftJoin(DB::raw('(SELECT aa.acs_user_id,COUNT( aa.acs_id ) AS count_assign_tck FROM act_activity_access aa JOIN act_activities bb ON aa.acs_act_id = bb.act_id 
				WHERE aa.acs_rules = "assignee" AND bb.act_label_category = "TICKET" GROUP BY aa.acs_user_id) tickets_ass'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','tickets_ass.acs_user_id');				
				})
			->leftJoin(DB::raw('(SELECT aa.acs_user_id,COUNT( aa.acs_id ) AS count_create_tck FROM act_activity_access aa JOIN act_activities bb ON aa.acs_act_id = bb.act_id 
				WHERE aa.acs_rules = "creator" AND bb.act_label_category = "TICKET" GROUP BY aa.acs_user_id) tickets_crt'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','tickets_crt.acs_user_id');				
				})
			->select('id','name','uts_team_name','count_prs','count_opr','count_pur','count_act','count_assign_tck','count_create_tck')
			->get();
		}elseif (checkRule(array('MGR','MGR.TCH'))) {
			$colect_data = User_structure::join('users','user_structures.usr_user_id','=','users.id')
			->join('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
			->leftJoin(DB::raw('(SELECT aa.slm_user,COUNT(aa.slm_id) AS count_prs FROM prs_accessrules aa WHERE aa.slm_rules = "master" GROUP BY aa.slm_user ) leads'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','leads.slm_user');				
				})
			->leftJoin(DB::raw('(SELECT aa.slm_user,COUNT(bb.opr_id) as count_opr FROM prs_accessrules aa JOIN opr_opportunities bb ON aa.slm_lead = bb.opr_lead_id 
				WHERE aa.slm_rules="master" AND bb.opr_close_status IS NULL GROUP BY aa.slm_user) opportunities'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','opportunities.slm_user');				
				})
			->leftJoin(DB::raw('(SELECT aa.slm_user,COUNT( cc.pur_id ) AS count_pur FROM prs_accessrules aa JOIN opr_opportunities bb ON aa.slm_lead = bb.opr_lead_id
				JOIN ord_purchases cc ON bb.opr_id = cc.pur_oppr_id WHERE aa.slm_rules = "master" GROUP BY aa.slm_user) purchases'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','purchases.slm_user');				
				})
			->leftJoin(DB::raw('(SELECT aa.acs_user_id,COUNT( aa.acs_id ) AS count_act FROM act_activity_access aa JOIN act_activities bb ON aa.acs_act_id = bb.act_id 
				WHERE aa.acs_rules = "assignee" AND bb.act_label_category = "ACTIVITY" GROUP BY aa.acs_user_id) activities'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','activities.acs_user_id');				
				})
			->leftJoin(DB::raw('(SELECT aa.acs_user_id,COUNT( aa.acs_id ) AS count_assign_tck FROM act_activity_access aa JOIN act_activities bb ON aa.acs_act_id = bb.act_id 
				WHERE aa.acs_rules = "assignee" AND bb.act_label_category = "TICKET" GROUP BY aa.acs_user_id) tickets_ass'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','tickets_ass.acs_user_id');				
				})
			->leftJoin(DB::raw('(SELECT aa.acs_user_id,COUNT( aa.acs_id ) AS count_create_tck FROM act_activity_access aa JOIN act_activities bb ON aa.acs_act_id = bb.act_id 
				WHERE aa.acs_rules = "creator" AND bb.act_label_category = "TICKET" GROUP BY aa.acs_user_id) tickets_crt'),
				function ($join){
					$join->on('user_structures.usr_user_id','=','tickets_crt.acs_user_id');				
				})
			->whereIn('users.id',$user_ids_team)
			->select('id','name','uts_team_name','count_prs','count_opr','count_pur','count_act','count_assign_tck','count_create_tck')
			->get();
		}else {
			$colect_data = null;
		}
		return DataTables::of($colect_data)
		->addIndexColumn()
		->addColumn('empty_str', function ($k) {
			return '';
		})
		->addColumn('menu', function ($colect_data) {
			return '
			<div style="text-align:center;">
			<button type="button" class="badge bg-cyan" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="ri-list-settings-line"></i></button>
			<div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 38px);">
			<a class="dropdown-item" href="'.url('management/user-information/'.$colect_data->id.'?extpg=information').'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail User</a>
      </div></div>';
		})
		->addColumn('name', function ($colect_data) {
			return $colect_data->name;
		})
		->addColumn('team', function ($colect_data) {
			return $colect_data->uts_team_name;
		})
		->addColumn('c_lead', function ($colect_data) {
			if ($colect_data->count_prs == null) {
				return '-';
			}else{
				return $colect_data->count_prs;
			}
		})
		->addColumn('c_opportunity', function ($colect_data) {
			if ($colect_data->count_opr == null) {
				return '-';
			}else{
				return $colect_data->count_opr;
			}
		})
		->addColumn('c_purchase', function ($colect_data) {
			if ($colect_data->count_pur == null) {
				return '-';
			}else{
				return $colect_data->count_pur;
			}
		})
		->addColumn('c_activity', function ($colect_data) {
			if ($colect_data->count_act == null) {
				return '-';
			}else{
				return $colect_data->count_act;
			}
		})
		->addColumn('c_ticket_create', function ($colect_data) {
			if ($colect_data->count_create_tck == null) {
				return '-';
			}else{
				return $colect_data->count_create_tck;
			}
		})
		->addColumn('c_ticket_assign', function ($colect_data) {
			if ($colect_data->count_assign_tck == null) {
				return '-';
			}else{
				return $colect_data->count_assign_tck;
			}
		})
		->rawColumns(['menu','name','team','c_lead','c_opportunity','c_purchase','c_activity','c_ticket_create','c_ticket_assign'])
		->make('true');
	}
	/* Tags:... */
	public function sourceStatisticActivityUser(Request $request)
	{
		$id = $request->id;
		$date_now = now();
		$start_date = new DateTime(date('Y-m-d', strtotime($date_now.'-10 day')));
		$end_date = new DateTime(date('Y-m-d', strtotime($date_now.'+11 day'))) ;
		$interval = new DateInterval('P1D');
		$datePeriod = new DatePeriod($start_date, $interval, $end_date);
		foreach ($datePeriod as $key => $date) {
			$DtLabels[$key] = $date->format('Y-m-d');
			$todo[$key] = Act_activity::where('act_user_assigned',$id)
			->where('act_todo_type_id','1')
			->where('act_task_times_due','like',$date->format('Y-m-d').'%')
			->count();
			$phone[$key] = Act_activity::where('act_user_assigned',$id)
			->where('act_todo_type_id','2')
			->where('act_task_times_due','like',$date->format('Y-m-d').'%')
			->count();
			$email[$key] = Act_activity::where('act_user_assigned',$id)
			->where('act_todo_type_id','3')
			->where('act_task_times_due','like',$date->format('Y-m-d').'%')
			->count();
			$visit[$key] = Act_activity::where('act_user_assigned',$id)
			->where('act_todo_type_id','4')
			->where('act_task_times_due','like',$date->format('Y-m-d').'%')
			->count();
			$proofing[$key] = Act_activity::where('act_user_assigned',$id)
			->where('act_todo_type_id','5')
			->where('act_task_times_due','like',$date->format('Y-m-d').'%')
			->count();
			$webinar[$key] = Act_activity::where('act_user_assigned',$id)
			->where('act_todo_type_id','6')
			->where('act_task_times_due','like',$date->format('Y-m-d').'%')
			->count();
			$video_call[$key] = Act_activity::where('act_user_assigned',$id)
			->where('act_todo_type_id','7')
			->where('act_task_times_due','like',$date->format('Y-m-d').'%')
			->count();
		}
		$result = [
			'param'=>true,
			'DtLabels' => $DtLabels,
			'ValSeries' =>[
				0 => [
					'name'=> 'Todo',
					'data'=> $todo,
				],
				1 => [
					'name'=> 'Phone',
					'data'=> $phone,
				],
				2 => [
					'name'=> 'Email',
					'data'=> $email,
				],
				3 => [
					'name'=> 'Visit',
					'data'=> $visit,
				],
				4 => [
					'name'=> 'PoC',
					'data'=> $proofing,
				],
				5 => [
					'name'=> 'Webinar',
					'data'=> $webinar,
				],
				6 => [
					'name'=> 'Video Call',
					'data'=> $video_call,
				],

			]
		];
		return $result;
	}
	/* Tags:... */
	public function sourceStatisticLeadUser(Request $request)
	{
		$id = $request->id;
		$lds_prospect = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
		->where('slm_user',$id)
		->where('lds_status','1')
		->where('lds_stage_opr','false')
		->where('slm_rules','master')
		->count();
		$lds_qualify = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
		->where('slm_user',$id)
		->whereIn('lds_status',['2','3'])
		->where('lds_stage_opr','false')
		->where('slm_rules','master')
		->count();
		$lds_opportunity = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
		->where('slm_user',$id)
		->where('lds_status','3')
		->where('lds_stage_opr','true')
		->where('slm_rules','master')
		->count();
		$lds_lose = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
		->where('slm_user',$id)
		->where('lds_status','0')
		->where('lds_stage_opr','false')
		->where('slm_rules','master')
		->count();
		$result = [
			'param'=>true,
			'ValLabels' =>[
				'0'=> 'Prospecting',
				'1'=> 'Qualifying',
				'2'=> 'Opportunity',
				'3'=> 'Lose',
			],
			'ValSeries'=>[
				'0'=> $lds_prospect,
				'1'=> $lds_qualify,
				'2'=> $lds_opportunity,
				'3'=> $lds_lose,
			]
		];
		return $result;
	}
	/* Tags:... */
	public function sourceStatisticOpportunityUser(Request $request)
	{
		$id = $request->id;
		$opr = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
		->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
		->where('slm_rules','master')
		->where('slm_user',$id)
		->get();
		$list_new = [];
		$list_presentation = [];
		$list_poc = [];
		$list_proposal = [];
		$list_negotiation = [];
		$list_win = [];
		$list_lose = [];
		$list_none = [];
		foreach ($opr as $key => $value) {
			if ($value->opr_status == '1') {
				$list_new[] = $value->opr_id;
			}elseif ($value->opr_status == '2') {
				$list_presentation[] = $value->opr_id;
			}elseif ($value->opr_status == '3') {
				$list_poc[] = $value->opr_id;
			}elseif ($value->opr_status == '4') {
				$list_proposal[] = $value->opr_id;
			}elseif ($value->opr_status == '5') {
				$list_negotiation[] = $value->opr_id;
			}elseif ($value->opr_status == '6') {
				$list_win[] = $value->opr_id;
			}elseif ($value->opr_status == '7') {
				$list_lose[] = $value->opr_id;
			}else{
				$list_none[] = null; 
			}
		}
		$result = [
			'param'=>true,
			'ValLabels' => ['New Opportunity','Presentation','POC','Proposal','Negotiation','Win','Lose'],
			'ValSeries' => [
				0=> count($list_new),
				1=> count($list_presentation),
				2=> count($list_poc),
				3=> count($list_proposal),
				4=> count($list_negotiation),
				5=> count($list_win),
				6=> count($list_lose),
			]
		];
		return $result;
	}
	/* Tags:... */
	public function sourceStatisticPurchaseUser(Request $request)
	{
		$id = $request->id;
		$date_now = now();
		$start_date = new DateTime(date('Y-m', strtotime($date_now.'-11 month')));
		$end_date = new DateTime(date('Y-m', strtotime($date_now.'+1 month')));
		$interval = new DateInterval('P1M');
		$datePeriod = new DatePeriod($start_date, $interval, $end_date);
		foreach ($datePeriod as $key => $date) {
			$labels[] = $date->format('M-y');
			$series[] = Ord_purchase::where('pur_date','like',$date->format('Y-m').'%')->where('created_by',$id)->count();
		}
		$result = [
			'param'=>true,
			'ValLabels' => $labels,
			'ValSeries' => $series,
		];
		return $result;
	}
}
