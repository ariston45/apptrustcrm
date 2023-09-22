<?php

namespace App\Http\Controllers;

use App\Models\Act_activity;
use App\Models\Act_activity_access;
use App\Models\Addr_city;
use App\Models\Addr_district;
use App\Models\Addr_province;
use App\Models\Addr_subdistrict;
use App\Models\Cst_customer;
use App\Models\Cst_institution;
use App\Models\Prs_lead;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Prs_salesperson;
use App\Models\Prs_accessrule;
use DataTables;
use Str;
use Auth;
use DB;
use Illuminate\Support\Carbon;
class DataController extends Controller
{
	# <===========================================================================================================================================================>
	#user #data_user
  public function sourceDataUser(Request $request)
	{
		$colect_data = User::all();
		return DataTables::of($colect_data)
		->addIndexColumn()
		->addColumn('empty_str', function ($k) {
			return '';
		})
		->addColumn('menu', function ($colect_data) {
			return '<div class="btn-group">
			<button type="button" class="btn btn-xs bg-gradient-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menu</button>
			<div class="dropdown-menu dropdown-menu-right">
				<a href="'.url('setting/user/detail-user/'.$colect_data->id).'"><button class="dropdown-item btn-sm" type="button"><i class="fas fa-eye cst-mr-5"></i>Lihat Detail</button></a>
			</div></div>';
		})
		->addColumn('name', function ($colect_data) {
			return $colect_data->name;
		})
		->addColumn('username', function ($colect_data) {
			return $colect_data->username;
		})
		->addColumn('email', function ($colect_data) {
			return $colect_data->email;
		})
		->rawColumns(['name', 'username', 'email','menu'])
		->make('true');
	}
	# <===========================================================================================================================================================>
	#
  public function sourceDataSubdistrict(Request $request)
  {
    $colect_district = Addr_subdistrict::all('subdis_name')->groupBy('subdis_name');
    foreach ($colect_district as $key => $value) {
      $subdistrict[] = $key;
    }
    header('Content-Type: application/json');
    echo json_encode($subdistrict);
  }
	
  public function sourceDataDistrict(Request $request)
  {
    if ($request->data_city == null AND $request->data_province != null ) {
      $province = Addr_province::where('prov_name', $request->data_province)->select('prov_id')->first();
      $city = Addr_city::where('prov_id',$province->prov_id)->select('city_id')->get();
      foreach ($city as $key => $value) {
        $cities[$key] = $value->city_id;
      }
      $district = Addr_district::whereIn('city_id',$cities)->select('dis_name')->get();
    }elseif($request->data_city != null AND $request->data_province == null) {
      $city = Addr_city::where('city_name', $request->data_city)->select('city_id')->get();
      foreach ($city as $key => $value) {
        $city_ids[] = $value->city_id;
      }
      $district = Addr_district::whereIn('city_id', $city_ids)->select('dis_name')->get();
    }elseif ($request->data_city != null and $request->data_province != null) {
      $city = Addr_city::where('city_name', $request->data_city)->select('city_id')->get();
      foreach ($city as $key => $value) {
        $city_ids[] = $value->city_id;
      }
      $district = Addr_district::whereIn('city_id', $city_ids)->select('dis_name')->get();
    }else {
      $district = Addr_district::select('dis_name')->get();
    }
    foreach ($district as $key => $value) {
      $districts[] = $value->dis_name;
    }
    header('Content-Type: application/json');
    echo json_encode($districts);
  }
  public function sourceDataCities(Request $request)
  {
    if ($request->data_district == null and $request->data_province == null) {
      $city = Addr_city::select('city_name')->get();
    }elseif ($request->data_district != null and $request->data_province == null) {
      $city_data = Addr_district::where('dis_name', $request->data_district)->select('city_id')->get();
      foreach ($city_data as $key => $value) {
        $city_ids[] = $value->city_id;
      }
      $city = Addr_city::whereIn('city_id',$city_ids)->select('city_name')->get();
    }elseif ($request->data_district == null and $request->data_province != null) {
      $province = Addr_province::where('prov_name', $request->data_province)->select('prov_id')->first();
      $city = Addr_city::where('prov_id',$province->prov_id)->select('city_name')->get();
    }else {
      $province = Addr_province::where('prov_name', $request->data_province)->select('prov_id')->first();
      $city = Addr_city::where('prov_id', $province->prov_id)->select('city_name')->get();
    }
    foreach ($city as $key => $value) {
      $cities[] = $value->city_name;
    }
    header('Content-Type: application/json');
    echo json_encode($cities);
  }
  public function sourceDataProvinces(Request $request)
  {
    if ($request->data_district == null AND $request->data_city == null) {
      $province = Addr_province::select('prov_name')->get();
    }elseif ($request->data_district != null and $request->data_city == null) {
      $district = Addr_district::where('dis_name',$request->data_district)->select('city_id')->get();
      foreach ($district as $key => $value) {
        $city_ids[] = $value->city_id; 
      }
      $city = Addr_city::whereIn('city_id',$city_ids)->select('prov_id')->first();
      
      foreach ($city as $key => $value) {
        $province_ids[] = $value->prov_id;
      }
      $province = Addr_province::whereIn('prov_id',$province_ids)->get();
    }elseif ($request->data_district == null and $request->data_city != null) {
      $city = Addr_city::where('city_name', $request->data_city)->select('prov_id')->get();
      foreach ($city as $key => $value) {
        $province_ids[$key] = $value->prov_id;
      }
      $province = Addr_province::whereIn('prov_id', $province_ids)->select('prov_name')->get();
    }else {
      $city = Addr_city::where('city_name', $request->data_city)->select('prov_id')->get();
      foreach ($city as $key => $value) {
        $province_ids[$key] = $value->prov_id;
      }
      $province = Addr_province::whereIn('prov_id', $province_ids)->select('prov_name')->get();
    }
    foreach ($province as $key => $value) {
      $provinces[] = $value->prov_name;
    }
    header('Content-Type: application/json');
    echo json_encode($provinces);
  }
  public function dataDistricts(Request $request)
  {
    if ($request->city != null && $request->province ==  null) {
      $cities = Addr_city::where('city_name', $request->city)->select('city_id')->get();
      foreach ($cities as $key => $value) {
        $city_ids[] = $value->city_id;
      }
      $district = Addr_district::whereIn('city_id', $city_ids)->select('dis_name')->orderBy('dis_name', 'asc')->get();
    }elseif($request->city == null && $request->province !=  null){
      $province = Addr_province::where('prov_name', $request->province)->select('prov_id')->first();
      $cities = Addr_city::where('prov_id', $province->prov_id)->select('city_id')->get();
      foreach ($cities as $key => $value) {
        $city[$key] = $value->city_id;
      }
      $district = Addr_district::whereIn('city_id', $city)->select('dis_name')->orderBy('dis_name', 'asc')->get();
    }elseif ($request->city != null && $request->province !=  null) {
      $cities = Addr_city::where('city_name', $request->city)->select('city_id')->get();
      foreach ($cities as $key => $value) {
        $city_ids[] = $value->city_id;
      }
      $district = Addr_district::whereIn('city_id', $city_ids)->select('dis_name')->get();
    }else {
      $district = Addr_district::select('dis_name')->orderBy('dis_name', 'asc')->get();
    }
    $data = array();
    foreach ($district as $key => $value) {
      $data[$key] = [
        'id' => $value->dis_name,
        'title' => $value->dis_name
      ];
    }
    return $data;
  }
  public function dataCities(Request $request)
  {
    if ($request->district != null && $request->province ==  null) {
      $city_data = Addr_district::where('dis_name', $request->district)->select('city_id')->get();
      foreach ($city_data as $key => $value) {
        $city_ids[] = $value->city_id;
      }
      $city = Addr_city::whereIn('city_id', $city_ids)->select('city_name')->get();
    }elseif ($request->district == null && $request->province !=  null) {
      $province = Addr_province::where('prov_name', $request->province)->select('prov_id')->first();
      $city = Addr_city::where('prov_id', $province->prov_id)->select('city_name')->get();
    }elseif ($request->district != null && $request->province !=  null) {
      $province = Addr_province::where('prov_name', $request->province)->select('prov_id')->first();
      $city = Addr_city::where('prov_id', $province->prov_id)->select('city_name')->get();
    }else {
      $city = Addr_city::select('city_name')->get();
    }
    foreach ($city as $key => $value) {
      $data[$key] = [
        'id' => $value->city_name,
        'title' => $value->city_name
      ];
    }
    return $data;
  }
  public function dataProvincies(Request $request)
  {
    if ($request->district != null && $request->city ==  null) {
      $district = Addr_district::where('dis_name', $request->district)->select('city_id')->get();
      foreach ($district as $key => $value) {
        $city_ids[] = $value->city_id;
      }
      $city = Addr_city::whereIn('city_id', $city_ids)->select('prov_id')->get();
      foreach ($city as $key => $value) {
        $province_ids[] = $value->prov_id;
      }
      $province = Addr_province::whereIn('prov_id', $province_ids)->get();
    } elseif ($request->district == null && $request->city !=  null) {
      $city = Addr_city::where('city_name', $request->city)->select('prov_id')->get();
      foreach ($city as $key => $value) {
        $province_ids[$key] = $value->prov_id;
      }
      $province = Addr_province::whereIn('prov_id', $province_ids)->select('prov_name')->get();
    } elseif ($request->district != null && $request->city !=  null) {
      $city = Addr_city::where('city_name', $request->city)->select('prov_id')->get();
      foreach ($city as $key => $value) {
        $province_ids[$key] = $value->prov_id;
      }
      $province = Addr_province::whereIn('prov_id', $province_ids)->select('prov_name')->get();
    } else {
      $province = Addr_province::select('prov_name')->get();
    }
    foreach ($province as $key => $value) {
      $data[$key] = [
        'id' => $value->prov_name,
        'title' => $value->prov_name
      ];
    }
    return $data;
  }
	# <===========================================================================================================================================================>
	#Data Customer
	// public function sourceDataCustomer(Request $request)
  // {
  //   $data_companies = Cst_customer::all('cst_id', 'cst_name');
  //   foreach ($data_companies as $key => $value) {
  //     $cst[$key] = $value->cst_name;
  //   }
  //   header('Content-Type: application/json');
  //   echo json_encode($cst);
  // }
	public function sourceDataCustomer(Request $request)
	{
		$colect_data = Cst_institution::join('cst_customers','cst_institutions.ins_id','=','cst_customers.cst_institution')
		->leftJoin(DB::raw('(select loc_id, loc_represent, loc_cst_id, loc_street, loc_district, loc_city, loc_province from cst_locations where loc_represent="INSTITUTION") locations'),
			function ($join){
				$join->on('cst_customers.cst_id','=','locations.loc_cst_id');
			})
		->select('ins_id','cst_id','ins_name','cst_name','ins_business_field','cst_business_field','loc_city','cst_customers.created_at')
		->get();
		$num = 1;
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
			<a class="dropdown-item" href="'.url('customer/detail-customer/'.$colect_data->cst_id.'?extpg=information').'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Customer</a>
			<a class="dropdown-item" href="'.url('customer/create-customer/'.$colect_data->cst_id).'"><i class="ri-user-add-line" style="margin-right:6px;"></i>Add Contact</a>
			<a class="dropdown-item" href="'.url('customer/detail-customer-activities/'.$colect_data->cst_id.'?extpg=activities').'"><i class="ri-run-line" style="margin-right:6px;"></i>Activities</a>
      <a class="dropdown-item" href="#"><i class="ri-filter-2-line" style="margin-right:6px;"></i>Leads</a>
			<a class="dropdown-item" href="#"><i class="ri-briefcase-2-line" style="margin-right:6px;"></i>Opportunities</a>
      </div>
			</div>
			';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('name', function ($colect_data) {
			return $colect_data->cst_name;
		})
		->addColumn('institution', function ($colect_data) {
			return $colect_data->ins_name;
		})
		->addColumn('category', function ($colect_data) {
			if ($colect_data->cst_business_field == null) {
				return "<div style='text-align:center;'>-</div>";
			}else {
				return $colect_data->cst_business_field;
			}
		})
		->addColumn('city', function ($colect_data) {
			if ($colect_data->loc_city == null) {
				return "<div style='text-align:center;'>-</div>";
			} else {
				return "<div style='text-align:center;'>".$colect_data->loc_city."</div>";
			}
		})
		->addColumn('datein', function ($colect_data) {
			if ($colect_data->created_at == null) {
				return '<div style="text-align:center;">-</div>';
			} else {
				return '<div style="text-align:center;">'. $colect_data->created_at.'</div>';
			}
		})
		->addColumn('lastactive', function ($colect_data) {
			return "-";
		})
		->rawColumns(['number_index','menu', 'name','ins_name', 'category', 'city', 'datein','lastactive'])
		->make('true');
	}
	# <===========================================================================================================================================================>
	/* Tags:source data leads */
	public function sourceDataLeads(Request $request)
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
	public function sourceDataLeadCst(Request $request)
	{
		$id = 50;
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->where('lds_customer',$id)
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','prs_leads.created_at as created')
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
			->where('lds_customer',$id)
			->whereIn('lds_id',$lead_id)
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','prs_leads.created_at as created')
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
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','prs_leads.created_at as created')
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
			->where('lds_customer',$id)
			->whereIn('lds_id',$lead_ids)
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','prs_leads.created_at as created')
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
			->whereIn('lds_id',$lead_ids)
			->where('lds_customer',$id)
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','prs_leads.created_at as created')
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
		->addColumn('created', function ($lead_data) {
			$date = date('d M/Y',strtotime($lead_data->created));
			return $date;
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
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','created'])
		->make('true');
	}
	# <===========================================================================================================================================================>
	/* Tags:... */
	public function sourceDataOpportunities(Request $request)
	{
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="head") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name')
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
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="head") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name')
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
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="head") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			// ->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name')
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
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="head") salesperson'),
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
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="head") salesperson'),
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
			<a class="dropdown-item" href="'.url('opportunities/detail-opportunity').'/'.$lead_data->opr_id.'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Opportunity</a>
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
	public function sourceDataOpportunitiesCst(Request $request)
	{
		$customer_id = $request->id;
		$user = Auth::user();
		if ($user->level == 'ADM' || $user->level == 'MGR.PAS' || $user->level == 'AGM') {
			$lead_user_head = Prs_accessrule::where('slm_rules','head')->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lead_user_member = Prs_accessrule::where('slm_rules','member')->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lead_merge = array_merge($lead_user_head,$lead_user_member);
			$ids = array();
			foreach ($lead_merge as $key => $value) {
				$ids[$key] = $value['slm_lead'];
			}
			$ids_string = implode(',',$ids);
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="head") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name')
			// ->whereIn('lds_id',$lead_ids)
			->get();
		}elseif($user->level == 'MKT'|| $user->level == 'MGR'){
			$lead_user_head = Prs_accessrule::where('slm_rules','head')->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lead_user_member = Prs_accessrule::where('slm_rules','member')->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lead_merge = array_merge($lead_user_head,$lead_user_member);
			$ids = array();
			foreach ($lead_merge as $key => $value) {
				$ids[$key] = $value['slm_lead'];
			}
			$ids_string = implode(',',$ids);
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="head") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->whereIn('lds_id',$lead_ids)
			->where('lds_customer',$request->id)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','oss_id','oss_status_code','oss_status_name','oss_status_name')
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
			<a class="dropdown-item" href="'.url("opportunities/detail-opportunity").'/'.$lead_data->opr_id.'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Opportunity</a>
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
	public function sourceActivities(Request $request)
	{
		if (in_array($request->act_status, array("beready","running","finished"))) {
			$status = [
				0 => $request->act_status
			];
		}else {
			$status = array("beready","running","finished");
		}
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('MGR'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('STF'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('MGR.TCH'))){
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('STF.TCH'))){
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
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
			<a class="dropdown-item" href="'. url('activity/activity-detail') .'/'. $colect_data->act_id.'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Activity</a>
      </div>
			</div>
			';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('title', function ($colect_data) {
			return '<style="text-align:center;">'. $colect_data->aat_type_button.'</style>';
		})
		->addColumn('customer', function ($colect_data) {
			return $colect_data->cst_name;
		})
		->addColumn('due_date', function ($colect_data) {
			$date = date('d/M Y, h:i a', strtotime($colect_data->act_task_times_due));
			return $date;
		})
		->addColumn('assign', function ($colect_data) {
			return '<style="text-align:center;">'.$colect_data->assign.'</style>';
		})
		->addColumn('info', function ($colect_data) {
			return '<style="text-align:center;">'.$colect_data->act_todo_result.'</style>';
		})
		->addColumn('complete', function ($colect_data) {
			$btn ='';
			if ($colect_data->act_run_status == 'beready') {
				$btn .='<button class="badge bg-blue text-blue-fg" onclick="actionChangeStatusAct(\''.$colect_data->act_id.'\',\'beready\')">Beready</button>';
			}else if ($colect_data->act_run_status == 'running'){
				$btn .='<button class="badge bg-green text-green-fg" onclick="actionChangeStatusAct(\''.$colect_data->act_id.'\',\'running\')">Running</button>';
			}else{
				$btn .='<button class="badge bg-muted-lt" onclick="actionChangeStatusAct(\''.$colect_data->act_id.'\',\'finished\')">Finish</button>';
			}
			return $btn;
		})
		->addColumn('project', function ($colect_data){
			return $colect_data->lds_title;
		})
		->rawColumns(['number_index','title', 'customer','due_date', 'assign','menu','info','complete','project'])
		->make('true');
	}
	public function sourceActivitiesCst(Request $request)
	{
		if (in_array($request->act_status, array("beready","running","finished"))) {
			$status = [
				0 => $request->act_status
			];
		}else {
			$status = array("beready","running","finished");
		}
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('MGR'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
			// die('stop');
		}elseif(checkRule(array('STF'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('MGR.TCH'))){
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id',$tech_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('act_id',$act_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('STF.TCH'))){
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('act_id',$act_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_cst', $request->cst_id)
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
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
			<a class="dropdown-item" href="'. url('activity/activity-detail') .'/'. $colect_data->act_id.'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Activity</a>
      </div>
			</div>
			';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('title', function ($colect_data) {
			return '<style="text-align:center;">'. $colect_data->aat_type_button.'</style>';
		})
		->addColumn('customer', function ($colect_data) {
			return $colect_data->cst_name;
		})
		->addColumn('due_date', function ($colect_data) {
			$date = date('d/M Y, h:i a', strtotime($colect_data->act_task_times_due));
			return $date;
		})
		->addColumn('assign', function ($colect_data) {
			return '<style="text-align:center;">'.$colect_data->assign.'</style>';
		})
		->addColumn('info', function ($colect_data) {
			return '<style="text-align:center;">'.$colect_data->act_todo_result.'</style>';
		})
		->addColumn('complete', function ($colect_data) {
			$btn ='';
			if ($colect_data->act_run_status == 'beready') {
				$btn .='<button class="badge bg-blue text-blue-fg" onclick="actionChangeStatusAct(\''.$colect_data->act_id.'\',\'beready\')">Beready</button>';
			}else if ($colect_data->act_run_status == 'running'){
				$btn .='<button class="badge bg-green text-green-fg" onclick="actionChangeStatusAct(\''.$colect_data->act_id.'\',\'running\')">Running</button>';
			}else{
				$btn .='<button class="badge bg-muted-lt" onclick="actionChangeStatusAct(\''.$colect_data->act_id.'\',\'finished\')">Finish</button>';
			}
			return $btn;
		})
		->addColumn('project', function ($colect_data){
			return $colect_data->lds_title;
		})
		->rawColumns(['number_index','title', 'customer','due_date', 'assign','menu','info','complete','project'])
		->make('true');
	}
}
