<?php

namespace App\Http\Controllers;

use App\Models\Prd_product;
use App\Models\Prd_subproduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function ajaxProductValue(Request $request)
	{
		$product_value = Prd_subproduct::where('psp_id',$request->id)->select('psp_estimate_value')->first();
		$invalue = $product_value->psp_estimate_value * $request->cnt;
		$outvalue = rupiahFormat($invalue);
		return $outvalue;
	}
}
