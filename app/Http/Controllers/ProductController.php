<?php

namespace App\Http\Controllers;

use App\Models\Prd_principle;
use App\Models\Prd_product;
use App\Models\Prd_subproduct;
use Illuminate\Http\Request;
use DataTables;

class ProductController extends Controller
{
	/* Tags:... */
	public function viewProducts(Request $request)
	{
		return view('contents.page_product.product');
	}

	/* Tags:... */
	public function viewPrinciple(Request $request)
	{
		return view('contents.page_product.principle');
	}
	public function ajaxProductValue(Request $request)
	{
		$product_value = Prd_subproduct::where('psp_id',$request->id)->select('psp_estimate_value')->first();
		$invalue = $product_value->psp_estimate_value * $request->cnt;
		$outvalue = rupiahFormat($invalue);
		return $outvalue;
	}
	/* Tags:... */
	public function sourceProduct(Request $request)
	{
		$data = Prd_product::leftjoin('prd_principles','prd_products.psp_product_id','=','prd_principles.prd_id')->get();
		
		return DataTables::of($data)
		->addIndexColumn()
		->addColumn('empty_str', function ($k) {
			return '';
		})
		->addColumn('menu', function ($data) {
			return '
			<div style="text-align:center;">
			<button type="button" class="badge bg-cyan" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="ri-list-settings-line"></i></button>
			<div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 38px);">
			<a class="dropdown-item" onclick="actionDetailProduct('.$data->psp_id.')" ><i class="ri-folder-user-line" style="margin-right:6px;"></i>Detail Product</a>
      </div>
			</div>
			';
		})
		->addColumn('product', function ($data) {
			return $data->psp_subproduct_name;
		})
		->addColumn('principle', function ($data) {
			if ($data->prd_name == null) {
				return '-';
			}else{
				return $data->prd_name;
			}
		})
		->addColumn('category', function ($data) {
			if ($data->psp_category == null) {
				return '-';
			}else{
				return $data->psp_category;
			}
		})
		->rawColumns(['menu','product', 'principle','category'])
		->make('true');
	}
	public function sourcePrinciple	(Request $request)
	{
		$data = Prd_principle::get();
		return DataTables::of($data)
		->addIndexColumn()
		->addColumn('empty_str', function ($k) {
			return '';
		})
		->addColumn('menu', function ($data) {
			return '
			<div style="text-align:center;">
			<button type="button" class="badge bg-cyan" aria-expanded="true" onclick="actionDetailPrinciple('.$data->prd_id.')">View</button>
			</div>
			';
		})
		->addColumn('principle', function ($data) {
			if ($data->prd_name == null) {
				return '-';
			}else{
				return $data->prd_name;
			}
		})
		->addColumn('describe', function ($data) {
			if ($data->prd_describe == null) {
				return '-';
			}else{
				return $data->prd_describe;
			}
		})
		->rawColumns(['menu','product', 'principle','describe'])
		->make('true');
	}
	/* Tags:... */
	public function checkDataPrinciple(Request $request)
	{
		$data = Prd_principle::get();
		$list = array();
		foreach ($data as $key => $value) {
			$list[$key] = [
				'id'=> $value->prd_id,
				'title' => $value->prd_name,
			];
		}
		$result = [
			'param'=>true,
			'data' => $list,
		];
		return $result;
	}
	public function checkDataPrincipleItem(Request $request)
	{
		$data = Prd_principle::where('prd_id',$request->id)->first();
		if ($data->prd_describe == null) {
			$describe = '<p></p>';
		} else {
			$describe = $data->prd_describe;
		}
		
		$result = [
			'param'=>true,
			'prd_id'=> $data->prd_id,
			'name'=> $data->prd_name,
			'describe' => $describe,
		];
		return $result;
	}
	/* Tags:... */
	public function storeProduct(Request $request)
	{
		if (!is_numeric($request->principle)) {
			$id_principle =  genIdPrinciple();
			$data_principle = [
				'prd_id'=> $id_principle,
				'prd_name' => $request->principle,
			];
			$actionStorePrinciple = Prd_principle::insert($data_principle);
		}else{
			$id_principle = $request->principle;
		}
		$id = genIdProduct();
		$data = [
			'psp_id'=> $id,
			'psp_product_id' => $id_principle,
			'psp_subproduct_name' => $request->product,
			'psp_category' => $request->category,
			'psp_describe' => $request->describe_product,
		];
		
		$actionStoreProduct = Prd_product::insert($data);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	public function storeProductUpdate(Request $request)
	{
		$id = $request->id_product;
		if (!is_numeric($request->principle)) {
			$id_principle =  genIdPrinciple();
			$data_principle = [
				'prd_id'=> $id_principle,
				'prd_name' => $request->principle,
			];
			$actionStorePrinciple = Prd_principle::insert($data_principle);
		}else{
			$id_principle = $request->principle;
		}
		$data = [
			'psp_product_id' => $id_principle,
			'psp_subproduct_name' => $request->product,
			'psp_category' => $request->category,
			'psp_describe' => $request->describe_product,
		];
		
		$actionStoreProduct = Prd_product::where('psp_id',$id)->update($data);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function checkDataProductItem(Request $request)
	{
		$data = Prd_product::join('prd_principles','prd_products.psp_product_id','=','prd_principles.prd_id')
		->where('prd_products.psp_id','=', $request->id)
		->first();
		if ($data->psp_describe == null) {
			$notes = '<p></p>';
		} else {
			$notes = $data->psp_describe;
		}
		
		$result = [
			'param'=>true,
			'product_id' => $data->psp_id,
			'principle_id' => $data->psp_product_id,
			'product_name' => $data->psp_subproduct_name,
			'product_category' => $data->psp_category,
			'product_note'=> $notes,
		];
		return $result;
	}
	/* Tags:... */
	public function storePrinciple(Request $request)
	{
		$id_principle =  genIdPrinciple();
		$data_principle = [
			'prd_id'=> $id_principle,
			'prd_name' => $request->principle,
			'prd_describe' => $request->describe_principle
		];
		$actionStorePrinciple = Prd_principle::insert($data_principle);
		$result = [
			'param'=>true,
		];
		return $result;
	}
	/* Tags:... */
	public function storePrincipleUpdate(Request $request)
	{
		$data_principle = [
			'prd_name' => $request->principle,
			'prd_describe' => $request->describe_principle
		];
		$actionStorePrinciple = Prd_principle::where('prd_id',$request->id)->update($data_principle);
		$result = [
			'param'=>true,
		];
		return $result;
	}
}
