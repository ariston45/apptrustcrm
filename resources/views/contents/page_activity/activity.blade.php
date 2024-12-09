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
		<h3 class="card-title">Detail Activity</h3>
		<div class="card-actions" style="padding-right: 10px;">
			<button class="btn btn-sm btn-primary btn-pill btn-light" onclick="actionAddActivities()" style="width: 130px;">
				<div style="font-weight: 700;">
					<i class="ri-add-circle-line icon" style="font-size: 14px; vertical-align: middle;"></i>
					Add Activity
				</div>
			</button>
		</div>
	</div>
	{{-- ========================================================================================================= --}}
	<div class="card-body pt-2 pb-3" style="padding-left: 10px;padding-right: 10px;background-color: whitesmoke;">
		<div class="row">
			<div class="col-xl-8">
				<div id="calender"></div>			
			</div>
			<div id="act-detail-no-display" class="col-xl-4" style="display: true;">
				<div class="card bg-muted-lt" style="min-height: 390px;">
					<div class="card-body p-0 d-flex flex-column justify-content-center">
						<div style="text-align: center;">No Data View</div>
					</div>
				</div>
			</div>
			<div id="act-detail-display" class="col-xl-4" style="display: none;">
				<div class="card bg-muted-lt" style="min-height: 390px;">
					<div class="card-body p-2">
						<div class="row">
							<div class="col-11">
								<div class="row pb-2">
									<div class="col-3">
										<strong>Customer</strong>
									</div>
									<div class="col-auto">
										<p id="value-act-customer">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div class="col-3">
										<strong>Sub Cst.</strong>
									</div>
									<div class="col-auto">
										<p id="value-act-subcustomer">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div class="col-3">
										<strong>Activity</strong>
									</div>
									<div class="col-auto">
										<p id="value-act-type">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div	div class="col-3">
										<strong>Due Date</strong>
									</div>
									<div	div class="col-auto">
										<p id="value-act-due-date">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div class="col-3">
										<strong>Assign</strong>
									</div>
									<div class="col-auto">
										<p id="value-act-assign">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div class="col-3">
										<strong>Team</strong>
									</div>
									<div class="col-auto">
										<p id="value-act-team">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div class="col-3">
										<strong>PIC</strong>
									</div>
									<div class="col-auto">
										<p id="value-act-personal">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div class="col-3">
										<strong>Completion</strong>
									</div>
									<div class="col-auto">
										<p id="value-act-completion">-</p>
									</div>
								</div>
								<div class="row pb-2">
									<div class="col-12">
										<u><strong>Information Describe : </strong></u>
									</div>
									<div class="col-auto">
										<p id="value-act-describe">-</p>
									</div>
									<div class="col-12">
										<u><strong>Information Result : </strong></u>
									</div>
									<div class="col-auto">
										<p id="value-act-result">-</p>
									</div>
								</div>
							</div>
							<div class="col p-0">
								<div class="col-auto ms-auto" style="text-align: right;float: right;">
									<button class="badge text-muted bg-transparent pt-0" onclick="actionResetDetailCalender()"><i class="ri-close-circle-fill icon"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- ======================================================================================================== --}}
	<div class="card-body pt-3" style="padding-left: 10px;padding-right: 10px;">
		<div class="col-12">
			<div id="init-page-activities" class="row mb-2">
				<em class="col text-muted lh-base mb-1"><i>Activities</i></em>
				<div class="col-auto">
					<button class="btn btn-sm" onclick="actionResetFilter()"> <div class="text-muted">Reset Filter</div></button>
					<input type="hidden" id="param-code-status" name="code_status" value="act_total" readonly>
				</div>
			</div>
			<div id="contact-area" class="row">
				<div class="col-xl-auto col-md-2 py-3 pt-1 pb-2 ">
					<a href="#init-page-activities" id="id-btn-act_total" class="btn btn-square bg-dark-lt" style="display: inherit;" onclick="actionLoadActivities('act_total')">
						<div id="id-total-activities">{{ $cnt_total }}</div>
						<div><i class="ri-database-line" style="vertical-align: bottom;"></i> Total</div>
					</a>
				</div>
				@php
					$count_activity = null;
				@endphp
				@foreach ($activity_type as $list)
				@if ($list->aat_type_code == 'act_todo')
					@php
						$count_activity = $cnt_todo;
					@endphp
				@elseif ($list->aat_type_code == 'act_phone')
					@php
						$count_activity = $cnt_phone;
					@endphp
				@elseif ($list->aat_type_code == 'act_email')
					@php
						$count_activity = $cnt_email;
					@endphp
				@elseif ($list->aat_type_code == 'act_visit')
					@php
						$count_activity = $cnt_visit;
					@endphp
				@elseif ($list->aat_type_code == 'act_poc')
					@php
						$count_activity = $cnt_poc;
					@endphp
				@elseif ($list->aat_type_code == 'act_webinar')
					@php
						$count_activity = $cnt_webinar;
					@endphp
				@elseif ($list->aat_type_code == 'act_video_call')
					@php
						$count_activity = $cnt_video_call;
					@endphp	
				@endif
				<div class="col-xl-auto col-md-2 py-3 pt-1 pb-2">
					<a href="#init-page-activities" id="id-btn-{{ $list->aat_type_code }}" class="{{ $list->aat_custom_class_2 }}" style="display: inherit;" onclick="actionLoadActivities('{{ $list->aat_type_code }}')">
						<div id="id-{{ $list->aat_type_code }}">{{ $count_activity }}</div>
						<div> <i class="{{ $list->aat_icon }}" style="vertical-align: bottom;"></i> {{ $list->aat_type_name }}</div>
					</a>
				</div>
				@endforeach
			</div>
			<div class="col-12 mb-3">
				<div class="table table-default">
					<table class="table custom-datatables" id="activity-table" style="width: 100%;">
						<thead>
							<tr>
								{{-- <th></th> --}}
								<th style="width: 5%;"></th>
								<th style="text-align: center; width: 12%;">Due date</th>
								<th style="text-align: left; width: 8%;">Activity</th>
								<th style="text-align: left; width: 15%;">Customer</th>
								<th style="text-align: left; width: 15%;">Project</th>
								<th style="text-align: left; width: 10%;">Assign</th>
								<th style="text-align: left; width: 30%;">Info</th>
								<th style="text-align: center; width: 5%;">Completion</th>
							</tr>
						</thead>
						<tbody class="table-tbody"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true" >
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Create Activities</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body pt-3 pl-3 pr-3 pb-0">
				<form id="formContent1" name="form_content6" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="lead_status_id" name="lead_status_id" value="" readonly>
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
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Customer</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-customer" class="form-select ts-input-custom" name="customer" placeholder="Select customer"  value="">
										<option value="{{ null }}"></option>
										@foreach ($customer_all as $list)
											<option value="{{ $list->ins_id }}">{{ $list->ins_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Sub Customer</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-subcustomer" class="form-select ts-input-custom" name="sub_customer" placeholder="Select sub customer"  value="">
										<option value="{{ null }}"></option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Project Name</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-project-name" class="form-select ts-input-custom" name="lead_project" placeholder="Select project"  value="">
										<option value="{{ null }}"></option>
									</select>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col" style="padding: 0px;">
									@if (checkRule(['STF','STF.TCH']))
									<input type="text" name="assignment_user_view" class="form-control p-1" value="{{ $user->name }}" placeholder="Due Date" autocomplete="off" readonly>
									<input type="hidden" name="assignment_user" class="form-control p-1" value="{{ $user->id }}" placeholder="Due Date" autocomplete="off" readonly>
									@else
									<select type="text" id="select-assign-user" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="" readonly>
										@foreach ($user_all as $list)
										@if ( $user->id == $list->id)
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
										<select type="text" class="form-select ts-input-custom" name="assignment_user_team[]" placeholder="User team assignment..." id="select-team-user" value="">
											@foreach ($users as $list)
											@if ( $user->id == $list->id)
											<option value="{{ $list->id }}" selected>{{ $list->uts_team_name }} - {{ $list->name }}</option>
											@else
											<option value="{{ $list->id }}">{{ $list->uts_team_name }} - {{ $list->name }}</option>
											@endif
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-customer-contact" class="form-select ts-input-custom" name="pic_user[]" placeholder="Select PIC..."  value="">
										<option value="{{ null }}"></option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Describe Activities</label>
								<textarea id="notesActivitiesDes" name="activity_describe"></textarea>
							</div>
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Result Activities</label>
								<textarea id="notesActivitiesRes" name="activity_result"></textarea>
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
			<div class="modal-footer" style="padding-left: 16px;padding-right: 16px;padding-bottom: 16px;">
				<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent1"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
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
				<form id="formContent2" name="form_content9" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="activity_id_i" name="act_id" value="">
					<select type="text" class="form-select ts-input-custom" name="status" id="select-act-status" value="">
						<option value="{{ null }}">{{ null }}</option>
						<option value="beready">Beready</option>
						<option value="running">Running</option>
						<option value="finished">Finish</option>
					</select>
				</form>
			</div>
			<div class="modal-footer" style="padding-left: 16px;padding-right: 16px;padding-bottom: 25px;">
				<button type="button" id="ResetButtonFormUpdateInfo" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent2"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
			</div>
		</div>
	</div>
</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
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
		font-size: 13px;
		font-weight: 500;
		padding-top: 0.5rem;
		padding-bottom: 0.5rem;
		padding-left: 0.5rem;
		padding-right: 0.5rem;
	}
	.img-status {
		max-height: 200px;
	}
	.custom-datatables tbody tr td{
		padding-top: 4px;
		padding-bottom: 4px;
	}
	.paginate_button .current {
		background-color: red;
	}
	.event-todo {
		background-color: #EFF8EA;
		color: #00A4B6;
	}
	.event-phone {
		background-color: #EFF8EA;
		color: #1EAD4E;
	}
	.event-poc {
		background-color: #FCEAEB;
		color: #E72E3C;
	}
	.event-visit {
		background-color: #FFF5E9;
		color: #F29833;
	}
	.event-webinar {
		background-color: #F8EBF9;
		color: #96509A;
	}
	.event-email {
		background-color: #E5F0F9;
		color: #0D6DB6;
	}
	.event-video-call {
		background-color: #E5F5FC;
		color: #0C9BD8;
	}
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalender-scheduler/dist/index.global.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
{{-- ============================================================================================ --}}
{{-- Script 1 --}}
@if (!checkRule(['STF','STF.TCH']))
	<script>
		var select_assign_user = new TomSelect("#select-assign-user",{
			create: false,
			maxItems: 1,
			maxOptions: 40,			
			valueField: 'id',
			labelField: 'title',
			searchField: 'title',
			render: {}
		});
	</script>
@endif
<script>
var array = @json($users_mod);
console.log(array);
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
/*===============================================================================================*/
var select_customer = new TomSelect("#select-customer",{
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
select_customer.on('change',function () {
	var idcst = select_customer.getValue();
	actionGetsubcustomer(idcst);  
	actionObtainProject(idcst);
	actionGetDataContact(idcst);
});
var select_subcustomer = new TomSelect("#select-subcustomer",{
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
/*===============================================================================================*/
var select_project_name = new TomSelect("#select-project-name",{
	create: false,
	maxItems: 1,
	maxOptions: 40,			
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
/*===============================================================================================*/
var select_team_user = new TomSelect("#select-team-user",{
	create: false,
	maxItems: null,
	maxOptions: 40,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {}
});
/*===============================================================================================*/
var select_customer_contact = new TomSelect("#select-customer-contact",{
	create: false,
	maxItems: null,
	maxOptions: 40,			
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
/*===============================================================================================*/
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
/*===============================================================================================*/
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
</script>
{{-- ============================================================================================ --}}
{{-- Class Function --}}
<script>
function actionGetsubcustomer(id) {
	var subCstData = [];
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('sub-customers') }}",
		async: false,
		data: {
			'id':id
		},
		success: function(result) {
			for (let n = 0; n < result.data.length; n++) {
				subCstData.push({
					id:result.data[n].id,
					title:result.data[n].title
				});
			}
		}
	});
	select_subcustomer.clear();
	select_subcustomer.clearOptions();
	select_subcustomer.addOptions(subCstData);
};
function mainDataActivity(val_type,val_status,val_user) {
	var id = "";
	$('#myTable_filter input').addClass('form-control custom-datatables-filter');
	$('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
	var tableAct = $('#activity-table').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		stateSave: false,
		bServerSide: true,
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Activity"
		},
		ajax: {
			'url': '{!! route("source-data-activity") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'act_param': val_type,
				'act_status' : val_status,
				'act_user' : val_user,
			}
		},
		columnDefs: [
			{
				"targets": 0, 
				"className": "",
				"orderable": false
			},
			{
				"targets": 1, 
				"className": "text-left",
				"orderable": false
			},
			{
				"targets": 2, 
				"className": "text-left",
				"orderable": false,
				"searchable":true,
			},
			{
				"targets": 3, 
				"className": "text-left",
				"orderable": true,
				"searchable":true,
			}
		],
		order:[[0,'asc']],
		columns: [
			{data: 'menu', name: 'menu' },
			{data: 'due_date', name: 'due_date'},
			{data: 'title', name: 'title'},
			{data: 'customer', name: 'customer'},
			{data: 'project', name: 'project', orderable: true, searchable: true },
			{data: 'assign', name: 'assign', orderable: true, searchable: true },
			{data: 'info', name: 'info', orderable: true, searchable: true },
			/*
			*/
			{data: 'complete', name: 'complete', orderable: true, searchable: true },
		],
		initComplete: function (settings, json) {
			$("#activity-table_filter.dataTables_filter")
			.append('<label id="categoryFilter"><select id="filter_completion" class="form-control btn-square" onchange="actioFilterCompletion(this)" style="padding: 5px; border-color: #6f797a;"></select> </label>');
			am_aplicacion_ids = [{'all-status': 'Filter Completion'}, {'beready': 'Beready'}, {'running': 'Running'}, {'finished': 'Finish'}];
			for (var key in am_aplicacion_ids) {
				var obj = am_aplicacion_ids[key];
				for (var prop in obj) {
					if (obj.hasOwnProperty(prop)) {
						$('#filter_completion').append('<option value="' + prop + '">' + obj[prop] + '</option>');
					}
				}
			};
			$("#activity-table_filter.dataTables_filter")
			.append('<label id="userFilter"><select id="filter_user" class="form-control btn-square" onchange="actioFilterCompletion(this)" style="padding: 5px; border-color: #6f797a;"></select> </label>');
			am_aplicacion_ids_a = @json($users_mod);
			for (var key in am_aplicacion_ids_a) {
				var obj = am_aplicacion_ids_a[key];
				for (var prop in obj) {
					if (obj.hasOwnProperty(prop)) {
						$('#filter_user').append('<option value="' + prop + '">' + obj[prop] + '</option>');
					}
				}
			};
		}
	});
	tableAct.column('0:visible').order( 'asc' ).draw();
	return tableAct;
};
function sourceDataActivityCalender(start,end) {
	var passData = "";
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('source-data-activity-calender') }}",
		async: false,
		data: {
			'start':start,
			'end':end
		},
		success: function(result) {
			passData = result.activities;
		}
	});
	return passData;
};
function actionLoadActivities(type_act) {
	if (type_act == 'act_todo') {
		$('#id-btn-'+type_act).removeClass('bg-cyan-lt').addClass('bg-cyan').addClass('text-cyan-fg');
		$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
		$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
		$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
		$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
		$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
		$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	}else if(type_act == 'act_phone'){
		$('#id-btn-'+type_act).removeClass('bg-green-lt').addClass('bg-green').addClass('text-green-fg');
		$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
		$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
		$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
		$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
		$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
		$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	}else if(type_act == 'act_email'){
		$('#id-btn-'+type_act).removeClass('bg-blue-lt').addClass('bg-blue').addClass('text-blue-fg');
		$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
		$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
		$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
		$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
		$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
		$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	}else if(type_act == 'act_visit'){
		$('#id-btn-'+type_act).removeClass('bg-yellow-lt').addClass('bg-yellow').addClass('text-yellow-fg');
		$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
		$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
		$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
		$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
		$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
		$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	}else if(type_act == 'act_poc'){
		$('#id-btn-'+type_act).removeClass('bg-red-lt').addClass('bg-red').addClass('text-red-fg');
		$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
		$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
		$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
		$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
		$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
		$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	}else if(type_act == 'act_webinar'){
		$('#id-btn-'+type_act).removeClass('bg-purple-lt').addClass('bg-purple').addClass('text-purple-fg');
		$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
		$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
		$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
		$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
		$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
		$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	}else if(type_act == 'act_video_call'){
		$('#id-btn-'+type_act).removeClass('bg-azure-lt').addClass('bg-azure').addClass('text-azure-fg');
		$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
		$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
		$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
		$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
		$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
		$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
	}else{
		$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
		$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
		$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
		$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
		$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
		$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
		$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	}
	$('#param-code-status').val(type_act);
	/*************************************************/
	$('#activity-table').DataTable().clear().destroy();
	var cr = mainDataActivity(type_act,'all_status','all_user');
};
function actioFilterCompletion(param) {
	var type = $('#param-code-status').val();
	var val_status = document.getElementById("filter_completion");
	var val_user = document.getElementById("filter_user");
	$('#activity-table').DataTable().clear().destroy();
	mainDataActivity(type,val_status.value,val_user.value);
};
function actionViewMiniDetail(id) {  
	$('#act-detail-no-display').hide();
	$('#act-detail-display').fadeIn();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('source-data-activity-detail-calender') }}",
		async: false,
		data: {
			"id": id,
		},
		success: function(result) {
			$('#value-act-customer').html(result.data_act_name);
			$('#value-act-subcustomer').html(result.data_act_subname);
			$('#value-act-type').html(result.data_act_type);
			$('#value-act-due-date').html(result.data_act_due_date);
			$('#value-act-assign').html(result.data_act_assign);
			$('#value-act-completion').html(result.data_act_status);
			$('#value-act-describe').html(result.data_act_describe);
			$('#value-act-result').html(result.data_act_result);
			$('#value-act-team').html(result.data_act_team);
			$('#value-act-personal').html(result.data_act_personal);
		}
	});
};
function actionAnyClick() {  
	$('body').on('click',function () {
		$('#act-detail-display').hide();  
		$('#act-detail-no-display').fadeIn();
	});
};
function actionResetDetailCalender() {  
	$('#act-detail-display').hide();  
	$('#act-detail-no-display').fadeIn();
};
function actionAddActivities() {  
	$('#modal-add-activities').modal('toggle');
	window.onbeforeunload = function(){
		return 'Are you sure you want to leave?';
	};
};
function actionRemoveInputTeam(x) {
	x.clear();
	$('#btn-remove-team').hide();  
	$('#btn-add-team').show();
	$('#select-signed-user-team-area').slideUp();
};
function actionObtainProject(id) {  
	var dataOption_1 = [];  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('optain-lead-project') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			for (let n = 0; n < result.data.length; n++) {
				dataOption_1.push({
					id:result.data[n].id,
					title:result.data[n].title
				});
			}
		}
	});
	select_project_name.clear();
	select_project_name.clearOptions();
	select_project_name.addOptions(dataOption_1);
};
function actionGetDataContact(id) {  
	var dataOption_2 = [];  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('customer-person-contact') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			for (let n = 0; n < result.data.length; n++) {
				dataOption_2.push({
					id:result.data[n].id,
					title:result.data[n].title
				});
			}
		}
	});
	select_customer_contact.clear();
	select_customer_contact.clearOptions();
	select_customer_contact.addOptions(dataOption_2);
};
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
function actionChangeStatusAct(id,status) {  
	$('#modal-change-activity-status').modal('toggle');
	var res_act = actionGetDataActivity(id);
	$('#activity_id_i').val(res_act.act_id);
	select_act_status.setValue([res_act.actstatus]);
};
function actionViewInputTeam() {  
	$('#btn-add-team').hide();
	$('#btn-remove-team').show(); 
	$('#select-signed-user-team-area').slideDown();
};
function actionResetFilter(){
	$('#id-btn-act_todo').removeClass('bg-cyan').removeClass('text-cyan-fg').addClass('bg-cyan-lt');
	$('#id-btn-act_phone').removeClass('bg-green').removeClass('text-green-fg').addClass('bg-green-lt');
	$('#id-btn-act_email').removeClass('bg-blue').removeClass('text-blue-fg').addClass('bg-blue-lt');
	$('#id-btn-act_visit').removeClass('bg-yellow').removeClass('text-yellow-fg').addClass('bg-yellow-lt');
	$('#id-btn-act_poc').removeClass('bg-red').removeClass('text-red-fg').addClass('bg-red-lt');
	$('#id-btn-act_webinar').removeClass('bg-purple').removeClass('text-purple-fg').addClass('bg-purple-lt');
	$('#id-btn-act_video_call').removeClass('bg-azure').removeClass('text-azure-fg').addClass('bg-azure-lt');
	$('#activity-table').DataTable().clear().destroy();
	mainDataActivity('act_total','all_status','all_user');
};
</script>
{{-- ============================================================================================ --}}
{{-- Script 2 --}}
<script>
mainDataActivity('act_total','all_status','all_user');
</script>
{{-- ============================================================================================ --}}
{{-- Script 3 --}}
<script>	
document.addEventListener('DOMContentLoaded', function() {
	var now = '{{ date("Y-m-d") }}';
	var calendarEl = document.getElementById('calender');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
		themeSystem: 'bootstrap5',
		initialDate: now,
		height: 390,
		editable: true,
		selectable: true,
		nowIndicator: true,
		dayMaxEventRows: true,
		aspectRatio: 1,
		scrollTime: '00:00',
		headerToolbar: {
			left: 'today prev,next',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,resourceTimelineDay'
		},
		initialView: 'dayGridMonth',
		views: {
			timeGrid: {
				dayMaxEventRows: 4 // adjust to 6 only for timeGridWeek/timeGridDay
			}
		},
		dateClick: function(info) {
		},
		eventClick: function(info) {
			actionViewMiniDetail(info.event.id);
		},
		resourceGroupField: 'activity',
		resources: [
			{ id: 'a', title: 'todo', eventColor: '#00A4B6'},
			{ id: 'b', title: 'Phone', eventColor: '#1EAD4E' },
			{ id: 'c', title: 'Email', eventColor: '#0D6DB6'},
			{ id: 'd', title: 'Visit', eventColor: '#F29833'},
			{ id: 'e', title: 'POC', eventColor:'#E72E3C'},
			{ id: 'f', title: 'Video Call', eventColor: '#0C9BD8'},
			{ id: 'g', title: 'Webinar', eventColor: '#96509A'},
		],
		events: function (fetchInfo, successCallback, failureCallback) {
			var startDate = fetchInfo.start;
			var endDate = fetchInfo.end;
			var eventData = sourceDataActivityCalender(startDate,endDate);
			successCallback(eventData);
		},
		eventTimeFormat: {
			hour: '2-digit',
			minute: '2-digit',
			hour12: false
		}
	});
	calendar.render();
});
</script>
{{-- ============================================================================================ --}}
{{-- Script 3 --}}
<script>
$(document).ready(function() {
	$("#formContent1").submit(function(e) {
		e.preventDefault();
		var formData1 = new FormData(this);
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-new-activty') }}",
			data: formData1,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				actionLoadActivities('act_total');
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
								text:"Go To Activity",
								action:function () {  
									window.location.href = "{{ url('activity/activity-detail') }}/"+result.idactivity;
								}
							},
							createAgain:{
								text:"Create Again",
								action:function () {  
									window.location.reload();
								}
							}
						}
					});
				}
			}
		});
	});
	$('#formContent2').submit(function(e) {
		e.preventDefault();
		var formData2 = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-status-lead-activities') }}",
			data: formData2,
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
					actionLoadActivities('act_total');
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
{{-- Script 4 --}}
<script>
$('#btn-remove-team').click(function () {
	actionRemoveInputTeam(select_team_user);
});
</script>
{{-- ============================================================================================ --}}
{{-- Script 5 --}}
<script type="text/javascript">
var notes_a = tinymce.init({
	selector: 'textarea#notesActivities',
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
var notes_b = tinymce.init({
	selector: 'textarea#notesActivitiesDes',
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
var notes_b = tinymce.init({
	selector: 'textarea#notesActivitiesRes',
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
</script>
{{-- ============================================================================================ --}}
{{-- Script 6 --}}
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
@endpush