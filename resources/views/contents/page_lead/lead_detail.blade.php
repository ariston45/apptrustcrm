@extends('layout.app')
@section('title')
Leads
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title">Leads</h4>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('leads') }}">Leads</a></li>
<li class="breadcrumb-item active"><a href="#">Lead Detail</a></li>
@endsection
@section('content')
<div class="card">
	<div class="card-status-top bg-success"></div>
	<div class="card-header card-header-custom card-header-light">
		<h3 class="card-title">Detail Lead</h3>
		<div class="card-actions" style="padding-right: 10px;">
			<a href="{{ url('leads') }}">
				<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
					<div style="font-weight: 700;">
						<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
					</div>
				</button>
			</a>
		</div>
	</div>
	{{-- ========================================================================================================= --}}
	<div class="card-body" style="padding-left:10px;padding-right:10px;padding-bottom: 0px;padding-top: 8px;">
		<div class="row mb-2">
			@if (checkRule(array('ADM','AGM','MGR','MGR.PAS','STF')))
			<div class="col-xl-6 col-md-6" @if ($checkOppor != null) style="display:none;" @endif>
				@if ($checkOppor == null)
					<a href="" id="btn-go-page-opportunity-ii" target="_blank" style="display: none;">
						<button type="button" class="btn pr-2 pb-1 pt-1 ml-3">
							<i class="ri-zoom-in-line" style="margin-right: 8px;"></i> View Opportunity
						</button>
					</a>
					<div id="btn-lead-status" class="btn-group mb-0" role="group" style="margin-right: 15px;">
						@foreach ($status as $list)
						<input type="button" id="btn-{{ $list->pls_code_name }}" 
							class="btn active pr-2 pb-1 pt-1 @if ($lead->lds_status == $list->pls_id) btn-ghost-primary @else btn-ghost-light bg-blue-lt @endif" 
							value="{{ $list->pls_status_name }}" onclick="actionChangeStatus('{{ $list->pls_code_name }}','{{ $list->pls_id }}')">
						</input>
						@endforeach
					</div>
				@endif
			</div>
			<div class="col">
				<input type="hidden" id="param-button-crt-oppor" value="">
				@if ($checkOppor == null)
				<button type="button" id="btn-go-opportunity" class="btn active pr-2 pb-1 pt-1 ml-3 btn-ghost-success" value="Create Opportunity" 
					onclick="actionGoOpportunity()" @if ($lead->lds_status == 3) style="display: true;" @else style="display: none;" @endif >
					Create Opportunity
				</button>
				@else
				<a href="{{ url('opportunities/detail-opportunity') }}/{{ $checkOppor->opr_id }}" id="btn-go-page-opportunity" target="_blank">
					<button type="button" class="btn pr-2 pb-1 pt-1 ml-3"><i class="ri-zoom-in-line" style="margin-right: 8px;"></i> View Opportunity</button>
				</a>
				@endif
			</div>
			<div class="col-auto">
				@if ($checkOppor == null)
					<button type="button" id="btn-lock" class="btn active pr-2 pb-1 pt-1 btn-ghost-light bg-muted-lt" 
						@if ($lead->lds_status == 3) style="display: true;" @else style="display: none;" @endif> <i class="ri-lock-2-line"></i>
					</button>
					<input type="button" id="btn-death-end" 
						class="btn active pr-2 pb-1 pt-1  @if ($lead->lds_status == 0) btn-ghost-danger @else btn-ghost-light bg-red-lt @endif" 
						value="Dead End" onclick="actionChangeStatus('dead_end','0')" @if ($lead->lds_status == 3) style="display: none;" @else style="display: true;" @endif>
					</input>
				@endif
			</div>
			@else
			<div class="col">
				<span class="text-muted"><h3 class="mb-0">Status : {{ $lead->pls_status_name }}</h3></span>
			</div>
			@endif
		</div>
	</div>
	{{-- ======================================================================================================== --}}
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-3">
			<div class="col-6">
				<h2 class=" mb-1">{{ $lead->lds_title }}</h2>
				<em class="text-muted lh-base mb-1"><i>Customer</i></em>
				<div class="page-pretitle-custom mb-2">{{ html_entity_decode($institution_names->ins_name) }}</div>
				<em class="text-muted lh-base mb-1"><i>Sub Customer</i></em>
				@if ($sub_customer === null)
					<div class="page-pretitle-custom mb-2">-</div>
				@else
					<div class="page-pretitle-custom mb-2">{{ html_entity_decode($sub_customer->cst_name) }}</div>		
				@endif
			</div>
			<div class="col-auto">
			</div>
		</div>
		<div class="row mb-3">
			@if (checkRule(array('ADM','AGM','MGR','MGR.PAS','STF')))
			<div class="col-6">
				<div class="mb-1 row">
					<label class="text-muted col-4 col-3 pb-0 pt-0">Base Value</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="estimate-base-value">{{ fcurrency($lead_value->lvs_base_value) }}</span> 
						<button class="badge ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeBValue()"><i class="ri-edit-2-line"></i></button>
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Target Value</label>
					<div class="col pb-0 pt-0 text-muted" style="vertical-align: middle">
						<span id="estimate-target-value">{{ fcurrency($lead_value->lvs_target_value) }}</span> 
						<button class="badge ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeTValue()"><i class="ri-edit-2-line"></i></button>
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
						@if (checkRule(array('ADM','AGM','MGR','MGR.PAS','STF')))
						<button class="badge ms-0 p-0" style="margin-bottom: 3px;" onclick="actionChangeSales()"><i class="ri-edit-2-line"></i></button>
						@endif
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Colaborator</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="user-team">
							{{ $member_name }}
						</span>
						@if (checkRule(array('ADM','AGM','MGR','MGR.PAS','STF')))
						<button class="badge ms-0 p-0" style="margin-bottom: 3px;" onclick="actionAddTeam()"><i class="ri-edit-2-line"></i></button>
						@endif
					</div>
				</div>
				<div class="mb-1 row">
					<label class="text-muted col-4 pb-0 pt-0">Technical</label>
					<div class="col pb-0 pt-0 text-muted">
						<span id="user-tech">
							{{ $tech_name }}
						</span>
						@if (checkRule(array('ADM','AGM','MGR','MGR.PAS','STF')))
						<button class="badge ms-0 p-0" style="margin-bottom: 3px;" onclick="actionAddTechnical()"><i class="ri-edit-2-line"></i></button>
						@endif
					</div>
				</div>
			</div>
		</div>
		@if (checkRule(array('ADM','AGM','MGR','MGR.PAS','STF')))
		<div class="row mb-0">
			<em class="text-muted lh-base mb-1"><i>Qualifying</i></em>
			<div class="col-6 mb-2">
				<label class="form-label">
					Need Identity 
					<span class="form-label-description">
						<label class="form-check form-switch">
							<input id="check_param_needs" class="form-check-input" type="checkbox" onchange="handleChangeParam(this)"  
							@if($ident_need['param'] == 'true') value="true" checked @else value="false" @endif>
							<span class="form-check-label"> achieved</span>
						</label>
					</span>
				</label>
				<textarea id="textingContent1" class="form-control" name="identification_needs"
				rows="3" placeholder="Describe something ..." oninput="actionIdentQualification('identification_needs')">{{ $ident_need['textdata'] }}</textarea>
			</div>
			<div class="col-6 mb-2">
				<label class="form-label">
					Budget Identity 
					<span class="form-label-description">
						<label class="form-check form-switch">
							<input id="check_param_budgets" class="form-check-input" type="checkbox" onchange="handleChangeParam(this)"
							@if($ident_budget['param'] == 'true') value="true" checked @else value="false" @endif>
							<span class="form-check-label"> achieved</span>
						</label>
					</span>
				</label>
				<textarea id="textingContent2" class="form-control" name="identification_budgets"
				rows="3" placeholder="Describe something ..." oninput="actionIdentQualification('identification_budgets')">{{ $ident_budget['textdata'] }}</textarea>
			</div>
		</div>	
		@endif
	</div>
	{{-- ========================================================================================================= --}}
	<div class="card-body pt-2 pb-2" style="padding-left: 10px;padding-right: 10px;background-color: whitesmoke;">
		<div class="row mb-2 mt-2">
			<em class="col text-muted lh-base mb-1"><i>PIC Contacts</i></em>
			<div class="col-auto">
				<button class="btn btn-primary btn-pill btn-sm" onclick="actionAddContactForm()">+ Add Contact</button>
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
				<button class="btn btn-primary btn-pill btn-sm" onclick="actionAddActivities()">+ Add Activity</button>
			</div>
		</div>
		<div class="col-12 mb-3">
			<div class="table-responsive">
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
				<h5 class="modal-title">Change Base Value</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent1" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<input type="text" id="input-base-value" class="form-control pb-1 pt-1" name="base_value" placeholder="Input placeholder" 
					oninput="fcurrencyInput('input-base-value')" value="{{ fcurrency($lead_value->lvs_base_value) }}">
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
<div id="modal-change-target-value" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Change Target Value</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent2" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					@csrf
					<input type="text" id="input-target-value" class="form-control pb-1 pt-1" name="target_value" placeholder="Input placeholder" 
					oninput="fcurrencyInput('input-target-value')" value="{{ fcurrency($lead_value->lvs_target_value) }}">
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
<div id="modal-add-team" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Add Colaborator</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent4" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<select type="text" class="form-select select-teams" name="select_teams[]" multiple  id="select-user-team" value="">
						{{-- <option value="{{ $sales_selected->userid }}">{{ $sales_selected->name }}</option> --}}
						{{-- @foreach ($user_marketing as $list)
						<option value="{{ $list->id }}" @if (in_array($list->id, $team_member)) selected @endif >
							{{ $list->name }}
						</option>
						@endforeach --}}
						@foreach ($user_marketing as $list)
						<option value="{{ $list->id }}" @if (in_array($list->id, $team_member_id)) selected @endif>
							<b>{{ $list->uts_team_name }}</b> - {{ $list->name }}
						</option>
						@endforeach
					</select>
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
<div id="modal-add-technical" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Add Technical</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent11" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
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
				<button type="submit" form="formContent11" class="btn btn-sm btn-primary m-0" data-bs-dismiss="modal">Save</button>
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
				<form id="formContent3" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<select type="text" class="form-select select-sales" name="select_sales" id="select-user-sales" value="">
						<option value="{{ $sales_selected->userid }}">{{ $sales_selected->uts_team_name }} - {{ $sales_selected->name }}</option>
						@foreach ($user_marketing as $list)
						<option value="{{ $list->id }}"><b>{{ $list->uts_team_name }}</b> - {{ $list->name }}</option>
						@endforeach
					</select>
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
<div id="modal-add-contacts" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-3">
				<h5 class="modal-title">Add Contact</h5>
				<button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
				<form id="formContent5" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
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
							<input type="hidden" name="lds_id" value="{{ $lead->lds_id }}">
							<input type="hidden" name="id_cst" value="{{ $lead->lds_customer }}">
							<input type="hidden" name="id_subcst" value="{{ $lead->lds_subcustomer }}">
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
				<button type="submit" form="formContent5" class="btn btn-sm btn-primary m-0 pl-3 pr-3" data-bs-dismiss="modal">Save</button>
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
				<form id="formContent6" name="form_content6" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<input type="hidden" id="lead_status_id" name="lead_status_id" value="{{ $lead->lds_status }}" readonly>
						<input type="hidden" id="lead_customer" name="customer" value="{{ $lead->lds_customer }}" readonly>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Date</label>
								<div class="col">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_due" name="due_date" class="form-control p-1" placeholder="Due Date" autocomplete="off">
									</div>
								</div>
							</div>
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Type Activity</label>
								<div class="col">
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
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col">
									@if (checkRule(['STF','STF.TCH']))
									<input type="text" name="assignment_user_view" class="form-control p-1" value="{{ $user->name }}" placeholder="Due Date" autocomplete="off" readonly>
									<input type="hidden" name="assignment_user" value="{{ $user->id }}">
									@else
									<select type="text" id="select-signed-user" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="">
										@foreach ($users as $list)
										@if ($list->id == $user->id)
											<option value="{{ $list->id }}" selected>{{ $list->uts_team_name }} - {{ $list->name }}</option>
										@else
											<option value="{{ $list->id }}">{{ $list->uts_team_name }} - {{ $list->name }}</option>
										@endif
										@endforeach
									</select>
									@endif
									<button type="button" id="btn-add-team" class="badge mt-1 mb-1" onclick="actionViewInputTeam()">+ Team</button>
									<button type="button" id="btn-remove-team" class="badge mt-1 mb-1" style="display: none;">x Close</button>
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
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col">
									<select type="text" id="select-pic-user" class="form-select ts-input-custom" multiple name="pic_user[]" placeholder="Select PIC..."  value="">
										<option value=""></option>
										@foreach ($lead_contacts as $list)
										<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-12 col-md-12">
						<div class="mb-2" style="margin-right: 0px;">
							<label class="col-12 col-form-label">Activty Describe</label>
							<textarea id="notesActivitiesDescribe" name="activity_describe"></textarea>
						</div>
						<div class="mb-2" style="margin-right: 0px;">
							<label class="col-12 col-form-label">Activty Result</label>
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
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-auto">
					<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent6"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
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
				<form id="formContent7" name="form_content6" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="lead-status-id" name="lead_status_id" value="{{ $lead->lds_status }}" readonly>
					<input type="hidden" id="activity-id" name="activity_id" value="" readonly>
					<div class="mb-2" style="margin-right: 0px;">
						<label class="col-12 col-form-label">Post Info</label>
						<textarea id="initUpdateInfo" class="notesUpdateInfo" name="activity_summary"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="padding-left: 16px;padding-right: 16px;padding-bottom: 25px;">
				<button type="button" id="ResetButtonFormUpdateInfo" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent7"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-edit-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Update Activities</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3 pb-1">
				<form id="formContent8" name="formContent8" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<input type="hidden" id="lead_status_id" name="lead_status_id" value="{{ $lead->lds_status }}" readonly>
						<input type="hidden" id="act-id" name="act_id" value="" readonly>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Date</label>
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
									<button type="button" id="btn-add-team-i" class="badge mt-1 mb-1" onclick="actionViewInputTeamI()">+ Team</button>
									<button type="button" id="btn-remove-team-i" class="badge mt-1 mb-1" style="display: none;">x Close</button>
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
					</div>
					<div class="row">
						<div class="col-12">
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
						<div class="col-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Describe</label>
								<textarea id="act-note-describe" name="activity_describe"></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Result</label>
								<textarea id="act-note-result" name="activity_result"></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer" >
				<div class="col">
					<label class="form-check form-switch mb-1 mt-1" onclick="actionAutosaveAct()">
						<input class="form-check-input" type="checkbox" value="checkOff" name="actionInputAct" id="actionInputAct">
						<span class="form-check-label" id="lbl-autocheck-act-result">AutoSave</span>
					</label>
				</div>
				<div class="col-auto">
					<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent8"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">SAVE</button>
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
				<form id="formContent9" name="form_content9" enctype="multipart/form-data" action="javascript:void(0)" method="post">
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
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent9"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-create-opportunity" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Create Opportunity</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent10" name="form_content10" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="mb-2 mt-0 row" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Estimate Close</label>
						<div class="col" style="padding: 0px;margin-left: 0px;">
							<div class="input-group">
								<span class="input-group-text p-1">
									<i class="ri-calendar-2-line"></i>
								</span>
								<input type="text" id="est-closing-date" name="est_closing_date" class="form-control p-1" placeholder="Estimate closing" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="mb-2" style="margin-right: 0px;">
						<label class="col-3 col-form-label">Notes</label>
						<div class="pl-1" style="padding:0px;">
							<textarea id="notesOpportunity" name="opportunities_notes"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="padding-left: 16px;padding-right: 16px;padding-bottom: 25px;">
				<button type="button" id="ResetButtonFormUpdateInfo" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent10"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
			</div>
		</div>
	</div>
</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<style>
	p{
		margin-bottom: 0px;
	}
	@media only screen and (max-width: 1050px) {
		#btn-lead-status {
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
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
{{-- ============================================================================================ --}}
@if (checkRule(['STF','STF.TCH']))
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
</script>
@endif
{{-- ============================================================================================ --}}
<script>
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
			return '<div id="select-signed-user">'+escape(data.title)+'</div>';
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
			return '<div id="select_pic_user_i">'+escape(data.title)+'</div>';
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
const picker_d = new easepick.create({
	element: "#est-closing-date",
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
var notes_b = tinymce.init({
	selector: 'textarea#notesOpportunity',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
		editor.on('init',function(e) {
			if (notesOpportunityNotesLOC != null) {
				/* editor.setContent(notesOpportunityNotesLOC); */
			}
		});
		editor.on('input', function(e) {
			notes = editor.getContent();
			localStorage.setItem('notesInputOpportunity', notes);
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
<script>
function actionUpdatingStatus(status) {
	var idLead = {{ $id_lead }};
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('store-update-lead-status') }}",
		data: {
			'id': idLead,
			'status': status
		},
		success: function(result) {
			$('#lead_status_id').val(result.new_status);
		}
	});
};
function actionChangeStatus(x,y) {
	if (y == '1') {
		$('#btn-prospecting').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-qualifying').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opportunity').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-death-end').removeClass('btn-ghost-danger').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-opportunity').hide();
		$('#btn-death-end').show();
		$('#btn-lock').hide();
		actionUpdatingStatus(y);
	}else if(y == '2'){
		$('#btn-prospecting').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-qualifying').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-opportunity').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-death-end').removeClass('btn-ghost-danger').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-opportunity').hide();
		$('#btn-death-end').show();
		$('#btn-lock').hide(2);
		actionUpdatingStatus(y);
	}else if (y == '3'){
		$('#btn-prospecting').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-qualifying').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opportunity').removeClass('btn-ghost-light').removeClass('bg-blue-lt').addClass('btn-ghost-primary');
		$('#btn-death-end').removeClass('btn-ghost-danger').addClass('btn-ghost-light').addClass('bg-red-lt');
		$('#btn-go-opportunity').show();
		$('#btn-death-end').hide();
		$('#btn-lock').show();
		actionUpdatingStatus(y);
	}else{
		$('#btn-prospecting').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-qualifying').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-opportunity').removeClass('btn-ghost-primary').addClass('btn-ghost-light').addClass('bg-blue-lt');
		$('#btn-death-end').removeClass('btn-ghost-light').removeClass('bg-red-lt').addClass('btn-ghost-danger');
		$('#btn-go-opportunity').hide();
		$('#btn-death-end').show();
		$('#btn-lock').hide();
		actionUpdatingStatus(y);
	}
};
function actionGoOpportunity() {  
	$('#modal-create-opportunity').modal('toggle');
};
function actionChangeBValue() {  
	$('#modal-change-base-value').modal('toggle');
};
function actionChangeTValue() {  
	$('#modal-change-target-value').modal('toggle');
};
function actionChangeSales() {  
	$('#modal-change-sale').modal('toggle');
};
function actionAddTeam() {  
	$('#modal-add-team').modal('toggle');
};
function actionAddTechnical() {  
	$('#modal-add-technical').modal('toggle');
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
function actionIdentQualification(rules) {
	if (rules == 'identification_needs') {
		var check_param = $('#check_param_needs').val();
		var describe = $('#textingContent1').val();
	}else if(rules == 'identification_budgets'){
		var check_param = $('#check_param_budgets').val();
		var describe = $('#textingContent2').val();
	}
	var param_need = $('#init-need-identification').val();
	var lead_id = "{{ $id_lead }}";
	$.ajaxSetup({
		headers: {
			"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('store-identity-need') }}",
		data: {
			"id":lead_id,
			"textdata":describe,
			"param":check_param,
			"rule":rules
		},
		success: function(result) {
			console.log(1);
			}
	});
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
		url: "{{ route('lead-activities-update-info') }}",
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
function actionAutoSaveUpdateActivity() {  
	setInterval(
		function () {  
			var param_auto_save_i = $('#actionInputAct').val();
			if (param_auto_save_i == 'checkOn') {
				$('#lbl-autocheck-act-result').html('<div class="text-muted"> Saving...</div>');
				var textContentAct1 = tinymce.get('act-note-describe').getContent();
				var textContentAct2 = tinymce.get('act-note-result').getContent();
				var dataForm1 = $('#formContent8').serialize()+'&lead_id='+'{{ $id_lead }}'+'&note_describe='+textContentAct1+'&note_result='+textContentAct2;
				console.log(dataForm1);
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: "{{ route('autosave-update-data-lead-activities') }}",
					data: dataForm1,
					success: function(result) {
						setTimeout(function () {
							$('#lbl-autocheck-act-result').html('AutoSave');
						},1000 );
					}
				});
			}
		}
	,5000);
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
function actionAutosaveAct() {  
	var ASactivity = $('#actionInputAct').val();
	if (ASactivity == 'checkOff') {
		$('#actionInputAct').val('checkOn');
	}else{
		$('#actionInputAct').val('checkOff');
	}
};
</script>
{{-- ============================================================================================ --}}
<script>
$(document).ready(function() {
	actionLoadDataActivities();
	actionAutoSaveUpdateActivity();
	/*=================================================================================================================*/
	$("#formContent1").submit(function(e) {
		e.preventDefault();
		var formData1 = new FormData(this);
		formData1.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-update-base-value') }}",
			data: formData1,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#estimate-base-value').html(result.currency);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent2").submit(function(e) {
		e.preventDefault();
		var formData2 = new FormData(this);
		formData2.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-update-target-value') }}",
			data: formData2,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#estimate-target-value').html(result.currency);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent3").submit(function(e) {
		e.preventDefault();
		var formData3 = new FormData(this);
		formData3.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-change-sales-lead') }}",
			data: formData3,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#user-sale').html(result.user);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent4").submit(function(e) {
		e.preventDefault();
		var formData4 = new FormData(this);
		formData4.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-select-team') }}",
			data: formData4,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#user-team').html(result.user_member);
			}
		});
	});
	/*=================================================================================================================*/
	$("#formContent5").submit(function(e) {
		e.preventDefault();
		var formData5 = new FormData(this);
		formData5.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-add-lead-contact') }}",
			data: formData5,
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
	$('#formContent6').submit(function(e) {
		e.preventDefault();
		var formData6 = new FormData(this);
		formData6.append("lead_id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-data-lead-activities') }}",
			data: formData6,
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
	$('#formContent7').submit(function(e) {
		e.preventDefault();
		var formData7 = new FormData(this);
		formData7.append("activity_summary",tinymce.get('initUpdateInfo').getContent());
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-data-update-info') }}",
			data: formData7,
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
	/*=================================================================================================================*/
	$('#formContent8').submit(function(e) {
		e.preventDefault();
		var formData8 = new FormData(this);
		formData8.append("lead_id", "{{ $id_lead }}");
		formData8.append("alt_describe", tinymce.get('act-note-describe').getContent());
		formData8.append("alt_result", tinymce.get('act-note-result').getContent());
		console.log(formData8);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-data-lead-activities') }}",
			data: formData8,
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
	$('#formContent9').submit(function(e) {
		e.preventDefault();
		var formData9 = new FormData(this);
		formData9.append("lead_id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-status-lead-activities') }}",
			data: formData9,
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
	/*=================================================================================================================*/
	$('#formContent10').submit(function(e) {
		e.preventDefault();
		var formData10 = new FormData(this);
		formData10.append("lead_id", "{{ $id_lead }}");
		formData10.append("lead_title", "{{ $lead->lds_title }}");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-opportunity-new-a') }}",
			data: formData10,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#btn-lead-status').hide();
				$('#btn-go-opportunity').hide();
				$('#btn-death-end').hide();
				$('#btn-lock').hide();
				$('#btn-go-page-opportunity-ii').show();
				if (result.param == true) {
					
					$.confirm({
						type: 'green',
						title: 'Success',
						content: 'Data stored in database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity',
						buttons: {
							goToLead:{
								text:"Open Opportunity",
								action:function () {  
									window.open("{{ url('opportunities/detail-opportunity') }}/"+result.id_opr);
								}
							},
							close:{
								text:"Close",
							}
						}
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
	/*=================================================================================================================*/
	$("#formContent11").submit(function(e) {
		e.preventDefault();
		var formData11 = new FormData(this);
		formData11.append("id", "{{ $id_lead }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-select-tech') }}",
			data: formData11,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#user-tech').html(result.user_tech);
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
{{-- ============================================================================================ --}}
<script type="text/javascript">
var notesStoredLOC =  localStorage.getItem('notesInputActivities');
var notesEitor = ""; 

</script>
{{-- ============================================================================================ --}}
<script type="text/javascript">
var notesOpportunityNotesLOC = localStorage.getItem('notesInputOpportunity');
var notesEitorOppor = ""; 
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
				return '<div id="select-user-sales">' + escape(data.title) + '</div>';
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
				return '<div id="select-user-team">' + escape(data.title) + '</div>';
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