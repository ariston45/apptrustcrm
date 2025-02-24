<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
#
use Illuminate\Support\Carbon;
use Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
#
use App\Models\Act_activity;
use App\Models\Act_activity_access;
use App\Models\Addr_city;
use App\Models\Addr_district;
use App\Models\Addr_province;
use App\Models\Addr_subdistrict;
use App\Models\Cst_contact_email;
use App\Models\Cst_contact_mobile;
use App\Models\Cst_institution;
use App\Models\Cst_personal;
use App\Models\Prs_lead;
use App\Models\User;
use App\Models\Prs_accessrule;
#
use App\Models\Prs_salesperson;
use App\Models\Cst_customer;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
#
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
	public function sourceDataCustomer(Request $request)
	{
		$user = Auth::user();
		if (checkRule(['MGR.PAS','ADM','AGM'])) {
			$colect_data = Cst_institution::leftjoin('cst_customers', 'cst_institutions.ins_id', '=', 'cst_customers.cst_institution')
			->leftJoin(
				DB::raw('(select loc_id, loc_represent, loc_cst_id, loc_street, loc_district, loc_city, loc_province from cst_locations where loc_represent="INSTITUTION") locations'),
				function ($join) {
						$join->on('cst_institutions.ins_id', '=', 'locations.loc_cst_id');
				}
			)
			->select('ins_id', 'cst_id', 'ins_name', 'cst_name', 'ins_business_field', 'cst_business_field', 'loc_city', 'cst_customers.created_at')
			->get();
		}elseif(checkRule(['MGR'])){
			$ids = checkTeamMgr($user->id);
			$colect_data = Cst_institution::leftjoin('cst_customers', 'cst_institutions.ins_id', '=', 'cst_customers.cst_institution')
			->leftJoin(
				DB::raw('(select loc_id, loc_represent, loc_cst_id, loc_street, loc_district, loc_city, loc_province from cst_locations where loc_represent="INSTITUTION") locations'),
				function ($join) {
					$join->on('cst_institutions.cst_id', '=', 'locations.loc_cst_id');
				}
			)
			->whereIn('cst_customers.created_by', $ids)
			->select('ins_id', 'cst_id', 'ins_name', 'cst_name', 'ins_business_field', 'cst_business_field', 'loc_city', 'cst_customers.created_at')
			->get();
		}elseif(checkRule(['STF'])){
			$colect_data = Cst_institution::leftjoin('cst_customers','cst_institutions.ins_id','=','cst_customers.cst_institution')
			->leftJoin(DB::raw('(select loc_id, loc_represent, loc_cst_id, loc_street, loc_district, loc_city, loc_province from cst_locations where loc_represent="INSTITUTION") locations'),
				function ($join){
					$join->on('cst_customers.cst_id','=','locations.loc_cst_id');
			})
			->where('cst_institutions.created_by',$user->id)
			->select('ins_id','cst_id','ins_name','cst_name','ins_business_field','cst_business_field','loc_city','cst_customers.created_at')
			->get();
		}
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
			<a class="dropdown-item" href="'.url('customer/detail-customer/'.$colect_data->ins_id.'?extpg=information').'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Customer</a>
			<a class="dropdown-item" href="'.url('customer/contacts/'.$colect_data->ins_id).'"><i class="ri-user-add-line" style="margin-right:6px;"></i>Contacts</a>
      </div>
			</div>
			';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('customer', function ($colect_data) {
			return '<di><b><a href="' . url('customer/detail-customer/' . $colect_data->ins_id . '?extpg=information') . '">' . $colect_data->ins_name . '</a></b></di>';
		})
		->addColumn('sub_customer', function ($colect_data) {
			if ($colect_data->cst_id != null) {
				return '<di><a href="' . url('customer/detail-sub-customer/' . $colect_data->cst_id . '?extpg=information') . '">' . $colect_data->cst_name . '</a></di>';
			} else {
				return '-';
			}
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
				return "<div style='text-align:left;'>-</div>";
			} else {
				return "<div style='text-align:left;'>".$colect_data->loc_city."</div>";
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
		->rawColumns(['number_index','menu', 'customer', 'sub_customer', 'category', 'city', 'datein','lastactive'])
		->make('true');
	}
	public function sourceDataSubCustomer(Request $request)
	{
		$colect_data = Cst_institution::join('cst_customers', 'cst_institutions.ins_id', '=', 'cst_customers.cst_institution')
		->leftJoin(
			DB::raw('(select loc_id, loc_represent, loc_cst_id, loc_street, loc_district, loc_city, loc_province from cst_locations where loc_represent="INSTITUTION") locations'),
			function ($join) {
				$join->on('cst_customers.cst_id', '=', 'locations.loc_cst_id');
			}
		)
		->where('ins_id',$request->id)
		->select('ins_id', 'cst_id', 'ins_name', 'cst_name', 'ins_business_field', 'cst_business_field', 'loc_city', 'cst_customers.created_at')
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
			<a class="dropdown-item" href="' . url('customer/detail-customer/' . $colect_data->cst_id . '?extpg=information') . '"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Customer</a>
			<a class="dropdown-item" href="' . url('customer/contacts/' . $colect_data->cst_id) . '"><i class="ri-user-add-line" style="margin-right:6px;"></i>Contacts</a>
			<a class="dropdown-item" href="' . url('customer/detail-customer-activities/' . $colect_data->cst_id . '?extpg=activities') . '"><i class="ri-run-line" style="margin-right:6px;"></i>Activities</a>
      <a class="dropdown-item" href="#"><i class="ri-filter-2-line" style="margin-right:6px;"></i>Leads</a>
			<a class="dropdown-item" href="#"><i class="ri-briefcase-2-line" style="margin-right:6px;"></i>Opportunities</a>
      </div>
			</div>
			';
			})
			->addColumn('number_index', function () {
				return 1;
			})
			->addColumn('customer', function ($colect_data) {
				return '<di><b><a href="' . url('customer/detail-customer/' . $colect_data->ins_id . '?extpg=information') . '">' . $colect_data->ins_name . '</a></b></di>';
			})
			->addColumn('sub_customer', function ($colect_data) {
				return '<di><a href="' . url('customer/detail-sub-customer/' . $colect_data->cst_id . '?extpg=information') . '">' . $colect_data->cst_name . '</a></di>';
			})
			->addColumn('category', function ($colect_data) {
				if ($colect_data->cst_business_field == null) {
					return "<div style='text-align:center;'>-</div>";
				} else {
					return $colect_data->cst_business_field;
				}
			})
			->addColumn('city', function ($colect_data) {
				if ($colect_data->loc_city == null) {
					return "<div style='text-align:left;'>-</div>";
				} else {
					return "<div style='text-align:left;'>" . $colect_data->loc_city . "</div>";
				}
			})
			->addColumn('datein', function ($colect_data) {
				if ($colect_data->created_at == null) {
					return '<div style="text-align:left;">-</div>';
				} else {
					return '<div style="text-align:left;">' . $colect_data->created_at . '</div>';
				}
			})
			->addColumn('lastactive', function ($colect_data) {
				return "-";
			})
			->rawColumns(['number_index', 'menu', 'customer', 'sub_customer', 'category', 'city', 'datein', 'lastactive'])
			->make('true');
	}
	public function sourceDataContact(Request $request)
	{
		$colect_data = Cst_personal::where('cnt_cst_id',$request->id)
		->get();
		// $colect_data = Cst_institution::join('cst_customers', 'cst_institutions.ins_id', '=', 'cst_customers.cst_institution')
		// ->leftJoin(
		// 	DB::raw('(select loc_id, loc_represent, loc_cst_id, loc_street, loc_district, loc_city, loc_province from cst_locations where loc_represent="INSTITUTION") locations'),
		// 	function ($join) {
		// 		$join->on('cst_customers.cst_id', '=', 'locations.loc_cst_id');
		// 	}
		// 	)
		// 	->select('ins_id', 'cst_id', 'ins_name', 'cst_name', 'ins_business_field', 'cst_business_field', 'loc_city', 'cst_customers.created_at')
		// 	->get();
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
			<a class="dropdown-item" href="' . url('customer/contacts/detail/' . $colect_data->cnt_id ) . '"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Contact</a>
			<a class="dropdown-item" href="' . url('customer/contacts/delete/' . $colect_data->cnt_id) . '"><i class="ri-user-add-line" style="margin-right:6px;"></i>Delete</a>
      </div>
			</div>
			';
			})
			->addColumn('number_index', function () {
				return 1;
			})
			->addColumn('name', function ($colect_data) {
				return $colect_data->cnt_fullname;
			})
			->addColumn('phone', function ($colect_data) {
				$data_phone = Cst_contact_mobile::where('mob_cnt_id', $colect_data->cnt_id)->get();
				$res = "";
				foreach ($data_phone as $key => $value) {
					$res.="- ".$value->mob_number."<br>";
				}
				return $res;
			})
			->addColumn('email', function ($colect_data) {
				$data_email = Cst_contact_email::where('eml_cnt_id', $colect_data->cnt_id)->get();
				$res = "";
				foreach ($data_email as $key => $value) {
					$res .= "- " . $value->eml_address . "<br>";
				}
				return $res;
			})
			->addColumn('job', function ($colect_data) {
				return $colect_data->cnt_company_position;
			})
			->rawColumns(['number_index', 'menu', 'name', 'phone', 'email', 'job'])
			->make('true');
	}
	# <===========================================================================================================================================================>
	/* Tags:source data leads */
	public function sourceDataLeads(Request $request)
	{
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions','prs_leads.lds_customer','=', 'cst_institutions.ins_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->where('lds_stage_opr','false')
			->select('lds_id', 'lds_subcustomer','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','ins_name','act_id','last_todo','last_date','aat_type_button')
			->get();
		}elseif (checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_id = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions', 'prs_leads.lds_customer', '=', 'cst_institutions.ins_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_id',$lead_id)
			->where('lds_stage_opr','false')
			->select('lds_id', 'lds_subcustomer','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','ins_name','act_id','last_todo','last_date','aat_type_button')
			->get();
		}elseif (checkRule(array('MGR.TCH'))) {
			# code...
			$lead_data = Prs_accessrule::whereIn('slm_rules',['technical'])->select('slm_lead')->get();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				# code...
				$lds_idr[$key] = $value->slm_lead;
			}
			$lds_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions', 'prs_leads.lds_customer', '=', 'cst_institutions.ins_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_id',$lds_ids)
			->where('lds_stage_opr','false')
			->select('lds_id', 'lds_subcustomer','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','ins_name','act_id','last_todo','last_date','aat_type_button')
			->get();
		}elseif(checkRule(array('STF'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$ids = array();
			foreach ($lead_data as $key => $value) {
				$ids[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions', 'prs_leads.lds_customer', '=', 'cst_institutions.ins_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->where('lds_stage_opr','false')
			->whereIn('lds_id',$lead_ids)
			->select('lds_id', 'lds_subcustomer','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','ins_name','act_id','last_todo','last_date','aat_type_button')
			->get();
		}elseif(checkRule(array('STF.TCH'))){
			$lead_user_master = Prs_accessrule::where('slm_rules','technical')->where('slm_user',$user->id)->select('slm_lead')->get();
			$ids = array();
			foreach ($lead_user_master as $key => $value) {
				$ids[$key] = $value->slm_lead;
			}
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions', 'prs_leads.lds_customer', '=', 'cst_institutions.ins_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->where('lds_stage_opr','false')
			->whereIn('lds_id',$lead_ids)
			->select('lds_id', 'lds_subcustomer','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','ins_name','act_id','last_todo','last_date','aat_type_button')
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
			$res="";
			if ($lead_data->lds_subcustomer == null) {
				$res.='<b>'.$lead_data->ins_name.'</b>';
			}else{
				$res.='<b>'.$lead_data->ins_name.'</b>';
				$res .= '<br><span style="text-size:7px;">Sub: '. getNameSubcustomer($lead_data->lds_subcustomer).'<span>';
			}
			return $res;
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
		->addColumn("last_activity", function ($lead_data) {
			if ($lead_data->last_date == null || $lead_data->last_date == "" ) {
				return "-";
			}else{
				$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
				return $date;
			}
		})
		->addColumn("activity", function ($lead_data) {
			if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "" ) {
				return "-";
			}else{
				return '<a href="'.url('activity/activity-detail').'/'.$lead_data->act_id.'" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">'.$lead_data->aat_type_button.'</a>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','last_activity','activity'])
		->make('true');
	}
	public function sourceDataLeadsUser(Request $request)
	{
		$user = Auth::user();
		$user_id = $request->id;
		$lead_data = Prs_accessrule::whereIn('slm_rules',['master'])->where('slm_user',$user_id)->select('slm_lead')->get()->toArray();
		$ids = array();
		foreach ($lead_data as $key => $value) {
			$ids[$key] = $value['slm_lead'];
		}
		$lead_ids = array_unique($ids);
		$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
		->leftjoin('cst_institutions','prs_leads.lds_customer','=', 'cst_institutions.ins_id')
		->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
			RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
			GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
			function($join){
				$join->on('prs_leads.lds_id','=','activity.lead_id');
			})
		->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
		->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
		->where('lds_stage_opr','false')
		->whereIn('lds_id',$lead_ids)
		->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','ins_name','act_id','aat_type_button','last_date', 'lds_subcustomer', 'lds_customer')
		->get();
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
			return '<a href="'.url('leads/detail-lead').'/'.$lead_data->lds_id.'"><b>'.$lead_data->lds_title.'</b></a>';
		})
		->addColumn('customer', function ($lead_data) {
			$res = "";
			if ($lead_data->lds_subcustomer == null) {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
			} else {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
				$res .= '<br><span style="text-size:7px;">Sub: ' . getNameSubcustomer($lead_data->lds_subcustomer) . '<span>';
			}
			return $res;
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
		->addColumn("last_activity", function ($lead_data) {
			if ($lead_data->last_date == null || $lead_data->last_date == "" ) {
				return "-";
			}else{
				$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
				return $date;
			}
		})
		->addColumn("activity", function ($lead_data) {
			if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "" ) {
				return "-";
			}else{
				return '<a href="'.url('activity/activity-detail').'/'.$lead_data->act_id.'" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">'.$lead_data->aat_type_button.'</a>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','last_activity','activity'])
		->make('true');
	}
	public function sourceDataLeadCst(Request $request)
	{
		$id = $request->id;
		$user = Auth::user();
		$ids_cst = getIdSubcustomer($id);
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('cst_id', $ids_cst)
			->where('lds_stage_opr','false')
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','act_id','last_todo','last_date','aat_type_button')
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
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_customer', $ids_cst)
			->whereIn('lds_id',$lead_id)
			->where('lds_stage_opr','false')
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','act_id','last_todo','last_date','aat_type_button')
			->get();
		} elseif (checkRule(array('MGR.TCH'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules',['technical'])->select('slm_lead')->get();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				# code...
				$lds_idr[$key] = $value->slm_lead;
			}
			$lds_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_id',$lds_ids)
			->where('lds_stage_opr','false')
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','act_id','last_todo','last_date','aat_type_button')
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
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_customer', $ids_cst)
			->where('lds_stage_opr','false')
			->whereIn('lds_id',$lead_ids)
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','act_id','last_todo','last_date','aat_type_button')
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
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_customer', $ids_cst)
			->where('lds_stage_opr','false')
			->whereIn('lds_id',$lead_ids)
			->select('lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name','act_id','last_todo','last_date','aat_type_button')
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
			return '<a href="'.url('leads/detail-lead/'.$lead_data->lds_id).'"><b>'.$lead_data->lds_title.'</b></a>';
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
		->addColumn("last_activity", function ($lead_data) {
			if ($lead_data->last_date == null || $lead_data->last_date == "" ) {
				return "-";
			}else{
				$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
				return $date;
			}
		})
		->addColumn("activity", function ($lead_data) {
			if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "" ) {
				return "-";
			}else{
				return '<a href="'.url('activity/activity-detail').'/'.$lead_data->act_id.'" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">'.$lead_data->aat_type_button.'</a>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','last_activity','activity'])
		->make('true');
	}
	public function sourceDataLeadSubCst(Request $request)
	{
		$id = $request->id;
		$user = Auth::user();
		// $ids_cst = getIdSubcustomer($id);
		if (checkRule(array('ADM', 'AGM', 'MGR.PAS'))) {
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->where('cst_id', $id)
				->where('lds_stage_opr', 'false')
				->select('lds_id', 'slm_lead', 'slm_user', 'name', 'lds_title', 'pls_status_name', 'pls_code_name', 'cst_name', 'act_id', 'last_todo', 'last_date', 'aat_type_button')
				->get();
		} elseif (checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['master', 'manager','colaborator', ])
			->where('slm_user', $user->id)
			->select('slm_lead')->get()
			->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_id = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
			->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
			->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
			->where('lds_subcustomer', $id)
			->whereIn('lds_id', $lead_id)
			->where('lds_stage_opr', 'false')
			->select('lds_id', 'slm_lead', 'slm_user', 'name', 'lds_title', 'pls_status_name', 'pls_code_name', 'cst_name', 'act_id', 'last_todo', 'last_date', 'aat_type_button')
			->get();
		} elseif (checkRule(array('MGR.TCH'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['technical'])->select('slm_lead')->get();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				# code...
				$lds_idr[$key] = $value->slm_lead;
			}
			$lds_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->whereIn('lds_id', $lds_ids)
				->where('lds_stage_opr', 'false')
				->select('lds_id', 'slm_lead', 'slm_user', 'name', 'lds_title', 'pls_status_name', 'pls_code_name', 'cst_name', 'act_id', 'last_todo', 'last_date', 'aat_type_button')
				->get();
		} elseif (checkRule(array('STF'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['colaborator', 'master'])->where('slm_user', $user->id)->select('slm_lead')->get()->toArray();
			$ids = array();
			foreach ($lead_data as $key => $value) {
				$ids[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->where('lds_subcustomer', $id)
				->where('lds_stage_opr', 'false')
				->whereIn('lds_id', $lead_ids)
				->select('lds_id', 'slm_lead', 'slm_user', 'name', 'lds_title', 'pls_status_name', 'pls_code_name', 'cst_name', 'act_id', 'last_todo', 'last_date', 'aat_type_button')
				->get();
		} elseif (checkRule(array('STF.TCH'))) {
			$lead_user_master = Prs_accessrule::where('slm_rules', 'technical')->where('slm_user', $user->id)->select('slm_lead')->get();
			$ids = array();
			foreach ($lead_user_master as $key => $value) {
				$ids[$key] = $value->slm_lead;
			}
			$lead_ids = array_unique($ids);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->where('lds_subcustomer', $id)
				->where('lds_stage_opr', 'false')
				->whereIn('lds_id', $lead_ids)
				->select('lds_id', 'slm_lead', 'slm_user', 'name', 'lds_title', 'pls_status_name', 'pls_code_name', 'cst_name', 'act_id', 'last_todo', 'last_date', 'aat_type_button')
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
			<a class="dropdown-item" href="' . url('leads/detail-lead') . '/' . $lead_data->lds_id . '"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Lead</a>
      </div>
			</div>
			';
			})
			->addColumn('number_index', function () {
				return 1;
			})
			->addColumn('title', function ($lead_data) {
				return '<a href="' . url('leads/detail-lead/' . $lead_data->lds_id) . '"><b>' . $lead_data->lds_title . '</b></a>';
			})
			->addColumn('customer', function ($lead_data) {
				return $lead_data->cst_name;
			})
			->addColumn('created', function ($lead_data) {
				$date = date('d M/Y', strtotime($lead_data->created));
				return $date;
			})
			->addColumn('status', function ($lead_data) {
				if ($lead_data->pls_code_name == 'prospecting') {
					return '<span class="badge bg-blue-lt">Prospecting</span>';
				} elseif ($lead_data->pls_code_name == 'qualifying') {
					return '<span class="badge bg-azure-lt">Qualifying</span>';
				} else {
					return '<span class="badge bg-green-lt">Opportunity</span>';
				}
			})
			->addColumn('salesperson', function ($lead_data) {
				if ($lead_data->name == "" || $lead_data->name == null) {
					return '<i>-</i>';
				} else {
					return '<style="text-align:center;">' . $lead_data->name . '</style>';
				}
			})
			->addColumn("last_activity", function ($lead_data) {
				if ($lead_data->last_date == null || $lead_data->last_date == "") {
					return "-";
				} else {
					$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
					return $date;
				}
			})
			->addColumn("activity", function ($lead_data) {
				if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "") {
					return "-";
				} else {
					return '<a href="' . url('activity/activity-detail') . '/' . $lead_data->act_id . '" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">' . $lead_data->aat_type_button . '</a>';
				}
			})
			->rawColumns(['number_index', 'title', 'customer', 'status', 'salesperson', 'menu', 'last_activity', 'activity'])
			->make('true');
	}
	# <===========================================================================================================================================================>
	/* Tags:... */
	public function sourceDataOpportunities(Request $request)
	{
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_accessrule::where('slm_rules','master')->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions', 'prs_leads.lds_customer', '=', 'cst_institutions.ins_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','opr_title','pls_status_name','pls_code_name','ins_name','lds_subcustomer',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
			->get();
		}elseif(checkRule(array('MGR'))){
			$lead_data = Prs_accessrule::where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions','prs_leads.lds_customer','=', 'cst_institutions.ins_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','opr_title','pls_status_name','pls_code_name','ins_name','lds_subcustomer',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
			->get();
			// die('-');
		}elseif(checkRule(array('STF'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions','prs_leads.lds_customer','=', 'cst_institutions.ins_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','opr_title','pls_status_name','pls_code_name','ins_name','lds_subcustomer',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
			->get();
		}elseif(checkRule(array('MGR.TCH'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['technical'])->select('slm_lead')->get();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions','prs_leads.lds_customer','=', 'cst_institutions.ins_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','opr_title','pls_status_name','pls_code_name','ins_name','lds_subcustomer',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
			->get();
		}elseif(checkRule(array('STF.TCH'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['technical'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->leftjoin('cst_institutions','prs_leads.lds_customer','=', 'cst_institutions.ins_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','opr_title','pls_status_name','pls_code_name','ins_name','lds_subcustomer',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
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
			return '<a href="'.url('opportunities/detail-opportunity').'/'.$lead_data->opr_id.'"><b>'.$lead_data->opr_title.'</b></a>';
		})
		->addColumn('customer', function ($lead_data) {
			$res = "";
			if ($lead_data->lds_subcustomer == null) {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
			} else {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
				$res .= '<br><span style="text-size:7px;">Sub: ' . getNameSubcustomer($lead_data->lds_subcustomer) . '<span>';
			}
			return $res;
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
		->addColumn("last_activity", function ($lead_data) {
			if ($lead_data->last_date == null || $lead_data->last_date == "" ) {
				return "-";
			}else{
				$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
				return $date;
			}
		})
		->addColumn("activity", function ($lead_data) {
			if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "" ) {
				return "-";
			}else{
				return '<a href="'.url('activity/activity-detail').'/'.$lead_data->act_id.'" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">'.$lead_data->aat_type_button.'</a>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','last_activity','activity'])
		->make('true');
	}
	public function sourceDataOpportunitiesUser(Request $request)
	{
		$user = Auth::user();
		$user_id = $user->id;
		$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user_id)->select('slm_lead')->get()->toArray();
		$lds_idr = array();
		foreach ($lead_data as $key => $value) {
			$lds_idr[$key] = $value['slm_lead'];
		}
		$lead_ids = array_unique($lds_idr);
		$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
		->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
		->leftjoin('cst_institutions','prs_leads.lds_customer','=', 'cst_institutions.ins_id')
		->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
		->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
			RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
			GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
			function($join){
				$join->on('prs_leads.lds_id','=','activity.lead_id');
			})
		->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
		->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
		->whereIn('lds_id',$lead_ids)
		->where('lds_stage_opr','true')
		->where('opr_close_status',null)
		->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','opr_title','pls_status_name','pls_code_name','ins_name',
		'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id',
			'lds_subcustomer')
		->get();
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
			return '<a href="'.url('opportunities/detail-opportunity').'/'.$lead_data->opr_id.'"><b>'.$lead_data->lds_title.'</b></a>';
		})
		->addColumn('customer', function ($lead_data) {
			$res = "";
			if ($lead_data->lds_subcustomer == null) {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
			} else {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
				$res .= '<br><span style="text-size:7px;">Sub: ' . getNameSubcustomer($lead_data->lds_subcustomer) . '<span>';
			}
			return $res;
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
		->addColumn("last_activity", function ($lead_data) {
			if ($lead_data->last_date == null || $lead_data->last_date == "" ) {
				return "-";
			}else{
				$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
				return $date;
			}
		})
		->addColumn("activity", function ($lead_data) {
			if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "" ) {
				return "-";
			}else{
				return '<a href="'.url('activity/activity-detail').'/'.$lead_data->act_id.'" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">'.$lead_data->aat_type_button.'</a>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','last_activity','activity'])
		->make('true');
	}
	public function sourceDataPurchasesUser(Request $request)
	{
		$user = Auth::user();
		$user_id = $user->id;
		$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user_id)->select('slm_lead')->get()->toArray();
		$lds_idr = array();
		foreach ($lead_data as $key => $value) {
			$lds_idr[$key] = $value['slm_lead'];
		}
		$lead_ids = array_unique($lds_idr);
		$lead_data = Prs_lead::join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
		->join('ord_purchases','opr_opportunities.opr_id','=','ord_purchases.pur_oppr_id')
		->leftjoin('cst_institutions', 'prs_leads.lds_customer', '=', 'cst_institutions.ins_id')
		->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
		->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
			RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
			GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
			function($join){
				$join->on('prs_leads.lds_id','=','activity.lead_id');
			})
		->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
		->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
		->whereIn('lds_id',$lead_ids)
		->where('lds_stage_opr','true')
		->where('opr_close_status','WIN')
		->select('opr_id','lds_id','pur_id','slm_lead','slm_user','name','lds_title','ins_name','lds_subcustomer',
		'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
		->get();
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
			return '<a href="'.url('opportunities/detail-opportunity').'/'.$lead_data->opr_id.'"><b>'.$lead_data->lds_title.'</b></a>';
		})
		->addColumn('customer', function ($lead_data) {
			$res = "";
			if ($lead_data->lds_subcustomer == null) {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
			} else {
				$res .= '<b>' . $lead_data->ins_name . '</b>';
				$res .= '<br><span style="text-size:7px;">Sub: ' . getNameSubcustomer($lead_data->lds_subcustomer) . '<span>';
			}
			return $res;
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
		->addColumn("last_activity", function ($lead_data) {
			if ($lead_data->last_date == null || $lead_data->last_date == "" ) {
				return "-";
			}else{
				$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
				return $date;
			}
		})
		->addColumn("activity", function ($lead_data) {
			if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "" ) {
				return "-";
			}else{
				return '<a href="'.url('activity/activity-detail').'/'.$lead_data->act_id.'" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">'.$lead_data->aat_type_button.'</a>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','last_activity','activity'])
		->make('true');
	}
	/* Tags:... */
	public function sourceDataOpportunitiesCst(Request $request)
	{
		$customer_id = $request->id;
		$cst_ids = getIdSubcustomer($customer_id);
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			$lead_data = Prs_accessrule::where('slm_rules','master')->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
			RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
			GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
			function($join){
				$join->on('prs_leads.lds_id','=','activity.lead_id');
			})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->whereIn('lds_customer', $cst_ids)
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
			->get();
		}elseif(checkRule(array('MGR'))){
			$lead_data = Prs_accessrule::where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
			->join('cst_customers','prs_leads.lds_customer','=','cst_customers.cst_id')
			->join('opr_opportunities','prs_leads.lds_id','=','opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_customer', $cst_ids)
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
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
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_customer', $cst_ids)
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
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
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function($join){
					$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
				})
			->whereIn('lds_customer', $cst_ids)
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
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
			->leftjoin(DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function($join){
					$join->on('prs_leads.lds_id','=','activity.lead_id');
				})
			->leftjoin('act_activity_types','activity.last_todo','=','act_activity_types.aat_id')
			->leftJoin(DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
			function($join){
				$join->on('prs_leads.lds_id','=','salesperson.slm_lead');
			})
			->whereIn('lds_customer', $cst_ids)
			->whereIn('lds_id',$lead_ids)
			->where('lds_stage_opr','true')
			->where('opr_close_status',null)
			->select('opr_id','lds_id','slm_lead','slm_user','name','lds_title','pls_status_name','pls_code_name','cst_name',
			'oss_id','oss_status_code','oss_status_name','oss_status_name','last_date','aat_type_button','act_id')
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
			return '<a href="'.url('opportunities/detail-opportunity').'/'.$lead_data->opr_id.'"><b>'.$lead_data->lds_title.'</b></a>';
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
		->addColumn("last_activity", function ($lead_data) {
			if ($lead_data->last_date == null || $lead_data->last_date == "" ) {
				return "-";
			}else{
				$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
				return $date;
			}
		})
		->addColumn("activity", function ($lead_data) {
			if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "" ) {
				return "-";
			}else{
				return '<a href="'.url('activity/activity-detail').'/'.$lead_data->act_id.'" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">'.$lead_data->aat_type_button.'</a>';
			}
		})
		->rawColumns(['number_index','title', 'customer','status', 'salesperson','menu','last_activity','activity'])
		->make('true');
	}
	public function sourceDataOpportunitiesSubCst(Request $request)
	{
		$customer_id = $request->id;
		
		$cst_ids = getIdSubcustomer($customer_id);
		$user = Auth::user();
		if (checkRule(array('ADM', 'AGM', 'MGR.PAS'))) {
			$lead_data = Prs_accessrule::where('slm_rules', 'master')->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->join('opr_opportunities', 'prs_leads.lds_id', '=', 'opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses', 'opr_opportunities.opr_status', '=', 'opr_stage_statuses.oss_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
			RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
			GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->whereIn('lds_id', $lead_ids)
				->where('lds_subcustomer', $customer_id)
				->where('lds_stage_opr', 'true')
				->where('opr_close_status', null)
				->select(
					'opr_id',
					'lds_id',
					'slm_lead',
					'slm_user',
					'name',
					'lds_title',
					'pls_status_name',
					'pls_code_name',
					'cst_name',
					'oss_id',
					'oss_status_code',
					'oss_status_name',
					'oss_status_name',
					'last_date',
					'aat_type_button',
					'act_id'
				)
				->get();
		} elseif (checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::where('slm_user', $user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->join('opr_opportunities', 'prs_leads.lds_id', '=', 'opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses', 'opr_opportunities.opr_status', '=', 'opr_stage_statuses.oss_id')
			->leftjoin(
					DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
					RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
					GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
					}
				)
			->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
			->leftJoin(
				DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
				}
			)
			->whereIn('lds_id', $lead_ids)
			->where('lds_subcustomer', $customer_id)
			->where('lds_stage_opr', 'true')
			->where('opr_close_status', null)
			->select(
					'opr_id',
					'lds_id',
					'slm_lead',
					'slm_user',
					'name',
					'lds_title',
					'pls_status_name',
					'pls_code_name',
					'cst_name',
					'oss_id',
					'oss_status_code',
					'oss_status_name',
					'last_date',
					'aat_type_button',
					'act_id'
				)
			->get();
		} elseif (checkRule(array('STF'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['colaborator', 'master'])->where('slm_user', $user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->join('opr_opportunities', 'prs_leads.lds_id', '=', 'opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses', 'opr_opportunities.opr_status', '=', 'opr_stage_statuses.oss_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->where('lds_subcustomer', $customer_id)
				->whereIn('lds_id', $lead_ids)
				->where('lds_stage_opr', 'true')
				->where('opr_close_status', null)
				->select(
					'opr_id',
					'lds_id',
					'slm_lead',
					'slm_user',
					'name',
					'lds_title',
					'pls_status_name',
					'pls_code_name',
					'cst_name',
					'oss_id',
					'oss_status_code',
					'oss_status_name',
					'oss_status_name',
					'last_date',
					'aat_type_button',
					'act_id'
				)
				->get();
		} elseif (checkRule(array('MGR.TCH'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['technical'])->select('slm_lead')->get();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->join('opr_opportunities', 'prs_leads.lds_id', '=', 'opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses', 'opr_opportunities.opr_status', '=', 'opr_stage_statuses.oss_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->where('lds_subcustomer', $customer_id)
				->whereIn('lds_id', $lead_ids)
				->where('lds_stage_opr', 'true')
				->where('opr_close_status', null)
				->select(
					'opr_id',
					'lds_id',
					'slm_lead',
					'slm_user',
					'name',
					'lds_title',
					'pls_status_name',
					'pls_code_name',
					'cst_name',
					'oss_id',
					'oss_status_code',
					'oss_status_name',
					'oss_status_name',
					'last_date',
					'aat_type_button',
					'act_id'
				)
				->get();
		} elseif (checkRule(array('STF.TCH'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['technical'])->where('slm_user', $user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lead_ids = array_unique($lds_idr);
			$lead_data = Prs_lead::join('prs_lead_statuses', 'prs_leads.lds_status', '=', 'prs_lead_statuses.pls_id')
			->join('cst_customers', 'prs_leads.lds_subcustomer', '=', 'cst_customers.cst_id')
			->join('opr_opportunities', 'prs_leads.lds_id', '=', 'opr_opportunities.opr_lead_id')
			->leftjoin('opr_stage_statuses', 'opr_opportunities.opr_status', '=', 'opr_stage_statuses.oss_id')
			->leftjoin(
				DB::raw('(SELECT a.act_id,a.act_lead_id AS lead_id,a.act_todo_type_id AS last_todo,b.last_date FROM act_activities a 
				RIGHT JOIN ( SELECT MAX( bb.act_task_times_due ) AS last_date FROM act_activities bb GROUP BY bb.act_lead_id ) b ON a.act_task_times_due = b.last_date 
				GROUP BY a.act_lead_id ORDER BY a.act_task_times_due DESC) activity'),
				function ($join) {
					$join->on('prs_leads.lds_id', '=', 'activity.lead_id');
				}
			)
				->leftjoin('act_activity_types', 'activity.last_todo', '=', 'act_activity_types.aat_id')
				->leftJoin(
					DB::raw('(select slm_lead,slm_user,name from prs_accessrules join users on prs_accessrules.slm_user = users.id where slm_rules="master") salesperson'),
					function ($join) {
						$join->on('prs_leads.lds_id', '=', 'salesperson.slm_lead');
					}
				)
				->where('lds_subcustomer', $customer_id)
				->whereIn('lds_id', $lead_ids)
				->where('lds_stage_opr', 'true')
				->where('opr_close_status', null)
				->select(
					'opr_id',
					'lds_id',
					'slm_lead',
					'slm_user',
					'name',
					'lds_title',
					'pls_status_name',
					'pls_code_name',
					'cst_name',
					'oss_id',
					'oss_status_code',
					'oss_status_name',
					'oss_status_name',
					'last_date',
					'aat_type_button',
					'act_id'
				)
				->get();
		}
		return DataTables::of($lead_data)
			->addIndexColumn()
			->addColumn('empty_str', function ($k) {
				return '';
			})
			->addColumn('number_index', function () {
				return 1;
			})
			->addColumn('title', function ($lead_data) {
				return '<a href="' . url('opportunities/detail-opportunity') . '/' . $lead_data->opr_id . '"><b>' . $lead_data->lds_title . '</b></a>';
			})
			->addColumn('customer', function ($lead_data) {
				return $lead_data->cst_name;
			})
			->addColumn('status', function ($lead_data) {
				if ($lead_data->oss_status_name == null || $lead_data->oss_status_name == "") {
					return "-";
				} else {
					return "<strong>" . $lead_data->oss_status_name . "</strong>";
				}
			})
			->addColumn('salesperson', function ($lead_data) {
				if ($lead_data->name == null || $lead_data->name == "") {
					return "-";
				} else {
					return $lead_data->name;
				}
			})
			->addColumn("last_activity", function ($lead_data) {
				if ($lead_data->last_date == null || $lead_data->last_date == "") {
					return "-";
				} else {
					$date = date("d/m/y, h:ia", strtotime($lead_data->last_date));
					return $date;
				}
			})
			->addColumn("activity", function ($lead_data) {
				if ($lead_data->aat_type_button == null || $lead_data->aat_type_button == "") {
					return "-";
				} else {
					return '<a href="' . url('activity/activity-detail') . '/' . $lead_data->act_id . '" title="Go to activity" data-bs-toggle="tooltip" data-bs-placement="bottom">' . $lead_data->aat_type_button . '</a>';
				}
			})
			->rawColumns(['number_index', 'title', 'customer', 'status', 'salesperson', 'last_activity', 'activity'])
			->make('true');
	}
	/* Tags:... */
	public function sourceActivities(Request $request)
	{
		// die();
		if (in_array($request->act_status, array("beready","running","finished"))) {
			$status = [
				0 => $request->act_status
			];
		}else {
			$status = array("beready","running","finished");
		}
		$users = User::get();
		$user_ids = array();
		foreach ($users as $key => $value) {
			$user_ids[$key] = $value->id;	
		}
		if (in_array($request->act_user, $user_ids)) {
			$user_param = [
				0 => $request->act_user
			];
		}else{
			$user_param = $user_ids;
		}
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('cst_institutions','act_activities.act_cst','=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('MGR'))){
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','manager','master'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_activity_types.aat_type_code',$request->act_param)
				->where('act_label_category', 'ACTIVITY')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
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
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
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
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('STF.TCH'))){
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category', 'ACTIVITY')
				->select('act_id','ins_name','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title','lds_id','lds_stage_opr')
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
			return $colect_data->aat_type_button;
		})
		->addColumn('customer', function ($colect_data) {
			$res = "";
			if ($colect_data->act_subcst == null) {
				$res .= '<b>' . $colect_data->ins_name . '</b>';
			} else {
				$res .= '<b>' . $colect_data->ins_name . '</b>';
				$res .= '<br><span style="text-size:7px;">Sub: ' . getNameSubcustomer($colect_data->act_subcst) . '<span>';
			}
			return $res;
		})
		->addColumn('due_date', function ($colect_data) {
			$date = date('d/M y, h:i a', strtotime($colect_data->act_task_times_due));
			return $date;
		})
		->addColumn('assign', function ($colect_data) {
			return $colect_data->assign;
		})
		->addColumn('info', function ($colect_data) {
			return $colect_data->act_todo_result;
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
			if ($colect_data->lds_stage_opr == 'false') {
				return '<a href="'.url('leads/detail-lead/'.$colect_data->lds_id).'"><b>'.$colect_data->lds_title.'</b></a>' ;
			} else {
				return '<a href="'.url('opportunities/check-opportunity/'.$colect_data->lds_id).'"><b>'.$colect_data->lds_title.'</b></a>' ;
			}
		})
		->rawColumns(['number_index','title', 'customer','due_date', 'assign','menu','info','complete','project'])
		->make('true');
	}
	public function sourceTickets(Request $request)
	{
		if (in_array($request->act_status, array("beready","running","finished"))) {
			$status = [
				0 => $request->act_status
			];
		}else {
			$status = array("beready","running","finished");
		}
		$users = User::get();
		$user_ids = array();
		foreach ($users as $key => $value) {
			$user_ids[$key] = $value->id;	
		}
		if (in_array($request->act_user, $user_ids)) {
			$user_param = [
				0 => $request->act_user
			];
		}else{
			$user_param = $user_ids;
		}
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('cst_institutions','act_activities.act_cst','=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id','ins_name', 'act_cst', 'act_subcst', 'aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('act_user_assigned', $user_param)
				->whereIn('act_run_status', $status)
				->where('act_label_category', 'TICKET')
				->select('act_id', 'ins_name', 'act_cst', 'act_subcst', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
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
				$colect_data = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('cst_institutions','act_activities.act_cst','=', 'cst_institutions.ins_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id','ins_name','act_cst','act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id', 'ins_name', 'act_cst', 'act_subcst', 'aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
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
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id','ins_name', 'act_cst', 'act_subcst', 'aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id','ins_name', 'act_cst', 'act_subcst', 'aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
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
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('act_id',$act_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id', 'ins_name', 'act_cst', 'act_subcst', 'aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id', 'ins_name', 'act_cst', 'act_subcst', 'aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('STF.TCH'))){
			$act_access = Act_activity_access::where('acs_user_id',$user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('act_id',$act_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id', 'ins_name', 'act_cst', 'act_subcst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->where('act_label_category','TICKET')
				->select('act_id', 'ins_name', 'act_cst', 'act_subcst', 'aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
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
			<a class="dropdown-item" href="'. url('ticket/ticket-detail') .'/'. $colect_data->act_id.'"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Ticket</a>
      </div>
			</div>';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('title', function ($colect_data) {
			return $colect_data->aat_type_button;
		})
		->addColumn('customer', function ($colect_data) {
			$res = "";
			if ($colect_data->act_subcst == null) {
				$res .= '<b>' . $colect_data->ins_name . '</b>';
			} else {
				$res .= '<b>' . $colect_data->ins_name . '</b>';
				$res .= '<br><span style="text-size:7px;">Sub: ' . getNameSubcustomer($colect_data->act_subcst) . '<span>';
			}
			return $res;
		})
		->addColumn('due_date', function ($colect_data) {
			$date = date('d/M y, h:i a', strtotime($colect_data->act_task_times_due));
			return $date;
		})
		->addColumn('assign', function ($colect_data) {
			return $colect_data->assign;
		})
		->addColumn('info', function ($colect_data) {
			return $colect_data->act_todo_result;
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
	public function sourceActivitiesInstant(Request $request)
	{
		if (in_array($request->act_status, array("beready","running","finished"))) {
			$status = [
				0 => $request->act_status
			];
		}else {
			$status = array("beready","running","finished");
		}
		$users = User::get();
		$user_ids = array();
		foreach ($users as $key => $value) {
			$user_ids[$key] = $value->id;	
		}
		if (in_array($request->act_user, $user_ids)) {
			$user_param = [
				0 => $request->act_user
			];
		}else{
			$user_param = $user_ids;
		}
		$user = Auth::user();
		if (checkRule(array('ADM','AGM','MGR.PAS'))) {
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_task_times_due','like', $request->date.'%')
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
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
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
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
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('prs_leads.lds_id',$lds_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('prs_leads.lds_id',$lds_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
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
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('act_id',$act_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}
		}elseif(checkRule(array('STF.TCH'))){
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
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('act_id',$act_id)
				->whereIn('act_user_assigned',$user_param)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('act_task_times_due','like', $request->date.'%')
				->whereIn('act_id',$act_id)
				->where('act_activity_types.aat_type_code',$request->act_param)
				->whereIn('act_user_assigned',$user_param)
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
			<a href="'. url('activity/activity-detail') .'/'. $colect_data->act_id.'">
			<span class="badge bg-azure">View</span>
			</a>';
		})
		->addColumn('number_index', function () {
			return 1;
		})
		->addColumn('title', function ($colect_data) {
			return $colect_data->aat_type_button;
		})
		->addColumn('customer', function ($colect_data) {
			return $colect_data->cst_name;
		})
		->addColumn('due_date', function ($colect_data) {
			$date = date('d/m y, h:ia', strtotime($colect_data->act_task_times_due));
			return $date;
		})
		->addColumn('assign', function ($colect_data) {
			return $colect_data->assign;
		})
		->addColumn('info', function ($colect_data) {
			return $colect_data->act_todo_result;
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
				->where('cst_institution', $request->cst_id)
				->whereIn('act_run_status',$status)
				->select('act_id','cst_name','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
				->orderByDesc('act_activities.act_task_times_due')
				->get();
			}else{
				$colect_data = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
				->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
				->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
				->leftjoin('users','act_activities.act_user_assigned','=','users.id')
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
				->where('cst_institution', $request->cst_id)
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
	public function sourceActivitiesSubCst(Request $request)
	{
		if (in_array($request->act_status, array("beready", "running", "finished"))) {
			$status = [
				0 => $request->act_status
			];
		} else {
			$status = array("beready", "running", "finished");
		}
		$user = Auth::user();
		if (checkRule(array('ADM', 'AGM', 'MGR.PAS'))) {
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			} else {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->where('act_activity_types.aat_type_code', $request->act_param)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			}
		} elseif (checkRule(array('MGR'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['colaborator', 'master', 'manager'])->where('slm_user', $user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('prs_leads.lds_id', $lds_id)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			} else {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('prs_leads.lds_id', $lds_id)
					->where('act_activity_types.aat_type_code', $request->act_param)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			}
			// die('stop');
		} elseif (checkRule(array('STF'))) {
			$lead_data = Prs_accessrule::whereIn('slm_rules', ['colaborator', 'master'])->where('slm_user', $user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_id = array_unique($lds_idr);
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('prs_leads.lds_id', $lds_id)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			} else {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('prs_leads.lds_id', $lds_id)
					->where('act_activity_types.aat_type_code', $request->act_param)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			}
		} elseif (checkRule(array('MGR.TCH'))) {
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::whereIn('acs_user_id', $tech_team)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('act_id', $act_id)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			} else {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('act_id', $act_id)
					->where('act_activity_types.aat_type_code', $request->act_param)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			}
		} elseif (checkRule(array('STF.TCH'))) {
			$tech_team = checkTeamMgr($user->id);
			$act_access = Act_activity_access::where('acs_user_id', $user->id)->select('acs_act_id')->get();
			$act_id = array();
			foreach ($act_access as $key => $value) {
				$act_id[$key] = $value->acs_act_id;
			}
			if ($request->act_param == 'act_total' || $request->act_param == null) {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('act_id', $act_id)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
					->orderByDesc('act_activities.act_task_times_due')
					->get();
			} else {
				$colect_data = Act_activity::join('cst_customers', 'act_activities.act_cst', '=', 'cst_customers.cst_id')
				->join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
				->join('prs_leads', 'act_activities.act_lead_id', '=', 'prs_leads.lds_id')
				->leftjoin('users', 'act_activities.act_user_assigned', '=', 'users.id')
				->where('act_cst', $request->cst_id)
					->whereIn('act_id', $act_id)
					->where('act_activity_types.aat_type_code', $request->act_param)
					->whereIn('act_run_status', $status)
					->select('act_id', 'cst_name', 'aat_type_button', 'act_activities.act_task_times_due', 'act_todo_result', 'users.name as assign', 'act_todo_result', 'act_run_status', 'lds_title')
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
			<a class="dropdown-item" href="' . url('activity/activity-detail') . '/' . $colect_data->act_id . '"><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Activity</a>
      </div>
			</div>
			';
			})
			->addColumn('number_index', function () {
				return 1;
			})
			->addColumn('title', function ($colect_data) {
				return '<style="text-align:center;">' . $colect_data->aat_type_button . '</style>';
			})
			->addColumn('customer', function ($colect_data) {
				return $colect_data->cst_name;
			})
			->addColumn('due_date', function ($colect_data) {
				$date = date('d/M Y, h:i a', strtotime($colect_data->act_task_times_due));
				return $date;
			})
			->addColumn('assign', function ($colect_data) {
				return '<style="text-align:center;">' . $colect_data->assign . '</style>';
			})
			->addColumn('info', function ($colect_data) {
				return '<style="text-align:center;">' . $colect_data->act_todo_result . '</style>';
			})
			->addColumn('complete', function ($colect_data) {
				$btn = '';
				if ($colect_data->act_run_status == 'beready') {
					$btn .= '<button class="badge bg-blue text-blue-fg" onclick="actionChangeStatusAct(\'' . $colect_data->act_id . '\',\'beready\')">Beready</button>';
				} else if ($colect_data->act_run_status == 'running') {
					$btn .= '<button class="badge bg-green text-green-fg" onclick="actionChangeStatusAct(\'' . $colect_data->act_id . '\',\'running\')">Running</button>';
				} else {
					$btn .= '<button class="badge bg-muted-lt" onclick="actionChangeStatusAct(\'' . $colect_data->act_id . '\',\'finished\')">Finish</button>';
				}
				return $btn;
			})
			->addColumn('project', function ($colect_data) {
				return $colect_data->lds_title;
			})
			->rawColumns(['number_index', 'title', 'customer', 'due_date', 'assign', 'menu', 'info', 'complete', 'project'])
			->make('true');
	}
	public function sourceActivitiesUser(Request $request)
	{
		$auth = Auth::user();
		$user_id = $request->usr_id;
		if (in_array($request->act_status, array("beready","running","finished"))) {
			$status = [
				0 => $request->act_status
			];
		}else {
			$status = array("beready","running","finished");
		}
		$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master'])->where('slm_user',$user_id)->select('slm_lead')->get()->toArray();
		$lds_idr = array();
		foreach ($lead_data as $key => $value) {
			$lds_idr[$key] = $value['slm_lead'];
		}
		$lds_id = array_unique($lds_idr);
		if ($request->act_param == 'act_total' || $request->act_param == null) {
			$colect_data = Act_activity::join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
			->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
			->leftjoin('cst_institutions','act_activities.act_cst','=', 'cst_institutions.ins_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('prs_leads.lds_id',$lds_id)
			->whereIn('act_run_status',$status)
			->select('act_id', 'ins_name', 'act_subcst', 'act_cst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
			->orderByDesc('act_activities.act_task_times_due')
			->get();
		}else{
			$colect_data = Act_activity::join('act_activity_types', 'act_activities.act_todo_type_id', '=', 'act_activity_types.aat_id')
			->join('prs_leads','act_activities.act_lead_id','=','prs_leads.lds_id')
			->leftjoin('cst_institutions', 'act_activities.act_cst', '=', 'cst_institutions.ins_id')
			->leftjoin('users','act_activities.act_user_assigned','=','users.id')
			->whereIn('prs_leads.lds_id',$lds_id)
			->where('act_activity_types.aat_type_code',$request->act_param)
			->whereIn('act_run_status',$status)
			->select('act_id','ins_name', 'act_subcst', 'act_cst','aat_type_button','act_activities.act_task_times_due','act_todo_result','users.name as assign','act_todo_result','act_run_status','lds_title')
			->orderByDesc('act_activities.act_task_times_due')
			->get();
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
			$res = "";
			if ($colect_data->act_subcst == null) {
				$res .= '<b>' . $colect_data->ins_name . '</b>';
			} else {
				$res .= '<b>' . $colect_data->ins_name . '</b>';
				$res .= '<br><span style="text-size:7px;">Sub: ' . getNameSubcustomer($colect_data->act_subcst) . '<span>';
			}
			return $res;
		})
		->addColumn('due_date', function ($colect_data) {
			$date = date('d/M y, H:i', strtotime($colect_data->act_task_times_due));
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
	/* Tags:... */
	public function exportStaffReport(Request $request)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		// Menulis data ke dalam spreadsheet
		# column definition
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);
		$sheet->getColumnDimension('I')->setAutoSize(true);
		$sheet->getColumnDimension('J')->setAutoSize(true);
		$sheet->getColumnDimension('K')->setAutoSize(true);
		$sheet->getColumnDimension('L')->setAutoSize(true);
		$sheet->getColumnDimension('M')->setAutoSize(true);
		$sheet->getColumnDimension('N')->setAutoSize(true);
		$sheet->getColumnDimension('O')->setAutoSize(true);
		$sheet->getColumnDimension('P')->setAutoSize(true);
		$sheet->getColumnDimension('Q')->setAutoSize(true);
		$sheet->getColumnDimension('R')->setAutoSize(true);
		$sheet->getColumnDimension('S')->setAutoSize(true);
		$sheet->getColumnDimension('T')->setAutoSize(true);
		$sheet->getColumnDimension('U')->setAutoSize(true);
		$sheet->getColumnDimension('V')->setAutoSize(true);
		$sheet->getColumnDimension('W')->setAutoSize(true);
		# Title
		$sheet->mergeCells('A1:A2');
		$sheet->mergeCells('B1:B2');
		$sheet->mergeCells('C1:C2');
		$sheet->mergeCells('D1:F1');
		$sheet->mergeCells('G1:L1');
		$sheet->mergeCells('M1:M2');
		$sheet->mergeCells('N1:T1');
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('B1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('C1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('M1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle('M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('N1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1:T2')->getFont()->setBold(true)->getColor()->setARGB('e6e7e9');
		$sheet->getStyle('A1:T2')->getFill()->getStartColor()->setARGB('39656b');
		$sheet->getStyle('A1:T2')->getFill()->setFillType(Fill::FILL_SOLID);
		$sheet->getStyle('A1:T2')->getBorders()->applyFromArray([
			'allBorders' => [
				'borderStyle' => Border::BORDER_MEDIUM,  // Jenis border: tipis
				'color' => ['argb' => 'e6e7e9'],     // Warna border: hitam dalam format ARGB
			],
		]);

		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Nama');
		$sheet->setCellValue('C1', 'Team');
		$sheet->setCellValue('D1', 'Lead');
		$sheet->setCellValue('D2', 'Propecting');
		$sheet->setCellValue('E2', 'Qualifying');
		$sheet->setCellValue('F2', 'Opportunity');
		$sheet->setCellValue('G2', 'Dead End');
		$sheet->setCellValue('H1', 'Opportunity');
		$sheet->setCellValue('H2', 'Presentation');
		$sheet->setCellValue('I2', 'POC');
		$sheet->setCellValue('J2', 'Proposal');
		$sheet->setCellValue('K2', 'Presentation');
		$sheet->setCellValue('L2', 'Win');
		$sheet->setCellValue('M2', 'Lose');
		$sheet->setCellValue('N1', 'Purchase');
		$sheet->setCellValue('M1', 'Activity');
		$sheet->setCellValue('M2', 'To Do');
		$sheet->setCellValue('N2', 'Phone');
		$sheet->setCellValue('O2', 'Email');
		$sheet->setCellValue('P2', 'Visit');
		$sheet->setCellValue('Q2', 'POC');
		$sheet->setCellValue('R2', 'Webinar');
		$sheet->setCellValue('T2', 'Video Call');
		# Test
		$sheet->setCellValue('A3', '1');
		$sheet->setCellValue('B3', 'John Doe');
		$sheet->setCellValue('C3', 'johndoe@example.com');
		// Menyimpan file spreadsheet ke dalam format XLSX
		$writer = new Xlsx($spreadsheet);
		$fileName = 'data-export.xlsx';
		$filePath = storage_path($fileName);

		$writer->save($filePath);

		// Mengunduh file
		return response()->download($filePath)->deleteFileAfterSend(true);
	}
}
