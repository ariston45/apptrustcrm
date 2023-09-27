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

class PurchaseController extends Controller
{
	/* Tags:... */
	public function PurchaseDataView(Request $request)
	{
		return view('contents.page_purchase.purchase');
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
}
