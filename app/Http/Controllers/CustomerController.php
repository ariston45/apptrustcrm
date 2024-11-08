<?php

#Init
namespace App\Http\Controllers;
#Models

use App\Models\Act_activity;
use App\Models\Act_activity_access;
use App\Models\Addr_city;
use App\Models\Addr_subdistrict;
use App\Models\Addr_district;
use App\Models\Addr_province;
use App\Models\Cst_bussiness_field;
use App\Models\Prs_accessrule;
use Illuminate\Http\Request;
#Helpers
use Str;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Stringable ;
#Models
use App\Models\User;
use App\Models\Cst_personal;
use App\Models\Cst_customer;
use App\Models\Cst_contact_email;
use App\Models\Cst_contact_mobile;
use App\Models\Cst_contact_phone;
use App\Models\Cst_location;
use App\Models\Prd_product;
use App\Models\Prs_lead_status;
use App\Models\Act_activity_type;
use App\Models\Cst_institution;
use App\Models\Opr_opportunity;
use App\Models\Ord_purchase;
use App\Models\Prs_contact;
use App\Models\Prs_lead;
use App\Models\User_structure;
use PhpParser\Node\Stmt\Echo_;

class CustomerController extends Controller
{
  public function CustomerDataView()
  {
    return view('contents.page_customer.customer');
  }
  ###
  public function detailCustomer(Request $request)
  {
    $cstId = $request->id;
    $auth = Auth::user();
    $id = $request->id;
    $company = Cst_customer::join('cst_institutions','cst_institutions.ins_id','=','cst_customers.cst_institution')
    ->leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->where('cst_id', $request->id)
    ->select('cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes','ins_name')
    ->first();
    $location = Cst_location::where('loc_cst_id', $id)
    ->where('loc_represent', 'INSTITUTION')
    ->select('loc_street', 'loc_district', 'loc_city', 'loc_province')
    ->first();
    if ($location == null) {
      $location_ar = array();
    } else {
      $location_ar = [
        0 => Str::title($location->loc_street),
        1 => Str::title($location->loc_district),
        2 => Str::title($location->loc_city),
        3 => Str::title($location->loc_province),
      ];
    }
    $email = Cst_contact_email::where('eml_cnt_id', $id)
    ->where('eml_param', 'INSTITUTION')
    ->select('eml_address')
    ->get();
    $phone = Cst_contact_phone::where('pho_cnt_id', $id)
    ->where('pho_param', 'INSTITUTION')
    ->select('pho_number')
    ->get();
    $last_lead = Prs_lead::where('lds_customer', $id)->select('lds_id')->orderBy('lds_close_date','desc')->first();
    if ($last_lead == null) {
      $lastproject_data = null;
    }else{
      $last_oppor = Opr_opportunity::where('opr_lead_id',$last_lead->lds_id)->first();
      // dd($last_oppor);
      if ($last_oppor == null) {
        $pro_data = Prs_accessrule::join('users','prs_accessrules.slm_user','=','users.id')
        ->join('prs_leads','prs_accessrules.slm_lead','=','prs_leads.lds_id')
        ->join('prs_lead_statuses','prs_leads.lds_status','=','prs_lead_statuses.pls_id')
        ->where('slm_rules','master')
        ->orderBy('lds_close_date','desc')
        ->select('name','lds_title as title','lds_close_date as close_date','pls_status_name as status')
        ->first();
        $lastproject_data = $pro_data;
      }else{
        $last_purchase = Ord_purchase::where('pur_oppr_id',$last_oppor->opr_id)->select('pur_id')->first();
        if ($last_purchase == null) {
          $pro_data = Prs_accessrule::join('users','prs_accessrules.slm_user','=','users.id')
          ->join('opr_opportunities','prs_accessrules.slm_lead','opr_opportunities.opr_lead_id')
          ->join('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
          ->where('slm_rules','master')
          ->select('name','opr_title as title','opr_close_date as close_date','oss_status_name as status')
          ->first();
          $lastproject_data = $pro_data;
        }else{
          $pro_data = Prs_accessrule::join('users','prs_accessrules.slm_user','=','users.id')
          ->join('opr_opportunities','prs_accessrules.slm_lead','opr_opportunities.opr_lead_id')
          ->join('opr_stage_statuses','opr_opportunities.opr_status','=','opr_stage_statuses.oss_id')
          ->where('slm_rules','master')
          ->select('name','opr_title as title','opr_close_date as close_date','oss_status_name as status')
          ->first();
          $lastproject_data = $pro_data;
        }
      }
    }
    $person_ar = array();
    $colect_person = Cst_personal::where('cnt_cst_id', $id)->get();
    $dataContact = array();
    foreach ($colect_person as $key => $value) {
      switch ($value->view_option) {
        case 'PUBLIC':
          $dataContact[$key] = [
            'name' => $value->cnt_fullname,
            'role' => $value->cnt_company_position,
            'phone' => $this->dataMobile($value->cnt_id, 'INDIVIDUAL'),
            'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
            'option' => '<div style="display:flex;"> 
              <button type="button" onclick="previewContactCustomer(' . $value->cnt_id . ')" class="badge" style="margin-right:3px;"><i class="ri-eye-line"></i> </button>
              <a href="' . url('customer/detail-customer/person-update/' . $value->cnt_id) . '"><button type="button" class="badge bg-green"><i class="ri-edit-2-line"></i></button></a>
            </div>'
          ];
          break;
        case 'MODERATE':
          if ($value->created_by == $auth->id) {
            $dataContact[$key] = [
              'name' => $value->cnt_fullname,
              'role' => $value->cnt_company_position,
              'phone' => $this->dataMobile($value->cnt_id, 'INDIVIDUAL'),
              'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
              'option' => 'Edit'
            ];
          } else {
            $dataContact[$key] = [
              'name' => $value->cnt_fullname,
              'role' => $value->cnt_company_position,
              'phone' => $this->dataMobile($value->cnt_id, 'INDIVIDUAL'),
              'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
              'option' => '-'
            ];
          }
          break;
        case 'PRIVATE':
          if ($value->created_by == $auth->id) {
            $dataContact[$key] = [
              'name' => $value->cnt_fullname,
              'role' => $value->cnt_company_position,
              'phone' => $this->dataMobile($value->cnt_id, 'INDIVIDUAL'),
              'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
              'option' => 'Edit'
            ];
          }
          break;

        default:
          # code...
          break;
      }
    }
    return view('contents.page_customer.detail_customer', compact('cstId', 'id', 'company', 'location_ar', 'email', 'phone', 'dataContact'));
  }

  /* Tags:... */
  public function viewContactCustomer(Request $request)
  {
    $id = $request->idcs;
    return view('contents.page_customer.contacts',compact('id'));
  }
  /* Tags:... */
  public function viewContactDetail(Request $request)
  {
    $auth = Auth::user();
    $id = $request->id;
    $data_personal = Cst_personal::join('cst_customers', 'cst_personals.cnt_cst_id','=', 'cst_customers.cst_id')
    ->where('cnt_id',$request->id)
    // ->select('cst_name', 'cnt_fullname', 'cnt_company_position', 'view_option', 'cnt_notes')
    ->first();
    #telephon
    $data_telephone = Cst_contact_phone::where('pho_cnt_id',$request->id)
    ->get();
    $str_phone = "";
    foreach ($data_telephone as $key => $value) {
      $str_phone.="- ".$value->pho_number."<br>";
    }
    $data_mobile = Cst_contact_mobile::where("mob_cnt_id", $request->id)
    ->get();
    $str_mobile ="";
    foreach ($data_mobile as $key => $value) {
      $str_mobile .= "- " . $value->mob_number . "<br>";
    }
    $data_email = Cst_contact_email::where('eml_cnt_id',$request->id)
    ->get();
    $str_email = "";
    foreach ($data_email as $key => $value) {
      $str_email .= "- " . $value->eml_address . "<br>";
    }
    // dd($data_personal);
    // die();
    return view('contents.page_customer.contact_info', compact('id','data_personal', 'str_phone', 'str_mobile', 'str_email'));
  }
  /* Tags:... */
  public function deleteContact(Request $request)
  {
    #code...
  }
  public function activityCustomer(Request $request)
  {
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
			$lead_data = Prs_accessrule::whereIn('slm_rules',['colaborator','master','manager'])->where('slm_user',$user->id)->select('slm_lead')->get()->toArray();
			$lds_idr = array();
			foreach ($lead_data as $key => $value) {
				$lds_idr[$key] = $value['slm_lead'];
			}
			$lds_ids = array_unique($lds_idr);
			$all_activities = Act_activity::join('cst_customers','act_activities.act_cst','=','cst_customers.cst_id')
			->join('act_activity_types','act_activities.act_todo_type_id','=','act_activity_types.aat_id')
      ->where('act_cst', $id)
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
      ->where('act_cst', $id)
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
      ->where('act_cst', $id)
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
    return view('contents.page_customer.detail_customer_activity', compact(
			'id','activity_type','cnt_todo','cnt_phone','cnt_email','cnt_visit','cnt_poc','cnt_webinar','cnt_video_call','cnt_total','user_all','user','customer_all','company'
    ));
  }
  ###
  public function viewCustomerLead(Request $request)
  {
    $user = Auth::user();
    $id = $request->id;
    $company = Cst_customer::join('cst_institutions','cst_institutions.ins_id','=','cst_customers.cst_institution')
    ->leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->where('cst_id', $id)
    ->select('cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes','ins_name')
    ->first();
    $leadStatus = Prs_lead_status::get();
    $company = Cst_customer::leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->where('cst_id', $request->id)
    ->select('cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes')
    ->first();
    $all_users = User::get();
    $personal_contact = Cst_personal::where('cnt_cst_id',$id)->get();
    $status_configs = Prs_lead_status::where('pls_user_profile',$user->id)->get();
    return view('contents.page_customer.detail_customer_lead', compact('id','leadStatus', 'company','status_configs','user','all_users','personal_contact','company'));
  }
  ###
  public function sourceDataInvidu(Request $request)
  {
    $data = Cst_personal::where('cnt_id',$request->param)->first();
    $email = $this->dataEmail($data->cnt_id,'INDIVIDUAL');
    $mobile = $this->dataMobile($data->cnt_id, 'INDIVIDUAL');
    $phone = $this->dataPhone($data->cnt_id, 'INDIVIDUAL');
    $location = $this->dataLocation($data->cnt_id,'INDIVIDUAL');
    $em = '';
    $mo = '';
    $pn = '';
    $cemail = count($email) - 1;
    $cmobile = count($mobile)-1;
    $cphone = count($phone)-1;
    foreach ($email as $key => $value) {
      if ($key < $cemail) {
        $em.= '<a href = "mailto:'.$value.'">'.$value.'</a>, ';
      }else {
        $em .= '<a href = "mailto:' . $value . '">' . $value . '</a>';
      }
    }
    foreach ($mobile as $key => $value) {
      if ($key < $cmobile) {
        $mo.= '<a href = "tel:' . $value . '">' . $value . '</a>, ';
      }else {
        $mo .= '<a href = "tel:' . $value . '">' . $value . '</a>' ;
      }
    }
    foreach ($phone as $key => $value) {
      if ($key < $cphone) {
        $pn.= '<a href = "tel:' . $value . '">' . $value . '</a>, ';
      }else {
        $pn .= '<a href = "tel:' . $value . '">' . $value . '</a>';
      }
    }
    $res = [
      'name' => $data->cnt_fullname,
      'role' => $data->cnt_company_position,
      'phone' => $pn,
      'mobile' => $mo,
      'email' => $em,
      'location' => $location,
      'note' => html_entity_decode($data->cnt_notes) 
    ];
    return $res;    
  }
  ###
  public function dataLocation($id,$param)
  {
    $location = Cst_location::where('loc_cst_id', $id)
    ->where('loc_represent', $param)
    ->select('loc_street', 'loc_district', 'loc_city', 'loc_province')
    ->first();
    if ($location == null) {
      return '-';
    }else{
      $adr = $location->loc_street.'<br>'.$location->loc_district.', '. $location->loc_city.', '. $location->loc_province;
      return $adr;
    }
  }
  ###
  public function dataPhone($id,$param)
  {
    $data = Cst_contact_phone::where('pho_param', $param)->where('pho_cnt_id', $id)->select('pho_number')->get();
    $dt = array();
    foreach ($data as $key => $value) {
      $dt[$key] = $value->pho_number;
    }
    return $dt;
  }
  ###
  public function dataEmail($id,$param)
  {
    $data = Cst_contact_email::where('eml_param', $param)->where('eml_cnt_id', $id)->select('eml_address')->get();
    $dt = array();
    foreach ($data as $key => $value) {
      $dt[$key] = $value->eml_address;
    }
    return $dt;
  }
  ###
  public function dataMobile($id,$param)
  {
    $data = Cst_contact_mobile::where('mob_param', $param)->where('mob_cnt_id', $id)->select('mob_number')->get();
    $dt = array();
    foreach ($data as $key => $value) {
      $dt[$key] = $value->mob_number;
    }
    return $dt;
  }
  ###
  public function actionPageCustomerInformation(Request $request)
  {
    $auth = Auth::user();
    $id = $request->id;
    $company = Cst_customer::leftjoin('users','cst_customers.created_by','=','users.id')
    ->where('cst_id', $request->id)
    ->select('cst_name','name as creator','cst_business_field','cst_web','cst_notes')
    ->first();
    $location = Cst_location::where('loc_cst_id',$id)
    ->where('loc_represent','INSTITUTION')
    ->select('loc_street','loc_district','loc_city','loc_province')
    ->first();
    if ($location == null) {
      $location_ar = array();
    }else {
      $location_ar = [
        0 => Str::title($location->loc_street),
        1 => Str::title($location->loc_district),
        2 => Str::title($location->loc_city),
        3 => Str::title($location->loc_province),
      ];
    }
    $email = Cst_contact_email::where('eml_cnt_id',$id)
    ->where('eml_param','INSTITUTION')
    ->select('eml_address')
    ->get();
    $phone = Cst_contact_phone::where('pho_cnt_id',$id)
    ->where('pho_param','INSTITUTION')
    ->select('pho_number')
    ->get();
    $colect_person = null;
    $person_ar = array();
    $colect_person = Cst_personal::where('cnt_cst_id',$id)->get();
    $dataContact = array();
    foreach ($colect_person as $key => $value) {
      switch ($value->view_option) {
        case 'PUBLIC':
          $dataContact[$key] = [
            'name' => $value->cnt_fullname,
            'role' => $value->cnt_company_position,
            'phone' => $this->dataMobile($value->cnt_id,'INDIVIDUAL'),
            'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
            'option' => '<div style="display:flex;"> 
              <button type="button" onclick="previewContactCustomer('.$value->cnt_id. ')" class="badge" style="margin-right:3px;"><i class="ri-eye-line"></i> </button>
              <a href="'.url('customer/detail-customer/person-update/'. $value->cnt_id).'"><button type="button" class="badge bg-green"><i class="ri-edit-2-line"></i></button></a>
            </div>'
          ];
          break;
        case 'MODERATE':
          if ($value->created_by == $auth->id) {
            $dataContact[$key] = [
              'name' => $value->cnt_fullname,
              'role' => $value->cnt_company_position,
              'phone' => $this->dataMobile($value->cnt_id, 'INDIVIDUAL'),
              'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
              'option' => 'Edit'
            ];
          }else {
            $dataContact[$key] = [
              'name' => $value->cnt_fullname,
              'role' => $value->cnt_company_position,
              'phone' => $this->dataMobile($value->cnt_id, 'INDIVIDUAL'),
              'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
              'option' => '-'
            ];
          }
          break;
        case 'PRIVATE':
          if ($value->created_by == $auth->id) {
            $dataContact[$key] = [
              'name' => $value->cnt_fullname,
              'role' => $value->cnt_company_position,
              'phone' => $this->dataMobile($value->cnt_id, 'INDIVIDUAL'),
              'email' => $this->dataEmail($value->cnt_id, 'INDIVIDUAL'),
              'option' => 'Edit'
            ];
          }
          break;
        
        default:
          # code...
          break;
      }
    }
    return response()->json([
      'html' => view('contents.page_customer.ext_page_customer_information', compact('id','company','location_ar','email','phone','dataContact'))->render()
    ]);
  }
  ###
  public function actionPageCustomerActivities(Request $request)
  {
    return response()->json([
      'html' => view('contents.page_customer.ext_page_customer_activity')->render()
    ]);
  }
  ###
  public function actionPageCustomerLeads(Request $request)
  {
    return response()->json([
      'html' => view('contents.page_customer.ext_page_customer_lead')->render()
    ]);
  }
  /* Tags:... */
  public function actionPageCustomerOpportunity(Request $request)
  {
    $id = $request->id;
    $company = Cst_customer::join('cst_institutions','cst_institutions.ins_id','=','cst_customers.cst_institution')
    ->leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->where('cst_id', $id)
    ->select('cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes','ins_name')
    ->first();
    $user = Auth::user();
		$user_all = User::whereIn('level',['MKT','MGR.PAS','MGR','AGM','TCK'])->get();
    $personal= Cst_personal::where('cnt_cst_id',$id)->get();
    $project =Prs_lead::where('lds_customer',$id)->where('lds_status','3')->get();
    return view('contents.page_customer.detail_customer_opportunity', compact(
			'id','project','user_all','user','personal','company'
    ));
  }
  public function actionPageCustomerPurchase(Request $request)
  {
    $id = $request->id;
    $company = Cst_customer::join('cst_institutions', 'cst_institutions.ins_id', '=', 'cst_customers.cst_institution')
    ->leftjoin('users', 'cst_customers.created_by', '=', 'users.id')
    ->where('cst_id', $id)
      ->select('cst_name', 'name as creator', 'cst_business_field', 'cst_web', 'cst_notes', 'ins_name')
      ->first();
    $user = Auth::user();
    $user_all = User::whereIn('level', ['MKT', 'MGR.PAS', 'MGR', 'AGM', 'TCK'])->get();
    $personal = Cst_personal::where('cnt_cst_id', $id)->get();
    $project = Prs_lead::where('lds_customer', $id)->where('lds_status', '3')->get();
    return view('contents.page_customer.detail_customer_purchase', compact(
      'id',
      'project',
      'user_all',
      'user',
      'personal',
      'company'
    ));
  }
  ###
  public function actionPageCustomerOpportunities(Request $request)
  {
    return response()->json([
      'html' => view('contents.page_customer.ext_page_customer_opportunity')->render()
    ]);
  }
  ###
  public function actionPageCustomerPurchased(Request $request)
  {
    return response()->json([
      'html' => view('contents.page_customer.ext_page_customer_purchased')->render()
    ]);
  }
  ###
  public function viewFormCreateCustomer()
  {
    $lead_status = Prs_lead_status::get();
    $business_fields = Cst_bussiness_field::get();
    // $products = Prd_subproduct::get();
    $customer = Cst_customer::select('cst_id','cst_name')->orderBy('cst_name','asc')->get();
    $institution = Cst_institution::get();
    $provincies = Addr_province::get();
    return view('contents.page_customer.form_create_customer',compact('customer','business_fields','lead_status','institution','provincies'));
  }
  ###
  public function viewFormCreateCustomerFixed(Request $request)
  {
    $company = Cst_customer::where('cst_id',$request->id)->first();
    $lead_status = Prs_lead_status::get();
    $business_fields = Cst_bussiness_field::get();
    $products = Prd_product::get();
    $id = $request->id;
    return view('contents.page_customer.form_create_customer_fixed', compact('business_fields', 'lead_status', 'products', 'company','id'));
  }
  ###
  public function updateCompanyData(Request $request)
  {
    $id = $request->id;
    $lead_status = Prs_lead_status::get();
    $business_fields = Cst_bussiness_field::get();
    $company = Cst_customer::where('cst_id', $request->id)->first();
    $phone = $this->dataPhone($request->id,'INSTITUTION');
    $email = $this->dataEmail($request->id,'INSTITUTION');
    $mobile = $this->dataMobile($request->id,'INSTITUTION');
    $district = Addr_district::get();
    $location = Cst_location::where('loc_cst_id',$id)->first();
    return view('contents.page_customer.form_update_customer',compact('id','business_fields', 'company','phone','email','mobile','location','district'));
  }
  ###
  public function storeUpdateCustomer(Request $request)
  {
    $id_cst = $request->id;
    $user = Auth::user();
    $data = [
      'cst_name' => $request->institution_name,
      'cst_web' => '',
      'cst_business_field' => $request->business_category,
      'cst_notes' => $request->notes,
      'view_option' => $request->view_option,
    ];
    $store_update_companies = Cst_customer::where('cst_id',$id_cst)->update($data);
    foreach ($request->mobile as $key => $value) {
      $mobiles[$key] = [
        'mob_cnt_id' => $id_cst,
        'mob_param' => 'INSTITUTION',
        'mob_number' => $value,
        'created_by' => $user->id
      ];
    }
    $delete_stored_mobile = Cst_contact_mobile::where('mob_param','INSTITUTION')->where('mob_cnt_id',$id_cst)->delete();
    $store_new_mobile = Cst_contact_mobile::insert($mobiles);
    foreach ($request->phone as $key => $value) {
      $phones[$key] = [
        'pho_cnt_id' => $id_cst,
        'pho_param' => 'INSTITUTION',
        'pho_number' => $value,
        'created_by' => $user->id
      ];
    }
    $delete_stored_phone = Cst_contact_phone::where('pho_param','INSTITUTION')->where('pho_cnt_id',$id_cst)->delete();
    $store_new_phone = Cst_contact_phone::insert($phones);
    foreach ($request->email as $key => $value) {
      $emails[$key] = [
        'eml_cnt_id' => $id_cst,
        'eml_param' => 'INSTITUTION',
        'eml_address' => $value,
        'created_by' => $user->id
      ];
      $delete_stored_email = Cst_contact_email::where('eml_param','INSTITUTION')->where('eml_cnt_id',$id_cst)->delete();
      $store_new_email = Cst_contact_email::insert($emails);
    }
    $location = [
      'loc_cst_id' => $id_cst,
      'loc_represent' => 'INSTITUTION',
      'loc_street' => $request->addr_street,
      'loc_district' => $request->district,
      'loc_city' => $request->city,
      'loc_province' => $request->province,
      'created_by' => $user->id
    ];
    $delete_stored_address = Cst_location::where('loc_represent','INSTITUTION')->where('loc_cst_id',$id_cst)->delete();
    $store_new_address = Cst_location::insert($location);
    return 1;
  }
  ###
  public function storeUpdatePersonal(Request $request)
  {
    $id_person = $request->id;
    $user = Auth::user();
    $data = [
      'cnt_fullname' => $request->institution_name,
      'cnt_company_position' => $request->job_position,
      'cnt_notes' => $request->notes,
      'updated_by' => $user->id, 
      'view_option' => $request->view_option,
    ];
    $store_update_personal = Cst_personal::where('cnt_id', $id_person)->update($data);
    foreach ($request->mobile as $key => $value) {
      $mobiles[$key] = [
        'mob_cnt_id' => $id_person,
        'mob_param' => 'INDIVIDUAL',
        'mob_number' => $value,
        'created_by' => $user->id
      ];
    }
    $delete_stored_mobile = Cst_contact_mobile::where('mob_param', 'INDIVIDUAL')->where('mob_cnt_id', $id_person)->delete();
    $store_new_mobile = Cst_contact_mobile::insert($mobiles);
    foreach ($request->phone as $key => $value) {
      $phones[$key] = [
        'pho_cnt_id' => $id_person,
        'pho_param' => 'INDIVIDUAL',
        'pho_number' => $value,
        'created_by' => $user->id
      ];
    }
    $delete_stored_phone = Cst_contact_phone::where('pho_param', 'INDIVIDUAL')->where('pho_cnt_id', $id_person)->delete();
    $store_new_phone = Cst_contact_phone::insert($phones);
    foreach ($request->email as $key => $value) {
      $emails[$key] = [
        'eml_cnt_id' => $id_person,
        'eml_param' => 'INDIVIDUAL',
        'eml_address' => $value,
        'created_by' => $user->id
      ];
      $delete_stored_email = Cst_contact_email::where('eml_param', 'INDIVIDUAL')->where('eml_cnt_id', $id_person)->delete();
      $store_new_email = Cst_contact_email::insert($emails);
    }
    $location = [
      'loc_cst_id' => $id_person,
      'loc_represent' => 'INDIVIDUAL',
      'loc_street' => $request->addr_street,
      'loc_district' => $request->district,
      'loc_city' => $request->city,
      'loc_province' => $request->province,
      'created_by' => $user->id
    ];
    $delete_stored_address = Cst_location::where('loc_represent', 'INDIVIDUAL')->where('loc_cst_id', $id_person)->delete();
    $store_new_address = Cst_location::insert($location);
    return true;
  }
  ###
  public function updatePersonData(Request $request)
  {
    $id = $request->id;
    $dataperson = Cst_personal::join('cst_customers','cst_personals.cnt_cst_id','=', 'cst_customers.cst_id')
      ->where('cnt_id',$id)
      ->select('cst_personals.view_option as view_option_individu','cnt_id', 'cnt_fullname', 'cnt_company_position', 'cnt_notes', 'cnt_cst_id', 'cst_name')
      ->first();
    $company = Cst_customer::select('cst_id','cst_name')->get();
    $lead_status = Prs_lead_status::get();
    $business_fields = Cst_bussiness_field::get();
    // $products = Prd_subproduct::get();
    $phone = $this->dataPhone($request->id, 'INDIVIDUAL');
    $email = $this->dataEmail($request->id, 'INDIVIDUAL');
    $mobile = $this->dataMobile($request->id, 'INDIVIDUAL');
    // echo $dataperson;
    // die();
    $district = Addr_district::get();
    $location = Cst_location::where('loc_cst_id', $id)->where('loc_represent','INDIVIDUAL')->first();
    return view('contents.page_customer.form_update_person', compact('id','location','company', 'dataperson','mobile','phone','email'));
  }
  ###
  ###
  public function dropzoneStore(Request $request)
  {
    $image = $request->file('file');
    $imageName = $image->getClientOriginalName();
    // $imageName = time() . '-' . strtoupper(Str::random(10)) . '.' . $image->extension();
    $image->move(public_path('tmp_dropzone'), $imageName);
    return response()->json(['success' => $imageName]);
  }
  ###
  public static function recheckCustomerCompany($institution_name)
  {
    $lower = Str::lower($institution_name);
    // die($lower);
    $codename = is_numeric($lower);
    if ($codename == true) {
      $company = Cst_customer::where('cst_id', $lower)->select('cst_id', 'cst_string_id')->first();
      $outVal = [
        'cst_string_id' => $company->cst_string_id,
        'cst_id' => $lower
      ];
    }else {
      $string_code = Str::snake($lower);
      $outVal = [
        'cst_string_id' => $string_code,
        'cst_id' =>  genIdCompany()
      ];
    }
    return $outVal;
  }
  ###
  public function storeCreateCustomer(Request $request)
  {
    $user = Auth::user();
    $tmp_ins_id = $request->institution_name;
    $tmp_cst_id = $request->customer_name;
    if ($tmp_ins_id == null || $tmp_cst_id == null) {
      $result = [
        'param'=>false,
      ];
      return $result;
    }else {
      if (is_numeric($request->institution_name)) {
        $ins_id = $request->institution_name;
      }else{
        $ins_id = genIdInstitution();
        $new_data_institution = [
          'ins_id' => $ins_id,
          'ins_name' => $request->institution_name,
          'ins_note' => $request->notes,
          'ins_business_field' => $request->business_category,
          'ins_notes' => $request->notes,
          'created_by' => $user->id,
        ];
        $res_Cst_institution = Cst_institution::insertOrIgnore($new_data_institution);
      }
      if (is_numeric($request->customer_name)) {
        $cst_id = $request->customer_name;
      }else{
        $cst_id = genIdCompany();
        $new_data_customer = [
          'cst_id' => $cst_id,
          'cst_name' => $request->customer_name,
          'cst_institution' => $ins_id,
          'cst_string_id' => Str::snake($request->customer_name, '_'),
          'cst_web' => $request->web,
          'cst_business_field' => $request->business_category,
          'cst_notes' => null,
          'created_by' => $user->id,
        ];
        $res_Cst_institution = Cst_customer::insertOrIgnore($new_data_customer);
      }
    }
    if ($request->cststatus == 'individual') {
      if ($request->view_option == null ||  $request->view_option == "") {
        $view = "PUBLIC";
      } else {
        $view = $request->view_option;
      }
      $cst_init = genIdPerson();
      $new_data_individual = [
        'cnt_id' => $cst_init,
        'cnt_cst_id' => $cst_id,
        'cnt_fullname' => $request->person_name,
        'cnt_nickname' => null,
        'cnt_company_position' => $request->job_position,
        'cnt_notes' => null,
        'view_option' => $view,
        'created_by' => $user->id
      ];
      $res_cst_personal = Cst_personal::insert($new_data_individual);
    }else{
      $cst_init = $cst_id;
    }
    if ($request->addr_street != null || $request->addr_district != null || $request->addr_city != null || $request->addr_province != null ) {
      $new_data_address = [
        'loc_cst_id' => $cst_id,
        'loc_represent' => Str::upper($request->cststatus),
        'loc_street' => $request->addr_street,
        'loc_district' => $request->addr_district,
        'loc_city' => $request->addr_city,
        'loc_province' => $request->addr_province,
        'created_by' => $user->id
      ];
      $res_cst_address = Cst_location::insert($new_data_address);
    }
    $first_val_mobile = head($request->mobile);
    if ($first_val_mobile != null or $first_val_mobile != "") {
      foreach ($request->mobile as $key => $value) {
        $data_mobile[$key] = [
          'mob_cnt_id' =>$cst_init,
          'mob_param' => Str::upper($request->cststatus),
          'mob_label' => null,
          'mob_number' => $value,
          'created_by' => $user->id
        ];
      }
      $res_cst_mobile = Cst_contact_mobile::insert($data_mobile);
    }
    $first_val_email = head($request->email);
    if ($first_val_email != null or $first_val_email != "") {
      foreach ($request->email as $key => $value) {
        $data_email[$key] = [
          'eml_cnt_id' => $cst_init,
          'eml_param' => Str::upper($request->cststatus),
          'eml_label' => null,
          'eml_address' => $value,
          'created_by' => $user->id
        ];
      }
      $res_cst_mobile = Cst_contact_email::insert($data_email);
    }
    $first_val_phone = head($request->phone);
    if ($first_val_phone != null or $first_val_phone != "") {
      foreach ($request->phone as $key => $value) {
        $data_phone[$key] = [
          'pho_cnt_id' => $cst_init,
          'pho_param' =>  Str::upper($request->cststatus),
          'pho_label' => null,
          'pho_number' => $value,
          'created_by' => $user->id
        ];
      }
      $res_cst_phone = Cst_contact_phone::insert($data_phone);
    }
    $result = [
      'param'=>true,
      'cst_id' => $cst_id
    ];
    return $result;
  }
  ###
  /* Tags:... */
  public function actionGetPersonContact(Request $request)
  {
    $persons = Cst_personal::where('cnt_cst_id',$request->id)->get();
    $data = array();
    foreach ($persons as $key => $value) {
      $data[$key] = [
        'id' => $value->cnt_id,
        'title' => $value->cnt_fullname
      ];
    }
    $result = [
      'param'=>true,
      'data' => $data
    ];
    return $result;
  }
  /* Tags:... */
  public function actionGeActivitiContact(Request $request)
  {
    $persons = Cst_personal::where('cnt_id',$request->idcnt)->first();
    $mobile = Cst_contact_mobile::where('mob_cnt_id',$request->idcnt)->get();
    $data_mobile = array();
    foreach ($mobile as $key => $value) {
      $data_mobile[$key] = $value->mob_number;
    }
    $data_email = array();
    $email = Cst_contact_email::where('eml_cnt_id',$request->idcnt)->get();
    foreach ($email as $key => $value) {
      $data_email[$key] = $value->eml_address;
    }
    $data_telephone = array();
    $telephone = Cst_contact_phone::where('pho_cnt_id',$request->idcnt)->get();
    foreach ($telephone as $key => $value) {
      $data_telephone[$key] = $value->pho_number;
    }
    $data_address = array();
    $pic_contact = [
      'cnt_id' => $request->idcnt,
      'cnt_name' => $persons->cnt_fullname,
      'cnt_position' => $persons->cnt_company_position,
      'cnt_mobile' => implode(', ',$data_mobile),
      'cnt_email' => implode(', ',$data_email),
      'cnt_phone' => implode(', ', $data_telephone),
      'cnt_address' => null,
    ];
    $result = [
      'param'=>true,
      'contact' => $pic_contact
    ];
    return $result;
  }
  /* Tags:... */
  public function actionGetSubcustomer(Request $request)
  {
    $customer = Cst_customer::where('cst_institution',$request->id)->get();
    foreach ($customer as $key => $value) {
      $data[$key] = [
        'id' => $value->cst_id,
        'title' => $value->cst_name
      ];
    }
    $result = [
      'param'=>true,
      'data' => $data
    ];
    return $result;
  }
  /* Tags:... */
  public function actionGetCity(Request $request)
  {
    $prov = Addr_province::where('prov_name',$request->id)->first();
    $city = Addr_city::where('prov_id',$prov->prov_id)->get();
    foreach ($city as $key => $value) {
      $data[$key] = [
        'id' => $value->city_name,
        'title' => $value->city_name
      ];
    }
    $result = [
      'param'=>true,
      'data' => $data
    ];
    return $result;
  }
  /* Tags:... */
  public function actionGetDistrict(Request $request)
  {
    $city = Addr_city::where('city_name',$request->id)->get();
    $ids = array();
    foreach ($city as $key => $value) {
      $ids[$key] = $value->city_id;
    }
    $district = Addr_district::whereIn('city_id',$ids)->get();
    foreach ($district as $key => $value) {
      $data[$key] = [
        'id' => $value->dis_name,
        'title' => $value->dis_name
      ];
    }
    $result = [
      'param'=>true,
      'data' => $data
    ];
    return $result;
  }
}

