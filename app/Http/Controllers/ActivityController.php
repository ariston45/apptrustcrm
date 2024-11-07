<?php

namespace App\Http\Controllers;

use App\Models\Act_activity_access;
use App\Models\Opr_opportunity;
use App\Models\Opr_product_opportunity;
use App\Models\Opr_stage_status;
use App\Models\Opr_value_assumtion;
use App\Models\Prd_principle;
use App\Models\Prd_product;
use App\Models\Prs_accessrule;
use App\Models\Prs_contact;
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
use App\Models\Prd_subproduct;
use App\Models\Prs_lead;
use App\Models\Prs_lead_customer;
use App\Models\Prs_lead_status;
use App\Models\Prs_lead_statuses;
use App\Models\Prs_product_offer;
use App\Models\Prs_lead_value;
use App\Models\Prs_qualification;
use App\Models\Prs_salesperson;
use App\Models\User;
use App\Models\User_structure;
use DataTables;
#Helpers
use stdClass;
use Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
	/* Tags:... */
	public function viewActivity(Request $request)
	{
		$user = Auth::user();
		$users = User::get();
		$users_mod[0] = ["all_user"=>"Filter User"];
		$idx_user = 1;
		foreach ($users as $key => $value) {
			$users_mod[$idx_user] = [
				$value->id => $value->name,
			];
			$idx_user++;
		}
		$status_activity = Act_activity::get();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			# code...
			$all_activities = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_ids = array_unique($lds_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->whereIn('act_activities.act_lead_id',$lds_ids)
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('STF'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_ids = array_unique($lds_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->whereIn('act_activities.act_lead_id',$lds_ids)
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('MGR.TCH'))) {
			$user = Auth::user();
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_idr = array();
			foreach ($act_access as $key => $value) {
				$act_idr[$key] = $value->acs_act_id;
			}
			$act_ids = array_unique($act_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->whereIn('act_activities.act_id',$act_ids)
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('STF.TCH','STF'))) {
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_idr = array();
			foreach ($act_access as $key => $value) {
				$act_idr[$key] = $value->acs_act_id;
			}
			$act_ids = array_unique($act_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
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
		$user_all = User::join('user_structures','users.id','=','user_structures.usr_user_id')
		->leftjoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->whereIn('level',['STF','STF.TCH','MGR.TCH','MGR.PAS','MGR','AGM','ADM'])
		->select('id','name','uts_team_name')
		->get();
		$customer_all = Cst_customer::select('cst_id','cst_name')->get();
		return view('contents.page_activity.activity',compact(
			'activity_type','cnt_todo','cnt_phone','cnt_email','cnt_visit','cnt_poc','cnt_webinar','cnt_video_call','cnt_total','user_all','user','customer_all','users','users_mod'
		));
	}
	/* Tags:... */
	public function viewActivityDetail(Request $request)
	{
		$id_activity = $request->id;
		$user = Auth::user();
		$all_user = User::get();
		$main_activity = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
		->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
		->join('users','act_activities.act_user_assigned','users.id')
		->where('act_id',$id_activity)
		->select('act_cst','lds_id','lds_title','aat_type_name','aat_type_code','aat_custom_class_2','aat_icon','act_run_status','act_todo_describe','act_todo_result','act_task_times',
		'act_task_times_due','act_user_customer','id as userid','name','act_user_teams','act_activities.created_at as act_date_crate',
		'act_activities.created_by as act_user_crate','act_todo_type_id','act_lead_id','lds_status','act_cst')
		->first();
		$activity_type = Act_activity_type::get();
		$customer = Cst_customer::join('cst_institutions','cst_customers.cst_institution','=','cst_institutions.ins_id')
		->where('cst_id',$main_activity->act_cst)
		->select('cst_name','ins_name')
		->first();
		$usr_team_id = array();
		$usr_team_id = explode(',',$main_activity->act_user_teams);
		$user_team = User::whereIn('id',$usr_team_id)
		->select('id','name')
		->get();
		$count_team = $user_team->count();
		$team_ar = array();
		$team_id_ar = array();
		foreach ($user_team as $key => $value) {
			$team_id_ar[$key] = $value->id;
			$team_ar[$key] = $value->name; 
		}
		$team = implode(', ',$team_ar);
		$usr_customer_id = array();
		$usr_customer_id = explode(',',$main_activity->act_user_customer);
		$cst_pic = Cst_personal::whereIn('cnt_id',$usr_customer_id)
		->select('cnt_id','cnt_fullname')
		->get();
		$user_created = User::where('id',$main_activity->act_user_crate)
		->select('id','name')
		->first();
		$date_created = date('D, d/M Y, h:i a',strtotime($main_activity->act_date_crate));
		$date_due = date('D, d/M Y, h:i a',strtotime($main_activity->act_task_times_due));
		$cst_person_id = explode(',',$main_activity->act_user_customer);
		$cst_person = Cst_personal::whereIn('cnt_id',$cst_person_id)->select('cnt_id','cnt_fullname')->get();
		$cst_person_all = Cst_personal::where('cnt_cst_id',$main_activity->act_cst)->select('cnt_id','cnt_fullname')->get();
		// echo $cst_person_all;
		// die();
		return view('contents.page_activity.activity_detail',compact('id_activity','customer','main_activity','team','date_created','date_due','user_created',
			'cst_person','activity_type','all_user','count_team','user_team','team_id_ar','cst_person_all','cst_person_id','user'
		));
	}
	public function sourceDataActivityCalender(Request $request)
	{
		$user = Auth::user();
		$start_date_arr = explode(" ",$request->start);
		$start_date_str = $start_date_arr[3].'-'.$start_date_arr[1].'-'.$start_date_arr[2];
		$start = date('Y-m-d',strtotime($start_date_str));
		$end_date_arr = explode(" ",$request->end);
		$end_date_str = $end_date_arr[3].'-'.$end_date_arr[1].'-'.$end_date_arr[2];
		$end = date('Y-m-d',strtotime($end_date_str));
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			# code...
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_lead_id',$lds_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('STF'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_lead_id',$lds_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('MGR.TCH'))) {
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$act_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('STF.TCH'))) {
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$act_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}
		$data = array();
		foreach ($colect_data as $key => $value) {
			$status[$key] = date('Y-m-d',strtotime($value->act_task_times_due))."T".date('H:i:s',strtotime($value->act_task_times_due));
			$title[$key] = $value->aat_type_name.' - '.$value->cst_name;
			$data[$key] =[
				'id' => $value->act_id, 
				'resourceId' => $value->aat_custom_style_1 , 
				'start' => $status[$key],
				'end' =>'', 
				'title'=> $title[$key],
				'className'=> $value->aat_custom_class_1
			];
		}
		$result = [
			'param'=>true,
			'activities' => $data
		];
		return $result;
	}
	/* Tags:... */
	public function sourceDataTiketCalender(Request $request)
	{
		$user = Auth::user();
		$start_date_arr = explode(" ",$request->start);
		$start_date_str = $start_date_arr[3].'-'.$start_date_arr[1].'-'.$start_date_arr[2];
		$start = date('Y-m-d',strtotime($start_date_str));
		$end_date_arr = explode(" ",$request->end);
		$end_date_str = $end_date_arr[3].'-'.$end_date_arr[1].'-'.$end_date_arr[2];
		$end = date('Y-m-d',strtotime($end_date_str));
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			# code...
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereBetween('act_task_times_due',[$start,$end])
			->where('act_label_category','TICKET')
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_lead_id',$lds_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('STF'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_lead_id',$lds_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('MGR.TCH'))) {
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$act_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif(checkRule(array('STF.TCH'))) {
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$act_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}
		$data = array();
		foreach ($colect_data as $key => $value) {
			$status[$key] = date('Y-m-d',strtotime($value->act_task_times_due))."T".date('H:i:s',strtotime($value->act_task_times_due));
			$title[$key] = $value->aat_type_name.' - '.$value->cst_name;
			$data[$key] =[
				'id' => $value->act_id, 
				'resourceId' => $value->aat_custom_style_1 , 
				'start' => $status[$key],
				'end' =>'', 
				'title'=> $title[$key],
				'className'=> $value->aat_custom_class_1
			];
		}
		$result = [
			'param'=>true,
			'activities' => $data
		];
		return $result;
	}
	public function sourceDataActivityCalenderCst(Request $request)
	{
		$user = Auth::user();
		$start_date_arr = explode(" ",$request->start);
		$start_date_str = $start_date_arr[3].'-'.$start_date_arr[1].'-'.$start_date_arr[2];
		$start = date('Y-m-d',strtotime($start_date_str));
		$end_date_arr = explode(" ",$request->end);
		$end_date_str = $end_date_arr[3].'-'.$end_date_arr[1].'-'.$end_date_arr[2];
		$end = date('Y-m-d',strtotime($end_date_str));
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			# code...
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->where('act_cst',$request->cst_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		} elseif(checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_lead_id',$lds_id)
			->where('act_cst',$request->cst_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif (checkRule(array('MGR.TCH'))) {
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$act_id)
			->where('act_cst',$request->cst_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}elseif (checkRule(array('STF','STF.TCH'))) {
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$act_id)
			->where('act_cst',$request->cst_id)
			->whereBetween('act_task_times_due',[$start,$end])
			->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
			'users.name as assign','aat_type_name','cst_name')
			->get();
		}
		$data = array();
		foreach ($colect_data as $key => $value) {
			$status[$key] = date('Y-m-d',strtotime($value->act_task_times_due))."T".date('H:i:s',strtotime($value->act_task_times_due));
			$title[$key] = $value->aat_type_name.' - '.$value->cst_name;
			$data[$key] =[
				'id' => $value->act_id, 
				'resourceId' => $value->aat_custom_style_1 , 
				'start' => $status[$key],
				'end' =>'', 
				'title'=> $title[$key],
				'className'=> $value->aat_custom_class_1
			];
		}
		$result = [
			'param'=>true,
			'activities' => $data
		];
		return $result;
	}
	public function sourceDataActivityCalenderUsr(Request $request)
	{
		$user_id = $request->usr_id;
		$start_date_arr = explode(" ",$request->start);
		$start_date_str = $start_date_arr[3].'-'.$start_date_arr[1].'-'.$start_date_arr[2];
		$start = date('Y-m-d',strtotime($start_date_str));
		$end_date_arr = explode(" ",$request->end);
		$end_date_str = $end_date_arr[3].'-'.$end_date_arr[1].'-'.$end_date_arr[2];
		$end = date('Y-m-d',strtotime($end_date_str));
		$act_access = Act_activity_access::where('acs_user_id',$user_id)->select('acs_act_id')->get();
		$act_id = array();
		foreach ($act_access as $key => $value) {
			$act_id[$key] = $value->acs_act_id;
		}
		$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
		->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
		->leftjoin('users','act_activities.act_user_assigned','=','users.id')
		->whereIn('act_id',$act_id)
		->whereBetween('act_task_times_due',[$start,$end])
		->select('act_id','cst_name','aat_type_button','aat_custom_class_1','act_activities.act_task_times_due','act_todo_result','aat_custom_style_1','aat_type_code',
		'users.name as assign','aat_type_name','cst_name')
		->get();
		$data = array();
		foreach ($colect_data as $key => $value) {
			$status[$key] = date('Y-m-d',strtotime($value->act_task_times_due))."T".date('H:i:s',strtotime($value->act_task_times_due));
			$title[$key] = $value->aat_type_name.' - '.$value->cst_name;
			$data[$key] =[
				'id' => $value->act_id, 
				'resourceId' => $value->aat_custom_style_1 , 
				'start' => $status[$key],
				'end' =>'', 
				'title'=> $title[$key],
				'className'=> $value->aat_custom_class_1
			];
		}
		$result = [
			'param'=>true,
			'activities' => $data
		];
		return $result;
	}
	/* Tags:... */
	public function sourceDataActivityDetailCalender(Request $request)
	{
		$user = Auth::user();
		$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
		->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
		->leftjoin('users','act_activities.act_user_assigned','=','users.id')
		->where('act_id',$request->id)
		->select('act_id','cst_name','act_activities.act_task_times_due','act_todo_result','act_todo_describe','users.name as assign','aat_type_name','act_run_status','act_user_teams','act_user_customer')
		->first();

		if ($colect_data->act_user_teams == "" || $colect_data->act_user_teams == null) {
			$teams = '-';
		}else {
			$usr_ids = explode(',',$colect_data->act_user_teams);
			$user_team = User::whereIn('id',$usr_ids)->get();
			$teams_arr = array();
			foreach ($user_team as $key => $value) {
				$teams_arr[$key] = $value->name;
			}
			$teams = implode(', ', $teams_arr);
		}
		if ($colect_data->act_user_customer == "" || $colect_data->act_user_customer == null) {
			$personal = '-';
		}else {
			$usr_cst_ids = explode(',',$colect_data->act_user_customer);
			$user_personal = Cst_personal::whereIn('cnt_id',$usr_cst_ids)->get();
			$personal_arr = array();
			foreach ($user_personal as $key => $value) {
				$personal_arr[$key] = $value->cnt_fullname;
			}
			$personal = implode(', ', $personal_arr);
		}
		$result = [
			'param'=>true,
			'data_act_name' => $colect_data->cst_name,
			'data_act_type' => $colect_data->aat_type_name,
			'data_act_due_date' => date('D, d/M Y, h:i a',strtotime($colect_data->act_task_times_due)),
			'data_act_assign' => $colect_data->assign,
			'data_act_status' => $colect_data->act_run_status,
			'data_act_describe' => $colect_data->act_todo_describe,
			'data_act_result' => $colect_data->act_todo_result,
			'data_act_team' => $teams,
			'data_act_personal' => $personal
		];
		return $result;
	}
	/* Tags:... */
	public function storeActivitiesNew(Request $request)
	{
		$user = Auth::user();
		$today =Carbon::now()->locale('id-ID');
		$id = genIdActivity();
		$act_task_times = date('Y-m-d h:i:s',strtotime($request->start_date));
		$act_task_times_due = date('Y-m-d h:i:s', strtotime($request->due_date));
		if ($request->todo_status == "done") {
			$act_run_status = "finished";
		}else if($request->todo_status == "running"){
			$act_run_status = "running";
		}else {
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		if (isset($request->pic_user)) {
			# code...
			$pic_user = implode(',',$request->pic_user);
		} else {
			# code...
			$pic_user = null;
		}
		$lead = Prs_lead::where('lds_id',$request->lead_project)->first();
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
			foreach ($request->assignment_user_team as $key => $value) {
				$data_rule = [
					'acs_act_id' => $id,
					'acs_user_id' => $value,
					'acs_rules' =>'member',
				];
				Act_activity_access::insert($data_rule);
			}
		}else{
			$act_user_teams = null;
		}
		
		$data = [
			"act_id" => $id,
			"act_lead_id" => $request->lead_project,
			"act_lead_status" => $lead->lds_status,
			"act_cst" => $request->customer,
			"act_todo_type_id" => $request->action_todo,
			"act_run_status" => $act_run_status,
			'act_todo_priority' => null,
			'act_user_customer' => $pic_user,
			'act_todo_describe' => $request->activity_describe,
			'act_todo_result' =>  $request->activity_result,
			'act_task_times' => null,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::insert($data);
		$data_rule_a = [
			'acs_act_id' => $id,
			'acs_user_id' => $user->id,
			'acs_rules' =>'creator',
		];
		$actionStoreRule = Act_activity_access::insert($data_rule_a);
		$data_rule_b = [
			'acs_act_id' => $id,
			'acs_user_id' => $request->assignment_user,
			'acs_rules' =>'assignee',
		];
		$actionStoreRule = Act_activity_access::insert($data_rule_b);
		$result = [
			'param'=>true,
			'idactivity' => $id 
		];
		return $result;
	}
	public function storeTicketsNew(Request $request)
	{
		$user = Auth::user();
		$today =Carbon::now()->locale('id-ID');
		$id = genIdActivity();
		$act_task_times = date('Y-m-d h:i:s',strtotime($request->start_date));
		$act_task_times_due = date('Y-m-d h:i:s', strtotime($request->due_date));
		if ($request->todo_status == "done") {
			$act_run_status = "finished";
		}else if($request->todo_status == "running"){
			$act_run_status = "running";
		}else {
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		if (isset($request->pic_user)) {
			# code...
			$pic_user = implode(',',$request->pic_user);
		} else {
			# code...
			$pic_user = null;
		}
		$lead = Prs_lead::where('lds_id',$request->lead_project)->first();
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
			foreach ($request->assignment_user_team as $key => $value) {
				$data_rule = [
					'acs_act_id' => $id,
					'acs_user_id' => $value,
					'acs_rules' =>'member',
				];
				Act_activity_access::insert($data_rule);
			}
		}else{
			$act_user_teams = null;
		}
		
		$data = [
			'act_id' => $id,
			'act_lead_id' => $request->lead_project,
			'act_lead_status' => $lead->lds_status,
			'act_cst' => $request->customer,
			'act_todo_type_id' => $request->action_todo,
			'act_label_category' => 'TICKET',
			'act_run_status' => $act_run_status,
			'act_todo_priority' => null,
			'act_user_customer' => $pic_user,
			'act_todo_describe' => $request->activity_describe,
			'act_todo_result' =>  $request->activity_result,
			'act_task_times' => null,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::insert($data);
		$data_rule_a = [
			'acs_act_id' => $id,
			'acs_user_id' => $user->id,
			'acs_rules' =>'creator',
		];
		$actionStoreRule = Act_activity_access::insert($data_rule_a);
		$data_rule_b = [
			'acs_act_id' => $id,
			'acs_user_id' => $request->assignment_user,
			'acs_rules' =>'assignee',
		];
		$actionStoreRule = Act_activity_access::insert($data_rule_b);
		$result = [
			'param'=>true,
			'idactivity' => $id 
		];
		return $result;
	}
	/* Tags:... */
	public function storeCloseActivities(Request $request)
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
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		$pic_user = implode(',',$request->pic_user);
		$lead = Prs_lead::where('lds_id',$request->lead_project)->first();
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
		}else{
			$act_user_teams = null;
		}
		$id = genIdActivity();
		$data = [
			"act_id" => $id,
			"act_lead_id" => $request->lead_id,
			"act_lead_status" => $request->lead_status_id,
			"act_cst" => $request->customer,
			"act_todo_type_id" => $request->action_todo,
			"act_run_status" => $act_run_status,
			'act_todo_priority' => null,
			'act_user_customer' => $pic_user,
			'act_todo_describe' => $request->activity_describe,
			'act_todo_result' =>  $request->activity_result,
			'act_task_times' => null,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::insert($data);
		$actionUpdate = Act_activity::where('act_id',$request->act_id)->update(['act_run_status'=>'finished']);
		$result = [
			'param'=>true,
			'idactivity' => $id 
		];
		return $result;
	}
	/* Tags:... */
	public function storeTicketActivities(Request $request)
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
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		$pic_user = implode(',',$request->pic_user);
		$lead = Prs_lead::where('lds_id',$request->lead_project)->first();
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
		}else{
			$act_user_teams = null;
		}
		$id = genIdActivity();
		$data = [
			"act_id" => $id,
			"act_lead_id" => $request->lead_id,
			"act_lead_status" => $request->lead_status_id,
			"act_cst" => $request->customer,
			"act_todo_type_id" => $request->action_todo,
			"act_run_status" => $act_run_status,
			'act_todo_priority' => null,
			'act_user_customer' => $pic_user,
			'act_todo_describe' => $request->activity_describe,
			'act_todo_result' =>  $request->activity_result,
			'act_task_times' => null,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::insert($data);
		// $actionUpdate = Act_activity::where('act_id',$request->act_id)->update(['act_run_status'=>'finished']);
		$result = [
			'param'=>true,
			'idactivity' => $id 
		];
		return $result;
	}
  /* Tags:... */
  public function storeActivitiesLead(Request $request)
  {
    $user = Auth::user();
		$id = genIdActivity(); 
		$today =Carbon::now()->locale('id-ID');
		$act_task_times = date('Y-m-d h:i:s',strtotime($request->start_date));
		$act_task_times_due = date('Y-m-d h:i:s', strtotime($request->due_date));
		if ($request->todo_status == "done") {
			$act_run_status = "finished";
		}else if($request->todo_status == "running"){
			$act_run_status = "running";
		}else {
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		$pic_user = implode(',',$request->pic_user);
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
			foreach ($request->assignment_user_team as $key => $value) {
				$data_rule = [
					'acs_act_id' => $id,
					'acs_user_id' => $value,
					'acs_rules' =>'member',
				];
				Act_activity_access::insert($data_rule);
			}
		}else{
			$act_user_teams = null;
		}
		$data = [
			'act_id' => $id,
			'act_lead_id' => $request->lead_id,
			'act_lead_status' => $request->lead_status_id,
			'act_cst' => $request->customer, 
			'act_todo_type_id' => $request->action_todo,
			'act_run_status' => $act_run_status,
			'act_todo_priority' => null,
			'act_user_customer' => $pic_user,
			'act_todo_describe' => $request->activity_describe,
			'act_todo_result' => $request->activity_result,
			'act_task_times' => null,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::insert($data);
		$data_rule_a = [
			'acs_act_id' => $id,
			'acs_user_id' => $user->id,
			'acs_rules' =>'creator',
		];
		$actionStoreRule = Act_activity_access::insert($data_rule_a);
		$data_rule_b = [
			'acs_act_id' => $id,
			'acs_user_id' => $request->assignment_user,
			'acs_rules' =>'assignee',
		];
		$actionStoreRule = Act_activity_access::insert($data_rule_b);
    $result = [
      'param'=>true,
    ];
    return $result;
  }
	/* Tags:... */
	public function updateActivitiesLead(Request $request)
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
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		$pic_user = implode(',',$request->pic_user);
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
		}else{
			$act_user_teams = null;
		}
		if ($request->activity_describe == null || $request->activity_describe == "") {
			$act_describe = $request->alt_describe; 
		}else{
			$act_describe = $request->activity_describe; 
		}
		if ($request->activity_result == null || $request->activity_result == "") {
			$act_result = $request->alt_result;
		}else{
			$act_result = $request->activity_result;
		}
		$data = [
			'act_lead_id' => $request->lead_id,
			'act_todo_type_id' => $request->action_todo,
			'act_run_status' => $act_run_status,
			'act_lead_status' => $request->lead_status_id, 
			'act_todo_priority' => null,
			'act_user_customer' => $pic_user,
			'act_todo_describe' =>  $request->alt_describe,
			'act_todo_result' =>$request->alt_result,
			'act_task_times' => null,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::where('act_id',$request->act_id)->update($data);
    $result = [
      'param'=>true,
    ];
    return $result;
	}
	/* Tags:... */
	public function updateActivitiesScedule(Request $request)
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
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		$pic_user = implode(',',$request->pic_user);
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
		}else{
			$act_user_teams = null;
		}
		if ($request->activity_describe == null || $request->activity_describe == "") {
			$act_describe = $request->alt_describe; 
		}else{
			$act_describe = $request->activity_describe; 
		}
		if ($request->activity_result == null || $request->activity_result == "") {
			$act_result = $request->alt_result;
		}else{
			$act_result = $request->activity_result;
		}
		$data = [
			'act_todo_type_id' => $request->action_todo,
			'act_run_status' => $act_run_status, 
			'act_todo_priority' => null,
			'act_user_customer' => $pic_user,
			'act_task_times' => null,
			'act_task_times_due' => $act_task_times_due,
			'act_user_assigned' => $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'created_by' => $user->id
		];
		$actionStore = Act_activity::where('act_id',$request->act_id)->update($data);
    $result = [
      'param'=>true,
    ];
    return $result;
	}
	/* Tags:... */
	public function autoSaveUpdateActivitiesLead(Request $request)
	{
		$act_id = $request->act_id;
		$user = Auth::user();
		$today =Carbon::now()->locale('id-ID');
		$act_task_times = date('Y-m-d h:i:s',strtotime($request->start_date));
		$act_task_times_due = date('Y-m-d h:i:s', strtotime($request->due_date));
		if ($request->todo_status == "done") {
			$act_run_status = "finished";
		}else if($request->todo_status == "running"){
			$act_run_status = "running";
		}else {
			if ($act_task_times_due < $today) {
				$act_run_status = "finished";
			}else {
				$act_run_status = "beready";
			}
		}
		if (isset($request->assignment_user_team)) {
			$act_user_teams = implode(',',$request->assignment_user_team);
		}else{
			$act_user_teams = null;
		}
		$pic_user = implode(',',$request->pic_user);
		$data = [
			'act_task_times_due' => $act_task_times_due,
			'act_lead_status' => $request->lead_status_id,
			'act_todo_type_id' => $request->action_todo,
			'act_run_status' => $act_run_status,
			'act_todo_describe' => $request->note_describe,
			'act_todo_result' => $request->note_result,
			'act_user_assigned'=> $request->assignment_user,
			'act_user_teams' => $act_user_teams,
			'act_user_customer' => $pic_user,
			'updated_by' => $user->id
		];
		$actionStore = Act_activity::where('act_id',$act_id)->update($data);
    $result = [
      'param'=>true,
    ];
    return $result;
	}
	/* Tags:... */
	public function updateAStatusActivitiesLead(Request $request)
	{
		$updateStatusActivity = Act_activity::where('act_id',$request->act_id)->update(['act_run_status'=>$request->status]);
		$result = [
			'param'=>true,
			'status' => $request->status
		];
		return $result;
	}
	/* Tags:... */
	public function updateCloseActivities(Request $request)
	{
		$actionUpdate = Act_activity::where('act_id',$request->act_id)->update(['act_run_status'=>'finished']);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function deleteActivitiesLead(Request $request)
	{
		$actionDeleteActLead = Act_activity::where('act_id',$request->id)->delete();
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags: table activity on lead */
	public function sourceLeadActivities(Request $request)
	{
		$id_tmp = $request->lead_id;
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF'))) {
			# code...
			$activitytLeadData = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->where('act_lead_id',$id_tmp)
			->select('act_id','act_lead_id','act_lead_status','act_todo_type_id','act_run_status','act_todo_describe',
			'act_todo_result','act_task_times','act_task_times_due','act_activities.created_at as activity_created','aat_type_name','aat_type_button','aat_icon','act_run_status')
			->orderBy('act_task_times_due', 'desc')
			->get();
			#	
		}elseif (checkRule(array('MGR.TCH'))) {
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$activitytLeadData = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->where('act_lead_id',$id_tmp)
			->whereIn('act_id',$act_id)
			->select('act_id','act_lead_id','act_lead_status','act_todo_type_id','act_run_status','act_todo_describe',
			'act_todo_result','act_task_times','act_task_times_due','act_activities.created_at as activity_created','aat_type_name','aat_type_button','aat_icon','act_run_status')
			->orderBy('act_task_times_due', 'desc')
			->get();
		}elseif(checkRule(array('STF.TCH'))){
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$activitytLeadData = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->where('act_lead_id',$id_tmp)
			->whereIn('act_id',$act_id)
			->select('act_id','act_lead_id','act_lead_status','act_todo_type_id','act_run_status','act_todo_describe',
			'act_todo_result','act_task_times','act_task_times_due','act_activities.created_at as activity_created','aat_type_name','aat_type_button','aat_icon','act_run_status')
			->orderBy('act_task_times_due', 'desc')
			->get();
		}
		$actRunningLeadData = $activitytLeadData->where('act_run_status','running');
		$actBereadyLeadData = $activitytLeadData->where('act_run_status','beready');
		$actFinishLeadData = $activitytLeadData->where('act_run_status','finished');
		$tableDataSection1='';
			$tableDataSection1.='<tr class="bg-teal-lt"><td colspan="6">
			<button type="button" id="btn-area-run-act-min" class="badge badge-outline text-green" style="margin-right:1px;display:revert;" onclick="actionRunningListMin()">
				<i class="ri-subtract-line" style="vertical-align: middle;"></i></button>
			<button type="button" id="btn-area-run-act-plus" class="badge badge-outline text-green" style="margin-right:1px;display:none;" onclick="actionRunningListPlus()">
				<i class="ri-add-line" style="vertical-align: middle;"></i></button>
			<strong>RUNNING</strong></td></tr>';
			foreach ($actRunningLeadData as $key => $value) {
				$val_duedate[$key] = date('D, d/M Y, h:i a', strtotime($value->act_task_times_due));
				$val_createdate[$key] = date('D, d/M Y', strtotime($value->activity_created));
				$tableDataSection1.='<tr class="act-section-1">
					<td><div class="dropdown">
						<button href="#" class="badge bg-blue-lt" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-menu-2-line"></i></button>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#init-page-activities" onclick="actionUpdateActivity('.$value->act_id.')"><i class="ri-edit-2-line" style="margin-right:6px;"></i>Detail Activity</a>
							<a class="dropdown-item" href="#init-page-activities" onclick="actionRemoveAct('.$value->act_id.')"><i class="ri-delete-bin-2-line" style="margin-right:6px;"></i>Remove</a>
						</div>
					</div></td>
					<td>'.$val_duedate[$key].'</td>
					<td class="text-muted">'.$value->aat_type_button.'</td>
					<td class="text-muted">'.$value->act_todo_result.'</td>
					<td class="text-muted">'.$val_createdate[$key].'</td>
					<td>
					<button class="badge bg-green" onclick="actionChangeStatusAct('.$value->act_id.')">Running</button>
					</td></tr>';
			}
			#
			$tableDataSection2='';
			$tableDataSection2.='<tr class="bg-dark-lt"><td colspan="6">
			<button type="button" id="btn-area-br-act-min" class="badge badge-outline text-dark" style="margin-right:1px;display:revert;" onclick="actionBereadyListMin()">
				<i class="ri-subtract-line" style="vertical-align: middle;"></i></button>
			<button type="button" id="btn-area-br-act-plus" class="badge badge-outline text-dark" style="margin-right:1px;display:none;" onclick="actionBereadyListPlus()">
				<i class="ri-add-line" style="vertical-align: middle;"></i></button>
			<strong>BEREADY</strong></td></tr>';
			foreach ($actBereadyLeadData as $key => $value) {
				$val_duedate[$key] = date('D, d/M Y, h:i a', strtotime($value->act_task_times_due));
				$val_createdate[$key] = date('D, d/M Y', strtotime($value->activity_created));
				$tableDataSection2.='<tr class="act-section-2">
					<td><div class="dropdown">
						<button href="#" class="badge bg-blue-lt" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-menu-2-line"></i></button>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#init-page-activities" onclick="actionUpdateActivity('.$value->act_id.')"><i class="ri-edit-2-line" style="margin-right:6px;"></i>Detail Activity</a>
							<a class="dropdown-item" href="#init-page-activities" onclick="actionRemoveAct('.$value->act_id.')"><i class="ri-delete-bin-2-line" style="margin-right:6px;"></i>Remove</a>
						</div>
					</div></td>
					<td>'.$val_duedate[$key].'</td>
					<td class="text-muted">'.$value->aat_type_button.'</td>
					<td class="text-muted">'.$value->act_todo_result.'</td>
					<td class="text-muted">'.$val_createdate[$key].'</td>
					<td><button class="badge bg-dark-lt" onclick="actionChangeStatusAct('.$value->act_id.')">Beready</button></td></tr>';
			}
			#
			$tableDataSection3='';
			$tableDataSection3.='<tr class="bg-muted-lt"><td colspan="6">
			<button type="button" id="btn-area-finish-act-min" class="badge badge-outline text-muted" style="margin-right:1px;display:none ;" onclick="actionFinishListMin()">
				<i class="ri-subtract-line" style="vertical-align: middle;"></i></button>
			<button type="button" id="btn-area-finish-act-plus" class="badge badge-outline text-muted" style="margin-right:1px;display:revert;" onclick="actionFinishListPlus()">
				<i class="ri-add-line" style="vertical-align: middle;"></i></button>
			<strong>FINISH</strong></td></tr>';
			foreach ($actFinishLeadData as $key => $value) {
				$val_duedate[$key] = date('D, d/M Y, h:i a', strtotime($value->act_task_times_due));
				$val_createdate[$key] = date('D, d/M Y', strtotime($value->activity_created));
				$tableDataSection3.='<tr class="act-section-3" style="display:none;">
					<td><div class="dropdown">
						<button href="#" class="badge bg-blue-lt" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-menu-2-line"></i></button>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#init-page-activities" onclick="actionUpdateActivity('.$value->act_id.')"><i class="ri-edit-2-line" style="margin-right:6px;"></i>Detail Activity</a>
							<a class="dropdown-item" href="#init-page-activities" onclick="actionRemoveAct('.$value->act_id.')"><i class="ri-delete-bin-2-line" style="margin-right:6px;"></i>Remove</a>
						</div>
					</div></td>
					<td>'.$val_duedate[$key].'</td>
					<td class="text-muted">'.$value->aat_type_button.'</td>
					<td class="text-muted">'.$value->act_todo_result.'</td>
					<td class="text-muted">'.$val_createdate[$key].'</td>
					<td><button class="badge bg-muted-lt" onclick="actionChangeStatusAct('.$value->act_id.')">Finished</button></td></tr>';
			}
			#
			$result = [
				'param'=>true,
				'data_activity_section_i' =>$tableDataSection1,
				'data_activity_section_ii' =>$tableDataSection2,
				'data_activity_section_iii' =>$tableDataSection3
			];
			return $result;
		#
	}
	public function sourceAllLeadActivities(Request $request)
	{
		// $id_tmp = $request->lead_id;
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			# code...
			$activitytLeadData = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
			->leftjoin('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->select('act_id','act_run_status','act_task_times','act_task_times_due','aat_type_name','aat_type_button','aat_icon','act_run_status','lds_title','cst_name','name')
			->orderBy('act_task_times_due', 'desc')
			->get();
			#
		}elseif(checkRule(array('MGR','MGR.TCH'))){
			$ids_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$ids_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$uniq_act_id = array_unique($act_id);
			$activitytLeadData = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
			->leftjoin('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$uniq_act_id)
			->select('act_id','act_run_status','act_task_times','act_task_times_due','aat_type_name','aat_type_button','aat_icon','act_run_status','lds_title','cst_name','name')
			->orderBy('act_task_times_due', 'desc')
			->get();
		}elseif(checkRule(array('STF.TCH','STF'))){
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			$uniq_act_id = array_unique($act_id);
			$activitytLeadData = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->leftjoin('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
			->leftjoin('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('act_id',$uniq_act_id)
			->select('act_id','act_run_status','act_task_times','act_task_times_due','aat_type_name','aat_type_button','aat_icon','act_run_status','lds_title','cst_name','name')
			->orderBy('act_task_times_due', 'desc')
			->get();
		}
		$actRunningLeadData = $activitytLeadData->where('act_run_status','running');
		$actBereadyLeadData = $activitytLeadData->where('act_run_status','beready');
		$actFinishLeadData = $activitytLeadData->where('act_run_status','finished');
		$tableDataSection1='';
			$tableDataSection1.='<tr class="bg-teal-lt"><td colspan="7">
			<button type="button" id="btn-area-run-act-min" class="badge badge-outline text-green" style="margin-right:1px;display:revert;" onclick="actionRunningListMin()">
				<i class="ri-subtract-line" style="vertical-align: middle;"></i></button>
			<button type="button" id="btn-area-run-act-plus" class="badge badge-outline text-green" style="margin-right:1px;display:none;" onclick="actionRunningListPlus()">
				<i class="ri-add-line" style="vertical-align: middle;"></i></button>
			<strong>RUNNING</strong></td></tr>';
			foreach ($actRunningLeadData as $key => $value) {
				$val_duedate[$key] = date('D, d/m/y, h:i a', strtotime($value->act_task_times_due));
				$val_createdate[$key] = date('d/m/y', strtotime($value->activity_created));
				$tableDataSection1.='<tr class="act-section-1">
					<td><div class="dropdown">
						<button href="#" class="badge bg-blue-lt" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-menu-2-line"></i></button>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#init-page-todo" onclick="actionUpdateActivity('.$value->act_id.')"><i class="ri-edit-2-line" style="margin-right:6px;"></i>Detail Activity</a>';
							if (checkRule(array('ADM','AGM','MGR.PAS','MGR','MGR.TCH'))) {
								$tableDataSection1.='<a class="dropdown-item" href="#init-page-todo" onclick="actionRemoveAct('.$value->act_id.')"><i class="ri-delete-bin-2-line" style="margin-right:6px;"></i>Remove</a>';
							}
							$tableDataSection1.='
						</div>
					</div></td>
					<td>'.$val_duedate[$key].'</td>
					<td>'.$value->cst_name.'</td>
					<td class="text-muted">'.$value->lds_title.'</td>
					<td class="text-muted">'.$value->aat_type_button.'</td>
					<td class="text-muted">'.$value->name.'</td>
					<td>
					<button class="badge bg-green" onclick="actionChangeStatusAct('.$value->act_id.')">Running</button>
					</td></tr>';
			}
			#
			$tableDataSection2='';
			$tableDataSection2.='<tr class="bg-dark-lt"><td colspan="7">
			<button type="button" id="btn-area-br-act-min" class="badge badge-outline text-dark" style="margin-right:1px;display:revert;" onclick="actionBereadyListMin()">
				<i class="ri-subtract-line" style="vertical-align: middle;"></i></button>
			<button type="button" id="btn-area-br-act-plus" class="badge badge-outline text-dark" style="margin-right:1px;display:none;" onclick="actionBereadyListPlus()">
				<i class="ri-add-line" style="vertical-align: middle;"></i></button>
			<strong>BEREADY</strong></td></tr>';
			foreach ($actBereadyLeadData as $key => $value) {
				$val_duedate[$key] = date('D,d/m/y, h:i a', strtotime($value->act_task_times_due));
				$val_createdate[$key] = date('D, d/m/y', strtotime($value->activity_created));
				$tableDataSection2.='<tr class="act-section-2">
					<td><div class="dropdown">
						<button href="#" class="badge bg-blue-lt" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-menu-2-line"></i></button>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#init-page-todo" onclick="actionUpdateActivity('.$value->act_id.')"><i class="ri-edit-2-line" style="margin-right:6px;"></i>Detail Activity</a>';
							if (checkRule(array('ADM','AGM','MGR.PAS','MGR','MGR.TCH'))) {
								$tableDataSection2.='<a class="dropdown-item" href="#init-page-todo" onclick="actionRemoveAct('.$value->act_id.')"><i class="ri-delete-bin-2-line" style="margin-right:6px;"></i>Remove</a>';
							}
							$tableDataSection2.='
						</div>
					</div></td>
					<td>'.$val_duedate[$key].'</td>
					<td>'.$value->cst_name.'</td>
					<td class="text-muted">'.$value->lds_title.'</td>
					<td class="text-muted">'.$value->aat_type_button.'</td>
					<td class="text-muted">'.$value->name.'</td>
					<td><button class="badge bg-dark-lt" onclick="actionChangeStatusAct('.$value->act_id.')">Beready</button></td></tr>';
			}
			#
			$tableDataSection3='';
			$tableDataSection3.='<tr class="bg-muted-lt"><td colspan="6">
			<button type="button" id="btn-area-finish-act-min" class="badge badge-outline text-muted" style="margin-right:1px;display:none ;" onclick="actionFinishListMin()">
				<i class="ri-subtract-line" style="vertical-align: middle;"></i></button>
			<button type="button" id="btn-area-finish-act-plus" class="badge badge-outline text-muted" style="margin-right:1px;display:revert;" onclick="actionFinishListPlus()">
				<i class="ri-add-line" style="vertical-align: middle;"></i></button>
			<strong>FINISH</strong></td></tr>';
			foreach ($actFinishLeadData as $key => $value) {
				$val_duedate[$key] = date('D, d/M Y, h:i a', strtotime($value->act_task_times_due));
				$val_createdate[$key] = date('D, d/M Y', strtotime($value->activity_created));
				$tableDataSection3.='<tr class="act-section-3" style="display:none;">
					<td><div class="dropdown">
						<button href="#" class="badge bg-blue-lt" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-menu-2-line"></i></button>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#init-page-todo" onclick="actionUpdateActivity('.$value->act_id.')"><i class="ri-edit-2-line" style="margin-right:6px;"></i>Detail Activity</a>';
							if (checkRule(array('ADM','AGM','MGR.PAS','MGR','MGR.TCH'))) {
								$tableDataSection3.='<a class="dropdown-item" href="#init-page-todo" onclick="actionRemoveAct('.$value->act_id.')"><i class="ri-delete-bin-2-line" style="margin-right:6px;"></i>Remove</a>';
							}
							$tableDataSection3.='
						</div>
					</div></td>
					<td>'.$value->cst_name.'</td>
					<td class="text-muted">'.$value->lds_title.'</td>
					<td class="text-muted">'.$value->aat_type_button.'</td>
					<td class="text-muted">'.$value->name.'</td>
					<td><button class="badge bg-muted-lt" onclick="actionChangeStatusAct('.$value->act_id.')">Finished</button></td></tr>';
			}
			#
			$result = [
				'param'=>true,
				'data_activity_section_i' =>$tableDataSection1,
				'data_activity_section_ii' =>$tableDataSection2,
				'data_activity_section_iii' =>$tableDataSection3
			];
			return $result;
		#
	}
	/* Tags: ajax resource get info activity lead */
	public function sourceActivityInfo(Request $request)
	{
		$activity_data = Act_activity::where('act_id',$request->id)
		->select('act_todo_describe','act_todo_result')
		->first();
		return $activity_data->act_todo_result;
	}
	/* Tags:... */
	public function sourceActivityDetail(Request $request)
	{
		$activity_data = Act_activity::where('act_id',$request->id)
		->select('act_user_assigned','act_run_status','act_user_teams','act_todo_describe','act_todo_result','act_task_times_due','act_todo_result','act_todo_type_id','act_user_customer')
		->first();
		$team_ids = explode(',',$activity_data->act_user_teams);
		$assign = User::where('id',$activity_data->act_user_assigned)->select('id','name')->first();
		if ($assign != null) {
			$ass_id = $assign->id;
			$ass_name = $assign->name;
		}else{
			$ass_id = null;
			$ass_name = null;
		}
		$team = User::whereIn('id',$team_ids)->select('id','name')->get();
		$activity_type = Act_activity_type::where('aat_id',$activity_data->act_todo_type_id)->select('aat_id','aat_type_name')->first();
		$param_team = false;
		if ($team->count()>0) {
			$param_team = true;
		}
		$team_members = array();
		foreach ($team as $key => $list) {
			$team_members[$key] = $list->id;
		}
		$user_customer_ids = explode(',',$activity_data->act_user_customer);
		$data_user_customer = Cst_personal::whereIn('cnt_id',$user_customer_ids)->select('cnt_id as id','cnt_fullname as name')->get();
		$user_customer_id = array();
		$user_customer_name = array();
		foreach ($data_user_customer as $key => $value) {
			$user_customer_id[$key] = $value->id;
			$user_customer_name[$key] = $value->name;
		}
		$result = [
			'act_id' =>$request->id,
			'date_due' => date('Y-m-d h:i a',strtotime($activity_data->act_task_times_due)),
			'activity'=>[ $activity_type->aat_id,$activity_type->aat_type_name],
			'assign' => [$ass_id,$ass_name],
			'summary' => $activity_data->act_todo_result,
			'team' => $team_members,
			'param_team' => $param_team,
			'user_cst_id' => $user_customer_id,
			'user_cst_name' => $user_customer_name,
			'todo_describe' => $activity_data->act_todo_describe,
			'todo_result' => $activity_data->act_todo_result,
			'param'=>true,
			'actstatus' => $activity_data->act_run_status
		];
		return $result;
	}
	/* Tags:update activity info */
	public function updateActivityInfo(Request $request)
	{
		$action_update_activity_info = Act_activity::where('act_id',$request->id)->update(['act_todo_result'=>$request->data]);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function actionUpdateActivityInfo(Request $request)
	{
		$action_update_activity_info = Act_activity::where('act_id',$request->activity_id)->update(['act_todo_result'=>$request->activity_summary]);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function updateDescribeActivity(Request $request)
	{
		$updateActivity = Act_activity::where('act_id',$request->id)->update(['act_todo_describe'=>$request->todo_describe]);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function updateResultActivity(Request $request)
	{

		$updateActivity = Act_activity::where('act_id',$request->id)->update(['act_todo_result'=>$request->todo_result]);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function viewTicketActivity(Request $request)
	{
		$user = Auth::user();
		$users = User::get();
		$users_mod[0] = ["all_user"=>"Filter User"];
		$idx_user = 1;
		foreach ($users as $key => $value) {
			$users_mod[$idx_user] = [
				$value->id => $value->name,
			];
			$idx_user++;
		}
		$status_activity = Act_activity::get();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			# code...
			$all_activities = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_ids = array_unique($lds_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->whereIn('act_activities.act_lead_id',$lds_ids)
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('STF'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_ids = array_unique($lds_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->whereIn('act_activities.act_lead_id',$lds_ids)
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('MGR.TCH'))) {
			$user = Auth::user();
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_idr = array();
			foreach ($act_access as $key => $value) {
				$act_idr[$key] = $value->acs_act_id;
			}
			$act_ids = array_unique($act_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->whereIn('act_activities.act_id',$act_ids)
			->select('act_id','aat_type_code')
			->get();
		} elseif(checkRule(array('STF.TCH','STF'))) {
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_idr = array();
			foreach ($act_access as $key => $value) {
				$act_idr[$key] = $value->acs_act_id;
			}
			$act_ids = array_unique($act_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
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
		$user_all = User::join('user_structures','users.id','=','user_structures.usr_user_id')
		->leftjoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->whereIn('level',['STF','STF.TCH','MGR.TCH','MGR.PAS','MGR','AGM','ADM'])
		->select('id','name','uts_team_name','level')
		->get();
		$user_technical = $user_all->whereIn('level',['STF.TCH','MGR.TCH']);
		$customer_all = Cst_customer::select('cst_id','cst_name')->get();
		return view('contents.page_activity.ticket',compact(
			'activity_type','cnt_todo','cnt_phone','cnt_email','cnt_visit','cnt_poc','cnt_webinar','cnt_video_call','cnt_total','user_all','user','customer_all','users','users_mod',
			'user_technical',
		));
	}
	/* Tags:... */
	public function viewTicketDetail(Request $request)
	{
		$id_activity = $request->id;
		$user = Auth::user();
		$user_all = User::join('user_structures','users.id','=','user_structures.usr_user_id')
		->leftjoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->whereIn('level',['STF','STF.TCH','MGR.TCH','MGR.PAS','MGR','AGM','ADM'])
		->select('id','name','uts_team_name','level')
		->get();
		$main_activity = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
		->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
		->join('users','act_activities.act_user_assigned','users.id')
		->where('act_id',$id_activity)
		->select('act_cst','lds_id','lds_title','aat_type_name','aat_type_code','aat_custom_class_2','aat_icon','act_run_status','act_todo_describe','act_todo_result','act_task_times',
		'act_task_times_due','act_user_customer','id as userid','name','act_user_teams','act_activities.created_at as act_date_crate',
		'act_activities.created_by as act_user_crate','act_todo_type_id','act_lead_id','lds_status','act_cst')
		->first();
		$activity_type = Act_activity_type::get();
		$customer = Cst_customer::join('cst_institutions','cst_customers.cst_institution','=','cst_institutions.ins_id')
		->where('cst_id',$main_activity->act_cst)
		->select('cst_name','ins_name')
		->first();
		$usr_team_id = array();
		$usr_team_id = explode(',',$main_activity->act_user_teams);
		$user_team = User::whereIn('id',$usr_team_id)
		->select('id','name')
		->get();
		$count_team = $user_team->count();
		$team_ar = array();
		$team_id_ar = array();
		foreach ($user_team as $key => $value) {
			$team_id_ar[$key] = $value->id;
			$team_ar[$key] = $value->name; 
		}
		$team = implode(', ',$team_ar);
		$usr_customer_id = array();
		$usr_customer_id = explode(',',$main_activity->act_user_customer);
		$cst_pic = Cst_personal::whereIn('cnt_id',$usr_customer_id)
		->select('cnt_id','cnt_fullname')
		->get();
		$user_created = User::where('id',$main_activity->act_user_crate)
		->select('id','name')
		->first();
		$date_created = date('D, d/M Y, h:i a',strtotime($main_activity->act_date_crate));
		$date_due = date('D, d/M Y, h:i a',strtotime($main_activity->act_task_times_due));
		$cst_person_id = explode(',',$main_activity->act_user_customer);
		$cst_person = Cst_personal::whereIn('cnt_id',$cst_person_id)->select('cnt_id','cnt_fullname')->get();
		$cst_person_all = Cst_personal::where('cnt_cst_id',$main_activity->act_cst)->select('cnt_id','cnt_fullname')->get();
		// echo $cst_person_all;
		// die();
		return view('contents.page_activity.ticket_detail',compact('id_activity','customer','main_activity','team','date_created','date_due','user_created',
			'cst_person','activity_type','user_all','count_team','user_team','team_id_ar','cst_person_all','cst_person_id','user'
		));
	}
}
