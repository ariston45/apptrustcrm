@extends('layout.app')
@section('title')
opportunitys
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title">Opportunities</h4>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Opportunities</a></li>
<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="card">
	<div class="card-status-top bg-success"></div>
	<div class="card-header card-header-custom card-header-light">
		<h3 class="card-title">Detail Opportunity</h3>
		<div class="card-actions" style="padding-right: 10px;">
			<a href="{{ url('opportunities') }}">
				<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
					<div style="font-weight: 700;">
						<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
					</div>
				</button>
			</a>
		</div>
	</div>
	{{-- ========================================================================================================= --}}
	@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
	<div class="card-header card-header-custom" style="background-color: whitesmoke;">
		@if ($purchase_data == null)
		<div class="col-8" id="btn-g-status">
			<div id="btn-opportunity-status" class="btn-group" role="group" style="margin-right: 15px;">
				@foreach ($status as $list)
				<input type="button" id="btn-{{ $list->oss_status_code }}" 
					class="btn active pr-2 pb-1 pt-1 @if ($opportunity->opr_status == $list->oss_id) btn-ghost-primary @else btn-ghost-light bg-blue-lt @endif" 
					value="{{ $list->oss_status_name }}" onclick="actionChangeStatus('{{ $list->oss_status_code }}','{{ $list->oss_id }}')">
				</input>
				@endforeach
			</div>
			<button type="button" id="btn-go-po" class="btn active pr-2 pb-1 pt-1 ml-3 btn-ghost-success" onclick="actionCreatePO()" 
			@if ($opportunity->opr_status == 6) style="display: true;" @else style="display: none;" @endif >
				Create Purchase Order
			</button>
			<button type="button" id="btn-open-po" class="btn pr-2 pb-1 pt-1 ml-3" onclick="" style="display: none;">
				View Purchased Order
			</button>
		</div>
		<div class="col-4" id="btn-g-lock" style="text-align: right">
			<button type="button" id="btn-lock" class="btn active pr-2 pb-1 pt-1 btn-ghost-light bg-muted-lt" 
			@if ($opportunity->opr_status == 6) style="display: true;" @else style="display: none;" @endif> <i class="ri-lock-2-line"></i></button>
			<input type="button" id="btn-opr-status-lose" 
				class="btn active pr-2 pb-1 pt-1  @if ($opportunity->opr_status == 7) btn-ghost-danger @else btn-ghost-light bg-red-lt @endif" 
				value="Lose" onclick="actionChangeStatus('opr-status-lose','7')" @if ($opportunity->opr_status == 6) style="display: none;" @else style="display: true;" @endif>
			</input>
		</div>
		<div class="col-12" id="btn-g-page-purchase" style="display: none;">
			<button type="button" id="btn-open-po" class="btn pr-2 pb-1 pt-1 ml-3" onclick="actionOpenPO()">
				View Purchased Order
			</button>
		</div>
		@else
		<div class="col-12" id="btn-g-page-purchase">
			<button type="button" id="btn-open-po" class="btn pr-2 pb-1 pt-1 ml-3" onclick="actionOpenPO()">
				View Purchased Order
			</button>
		</div>
		@endif
	</div>
	@endif
	{{-- ======================================================================================================== --}}
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-1">
			<div class="col-12">
				<div class="row">
					<div class="col">
						<h3 class=" mb-1"> <b>{{ $opportunity->lds_title }}</b></h3>
					</div>
					<div class="col-auto">
						<span class="text-muted small"><i>Estimated Close: {{ $closing }}</i></span>
					</div>
				</div>
			</div>
			<div class="col-auto">
				<em class="text-muted lh-base mb-1"><i>Customer</i></em>
				<div class="page-pretitle-custom mb-1">{{ html_entity_decode($institution_names) }}</div>
				<em class="text-muted lh-base mb-1"><i>Sub Customer</i></em>
				@if ($lead_subcustomer === null)
					<div class="page-pretitle-custom mb-1">-</div>
				@else
					<div class="page-pretitle-custom mb-1">{{ html_entity_decode($lead_subcustomer->cst_name) }}</div>
				@endif
				<em class="text-muted lh-base mb-0"><i>Salesperson</i></em>
				@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
				<button class="badge bg-blue-lt ms-0 p-0" style="margin-bottom: 1px;" onclick="actionChangeSales()"><i class="ri-edit-2-line"></i></button>
				@endif
				<div class="page-pretitle-custom">
					{{ $sales_selected->name }}
				</div>
				<em class="text-muted lh-base mb-0"><i>Colaborator</i></em>
				@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
					<button class="badge bg-blue-lt ms-0 p-0" style="margin-bottom: 1px;" onclick="actionAddTeam()"><i class="ri-edit-2-line"></i></button>
				@endif
				<div class="page-pretitle-custom">
					@if ($member_name == null || $member_name == "")
					-
					@else
					{{ $member_name }}
					@endif
				</div>
				<em class="text-muted lh-base mb-0"><i>Technical</i></em>
				@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
					<button class="badge bg-blue-lt ms-0 p-0" style="margin-bottom: 1px;" onclick="actionAddTechnical()"><i class="ri-edit-2-line"></i></button>
				@endif
				<div class="page-pretitle-custom">
					@if ($tech_name == null || $tech_name == "")
					-
					@else
					{{ $tech_name }}
					@endif
				</div>
			</div>
			<div class="col" style="text-align: right;">
				<div class="row">
					<div class="col-12" style="text-align: center;">
						<img id="img-status-1" class="img-status" src="{{ asset('static/opportunity_status/opr-status-new.png') }}" alt="" 
						@if ($opportunity->opr_status == 1) style="display:true;" @else style="display:none;"	@endif>
						<img id="img-status-2" class="img-status" src="{{ asset('static/opportunity_status/opr-status-presentation.png') }}" alt="" 
						@if ($opportunity->opr_status == 2) style="display:true;" @else style="display:none;"	@endif>
						<img id="img-status-3" class="img-status" src="{{ asset('static/opportunity_status/opr-status-poc.png') }}" alt="" 
						@if ($opportunity->opr_status == 3) style="display:true;" @else style="display:none;"	@endif>
						<img id="img-status-4" class="img-status" src="{{ asset('static/opportunity_status/opr-status-proposal.png') }}" alt="" 
						@if ($opportunity->opr_status == 4) style="display:true;" @else style="display:none;"	@endif>
						<img id="img-status-5" class="img-status" src="{{ asset('static/opportunity_status/opr-status-negotiation.png') }}" alt="" 
						@if ($opportunity->opr_status == 5) style="display:true;" @else style="display:none;"	@endif>
						<img id="img-status-6" class="img-status" src="{{ asset('static/opportunity_status/opr-status-win.png') }}" alt="" 
						@if ($opportunity->opr_status == 6) style="display:true;" @else style="display:none;"	@endif>
						<img id="img-status-7" class="img-status" src="{{ asset('static/opportunity_status/opr-status-lose.png') }}" alt="" 
						@if ($opportunity->opr_status == 7) style="display:true;" @else style="display:none;"	@endif>
					</div>
				</div>
			</div>
		</div>
		{{-- <hr class="mt-1 mb-3"> --}}
	</div>
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-2">
			<div class="col" style="padding: auto;">
				<span class="form-label text-muted mb-0"  style="">Products And Services :</span>
			</div>
			<div class="col-auto">
				@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
					<button class="btn btn-primary btn-pill btn-sm" onclick="actionAddProduct()" style="width: 130px;"><i class="ri-add-line" style="margin-right: 5px;"></i> Add Product</button>
				@endif
			</div>
		</div>
		<div class="row mb-0">
			<div class="col-12">
				@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
				<table id="table-product-detail" class="table table-striped table-responsive">
					<thead>
						<tr>
							<th style="width: 2%"></th>
							<th style="width: 20%">Principle</th>
							<th style="width: 35%">Product</th>
							<th class="text-center" style="width: 5%">Qnt</th>
							<th class="text-end" style="width: 15%">Unit</th>
							<th class="text-end" style="width: 15%">Amount</th>
							<th style="width: 8%"></th>
						</tr>
					</thead>
					<tbody id="tab_products">
						@php
							$count_product = count($opr_product);
							$n_index_product_list = 1 + $count_product;
						@endphp
							@foreach ($opr_product as $list)
							<tr>
								<td class="text-center">{{ $list['no'] }}</td>
								<td><span class="strong"><span id="opr_principle_{{ $list['id'] }}">{{ $list['principle'] }}</span></span></td>
								<td>
									<div class="strong"><span id="opr_product_{{ $list['id'] }}">{{ $list['product'] }}</span> </div>
									@if ($list['note'] == ''||$list['note'] == null)
									<span id="opr_product_note{{ $list['id'] }}" class="text-muted">-</span>
									@else
									<span id="opr_product_note{{ $list['id'] }}" class="text-muted">{{ $list['note'] }}</span>
									@endif
								</td>
								<td class="text-center"><span id="opr_quantity{{ $list['id'] }}">{{ $list['quantity'] }}</span></td>
								<td class="text-end"><span id="opr_unit_{{ $list['id'] }}">{{ rupiahFormat( $list['unit'] ) }}</span></td>
								<td class="text-end"><span id="opr_total_{{ $list['id'] }}">{{ rupiahFormat( $list['total'] ) }}</span></td>
								<td class="text-center" style="vertical-align: middle;">
									<button class="badge bg-blue-lt" onclick="actionUpdateProduct({!! $list['id'] !!})"><i class="ri-edit-2-line"></i></button>
								</td>
							</tr>
							@endforeach
					</tbody>
					<tbody id="tab_values">
						<tr>
							<td colspan="4" class="strong text-end"><i>Subtotal</i></td>
							<td class="text-end"></td>
							<td class="text-end"><span id="opr_suptotal">{{ rupiahFormat($opr_value->ovs_value_subtotal) }}</span></td>
							<td class="text-center" style="vertical-align: middle;">
								<button class="badge bg-blue-lt" onclick="actionChangeSubvalue()"><i class="ri-edit-2-line"></i></button>
							</td>
						</tr>
						<tr>
							<td colspan="4" class="strong text-end"><i>Tax Rate</i></td>
							<td class="text-end"><span id="opr_tax_rate">{{ $opr_value->ovs_rate_tax }}</span>%</td>
							<td class="text-end"><span id="opr_tax_value">{{ rupiahFormat($opr_value->ovs_value_tax) }}</span></td>
							<td class="text-center" style="vertical-align: middle;">
								<button class="badge bg-blue-lt" onclick="actionChangeTax()"><i class="ri-edit-2-line"></i></button>
							</td>
						</tr>
					</tbody>
					<tbody id="tab_other_values">
						<tr>
							<td colspan="4" class="strong text-end"><i>Other Cost</i></td>
							<td class="text-end"></td>
							<td rowspan="{{ $tab_row }}" class="text-end" style="vertical-align: middle;"><span id="opr_other_cost"></span>{{ rupiahFormat($opr_value->ovs_value_other_cost) }}</td>
							<td rowspan="{{ $tab_row }}" class="text-center" style="vertical-align: middle;">
								<button class="badge bg-blue-lt" onclick="actionChangeValOther()"><i class="ri-edit-2-line"></i></button>
							</td>
						</tr>
						@foreach ($opr_other as $list)
						<tr>
							<td colspan="4" class="text-muted text-end"><i><span id="opr_other_note_{{ $list['id'] }}"></span>{{ $list['note'] }}</i></td>
							<td class="text-end"><span id="opr_other_cost_{{ $list['id'] }}"></span>{{ rupiahFormat($list['coast'])  }}</td>
						</tr>
						@endforeach
					</tbody>
					<tbody>
						<tr>
							<td colspan="4" class="strong text-end"><i>Total Due</i></td>
							<td class="text-end"></td>
							<td class="text-end"><span id="opr_total">{{ rupiahFormat($opr_value->ovs_value_total) }}</span></td>
							<td class="text-center" style="vertical-align: middle;">
								<button class="badge bg-blue-lt" onclick="actionChangeValRevenue()"><i class="ri-edit-2-line"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
				@else
				@endif
			</div>
		</div>
		<div class="row mb-0">
			<div class="col">
				<label class="form-label text-muted col-2 pb-0 pt-0">Add Notes :</label>
			</div>
			@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
			<div class="col-auto">
				<button class="badge bg-blue btn-pill" onclick="actionUpdateNote()">Save Notes</button>
			</div>
			<div class="col-12 mb-2">
				<textarea id="textingContent1" class="form-control" name="identification_needs"
				rows="3" placeholder="Describe something ..." oninput="actionStoreOpporNotes()">{{ $opportunity->opr_notes }}</textarea>
			</div>
			@else
			<div class="col-12 mb-2">
				{!! html_entity_decode($opportunity->opr_notes) !!}
			</div>
			@endif
		</div>
	</div>
	{{-- ======================================================================================================== --}}
	{{-- <div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<em class="text-muted lh-base mb-1"><i>Technical Report</i></em>
	</div> --}}
	{{-- ========================================================================================================= --}}
	<div class="card-body pt-2 pb-2" style="padding-left: 10px;padding-right: 10px;background-color: whitesmoke;">
		<div class="row mb-2 mt-2">
			<em class="col text-muted lh-base mb-1"><i>PIC Contacts</i></em>
			<div class="col-auto">
				<button class="btn btn-primary btn-pill btn-sm" onclick="actionAddContactForm()" style="width: 130px;"><i class="ri-add-line" style="margin-right: 5px;"></i> Add Contact</button>
			</div>
		</div>
		<div class="col-12">
			<div id="contact-area" class="row">
				@foreach ($lead_contacts as $list)
				<div id="contact-{{ $list->plc_id }}" class="col-md-6 col-xl-3">
					<div class="card" style="margin-left: 0px;margin-right: 0px;padding-left: 0px;padding-right: 0px;margin-bottom: 8px;">
						<div class="card-status-start bg-primary"></div>
						<div class="card-body card-body-contact" style="">
							<div class="row">
								<div class="col-auto">
									<a href="{{ url('customer/contacts/detail/'.$list->cnt_id) }}">
										<span class="avatar"><i class="ri-user-line"></i></span>
									</a>
								</div>
								<div class="col text-truncate" style="padding-left: 0px;">
									<a href="{{ url('customer/contacts/detail/'.$list->cnt_id) }}">
										<strong>{{ $list->cnt_fullname }}</strong>
										<div class="text-muted text-truncate">{{ $list->cnt_company_position }}</div>
									</a>
								</div>
								<div class="col-auto align-self-center">
									<div class="dropdown">
										<button href="#" class="btn-action" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill"></i></button>
										<div class="dropdown-menu dropdown-menu-end">
											<a class="dropdown-item" href="#contact-area" onclick="actionRemoveContact({{ $list->plc_id }})"><i class="ri-delete-bin-2-line" style="margin-right:6px;"></i>Remove</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	{{-- ========================================================================================================= --}}
	<div class="card-body pt-3 pb-3" style="padding-left: 10px;padding-right: 10px;">
		<div id="init-page-activities" class="row mb-2">
			<em class="col text-muted lh-base mb-1"><i>Activities</i></em>
			<div class="col-auto">
				<button class="btn btn-primary btn-pill btn-sm" onclick="actionAddActivities()" style="width: 130px;"><i class="ri-add-line" style="margin-right: 5px;"></i> Add Activity</button>
			</div>
		</div>
		<div class="col-12 mb-3">
			<div class="table">
				<table class="table table-vcenter custom-datatables">
					<thead>
						<tr>
							<th style="width: 1%;"></th>
							<th style="width: 19%;">Due Date</th>
							<th style="width: 10%;">Activity</th>
							<th style="width: 45%;">Update Info</th>
							<th style="width: 15%;">Date Created</th>
							<th style="width: 10%;">Complete</th>
						</tr>
					</thead>
					<tbody id="listDataActivitySection1"></tbody>
					<tbody id="listDataActivitySection2"></tbody>
					<tbody id="listDataActivitySection3"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-subvalue" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Subvalue</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent1" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<input type="text" id="input-base-value" class="form-control pb-1 pt-1" name="opportunity_value" placeholder="Input placeholder" 
					oninput="fcurrencyInput('input-base-value')" value="{{ fcurrency($opportunity_value->opr_value_dpp) }}">
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-sm btn-primary m-0" form="formContent1" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-create-purchased" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Create Purchase Order</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent2" name="form_content2" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<input type="hidden" id="opportunity_status_id" name="opportunity_status_id" value="{{ $opportunity->lds_status }}" readonly>
						<div class="col-sm-12 col-xl-6">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label pt-1 pb-1">Purchased Date</label>
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_purchase" name="date_purchase" class="form-control p-1" placeholder="Purchase date" autocomplete="off">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-xl-6">
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label pt-1 pb-1">Invoice Number</label>
								<div class="col" style="padding: 0px;">
									<input type="text" id="inp_purchase" name="number_invoice" class="form-control p-1" placeholder="Invoice number" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-md-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Notes Purchase</label>
								<textarea id="notesPurchase" name="note_purchase"></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col">
				</div>
				<div class="col-auto">
					<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent2"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-tax" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Tax Value</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent3" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<div class="row mb-2">
						<label class="col-3 col-form-label" >Tax Rate</label>
						<div class="col">
							<div class="input-icon">
								<input type="number" id="input-opportunity-tax-rate" class="form-control pb-1 pt-1" name="tax_rate" oninput="actionTriggerTaxVal()">
                  <span class="input-icon-addon">
										<i class="ri-percent-line"></i>
                  </span>
                </div>
						</div>
					</div>
					<div class="row">
						<label class="col-3 col-form-label" >Tax Value</label>
						<div class="col">
							<input type="text" id="input-opportunity-tax" class="form-control pb-1 pt-1" name="tax_value" placeholder="Input placeholder" 
							oninput="fcurrencyInput('input-opportunity-tax')" value="{{ fcurrency($opportunity_value->opr_tax) }}">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent3" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-other-value" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Other Value</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent4" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<table id="table-form-other-cost" class="table table-vcenter card-table table-custom-1">
						<thead>
							<tr>
								<th style="width: 65%;">Other Cost</th>
								<th style="width: 30%;">Amount</th>
								<th style="width: 5%;"></th>
							</tr>
						</thead>
						<tbody id="table-body-form-other-value">
						</tbody>
					</table>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<div class="col m-0">
					<button type="button" class="btn btn-sm" onclick="actionChangeValOtherData()" title="Reload other value data." data-bs-toggle="tooltip" data-bs-placement="top">
						<i class="ri-refresh-line"></i>
					</button>
					<button type="button" class="btn btn-sm" onclick="actionAddRowForm4()" title="Add new row field input other value." data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-add-line" style="margin-right: 2px;"></i> New Row</button>
				</div>
				<div class="col-auto m-0">
					<button type="submit" form="formContent4" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-revenue-value" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Total Value</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent5" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<input type="text" id="input-opportunity-revenue" class="form-control pb-1 pt-1" name="revenue_value" placeholder="Input placeholder" 
					oninput="fcurrencyInput('input-opportunity-revenue')" value="{{ fcurrency($opportunity_value->opr_revenue) }}">
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent5" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-sale" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Salesperson</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent6" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<select type="text" class="form-select select-sales" name="select_sales" id="select-user-sales" value="">
						<option value="{{ $sales_selected->userid }}">{{ $sales_selected->name }}</option>
						@foreach ($user_marketing as $list)
						<option value="{{ $list->id }}">{{ $list->name }}</option>
						@endforeach
					</select>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent6" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-team" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Add Teams Sales</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent7" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<select type="text" class="form-select select-teams" name="select_teams[]" multiple  id="select-user-team" value="">
						@foreach ($user_marketing as $list)
						<option value="{{ $list->id }}" @if (in_array($list->id, $team_member_id)) selected @endif >
							{{ $list->name }}
						</option>
						@endforeach
					</select>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent7" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-contacts" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Add Contact</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent8" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<div class="row mb-2">
						<label class="col-3 col-form-label required" >
							Name
						</label>
						<div class="col">
							<select type="text" class="form-select select-sales" name="contact" id="select-contact" value="" required>
								<option value="{{ null }}"></option>
								@foreach ($all_contacts as $list)
								<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
								@endforeach
							</select>
							<input type="hidden" name="id_cst" value="{{ $opportunity->lds_customer }}">
							<input type="hidden" name="id_subcst" value="{{ $opportunity->lds_subcustomer }}">
						</div>
					</div>
					<span id="additional-form-contact" style="display: none;">
						<div class="row mb-2">
							<label class="col-3 col-form-label" >Contact</label>
							<div class="col">
								<select type="text" class="form-select select-sales" name="type" id="select-type" value="">
									<option value="{{ null }}"></option>
									<option value="mobile">Mobile</option>
									<option value="telephone">Telephone</option>
									<option value="email">Email</option>
								</select>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-3 col-form-label" >Number/Email</label>
							<div class="col">
								<input type="text" class="form-control" name="textcontact" value="{{ null }}">
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-3 col-form-label" >Position</label>
							<div class="col">
								<input type="text" class="form-control" name="position" value="{{ null }}">
							</div>
						</div>
					</span>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent8" class="btn btn-sm btn-primary m-0 pl-3 pr-3" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Create Activities</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent9" name="form_content9" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="opportunity_status_id" name="opportunity_status_id" value="{{ $opportunity->lds_status }}" readonly>
					<input type="hidden" name="customer" value="{{ $opportunity->lds_customer }}" readonly>
					<input type="hidden" name="lead_status_id" value="{{ $opportunity->lds_status }}" readonly>
					<div class="row">
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Date</label>
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_due" name="due_date" class="form-control p-1" placeholder="Due Date" autocomplete="off">
									</div>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Type Activity</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-type-activity" class="form-select ts-input-custom" name="action_todo" placeholder="Select your type activity"  value="">
										<option value="{{ null }}"></option>
										@foreach ($activity_type as $list)
											<option value="{{ $list->aat_id }}">{{ $list->aat_type_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-signed-user" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="">
										<option value="{{ $sales_selected->userid }}">{{ $sales_selected->name }}</option>
										@foreach ($team_selected as $list)
										<option value="{{ $list->userid }}">{{ $list->name }}</option>
										@endforeach
									</select>
									<button type="button" id="btn-add-team" class="badge bg-cyan mt-1 mb-1" onclick="actionViewInputTeam()">+ Team</button>
									<button type="button" id="btn-remove-team" class="badge bg-cyan mt-1 mb-1" style="display: none;">x Close</button>
									<div id="select-signed-user-team-area" style="display: none;">
										<select type="text" class="form-select ts-input-custom" name="assignment_user_team[]" placeholder="User team assignment..." id="select-signed-user-team" value="">
											<option value="{{ null }}"></option>
											@foreach ($users as $list)
											<option value="{{ $list->id }}">{{ $list->uts_team_name }} - {{ $list->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-pic-user" class="form-select ts-input-custom" multiple name="pic_user[]" placeholder="Select PIC..."  value="">
										{{-- <option value="{{ null }}">{{ null }}</option> --}}
										@foreach ($lead_contacts as $list)
										<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-12 col-md-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Activity Describe</label>
								<textarea id="notesActivitiesDescribe" name="activity_describe"></textarea>
							</div>
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Activity Result</label>
								<textarea id="notesActivitiesResult" name="activity_result"></textarea>
							</div>
							<div class="mb-2" style="margin-right: 0px;">
								<label class="form-check">
									<input id="todo_done" class="form-check-input" type="checkbox" name="todo_status" value="done" onclick="actionTodo('todo_done')">
									<span class="form-check-label">It is already done.</span>
								</label>
								<label class="form-check">
									<input id="todo_running" class="form-check-input" type="checkbox" name="todo_status" value="running" onclick="actionTodo('todo_running')">
									<span class="form-check-label">It is already running.</span>
								</label>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col">
				</div>
				<div class="col-auto">
					<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent9"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-update-information" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Update Info</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent10" name="form_content10" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="opportunity-status-id" name="opportunity_status_id" value="{{ $opportunity->lds_status }}" readonly>
					<input type="hidden" id="activity-id" name="activity_id" value="" readonly>
					<div class="mb-2" style="margin-right: 0px;">
						<label class="col-12 col-form-label">Summary</label>
						<textarea id="initUpdateInfo" class="notesUpdateInfo" name="activity_summary"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="padding-left: 16px;padding-right: 16px;padding-bottom: 25px;">
				<button type="button" id="ResetButtonFormUpdateInfo" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent10"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-edit-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Detail Activity</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent11" name="formContent11" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="opportunity_status_id" name="opportunity_status_id" value="{{ $opportunity->lds_status }}" readonly>
					<input type="hidden" id="act-id" name="act_id" value="" readonly>
					<div class="row">
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Date</label>
								{{-- <div class="col" style="padding: 0px; margin-right: 10px;">
									<div class="input-group" id="datetimepicker1">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_start" name="start_date" class="form-control p-1" placeholder="Start Date" autocomplete="off">
									</div>
								</div> --}}
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_due_edit" name="due_date" class="form-control p-1" placeholder="Due Date" autocomplete="off" value="">
									</div>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Type Activity</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-type-activity-i" class="form-select ts-input-custom" name="action_todo" placeholder="Select your type activity" >
										<option value="{{ null }}"></option>
										@foreach ($activity_type as $list)
											<option value="{{ $list->aat_id }}">{{ $list->aat_type_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-signed-user-i" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="">
										<option value="{{ $sales_selected->userid }}">{{ $sales_selected->name }}</option>
										@foreach ($team_selected as $list)
										<option value="{{ $list->userid }}">{{ $list->name }}</option>
										@endforeach
									</select>
									<button type="button" id="btn-add-team-i" class="badge bg-cyan mt-1 mb-1" onclick="actionViewInputTeamI()">+ Team</button>
									<button type="button" id="btn-remove-team-i" class="badge bg-cyan mt-1 mb-1" style="display: none;">x Close</button>
									<div id="select-signed-user-team-area-i" style="display: none;">
										<select type="text" class="form-select ts-input-custom" name="assignment_user_team[]" placeholder="User team assignment..." id="select-signed-user-team-i" value="">
											<option value="{{ null }}"></option>
											@foreach ($users as $list)
											<option value="{{ $list->id }}">{{ $list->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-pic-user-i" class="form-select ts-input-custom" name="pic_user[]" placeholder="Select PIC..."  value="">
										<option value="{{ null }}"></option>
										@foreach ($lead_contacts as $list)
										<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-12 col-md-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Activity Describe</label>
								<textarea id="act-note-describe" name="alt_describe"></textarea>
							</div>
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Activity Result</label>
								<textarea id="act-note-result" name="alt_result"></textarea>
							</div>
							<div class="mb-2" style="margin-right: 0px;">
								<label class="form-check">
									<input id="todo_done_i" class="form-check-input" type="checkbox" name="todo_status" value="done" onclick="actionTodo_i('todo_done')">
									<span class="form-check-label">It is already done.</span>
								</label>
								<label class="form-check">
									<input id="todo_running_i" class="form-check-input" type="checkbox" name="todo_status" value="running" onclick="actionTodo_i('todo_running')">
									<span class="form-check-label">It is already running.</span>
								</label>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col">
				</div>
				<div class="col-auto">
					<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent11"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-activity-status" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Change Activity Status</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent12" name="form_content9" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="activity_id_i" name="act_id" value="">
					<select type="text" class="form-select select-sales" name="status" id="select-act-status" value="">
						<option value="{{ null }}">{{ null }}</option>
						<option value="beready">Beready</option>
						<option value="running">Running</option>
						<option value="finished">Finish</option>
					</select>
				</form>
			</div>
			<div class="modal-footer" style="padding-left: 16px;padding-right: 16px;padding-bottom: 25px;">
				<button type="button" id="ResetButtonFormUpdateInfo" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent12"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-product" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Edit Products Opportunity</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent13" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<input type="text" id="prd-id" name="prd_id" value="">
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Set Product Priciple</label>
						<div class="col" style="padding: 0px;">
							<select type="text" id="select-principles-update" class="form-select ts-input-custom" name="product_principle" placeholder="Select product priciple">
								<option value="{{ null }}">Select principle</option>
								@foreach ($allproduct as $list)
									<option value="{{ $list->prd_id }}">{{ $list->prd_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Set Product</label>
						<div class="col" style="padding: 0px;">
							<select type="text" id="select-product-update" class="form-select ts-input-custom" name="product" placeholder="Select your type activity">
							</select>
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Give Notes</label>
						<div class="col" style="padding: 0px;">
							<input type="text" id="prd-product-note-update" name="product_note" class="form-control p-1" placeholder="Product notes.." autocomplete="off" >
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Unit Value</label>
						<div class="col" style="padding: 0px;">
							<input type="text" id="prd-unit-value-update" name="unit_value" class="form-control p-1" oninput="fcurrencyInput('prd-unit-value-update')" placeholder="Unit value product">
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Quantity</label>
						<div class="col" style="padding: 0px;">
							<input type="number" id="prd-quantity-update" name="quantity" class="form-control p-1" placeholder="Quantity products.." autocomplete="off" >
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent13" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-technical" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Add Technical</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent14" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<select type="text" class="form-select select-teams" name="select_tech[]" multiple  id="select-user-tech" value="">
						{{-- <option value="{{ $sales_selected->userid }}">{{ $sales_selected->name }}</option> --}}
						{{-- @foreach ($user_marketing as $list)
						<option value="{{ $list->id }}" @if (in_array($list->id, $team_member)) selected @endif >
							{{ $list->name }}
						</option>
						@endforeach --}}
						@foreach ($user_tech as $list)
						<option value="{{ $list->id }}" @if (in_array($list->id, $team_tech_id)) selected @endif>
							<b>{{ $list->uts_team_name }}</b> - {{ $list->name }}
						</option>
						@endforeach
					</select>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent14" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-product" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Add Products Opportunity</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent15" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Set Product Priciple</label>
						<div class="col" style="padding: 0px;">
							<select type="text" id="select-principles" class="form-select ts-input-custom" name="product_principle" placeholder="Select product priciple" >
								<option value="{{ null }}">Select principle</option>
								@foreach ($allproduct as $list)
									<option value="{{ $list->prd_id }}">{{ $list->prd_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Set Product</label>
						<div class="col" style="padding: 0px;">
							<select type="text" id="select-product" class="form-select ts-input-custom" name="product" placeholder="Select your type activity">
							</select>
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Give Notes</label>
						<div class="col" style="padding: 0px;">
							<input type="text" id="prd-product-note" name="product_note" class="form-control p-1" placeholder="Product notes.." autocomplete="off" >
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Unit Value</label>
						<div class="col" style="padding: 0px;">
							<input type="text" id="prd-unit-value" name="unit_value" class="form-control p-1" oninput="fcurrencyInput('prd-unit-value')" placeholder="Unit value product">
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Quantity</label>
						<div class="col" style="padding: 0px;">
							<input type="number" id="prd-quantity" name="quantity" class="form-control p-1" placeholder="Quantity products.." autocomplete="off" >
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent15" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<style>
	p {
		margin-bottom: 0px;
	}
	@media only screen and (max-width: 1050px) {
		#btn-opportunity-status {
			margin-bottom: 10px;
		}
	}
	.modal-full-width-custom {
    max-width: none;
    margin-top: 0px;
    margin-right: 8rem;
    margin-bottom: 0px;
    margin-left: 8rem;
	}
	.card-body-contact{
		padding: 4px 6px 4px 6px;
		/* padding-top: 2px;
		padding-left: 6px; */
	}
	.page-pretitle-custom {
		font-size: .725rem;
		font-weight: var(--tblr-font-weight-bold);
		text-transform: uppercase;
		letter-spacing: .04em;
		line-height: 1rem;
		color: var(--tblr-muted);
	}
	.btn-cst {
		text-decoration: none;
		--tblr-btn-icon-size: 0.25rem;
		--tblr-btn-bg: #ffffff;
		--tblr-btn-color: #0b1221;
		--tblr-btn-border-color: #e1e1e1;
		--tblr-btn-hover-bg: transparent;
		--tblr-btn-hover-border-color: var(--tblr-border-color-active);
		--tblr-btn-box-shadow: var(--tblr-shadow-button);
		--tblr-btn-active-color: #f8fffd;
		--tblr-btn-active-bg: #006dc0;
		--tblr-btn-active-border-color: #006dc0;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		white-space: nowrap;
		box-shadow: var(--tblr-btn-box-shadow);
	}
	.btn-cst {
		--tblr-btn-padding-x: 0.8rem;
		--tblr-btn-padding-y: 0.3rem;
	}
	.ts-control{
		padding-bottom: 0.28rem;
		padding-top: 0.28rem;
		padding-left: 0.39rem;
	}
	.ts-input-custom{
		min-height: 0.53rem;
	}
	.custom-datatables tbody tr td{
    padding-top: 4px;
    padding-bottom: 4px;
		padding-left: 0.8rem;
    padding-right: 0.8rem;
  }
	.img-status {
		max-height: 200px;
	}
	#table-product-detail{
		font-size: 13px;
	}
	#table-product-detail th{
		padding-right: 2px;
		padding-left: 2px;
		background-color: #006dc0;
		color: #e1e1e1;
	}
	#table-product-detail td{
		padding: 2px;
	}
	#table-form-other-cost{
		border-style: hidden;
	}
	#table-form-other-cost thead tr th{
		padding-left: 4px;
		border-style: none;
	}
	#table-form-other-cost tbody tr td{
		padding-top: 4px;
		padding-bottom: 4px;
		padding-left: 0px;
		padding-right: 2px;
		border-style: none;
	}
	
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
{{-- ============================================================================================ --}}s
<script>

</script>
{{-- ============================================================================================ --}}
<script>
var user_sales = new TomSelect("#select-user-sales",{
		create: true,
		valueField: 'id',
		labelField: 'title',
		searchField: 'title',
		render: {
			option: function(data, escape) {
				return '<div>' +
				'<span class="title">' + escape(data.title) + '</span>' +
				'</div>';
			},
			item: function(data, escape) {
				return '<div id="province-selected">' + escape(data.title) + '</div>';
			}
		}
});
var user_team = new TomSelect("#select-user-team",{
	create: true,
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	maxItems: 10,
	render: {
		option: function(data, escape) {
			return '<div>' +
			'<span class="title">' + escape(data.title) + '</span>' +
			'</div>';
		},
		item: function(data, escape) {
			return '<div id="province-selected">' + escape(data.title) + '</div>';
		}
	}
});
var user_contact = new TomSelect("#select-contact",{
	create: true,
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	onOptionAdd:  function () {
		$('#additional-form-contact').show(200);
	},
	onItemRemove: function () {
		$('#additional-form-contact').hide(200);
	},
	render: {
		option: function(data, escape) {
			return '<div>' +
			'<span class="title">' + escape(data.title) + '</span>' +
			'</div>';
		},
		item: function(data, escape) {
			return '<div id="contact-selected">' + escape(data.title) + '</div>';
		}
	}
});
var select_signed_user = new TomSelect("#select-signed-user",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_signed_user_team = new TomSelect("#select-signed-user-team",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-team">'+escape(data.title)+'</div>';
		}
	}
});
var select_type_activity = new TomSelect("#select-type-activity",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_pic_user = new TomSelect("#select-pic-user",{
	maxItem: 5,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-pic-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_signed_user_i = new TomSelect("#select-signed-user-i",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-i">'+escape(data.title)+'</div>';
		}
	}
});
var select_signed_user_team_i = new TomSelect("#select-signed-user-team-i",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-team-i">'+escape(data.title)+'</div>';
		}
	}
});
var select_type_activity_i = new TomSelect("#select-type-activity-i",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-type-activity-i">'+escape(data.title)+'</div>';
		}
	}
});
var select_pic_user_i = new TomSelect("#select-pic-user-i",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-i">'+escape(data.title)+'</div>';
		}
	}
});
var select_act_status = new TomSelect("#select-act-status",{
	persist: false,
	createOnBlur: true,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-act-status">'+escape(data.title)+'</div>';
		}
	}
});
var select_principles = new TomSelect("#select-principles",{
	persist: false,
	createOnBlur: true,
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-principles">'+escape(data.title)+'</div>';
		}
	}
});
var select_principles_update = new TomSelect("#select-principles-update",{
	persist: false,
	createOnBlur: true,
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-principles-update">'+escape(data.title)+'</div>';
		}
	}
});
var select_products = new TomSelect("#select-product",{
	maxItem:15,
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-product">'+escape(data.title)+'</div>';
		}
	}
});
var select_products_update = new TomSelect("#select-product-update",{
	maxItem:15,
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-product-update">'+escape(data.title)+'</div>';
		}
	}
});
var user_tech = new TomSelect("#select-user-tech",{
	create: true,
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	maxItems: 10,
	render: {
		option: function(data, escape) {
			return '<div>' +
			'<span class="title">' + escape(data.title) + '</span>' +
			'</div>';
		},
		item: function(data, escape) {
			return '<div id="select-user-tech">' + escape(data.title) + '</div>';
		}
	}
});
/*================================================================================================*/
select_principles.on('change',function () {
	var prd_id = select_principles.getValue();
	actionGetProduct(prd_id);
});
select_principles_update.on('change',function () {
	var prd_id = select_principles_update.getValue();
	actionGetProductUpdate(prd_id);
});
const picker_a = new easepick.create({
	element: "#datepicker_purchase",
	css: [ "{{ asset('plugins/litepicker/bundle/index.css') }}" ],
	zIndex: 10,
	format: "YYYY-MM-DD",
	AmpPlugin: {
		resetButton: true,
		darkMode: false
	},
});
const picker_b = new easepick.create({
	element: "#datepicker_due",
	css: [ "{{ asset('plugins/litepicker/bundle/index.css') }}" ],
	zIndex: 10,
	format: "YYYY-MM-DD hh:mm a",
	plugins: [
		"TimePlugin","AmpPlugin"
	],
	TimePlugin: {
		format12: true
	},
	AmpPlugin: {
		resetButton: true,
		darkMode: false
	},
});
const picker_c = new easepick.create({
	element: "#datepicker_due_edit",
	css: [ "{{ asset('plugins/litepicker/bundle/index.css') }}" ],
	zIndex: 10,
	format: "YYYY-MM-DD hh:mm a",
	plugins: [
		"TimePlugin","AmpPlugin"
	],
	TimePlugin: {
		format12: true
	},
	AmpPlugin: {
		resetButton: true,
		darkMode: false
	},
});
/*************************************************************************************************/
var notesStoredLOC = localStorage.getItem('notesInputActivities');
var notesEitor = ""; 
var notes_a = tinymce.init({
	selector: 'textarea#notesActivities',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
		editor.on('init',function(e) {
			if (notesStoredLOC != null) {
				editor.setContent(notesStoredLOC);
			}
		});
		editor.on('input', function(e) {
			notes = editor.getContent();
			localStorage.setItem('notesInputActivities', notes);
		});
	}
});
var notesOpportunityNotesLOC = localStorage.getItem('notesInputOpportunity');
var notesEitorOppor = ""; 
var notes_b = tinymce.init({
	selector: 'textarea#notesOpportunity',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
		editor.on('init',function(e) {
			if (notesOpportunityNotesLOC != null) {
				editor.setContent(notesOpportunityNotesLOC);
			}
		});
		editor.on('input', function(e) {
			notes = editor.getContent();
			localStorage.setItem('notesInputOpportunity', notes);
		});
	}
});
var notes_c = tinymce.init({
	selector: 'textarea#textingContent1',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
		editor.on('init',function(e) {
		});
		editor.on('input', function(e) {
		});
	}
});
var notes_describe = tinymce.init({
	selector: 'textarea#notesActivitiesDescribe',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
var notes_purchase = tinymce.init({
	selector: 'textarea#notesPurchase',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
var notes_result = tinymce.init({
	selector: 'textarea#notesActivitiesResult',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
/*************************************************************************************************/
</script>
<script>
function actionAutoSaveUpdateNote() {  
};
function actionUpdateNote() {
	var opr_notes = tinymce.get('textingContent1').getContent();  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('store-opportunity-notes') }}",
		async: false,
		data: {
			"id": "{{ $id_oppor }}",
			"notes": opr_notes
		},
		success: function(result) {
			if (result.param == true) {
				$.alert({
					type: 'green',
					title: 'Success',
					content: 'Data already store to database.',
					animateFromElement: false,
					animation: 'opacity',
					closeAnimation: 'opacity'
				});
			}else{
				$.alert({
					type: 'red',
					title: 'Something error!',
					content: result.message,
					animateFromElement: false,
					animation: 'opacity',
					closeAnimation: 'opacity'
				});
			}
		}
	});
};
function actionUpdatingStatus(status) {
	var idopportunity = {{ $id_oppor }};
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('update-status-opportunity') }}",
		data: {
			'id': idopportunity,
			'status': status
		},
		success: function(result) {
			$('#opportunity_status_id').val(result.new_status);
		}
	});
};
function actionChangeImgSts(status) {
	if (status == 1) {
		$('#img-status-1').show();
		$('#img-status-2').hide();
		$('#img-status-3').hide();
		$('#img-status-4').hide();
		$('#img-status-5').hide();
		$('#img-status-6').hide();
		$('#img-status-7').hide();
	} else if(status == 2){
		$('#img-status-1').hide();
		$('#img-status-2').show();
		$('#img-status-3').hide();
		$('#img-status-4').hide();
		$('#img-status-5').hide();
		$('#img-status-6').hide();
		$('#img-status-7').hide();
	} else if(status == 3){
		$('#img-status-1').hide();
		$('#img-status-2').hide();
		$('#img-status-3').show();
		$('#img-status-4').hide();
		$('#img-status-5').hide();
		$('#img-status-6').hide();
		$('#img-status-7').hide();
	} else if(status == 4){
		$('#img-status-1').hide();
		$('#img-status-2').hide();
		$('#img-status-3').hide();
		$('#img-status-4').show();
		$('#img-status-5').hide();
		$('#img-status-6').hide();
		$('#img-status-7').hide();
	} else if(status == 5){
		$('#img-status-1').hide();
		$('#img-status-2').hide();
		$('#img-status-3').hide();
		$('#img-status-4').hide();
		$('#img-status-5').show();
		$('#img-status-6').hide();
		$('#img-status-7').hide();
	} else if(status == 6){
		$('#img-status-1').hide();
		$('#img-status-2').hide();
		$('#img-status-3').hide();
		$('#img-status-4').hide();
		$('#img-status-5').hide();
		$('#img-status-6').show();
		$('#img-status-7').hide();
	} else {
		$('#img-status-1').hide();
		$('#img-status-2').hide();
		$('#img-status-3').hide();
		$('#img-status-4').hide();
		$('#img-status-5').hide();
		$('#img-status-6').hide();
		$('#img-status-7').show();
	}
	
};
function actionChangeStatus(x,y) {
	if (y == '1') {
		$('#btn-opr-status-new').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-opr-status-presentation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-poc').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-proposal').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-negotiation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-win').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-lose').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-po').hide();
		$('#btn-opr-status-lose').show();
		$('#btn-lock').hide();
		actionUpdatingStatus(y);
		actionChangeImgSts(y);
	}else if(y == '2'){
		$('#btn-opr-status-new').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-presentation').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-opr-status-poc').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-proposal').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-negotiation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-win').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-lose').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-po').hide();
		$('#btn-opr-status-lose').show();
		$('#btn-lock').hide(2);
		actionUpdatingStatus(y);
		actionChangeImgSts(y);
	}else if (y == '3'){
		$('#btn-opr-status-new').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-presentation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-poc').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-opr-status-proposal').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-negotiation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-win').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-lose').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-po').hide();
		$('#btn-opr-status-lose').show();
		$('#btn-lock').hide();
		actionUpdatingStatus(y);
		actionChangeImgSts(y);
	}else if (y == '4'){
		$('#btn-opr-status-new').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-presentation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-poc').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-proposal').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-opr-status-negotiation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-win').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-lose').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-po').hide();
		$('#btn-opr-status-lose').show();
		$('#btn-lock').hide();
		actionUpdatingStatus(y);
		actionChangeImgSts(y);
	}else if (y == '5'){
		$('#btn-opr-status-new').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-presentation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-poc').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-proposal').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-negotiation').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-opr-status-win').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-lose').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-po').hide();
		$('#btn-opr-status-lose').show();
		$('#btn-lock').hide();
		actionUpdatingStatus(y);
		actionChangeImgSts(y);
	}else if (y == '6'){
		$('#btn-opr-status-new').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-presentation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-poc').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-proposal').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-negotiation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-win').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-opr-status-lose').removeClass('btn-ghost-danger').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-po').show();
		$('#btn-opr-status-lose').hide();
		$('#btn-lock').show();
		actionUpdatingStatus(y);
		actionChangeImgSts(y);
	}else{
		$('#btn-opr-status-new').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-presentation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-poc').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-proposal').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-negotiation').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-win').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opr-status-lose').removeClass('btn-ghost-light').removeClass('bg-red-lt').addClass('btn-ghost-danger');
		$('#btn-go-opportunity').hide();
		$('#btn-death-end').show();
		$('#btn-lock').hide();
		actionUpdatingStatus(y);
		actionChangeImgSts(y);
	}
};
function actionCreatePO() {  
	$('#modal-create-purchased').modal('toggle');
};
function actionStoreOpporNotes() {
	var opr_notes = $('textarea#textingContent1').val();
	var opportunity_id = "{{ $id_oppor }}";
	$.ajaxSetup({
		headers: {
			"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('store-opportunity-notes') }}",
		data: {
			"id":opportunity_id,
			"notes":opr_notes,
		},
		success: function(result) {
			console.log(1);
			}
	});
};
function actionChangeSubvalue() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('source-product-value') }}",
		data: {
			'idOpportunity':'{{ $id_oppor }}',
		},
		success: function(result) {
			var subtotal_val = actionInstantIDR(result.value);
			$("#input-base-value").val(subtotal_val);
		}	
	}); 
	$('#modal-change-subvalue').modal('toggle');
};
function actionChangeValHPP() {  
	$('#modal-change-oppor-hpp').modal('toggle');
};
function actionChangeTax() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('source-tax-value') }}",
		data: {
			'idOpportunity':'{{ $id_oppor }}',
		},
		success: function(result) {
			var tax_val = actionInstantIDR(result.tax_value);
			$("#input-opportunity-tax").val(tax_val);
			$("#input-opportunity-tax-rate").val(result.tax_rate);
		}	
	});   
	$('#modal-change-tax').modal('toggle');
};
function actionChangeValOtherData() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		async: false,
		url: "{{ route('source-other-value-data') }}",
		data: {
			'idOpportunity':'{{ $id_oppor }}',
		},
		success: function(result) {
			$('#table-body-form-other-value').html(result.data);
		}	
	});
};
function actionChangeValOther() {
	actionChangeValOtherData();
	$('#modal-change-other-value').modal('toggle');
}
function actionChangeValRevenue(){
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		async: false,
		url: "{{ route('source-total-value') }}",
		data: {
			'idOpportunity':'{{ $id_oppor }}',
		},
		success: function(result) {
			var val_total = actionInstantIDR(result.val_total);
			$('#input-opportunity-revenue').val(val_total);
		}	
	});
	$('#modal-change-revenue-value').modal('toggle');
}
function actionChangeSales() {  
	$('#modal-change-sale').modal('toggle');
};
function actionAddTeam() {  
	$('#modal-add-team').modal('toggle');
};
function actionInstantIDR(number){
	return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
		minimumFractionDigits: 3
  }).format(number);
}
function fcurrencyInput(elem) {
	var inputElement = document.getElementById(elem);
	inputElement.value = formatRupiah(inputElement.value, 'Rp ');
}
function formatRupiah(number, prefix) {
	var number_string = number.replace(/[^,\d]/g, '').toString(),
	split = number_string.split(','),
	sisa = split[0].length % 3,
	rupiah = split[0].substr(0, sisa),
	ribuan = split[0].substr(sisa).match(/\d{3}/gi);  
  if (ribuan) {
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
  }
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
};
function handleChangeParam(parameter) {
	if (parameter.checked == true) {
		document.getElementById(parameter.id).value ='true';
	}else{
		document.getElementById(parameter.id).value ='false';
	}
	if (parameter.id == 'check_param_needs' ) {
		var rules = 'identification_needs';
	} else if(parameter.id == 'check_param_budgets') {
		var rules = 'identification_budgets';
	}
	actionIdentQualificatin(rules);
};
function actionAddContactForm() {
	$('#modal-add-contacts').modal('toggle');
};
function actionAddCardAvatar(params) {
	$('#contact-area').append(
		'<div id="contact-'+params.contact_id+'" class="col-md-6 col-xl-3">'
			+'<div class="card" style="margin-left: 0px;margin-right: 0px;padding-left: 0px;padding-right: 0px;margin-bottom: 8px;">'
				+'<div class="card-status-start bg-primary"></div>'
				+'<div class="card-body card-body-contact" style="">'
					+'<div class="row">'
						+'<div class="col-auto"><a href="{!! url("customer/contacts/detail/'+params.cnt_id+'") !!}"><span class="avatar"><i class="ri-user-line"></i></span></div>'
						+'<div class="col text-truncate" style="padding-left: 0px;"><a href="{!! url("customer/contacts/detail/'+params.personal_id+'") !!}"><strong>'+params.name_cnt+'</strong>'
							+'<div class="text-muted text-truncate">'+params.name_cst+'</div></a>'
					+'</div>'
					+'<div class="col-auto align-self-center">'
						+'<div class="dropdown">'
							+'<button href="#" class="btn-action" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill"></i></button>'
								+'<div class="dropdown-menu dropdown-menu-end">'
									+'<a class="dropdown-item" href="#contact-area" onclick="actionRemoveContact('+params.contact_id+')"><i class="ri-user-follow-line" style="margin-right:6px;"></i>Remove</a>'
								+'</div>'
							+'</div>'
						+'</div>'
					+'</div>'
				+'</div>'
			+'</div>'
		+'</div>'
	);
};
function actionRemoveContact(params) {
	$.ajaxSetup({
		headers: {
			"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
		}
	});
	$.ajax({
		type: "POST",
		url: "{{ route('remove-lead-contact') }}",
		data: {
			id:params
		},
		success: function(result) {
			$('#'+result.id_card).remove();
		}
	});
};
function actionLoadDataActivities() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('lead-activities') }}",
		data: {
			"lead_id": "{{ $id_lead }}"
		},
		success: function(result) {
			$('#listDataActivitySection1').html(result.data_activity_section_i);
			$('#listDataActivitySection2').html(result.data_activity_section_ii);
			$('#listDataActivitySection3').html(result.data_activity_section_iii);
		}
	});
}
function actionAddActivities() {  
	$('#modal-add-activities').modal('toggle');
};
function actionViewInputTeam() {
	$('#btn-add-team').hide();
	$('#btn-remove-team').show(); 
	$('#select-signed-user-team-area').slideDown();
};
function actionViewInputTeamI() {
	$('#btn-add-team-i').hide();
	$('#btn-remove-team-i').show(); 
	$('#select-signed-user-team-area-i').slideDown();
};
function actionRemoveInputTeam(x) {
	x.clear();
	$('#btn-remove-team').hide();  
	$('#btn-add-team').show();
	$('#select-signed-user-team-area').slideUp();
};
function actionRemoveInputTeamI(x) {
	x.clear();
	$('#btn-remove-team-i').hide();  
	$('#btn-add-team-i').show();
	$('#select-signed-user-team-area-i').slideUp();
};
function actionTodo(x) { 
	if (x == 'todo_done') {
		document.getElementById("todo_running").checked = false;
	}else{
		document.getElementById("todo_done").checked = false;
	}
};
function actionTodo_i(x) { 
	if (x == 'todo_done') {
		document.getElementById("todo_running_i").checked = false;
	}else{
		document.getElementById("todo_done_i").checked = false;
	}
};
function resetFormConten6(x,y,z) {
	var actionFollow = $('#action_todo').val();
	// picker_a.clear();
	picker_b.clear();
	x.clear();
	y.clear();
	z.clear();
	$('#btn-remove-team').hide();  
	$('#select-signed-user-team-area').slideUp();
	if (actionFollow == 'act_phone' || actionFollow == 'act_email') {
		$('#btn-add-team').hide();
	}else{
		$('#btn-add-team').show();
	}
	localStorage.removeItem("notesInputActivities");
	tinyMCE.activeEditor.setContent('');
	document.getElementById("todo_running").checked = false;
	document.getElementById("todo_done").checked = false;
}
function actionRunningListMin() {
	$('.act-section-1').hide(100);
	$('#btn-area-run-act-min').hide();
	$('#btn-area-run-act-plus').show();
}
function actionRunningListPlus() {
	$('.act-section-1').show(100);
	$('#btn-area-run-act-plus').hide();
	$('#btn-area-run-act-min').show();
}
function actionBereadyListMin() {
	$('.act-section-2').hide(100);
	$('#btn-area-br-act-min').hide();
	$('#btn-area-br-act-plus').show();
}
function actionBereadyListPlus() {
	$('.act-section-2').show(100);
	$('#btn-area-br-act-plus').hide();
	$('#btn-area-br-act-min').show();
}
function actionFinishListMin() {
	$('.act-section-3').hide(100);
	$('#btn-area-finish-act-min').hide();
	$('#btn-area-finish-act-plus').show();
}
function actionFinishListPlus() {
	$('.act-section-3').show(100);
	$('#btn-area-finish-act-plus').hide();
	$('#btn-area-finish-act-min').show();
}
function actionGetDataInfoAct(id) {
	var res_update_info ='';  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('lead-activities-detail-info') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			res_update_info = result;
		}
	});
	return res_update_info;
}
function actionGetDataActivity(id) {
	var data ='';  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('lead-activities-detail') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			data = result;
		}
	});
	return data;
}
function actionStoreUpdateInfo(id,data) {  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "",
		async: false,
		data: {
			"id": id,
			"data":data
		},
		success: function(result) {
			console.log(result);
		}
	});
}
function actionUpdateInfo(id) {
	$('#activity-id').val(id);
	$('#modal-update-information').modal('toggle');
	tinymce.remove('#initUpdateInfo');
	var editorAct = tinymce.init({
		selector : "textarea#initUpdateInfo",
		height : "300",
		promotion: false,
		statusbar: false,
		setup: function (editor) {  
			editor.on('init',function(e){
				editor.setContent(actionGetDataInfoAct(id));
			});
			// editor.on('input',function (e) {
			// 	var content_info = editor.getContent(); 
			// 	actionStoreUpdateInfo(id,content_info);
			// });
		}
	});
};
function actionUpdateActivity(id) { 
	var res_act = actionGetDataActivity(id);
	var val_assign_opt = [];
	var val_assign_team = [];
	var val_assign_pic = [];
	$('#modal-edit-activities').modal('toggle');
	/**/
	$('#act-id').val(id);
	$('#datepicker_due_edit').val(res_act.date_due);
	select_type_activity_i.setValue([res_act.activity[0]]);
	for (let i = 0; i < res_act.assign.length; i++) {
		val_assign_opt.push({id:res_act.assign[0],title:res_act.assign[1]});
	}
	select_signed_user_i.addOptions(val_assign_opt);
	select_signed_user_i.setValue(res_act.assign[0]);
	if (res_act.param_team == true) {
		$('#select-signed-user-team-area-i').show();
		$('#btn-remove-team-i').show();
		$('#btn-add-team-i').hide();
		select_signed_user_team_i.setValue(res_act.team);
	}
	for (let a = 0; a < res_act.user_cst_id.length; a++) {
		val_assign_pic.push({id:res_act.user_cst_id[a],title:res_act.user_cst_name[a]});
	}
	select_pic_user_i.addOptions(val_assign_pic);
	select_pic_user_i.setValue(res_act.user_cst_id);
	if (res_act.actstatus == 'finished') {
		$('#todo_done_i').prop('checked', true); 
		$('#todo_running_i').prop('checked', false);
	}else if (res_act.actstatus == 'beready') {
		$('#todo_done_i').prop('checked', false); 
		$('#todo_running_i').prop('checked', false);
	}else{
		$('#todo_done_i').prop('checked', false); 
		$('#todo_running_i').prop('checked', true);
	}
	if (res_act.todo_describe == null) {
		var todo_describe = "";
	}else{
		var todo_describe = res_act.todo_describe;
	}
	if (res_act.todo_result == null) {
		var todo_result = "";
	}else{
		var todo_result = res_act.todo_result;
	}
	tinymce.remove('#act-note-describe');
	tinymce.init({
		selector: 'textarea#act-note-describe',
		height : "300",
		promotion: false,
		statusbar: false,
		setup: function(editor) {
			editor.on('init',function(e) {
				editor.setContent(todo_describe);
			});
		}
	});
	tinymce.remove('#act-note-result');
	tinymce.init({
		selector: 'textarea#act-note-result',
		height : "300",
		promotion: false,
		statusbar: false,
		setup: function(editor) {
			editor.on('init',function(e) {
				editor.setContent(todo_result);
			});
		}
	});
};
function actionRemoveAct(id) {
	$.confirm({
    title: 'Warning',
    content: 'Are you sure will delete this activity ?',
		type: 'blue',
    buttons: {
      yes: function () {
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: "{{ route('delete-data-lead-activities') }}",
					data: {
						"id":id
					},
					success: function(result) {
						actionLoadDataActivities();
						if (result.param == true) {
							$.alert({
								type: 'green',
								title: 'Success',
								content: 'Data was deleted from database.',
								animateFromElement: false,
								animation: 'opacity',
								closeAnimation: 'opacity'
							});
						}else{
							$.alert({
								type: 'red',
								title: 'Something error!',
								content: result.message,
								animateFromElement: false,
								animation: 'opacity',
								closeAnimation: 'opacity'
							});
						}
					}
				});
      },
      cancel: function () {
      },
    }
	});
};
function actionChangeStatusAct(id) {
	$('#activity_id_i').val(id);
	$('#modal-change-activity-status').modal('toggle');
	var res_act_i = actionGetDataActivity(id);
	select_act_status.setValue([res_act_i.actstatus]);
};
function actionGetProduct(id) {  
	var dataOption_2 = [];  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('product-principle') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			for (let n = 0; n < result.product.length; n++) {
				dataOption_2.push({
					id:result.product[n].id,
					title:result.product[n].title
				});
			}
		}
	});
	select_products.clear();
	select_products.clearOptions();
	select_products.addOptions(dataOption_2);
};
function actionGetProductUpdate(id) {  
	var dataOption_3 = [];  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('product-principle') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			for (let n = 0; n < result.product.length; n++) {
				dataOption_3.push({
					id:result.product[n].id,
					title:result.product[n].title
				});
			}
		}
	});
	// select_products_update.clear();
	// select_products_update.clearOptions();
	select_products_update.addOptions(dataOption_3);
};
function actionAddTechnical() {  
	$('#modal-add-technical').modal('toggle');
};
function actionAddProduct() {
	$('#modal-add-product').modal('toggle');
};
function actionUpdateProduct(id) {  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('source-product-opportunity') }}",
		data: {
			'idOpportunity':'{{ $id_oppor }}',
			'idProduct':id,
		},
		success: function(result) {
			var product_lists = [];
			for (let i = 0; i < result.products.length; i++) {
				product_lists.push({id:result.products[i].id,title:result.products[i].title});
			}
			select_products_update.addOptions([{id:result.product_id,title:result.product}]);
			select_products_update.addOptions(product_lists);
			select_products_update.setValue(result.product_id);
			var principle_list = [];
			select_principles_update.addOptions([{id:result.principle_id,title:result.principle}]);
			select_principles_update.setValue(result.principle_id);
			$('#prd-product-note-update').val(result.note);
			var prd_val_unit = formatRupiah(result.unit.toString(),'Rp');
			$('#prd-id').val(result.prd_id);
			$('#prd-unit-value-update').val(prd_val_unit);
			$('#prd-quantity-update').val(result.quantity);
		}	
	});
	$('#modal-change-product').modal('toggle');
};
function triggerNewProduct(data) {
	var tab_product_list = $('#tab_products');
	tab_product_list.append(data.new_row);
	$('#opr_suptotal').html(data.val_subtotal);
	$('#opr_tax_value').html(data.val_tax);
	$('#opr_total').html(data.val_total);
};
function triggerUpdateProduct(data) {
	$('#opr_principle_'+data.init_prd).html(data.init_prd_priciple);
	$('#opr_product_'+data.init_prd).html(data.init_prd_product);
	$('#opr_product_note'+data.init_prd).html(data.init_prd_note);
	$('#opr_quantity'+data.init_prd).html(data.init_prd_quantity);
	$('#opr_unit_'+data.init_prd).html(data.init_prd_unit);
	$('#opr_total_'+data.init_prd).html(data.init_prd_total);
	$('#opr_suptotal').html(data.val_subtotal);
	$('#opr_tax_value').html(data.val_tax);
	$('#opr_total').html(data.val_total);
};
function actionTriggerTaxVal() {
	var tax_value_realtime = null;
	var tax_rate = $('#input-opportunity-tax-rate').val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		async: false,
		url: "{{ route('source-trigger-tax-value') }}",
		data: {
			'idOpportunity':'{{ $id_oppor }}',
			'tax_rate' : tax_rate
		},
		success: function(result) {
			tax_value_realtime = actionInstantIDR(result.value);
			$('#input-opportunity-tax').val(tax_value_realtime);
		}	
	});
};
function actionMakeRand(length) {
  let result = '';
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  const charactersLength = characters.length;
  let counter = 0;
  while (counter < length) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
    counter += 1;
  }
  return result;
}
function actionAddRowForm4() {
	var randomIndex = actionMakeRand(6);
	$('#table-body-form-other-value').append(
		'<tr><td><input type="text" class="form-control pb-1 pt-1" name="other_note[]" placeholder="Input description"></td>'
		+'<td><input type="text" id="input-opportunity-other-'+randomIndex+'" class="form-control pb-1 pt-1" name="other_value[]" placeholder="Input value"'
		+'oninput="fcurrencyInput(\'input-opportunity-other-'+randomIndex+'\')" value="" style="margin-right: 4px;"></td>'
		+'<td style="text-align: center;"><button type="button" class="btn btn-sm btn-ghost-danger" onclick="actionDelRowForm4(this)"><i class="ri-delete-bin-line"></i></button></td></tr>'
	);
};
function actionDelRowForm4(button) {  
	var rowTabForm4 = button.parentNode.parentNode;
	var tabForm4 = document.getElementById("table-form-other-cost");
	tabForm4.deleteRow(rowTabForm4.rowIndex);
};
function actionOpenPO() {  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('action-check-purchase') }}",
		async: false,
		data: {
			"opr_id": "{{ $id_oppor }}", 
		},
		success: function(result) {
			if (result.param == true) {
				window.open('{{ url("purchased/detail")}}/'+result.id);
			} else {
				$.alert({
					type: 'red',
					title: 'Warning',
					content: 'Something any problem, contact administrator for fix this.',
					animateFromElement: false,
					animation: 'opacity',
					closeAnimation: 'opacity'
				});
			}
		}
	});
};
</script>
{{-- ============================================================================================ --}}
<script>
$(document).ready(function() {
	actionLoadDataActivities();
	/*=================================================================================================================*/
	$("#formContent1").submit(function(e) {
		e.preventDefault();
		var formData1 = new FormData(this);
		formData1.append("id", "{{ $id_oppor }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-subvalue-opportunity') }}",
			data: formData1,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#opr_suptotal').html(result.val_subtotal);
				$('#opr_tax_value').html(result.val_tax);
				$('#opr_total').html(result.val_total);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent2").submit(function(e) {
		e.preventDefault();
		var formData2 = new FormData(this);
		formData2.append("id", "{{ $id_oppor }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-data-purchase-i') }}",
			data: formData2,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$('#btn-g-status').hide();
					$('#btn-g-lock').hide();
					$('#btn-g-page-purchase').show();
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already created.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent3").submit(function(e) {
		e.preventDefault();
		var formData3 = new FormData(this);
		formData3.append("id", "{{ $id_oppor }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-value-opportunity-tax') }}",
			data: formData3,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#opr_tax_rate').html(result.val_rate);
				$('#opr_tax_value').html(result.val_tax);
				$('#opr_total').html(result.val_total);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent4").submit(function(e) {
		e.preventDefault();
		var formData4 = new FormData(this);
		formData4.append("id", "{{ $id_oppor }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-value-opportunity-other') }}",
			data: formData4,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#opr_total').html(result.val_total);
				$('#tab_other_values').html(result.item_other);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent5").submit(function(e) {
		e.preventDefault();
		var formData5 = new FormData(this);
		formData5.append("id", "{{ $id_oppor }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-value-opportunity-revenue') }}",
			data: formData5,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#opr_total').html(result.val_total);
			}
		});
	});
	/*=================================================================================================================*/
	$('#formContent6').submit(function(e) {
		e.preventDefault();
		var formData6 = new FormData(this);
		formData6.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-change-sales-lead') }}",
			data: formData6,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#user-sale').html(result.user);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent7").submit(function(e) {
		e.preventDefault();
		var formData7 = new FormData(this);
		formData7.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-select-team') }}",
			data: formData7,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#user-team').html(result.user_member);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent8").submit(function(e) {
		e.preventDefault();
		var formData8 = new FormData(this);
		formData8.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-add-lead-contact') }}",
			data: formData8,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					actionAddCardAvatar(result);
				}else{
					alert('This contact already added.');
				}
			}
		});
	});
	/*=================================================================================================================*/
	$('#formContent9').submit(function(e) {
		e.preventDefault();
		var formData9 = new FormData(this);
		formData9.append("lead_id", "{{ $id_lead }}");
		formData9.append("alt_describe", tinymce.get('notesActivitiesDescribe').getContent());
		formData9.append("alt_result", tinymce.get('notesActivitiesResult').getContent());
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-data-lead-activities') }}",
			data: formData9,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already store to database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionLoadDataActivities();
					resetFormConten6(select_signed_user_team,select_signed_user,select_type_activity);
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	/*=================================================================================================================*/
	$('#formContent10').submit(function(e) {
		e.preventDefault();
		var formData10 = new FormData(this);
		formData10.append("lead_id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-data-update-info') }}",
			data: formData10,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					actionLoadDataActivities();
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already store to database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});		
	});
	$('#formContent11').submit(function(e) {
		e.preventDefault();
		var formData11 = new FormData(this);
		formData11.append("lead_id", "{{ $id_lead }}");
		formData11.append("alt_describe", tinymce.get('act-note-describe').getContent());
		formData11.append("alt_result", tinymce.get('act-note-result').getContent());
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-data-lead-activities') }}",
			data: formData11,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already store to database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionLoadDataActivities();
					resetFormConten6(select_signed_user_team,select_signed_user,select_type_activity);
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	$('#formContent12').submit(function(e) {
		e.preventDefault();
		var formData12 = new FormData(this);
		formData12.append("lead_id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-status-lead-activities') }}",
			data: formData12,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already updated.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionLoadDataActivities();
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	$('#formContent13').submit(function(e) {
		e.preventDefault();
		var formData13 = new FormData(this);
		formData13.append("lead_id", "{{ $id_lead }}");
		formData13.append("oppor_id", "{{ $id_oppor }}");
		formData13.append("alt_principle",$("#select-principles-update option:selected").text());
		formData13.append("alt_product",$("#select-product-update option:selected").text());
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-product-opportunity') }}",
			data: formData13,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					triggerUpdateProduct(result);
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already updated.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	$("#formContent14").submit(function(e) {
		e.preventDefault();
		var formData14 = new FormData(this);
		formData14.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-select-tech') }}",
			data: formData14,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#user-tech').html(result.user_tech);
			}
		});
	});
	$('#formContent15').submit(function(e) {
		e.preventDefault();
		var formData15 = new FormData(this);
		formData15.append("lead_id", "{{ $id_lead }}");
		formData15.append("oppor_id", "{{ $id_oppor }}");
		formData15.append("alt_principle",$("#select-principles option:selected").text());
		formData15.append("alt_product",$("#select-product option:selected").text());
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-product-opportunity') }}",
			data: formData15,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					triggerNewProduct(result);
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already updated.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
});
</script>
{{-- ============================================================================================ --}}
<script>
$('#btn-remove-team').click(function () {
	actionRemoveInputTeam(select_signed_user_team);
});
$('#btn-remove-team-i').click(function () {
	actionRemoveInputTeamI(select_signed_user_team_i);
});
</script>
@endpush