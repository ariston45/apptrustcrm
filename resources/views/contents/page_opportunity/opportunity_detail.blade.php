@extends('layout.app')
@section('title')
opportunitys
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
{{-- <h4 class="page-title">opportunity Detail</h4> --}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Step one</a></li>
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
		<div class="col-8">
			<div id="btn-opportunity-status" class="btn-group" role="group" style="margin-right: 15px;">
				@foreach ($status as $list)
				<input type="button" id="btn-{{ $list->oss_status_code }}" 
					class="btn active pr-2 pb-1 pt-1 @if ($opportunity->opr_status == $list->oss_id) btn-ghost-primary @else btn-ghost-light bg-blue-lt @endif" 
					value="{{ $list->oss_status_name }}" onclick="actionChangeStatus('{{ $list->oss_status_code }}','{{ $list->oss_id }}')">
				</input>
				@endforeach
			</div>
			<input type="button" id="btn-go-po" class="btn active pr-2 pb-1 pt-1 ml-3 btn-ghost-success" value="Create PO" 
			onclick="actionGoOpportunity()" @if ($opportunity->opr_status == 6) style="display: true;" @else style="display: none;" @endif >
		</div>
		<div class="col-4" style="text-align: right">
			<button type="button" id="btn-lock" class="btn active pr-2 pb-1 pt-1 btn-ghost-light bg-muted-lt" 
			@if ($opportunity->opr_status == 6) style="display: true;" @else style="display: none;" @endif> <i class="ri-lock-2-line"></i></button>
			<input type="button" id="btn-opr-status-lose" 
				class="btn active pr-2 pb-1 pt-1  @if ($opportunity->opr_status == 7) btn-ghost-danger @else btn-ghost-light bg-red-lt @endif" 
				value="Lose" onclick="actionChangeStatus('opr-status-lose','7')" @if ($opportunity->opr_status == 6) style="display: none;" @else style="display: true;" @endif>
			</input>
		</div>
	</div>
	@endif
	{{-- ======================================================================================================== --}}
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-3">
			<div class="col-12">
				<h2 class=" mb-1">{{ $opportunity->lds_title }}</h2>
			</div>
			<div class="col-6">
				<em class="text-muted lh-base mb-1"><i>Customer</i></em>
				@foreach ($opportunity_customer as $list)
					<div class="page-pretitle-custom">{{ $list }}</div>
				@endforeach
			</div>
			<div class="col-6" style="text-align: right;">
				<em class="text-muted lh-base mb-1"><i>Institution</i></em>
				<div class="page-pretitle-custom mb-2">{{ html_entity_decode($institution_names) }}</div>
			</div>
		</div>
		<div class="row mb-3">
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
		<hr class="mt-1 mb-3">
		<div class="row mb-1">
			<div class="col">
				<div class="mb-1 row">
					<label class="text-muted col-2 pb-0 pt-0"><b>PRINCIPLE</b></label>
					<div class="col pb-0 pt-0 text-muted" style="vertical-align: middle">
						<span id="opportunity-prd-priciple">{{ $principle->prd_name }}</span> 
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-2 pb-0 pt-0"><b>PRODUCT</b></label>
					<div class="col pb-0 pt-0 text-muted" style="vertical-align: middle">
						<span id="opportunity-prd-product">
							@php
								$prd = implode(", ", $products);
							@endphp
							{{ $prd }}
						</span>
					</div>
				</div>
			</div>
			<div class="col-auto">
				{{-- <button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeProduct()"><i class="ri-edit-2-line"></i></button> --}}
				<div class="col-auto">
					@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
					<button class="btn btn-primary btn-pill btn-sm" onclick="actionChangeProduct()" style="width: 130px;"><i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit Product</button>
					@endif
				</div>
			</div>
		</div>
		<div class="row mb-0">
			<div class="col">
				<label class="form-label text-muted col-2 pb-0 pt-0">NOTES :</label>
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
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-3">
			@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
			<div class="col-6">
				<div class="mb-1 row">
					<label class="text-muted col-4 col-3 pb-0 pt-0">Value / DPP</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="opportunity-value-dpp">{{ fcurrency($opportunity_value->opr_value_dpp) }}</span> 
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeValDPP()"><i class="ri-edit-2-line"></i></button>
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 col-3 pb-0 pt-0">HPP</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="opportunity-value-hpp">{{ fcurrency($opportunity_value->opr_value_hpp) }}</span> 
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeValHPP()"><i class="ri-edit-2-line"></i></button>
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Tax Value</label>
					<div class="col pb-0 pt-0 text-muted" style="vertical-align: middle">
						<span id="opportunity-value-tax">{{ fcurrency($opportunity_value->opr_tax) }}</span> 
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeTax()"><i class="ri-edit-2-line"></i></button>
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Other</label>
					<div class="col pb-0 pt-0 text-muted" style="vertical-align: middle">
						<span id="opportunity-value-other">{{ fcurrency($opportunity_value->opr_other) }}</span> 
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeValOther()"><i class="ri-edit-2-line"></i></button>
					</div>
				</div>
				<hr class="mt-1 mb-1">
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0"><b>REVENUE</b></label>
					<div class="col pb-0 pt-0 text-muted" style="vertical-align: middle">
						<span id="opportunity-value-revenue">{{ fcurrency($opportunity_value->opr_revenue) }}</span> 
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeValRevenue()"><i class="ri-edit-2-line"></i></button>
					</div>
				</div>
			</div>
			@endif
			<div class="col-6">
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Salesperson</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="user-sale">
							{{ $sales_selected->name }}
						</span>
						@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeSales()"><i class="ri-edit-2-line"></i></button>
						@endif
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Colaborator</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="user-team">
							{{ $member_name }}
						</span>
						@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionAddTeam()"><i class="ri-edit-2-line"></i></button>
						@endif
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Technical</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="user-team">
							{{ $tech_name }}
						</span>
						@if (checkRule(array('ADM','AGM','MGR.PAS','MGR','STF')))
						<button class="badge bg-cyan ms-0 p-0" style="margin-bottom: 3px;" onclick="actionAddTechnical()"><i class="ri-edit-2-line"></i></button>
						@endif
					</div>
				</div>
			</div>
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
									<span class="avatar"><i class="ri-user-line"></i></span>
								</div>
								<div class="col text-truncate" style="padding-left: 0px;">
									<strong>{{ $list->cnt_fullname }}</strong>
									<div class="text-muted text-truncate">{{ $list->cst_name }}</div>
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
<div id="modal-change-base-value" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Value / DPP</h5>
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
<div id="modal-change-oppor-hpp" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Value HPP</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent2" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<input type="text" id="input-opportunity-hpp" class="form-control pb-1 pt-1" name="hpp_value" placeholder="Input placeholder" 
					oninput="fcurrencyInput('input-opportunity-hpp')" value="{{ fcurrency($opportunity_value->opr_value_hpp) }}">
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-sm btn-primary m-0" form="formContent2" data-bs-dismiss="modal">Save</button>
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
					<input type="text" id="input-opportunity-tax" class="form-control pb-1 pt-1" name="tax_value" placeholder="Input placeholder" 
					oninput="fcurrencyInput('input-opportunity-tax')" value="{{ fcurrency($opportunity_value->opr_tax) }}">
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
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Other Value</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent4" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<input type="text" id="input-opportunity-other" class="form-control pb-1 pt-1" name="other_value" placeholder="Input placeholder" 
					oninput="fcurrencyInput('input-opportunity-other')" value="{{ fcurrency($opportunity_value->opr_other) }}">
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent4" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-revenue-value" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Revenue Value</h5>
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
						<label class="col-3 col-form-label required" for="">
							Name
						</label>
						<div class="col">
							<select type="text" class="form-select" name="contact" id="select-contact" value="" required>
								<option value="{{ null }}"></option>
								@foreach ($all_contacts as $list)
								<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<span id="additional-form-contact" style="display: none;">
						<div class="row mb-2">
							<label class="col-3 col-form-label" for="">Customer</label>
							<div class="col">
								<select type="text" class="form-select select-sales" name="customer" id="select-customer" value="">
									<option value="{{ null }}"></option>
									{{-- @foreach ($opportunity_customer as $list)
									<option value="{{ $list->cst_id }}">{{ $list->cst_name }}</option>
									@endforeach --}}
								</select>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-3 col-form-label" for="">Contact</label>
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
							<label class="col-3 col-form-label" for="">Number/Email</label>
							<div class="col">
								<input type="text" class="form-control" name="textcontact" value="{{ null }}">
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-3 col-form-label" for="">Position</label>
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
											<option value="{{ null }}">{{ null }}</option>
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
							{{-- <div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Summary</label>
								<textarea id="notesActivities" name="activity_summary"></textarea>
							</div> --}}
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
					<div class="mb-2 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Set Product Priciple</label>
						<div class="col" style="padding: 0px;">
							<select type="text" id="select-principles" class="form-select ts-input-custom" name="product_principle" placeholder="Select product priciple"  value="">
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
							<select type="text" id="select-product" class="form-select ts-input-custom" multiple name="product[]" placeholder="Select your type activity" value="">
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer p-3 pt-0">
				<button type="button" class="btn btn-sm btn-link link-secondary me-auto m-0" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" form="formContent13" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
			</div>;.. 
		</div>
	</div>
</div>
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
	.ts-wraper{
		
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
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
{{-- ============================================================================================ --}}
<script>

</script>
{{-- ============================================================================================ --}}
<script>
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
	create: false,			
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
var select_products = new TomSelect("#select-product",{
	maxItem:15,			
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
/*
const picker_a = new easepick.create({
	element: "#datepicker_start",
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
*/
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
function actionGoOpportunity() {  
	$('#modal-create-opportunity').modal('toggle');
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
function actionChangeValDPP() {  
	$('#modal-change-base-value').modal('toggle');
};
function actionChangeValHPP() {  
	$('#modal-change-oppor-hpp').modal('toggle');
};
function actionChangeTax() {  
	$('#modal-change-tax').modal('toggle');
};
function actionChangeValOther() {
	$('#modal-change-other-value').modal('toggle');
}
function actionChangeValRevenue(){
	$('#modal-change-revenue-value').modal('toggle');
}
function actionChangeSales() {  
	$('#modal-change-sale').modal('toggle');
};
function actionChangeProduct(){
	$('#modal-change-product').modal('toggle');
}
function actionAddTeam() {  
	$('#modal-add-team').modal('toggle');
};
function fcurrencyInput(x) {
	var input_field = document.getElementById(x);
	input_field.value = formatRupiah(input_field.value, "Rp");
}
function formatRupiah(angka, prefix) {
	var number_string = angka.replace(/[^,\d]/g, "").toString(),
		split = number_string.split(","),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);
	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan) {
		separator = sisa ? "." : "";
		rupiah += separator + ribuan.join(".");
	}
	rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
	return prefix == undefined ? rupiah : rupiah ? "Rp " + rupiah : "";
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
						+'<div class="col-auto"><span class="avatar"><i class="ri-user-line"></i></span></div>'
						+'<div class="col text-truncate" style="padding-left: 0px;"><strong>'+params.name_cnt+'</strong><div class="text-muted text-truncate">'+params.name_cst+'</div>'
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
function actionAddTechnical() {  
	$('#modal-add-technical').modal('toggle');
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
			url: "{{ route('store-value-opportunity') }}",
			data: formData1,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#opportunity-value-dpp').html(result.currency);
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
			url: "{{ route('store-value-opportunity-hpp') }}",
			data: formData2,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#opportunity-value-hpp').html(result.currency);
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
				$('#opportunity-value-tax').html(result.currency);
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
				$('#opportunity-value-other').html(result.currency);
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
				$('#opportunity-value-revenue').html(result.currency);
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
					$('#opportunity-prd-priciple').html(result.principle);
					$('#opportunity-prd-product').html(result.product);
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
{{-- ============================================================================================ --}}
<script type="text/javascript">
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
</script>
{{-- ============================================================================================ --}}
<script type="text/javascript">
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
</script>
<script>
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
var notes_result = tinymce.init({
	selector: 'textarea#notesActivitiesResult',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
</script>
{{-- ============================================================================================ --}}
<script>
$(document).ready(function() {
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
	/*=================================================================================================================*/
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
	/*=================================================================================================================*/
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
	/*=================================================================================================================*/
});
</script>
@endpush