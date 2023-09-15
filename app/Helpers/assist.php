<?php

use App\Models\Act_activity;
use App\Models\Cst_personal;
use App\Models\Cst_company;
use App\Models\Cst_customer;
use App\Models\Cst_institution;
use App\Models\Cst_location;
use App\Models\Prs_lead;
use App\Models\Opr_opportunity;
use App\Models\User;
use Illuminate\Support\Facades\Blade;


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
  $informat = number_format($value, 2, ",", ".");
  $outformat = "Rp".$informat;
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