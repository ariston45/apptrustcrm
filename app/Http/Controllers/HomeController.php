<?php

namespace App\Http\Controllers;

use App\Models\Act_activity;
use App\Models\Act_activity_access;
use App\Models\Act_activity_type;
use App\Models\Cst_customer;
use App\Models\Cst_personal;
use App\Models\Opr_opportunity;
use App\Models\Ord_purchase;
use App\Models\Prs_accessrule;
use App\Models\Prs_contact;
use App\Models\Prs_lead;
use App\Models\User;
use Auth;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		return view('layout.app');
	}
	public function homeFunction()
	{
		$user = Auth::user();
		$users = User::get();
		$act_ids = array();
		$prs_ids = array();
		$user_team_ids = checkTeamMgr($user->id);
		$c_all = "";
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$all_activities = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->select('act_id','aat_type_code')
			->get();
			#********************************
			$c_activities = Act_activity::count();
			$c_opportunities = Opr_opportunity::where('opr_close_status',null)->count();
			$c_lead = Prs_lead::where('lds_stage_opr','false')->count();
			$c_ticket = Act_activity::where('act_label_category','TICKET')->count();
			$c_customer = Cst_customer::count();
			$c_purchase = Ord_purchase::count();
			#********************************
		} elseif(checkRule(array('MGR','MGR.TCH'))) {
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
			#********************************
			$act_access = Act_activity_access::whereIn('acs_user_id',$user_team_ids)->get();
			foreach ($act_access as $key => $value) {
				$act_ids[$key] = $value->acs_act_id; 
			}
			$act_ids_uniq = array_unique($act_ids);
			$c_ticket = Act_activity::whereIn('act_id',$act_ids_uniq)->where('act_label_category','TICKET')->count();
			$c_activities = Act_activity::whereIn('act_id',$act_ids_uniq)->count();
			$prs_access = Prs_accessrule::whereIn('slm_user',$user_team_ids )->get();
			foreach ($prs_access as $key => $value) {
				$prs_ids[$key] = $value->slm_lead; 
			}
			$prs_ids_uniq = array_unique($prs_ids);
			$c_opportunities = Opr_opportunity::whereIn('opr_lead_id', $prs_ids_uniq)->where('opr_close_status',null)->count();
			$c_purchase = Opr_opportunity::join('ord_purchases','opr_opportunities.opr_id','=','ord_purchases.pur_oppr_id')
			->whereIn('opr_lead_id', $prs_ids_uniq)->count();
			$c_lead = Prs_lead::whereIn('lds_id', $prs_ids_uniq)->where('lds_stage_opr','false')->count();
			$c_customer = Cst_customer::count();
			#********************************
		} elseif(checkRule(array('STF','STF.TCH'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
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
			#********************************
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->get();
			foreach ($act_access as $key => $value) {
				$act_ids[$key] = $value->acs_act_id; 
			}
			$act_ids_uniq = array_unique($act_ids);
			$c_ticket = Act_activity::whereIn('act_id',$act_ids_uniq)->where('act_label_category','TICKET')->count();
			$c_activities = Act_activity::whereIn('act_id',$act_ids_uniq)->count();
			$prs_access = Prs_accessrule::where('slm_user',$user->id)->get();
			foreach ($prs_access as $key => $value) {
				$prs_ids[$key] = $value->slm_lead; 
			}
			$prs_ids_uniq = array_unique($prs_ids);
			$c_opportunities = Opr_opportunity::whereIn('opr_lead_id', $prs_ids_uniq)->where('opr_close_status',null)->count();
			$c_purchase = Opr_opportunity::join('ord_purchases','opr_opportunities.opr_id','=','ord_purchases.pur_oppr_id')
			->whereIn('opr_lead_id', $prs_ids_uniq)->count();
			$c_lead = Prs_lead::whereIn('lds_id', $prs_ids_uniq)->where('lds_stage_opr','false')->count();
			$c_customer = Cst_customer::count();
			#********************************
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
		$users_mod[0] = ["all_user"=>"Filter User"];
		$idx_user = 1;
		foreach ($users as $key => $value) {
			$users_mod[$idx_user] = [
				$value->id => $value->name,
			];
			$idx_user++;
		}
		$lead_contacts = Cst_personal::leftjoin('cst_customers','cst_customers.cst_id','=','cst_personals.cnt_cst_id')
		->select('cnt_id','cnt_fullname','cst_name')
		->get();
		$user = Auth::user();
		$users = User::join('user_structures','users.id','=','user_structures.usr_user_id')
		->leftJoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
		->select('id','name','level','uts_team_name')
		->whereNotIn('level',['ADM','AGM'])
		->get();
		$customer_all = Cst_customer::select('cst_id','cst_name')->get();
		$activity_type = Act_activity_type::get();
		return view('contents.page_home.home',compact(
			'activity_type','users','user','lead_contacts','customer_all','users_mod',
			'activity_type','cnt_todo','cnt_phone','cnt_email','cnt_visit','cnt_poc','cnt_webinar','cnt_video_call','cnt_total','user_all','user','customer_all','users','users_mod',
			'c_all','c_activities','c_opportunities','c_ticket','c_customer','c_lead','c_purchase'
		));
	}
	public function sourceChartLead(Request $request)
	{
		$id = Auth::user()->id;
		if (checkRule(['ADM','AGM','MGR.PAS'])) {
			$lds_prospect = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->where('lds_status','1')
			->where('lds_stage_opr','false')
			->where('slm_rules','master')
			->count();
			$lds_qualify = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->whereIn('lds_status',['2','3'])
			->where('lds_stage_opr','false')
			->where('slm_rules','master')
			->count();
			$lds_opportunity = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->join('opr_opportunities','opr_lead_id','=','prs_accessrules.slm_lead')
			->where('opr_close_status',null)
			->where('lds_status','3')
			->where('lds_stage_opr','true')
			->where('slm_rules','master')
			->count();
			$lds_lose = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->where('lds_status','0')
			->where('lds_stage_opr','false')
			->where('slm_rules','master')
			->count();
		} elseif(checkRule(['MGR'])) {
			$ids = checkTeamMgr($id);
			$lds_prospect = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->whereIn('slm_user',$ids)
			->where('lds_status','1')
			->where('lds_stage_opr','false')
			->where('slm_rules','master')
			->count();
			$lds_qualify = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->whereIn('slm_user',$ids)
			->whereIn('lds_status',['2','3'])
			->where('lds_stage_opr','false')
			->where('slm_rules','master')
			->count();
			$lds_opportunity = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->join('opr_opportunities','opr_lead_id','=','prs_accessrules.slm_lead')
			->whereIn('slm_user',$ids)
			->where('opr_close_status',null)
			->where('lds_status','3')
			->where('lds_stage_opr','true')
			->where('slm_rules','master')
			->count();
			$lds_lose = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->whereIn('slm_user',$ids)
			->where('lds_status','0')
			->where('lds_stage_opr','false')
			->where('slm_rules','master')
			->count();
		} elseif(checkRule(['STF'])) {
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
		}
		
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
	public function sourceChartOpportunity(Request $request)
	{
		$id = Auth::user()->id;
		if (checkRule(['ADM','AGM','MGR.PAS'])) {
			$opr = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->where('slm_rules','master')
			->where('opr_close_status',null)
			->get();
		} elseif(checkRule(['MGR'])) {
			$ids = checkTeamMgr($id);
			$opr = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->whereIn('slm_user',$ids)
			->where('slm_rules','master')
			->where('opr_close_status',null)
			->get();
		}elseif (checkRule(['STF'])) {
			$opr = Prs_lead::join('prs_accessrules','prs_leads.lds_id','=','prs_accessrules.slm_lead')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->where('slm_user',$id)
			->where('slm_rules','master')
			->where('opr_close_status',null)
			->get();
		}
		$id = $request->id;
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
	public function sourceChartPurchase(Request $request)
	{
		$id = Auth::user()->id;
		$date_now = now();
		$start_date = new DateTime(date('Y-m', strtotime($date_now.'-11 month')));
		$end_date = new DateTime(date('Y-m', strtotime($date_now.'+1 month')));
		$interval = new DateInterval('P1M');
		$datePeriod = new DatePeriod($start_date, $interval, $end_date);
		if (checkRule(['ADM','AGM','MGR.PAS'])) {
			foreach ($datePeriod as $key => $date) {
				$labels[] = $date->format('M-y');
				$series[] = Ord_purchase::where('pur_date','like',$date->format('Y-m').'%')->count();
			}
		} elseif(checkRule(['MGR'])) {
			$ids = checkTeamMgr($id);
			foreach ($datePeriod as $key => $date) {
				$labels[] = $date->format('M-y');
				$series[] = Ord_purchase::where('pur_date','like',$date->format('Y-m').'%')->whereIn('created_by',$ids)->count();
			}
		} elseif (checkRule(['STF'])) {
			foreach ($datePeriod as $key => $date) {
				$labels[] = $date->format('M-y');
				$series[] = Ord_purchase::where('pur_date','like',$date->format('Y-m').'%')->where('created_by',$id)->count();
			}
		}
		$result = [
			'param'=>true,
			'ValLabels' => $labels,
			'ValSeries' => $series,
		];
		return $result;
	}
}
