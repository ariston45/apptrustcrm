<?php

use App\Models\Act_activity;
use App\Models\Cst_personal;
use App\Models\Cst_company;
use App\Models\Cst_customer;
use App\Models\Cst_institution;
use App\Models\Cst_location;
use App\Models\Opr_value_product;
use App\Models\Prs_lead;
use App\Models\Opr_opportunity;
use App\Models\Opr_value_other;
use App\Models\Ord_purchase;
use App\Models\User;
use App\Models\User_structure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Query\Builder;

function auth_user(){
  $usr = Auth::user();
  $user = User::join('user_structures','users.id','=','user_structures.usr_user_id')
  ->leftJoin('user_teams','user_structures.usr_team_id','=','user_teams.uts_id')
  ->where('id',$usr->id)
  ->first();
  return $user;
}

function checkRule($arr_value){
  $user = Auth::user();
  if (is_array($arr_value)) {
    if (in_array($user->level,$arr_value)) {
      return true;
    }else {
      return false;
    }
  }else {
    return "Data must be array. exp: array('a','b','...')";
  }
}
function checkTeamMgr($id){
  $user= User_structure::where('usr_user_id',$id)->select('usr_team_id')->first();
  $user_structure = User_structure::where('usr_team_id',$user->usr_team_id)->select('usr_user_id')->get();
  $ids = array();
  foreach ($user_structure as $key => $value) {
    $ids[$key] = $value->usr_user_id;
  }
  return $ids;
}
function genIdUser(){
  $data = User::max('id');
  $new_id = $data + 1;
  return $new_id;
}
function genIdPerson()
{
  $data = Cst_personal::max('cnt_id');
  $new_id = $data + 1;
  return $new_id;
}
function genIdInstitution()
{
  $data = Cst_institution::max('ins_id');
  $new_id = $data + 1;
  return $new_id;
}
function genIdCompany()
{
  $data = Cst_customer::max('cst_id');
  $new_id = $data + 1;
  return $new_id;
}

function genIdLocation()
{
  $data = Cst_location::max('loc_id');
  $new_id = $data + 1;
  return $new_id;
}

function genIdLead()
{
  $data = Prs_lead::max('lds_id');
  $new_id = $data + 1;
  return $new_id;
}

function rupiahFormat($value)
{
  $informat = number_format($value, 3, ",", ".");
  $outformat = "Rp ".$informat;
  return $outformat;
}

function idenCustomer()
{
  $data = [
    'ids' => 1,
    'ids_string' => 2
  ];
  return $data;
}

function genIdActivity()
{
  $data = Act_activity::max('act_id');
  $new_id = $data + 1;
  return $new_id;
}

function fcurrency($value)
{
  return "Rp " . number_format($value, 2, ',', '.');
}

function genIdOpportunity()
{
  $data = Opr_opportunity::max('opr_id');
  $new_id = $data + 1;
  return $new_id;
}
function genIdProductList()
{
  $data = Opr_value_product::max('por_id');
  $new_id = $data + 1;
  return $new_id;
}
function asNumber($value) {
  $cleanedString = preg_replace("/[^0-9,]/", "", $value);
  $cleanedString = str_replace(",", ".", $cleanedString);
  $number = (float) $cleanedString;
  return $number;
}

function genIdOprValOther()
{
  $data = Opr_value_other::max('ots_id');
  $new_id = $data + 1;
  return $new_id;
}

function quickRandom($length){
  $random = Str::random($length);
  return $random;
}

function genIdPurchase(){
  $data = Ord_purchase::max('pur_id');
  $new_id = $data + 1;
  return $new_id;
}