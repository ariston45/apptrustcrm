@extends('layout.app')
@section('title')
Home
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title">Home</h4>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item active"><a href="#">Home</a></li>
@endsection
@section('content')
<div class="row">
</div>
<div class="row">
	<div class="col-xl-12 mb-3">
		<div class="card card-panel">
			<div class="card-body bg-cyan-lt card-body-panel">
				<div class="row">
					@if (checkRule(['ADM','AGM','MGR.PAS','MGR','STF']))
					<div class="col-xl-2 col-md-3 col-sm-6 item-panel-value" style="padding-left: 0px;">
						<div class="card card-sm">
							<div class="card-body p-2">
								<div class="row align-items-center">
									<div class="col-auto">
										<span class="bg-blue text-white avatar p-1">
											<img src="{{ getIcon('co_lead.svg') }}" alt="">
										</span>
									</div>
									<div class="col">
										<div class="font-weight-medium">
											<Strong>{{ $c_lead }}</Strong>
										</div>
										<div class="text-muted">
											Lead
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-md-3 col-sm-6 item-panel-value" style="padding-left: 0px;">
						<div class="card card-sm">
							<div class="card-body p-2">
								<div class="row align-items-center">
									<div class="col-auto">
										<span class="bg-purple text-white avatar p-1">
											<img src="{{ getIcon('co_briefcas.svg') }}" alt="">
										</span>
									</div>
									<div class="col">
										<div class="font-weight-medium">
											<strong>{{ $c_opportunities }}</strong>
										</div>
										<div class="text-muted">
											Opportunities
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-md-3 col-sm-6 item-panel-value" style="padding-left: 0px;">
						<div class="card card-sm">
							<div class="card-body p-2">
								<div class="row align-items-center">
									<div class="col-auto">
										<span class="bg-red text-white avatar p-1">
											<img src="{{ getIcon('co_all.svg') }}" alt="">
										</span>
									</div>
									<div class="col">
										<div class="font-weight-medium">
											<strong>{{ $c_purchase }}</strong>
										</div>
										<div class="text-muted">
											Purchase
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-md-3 col-sm-6 item-panel-value" style="padding-left: 0px;">
						<div class="card card-sm">
							<div class="card-body p-2">
								<div class="row align-items-center">
									<div class="col-auto">
										<span class="bg-yellow text-white avatar p-1">
											<img src="{{ getIcon('co_todo.svg') }}" alt="">
										</span>
									</div>
									<div class="col">
										<div class="font-weight-medium">
											<strong>{{ $c_activities }}</strong>
										</div>
										<div class="text-muted">
											To Do
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-md-3 col-sm-6 item-panel-value" style="padding-left: 0px;">
						<div class="card card-sm">
							<div class="card-body p-2">
								<div class="row align-items-center">
									<div class="col-auto">
										<span class="bg-cyan text-white avatar p-1">
											<img src="{{ getIcon('co_ticket.svg') }}" alt="">
										</span>
									</div>
									<div class="col">
										<div class="font-weight-medium">
											<strong>{{ $c_ticket }}</strong>
										</div>
										<div class="text-muted">
											Ticket
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-md-3 col-sm-6 item-panel-value" style="padding-left: 0px;">
						<div class="card card-sm">
							<div class="card-body p-2">
								<div class="row align-items-center">
									<div class="col-auto">
										<span class="bg-azure text-white avatar p-1">
											<img src="{{ getIcon('co_customer.svg') }}" alt="">
										</span>
									</div>
									<div class="col">
										<div class="font-weight-medium">
											<strong>{{ $c_customer }}</strong>
										</div>
										<div class="text-muted">
											Customers
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					{{-- @elseif (checkRule(['MGR.TCH','STF.TCH'])) --}}
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xl-3 mb-3">
		<div class="card">
			<div class="card-status-top bg-success"></div>
			<div class="card-body p-2">
				<h3 class="card-title text-muted">Leads Projects</h3>
				<div id="project-chart" class="chart-lg"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-5 mb-3">
		<div class="card">
			<div class="card-status-top bg-primary"></div>
			<div class="card-body p-2">
				<h3 class="card-title text-muted">Opportunity Projects</h3>
				<div id="opportunity-chart" class="chart-lg"></div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 mb-3">
		<div class="card">
			<div class="card-status-top bg-danger"></div>
			<div class="card-body p-2">
				<h3 class="card-title text-muted">Purchases</h3>
				<div id="purchase-chart" class="chart-lg"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xl-12 mb-3">
		<div class="row">
			<div class="col-12 mb-3">
				<div class="card">
					<div class="card-status-top bg-success"></div>
					<div class="card-header card-header-custom card-header-light">
						<h3 class="card-title">Activity</h3>
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
							<div class="col-xl-6">
								<div id="calender"></div>			
							</div>
							<div id="act-detail-no-display" class="col-xl-6" style="display: true;">
								<div class="card bg-muted-lt" style="min-height: 390px;">
									<div class="card-body p-0 d-flex flex-column justify-content-center">
										<div style="text-align: center;">No Data View</div>
									</div>
								</div>
							</div>
							<div id="act-detail-display" class="col-xl-6" style="display: none;">
								<div class="card bg-muted-lt" style="min-height: 390px;">
									<div class="card-body p-2">
										<div class="row mb-2">
											<div class="col">
												<i>Activities</i>
											</div>
											<div class="col-auto p-0">
												<div class="col-auto ms-auto" style="text-align: right;float: right;">
													<button class="badge text-muted bg-transparent pr-0 pt-0 pb-0" onclick="actionResetDetailCalender()"><i class="ri-close-circle-fill icon"></i></button>
												</div>
											</div>
										</div>
										<div class="row" >
											<div id="table-default">
												<table class="table custom-datatables-1" id="todo-table" style="width: 100%;">
													<thead>
														<tr>
															<th style="width: 20%;">Due Date</th>
															<th style="width: 50%;">Customer</th>
															<th style="width: 15%;">Activity</th>
															<th style="width: 15%;">Sales</th>
														</tr>
													</thead>
													<tbody class="table-tbody"></tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xl-12">
		<div class="row">
			{{-- <div class="col-12 mb-3">
				<div class="card">
					<div class="card-status-top bg-success"></div>
					<div class="card-header card-header-custom card-header-light">
						<h3 class="card-title">Leads Data</h3>
						<div class="card-actions" style="padding-right: 10px;">
							<a href="{{ url('leads/create-lead') }}">
								<button class="btn btn-sm btn-primary btn-pill btn-light" style="vertical-align: middle;">
									<div style="font-weight: 700;">
										<i class="ri-add-circle-line icon" style="font-size: 14px; vertical-align: middle;"></i> Create
									</div>
								</button>
							</a>
						</div>
					</div>
					<div class="card-body card-body-custom">
						<div id="table-default" class="">
							<table class="table custom-datatables" id="customer-table" style="width: 100%;">
								<thead>
									<tr>
										<th style="width: 40%;">Project Title</th>
										<th style="text-align: center; width: 25%;">Customer</th>
										<th style="text-align: center; width: 10%;">Status</th>
										<th style="text-align: center; width: 15%;">Salesperson</th>
										<th style="text-align: center; width: 10%">Menus</th>
									</tr>
								</thead>
								<tbody class="table-tbody"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div> --}}
			<div class="col-12 mb-3">
				<div class="card">
					<div class="card-status-top bg-success"></div>
					<div class="card-header card-header-custom card-header-light">
						<h3 class="card-title">Opportunities Data</h3>
						<div class="card-actions" style="padding-right: 10px;">
							<a href="{{ url('opportunities/create-new-opportunity') }}">
								<button class="btn btn-sm btn-primary btn-pill btn-light" style="vertical-align: middle;">
									<div style="font-weight: 700;">
										<i class="ri-add-circle-line icon" style="font-size: 14px; vertical-align: middle;"></i> Create
									</div>
								</button>
							</a>
						</div>
					</div>
					<div class="card-body card-body-custom">
						<div id="table-default" class="">
							<table class="table custom-datatables" id="customer-table" style="width: 100%;">
								<thead>
									<tr>
										<th style="width: 25%;">Customer</th>
										<th style="width: 25%;">Project Title</th>
										<th style="width: 13%;">Status</th>
										<th style="width: 10%;">Salesperson</th>
										<th style="width: 10%;">Follow Up</th>
										<th style="width: 12%;">Last Activity</th>
										<th style="width: 5%">Menus</th>
									</tr>
								</thead>
								<tbody class="table-tbody"></tbody>
							</table>
						</div>
					</div>
				</div>        
			</div>
		</div>
	</div>
</div>
{{-- ********************************************************************************************************** --}}
<div id="modal-change-activity-status" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Change Activity Status</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent2" name="form_content2" enctype="multipart/form-data" action="javascript:void(0)" method="post">
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
{{-- ********************************************************************************************************** --}}
<div id="modal-add-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Create Activities</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent1" name="form_content1" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
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
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Customer</label>
								<div class="col">
									<select type="text" id="select-customer" class="form-select ts-input-custom" name="customer" placeholder="Select customer"  value="">
										<option value="{{ null }}"></option>
										@foreach ($customer_all as $list)
											<option value="{{ $list->cst_id }}">{{ $list->cst_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Project Name</label>
								<div class="col">
									<select type="text" id="select-project-name" class="form-select ts-input-custom" name="lead_project" placeholder="Select project"  value="">
										<option value="{{ null }}"></option>
									</select>
								</div>
							</div>
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col">
									<select type="text" id="select-signed-user" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="">
										{{-- <option value="{{ $sales_selected->userid }}">{{ $sales_selected->name }}</option> --}}
										@foreach ($users as $list)
										@if ($list->id == $user->id)
											<option value="{{ $list->id }}" selected>{{ $list->uts_team_name }} - {{ $list->name }}</option>
										@else
											<option value="{{ $list->id }}">{{ $list->uts_team_name }} - {{ $list->name }}</option>
										@endif
										@endforeach
									</select>
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
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col">
									<select type="text" id="select-customer-contact" class="form-select ts-input-custom" name="pic_user[]" placeholder="Select PIC..."  value="">
										<option value="{{ null }}"></option>
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
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent1"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
<style>
	.custom-datatables tbody tr td{
		padding-top: 4px;
		padding-bottom: 4px;
		padding-left: 0.8rem;
		padding-right: 0.8rem;
	}
	.custom-datatables tbody tr td p{
		margin: 0;
	}
	.custom-datatables-1 tbody tr td{
		padding-top: 3px;
		padding-bottom: 3px;
		padding-left: 0.5rem;
		padding-right: 0.5rem;
	}
	.custom-datatables-1 thead tr th{
		padding-top: 8px;
		padding-bottom: 8px;
		padding-left: 0.5rem;
		padding-right: 0.5rem;
	}
	.custom-datatables-1 tbody tr td p{
		margin: 0px;
	}
	.ts-control{
		padding-bottom: 0.28rem;
		padding-top: 0.28rem;
		padding-left: 0.39rem;
	}
	.ts-input-custom{
		min-height: 0.53rem;
	}
	.modal-full-width-custom {
		max-width: none;
		margin-top: 0px;
		margin-right: 8rem;
		margin-bottom: 0px;
		margin-left: 8rem;
	}
	.item-panel-value {
		padding-bottom: 10px;
	}
	.card-body-panel{
		padding: 10px 10px 0px 18px;
	}
	#todo-table thead th{
		padding-left: 0px;
	}
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalender-scheduler/dist/index.global.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
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
/*
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
*/
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
			return '<div id="select-type-activity">'+escape(data.title)+'</div>';
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
/****************************************************************************************/
// triger
select_customer.on('change',function () {
	var idcst = select_customer.getValue();  
	actionObtainProject(idcst);
	actionGetDataContact(idcst);
});
</script>
<script>
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
function actionLoadDataActivities() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('all-lead-activities') }}",
		data: {
			"lead_id": "all"
		},
		success: function(result) {
			$('#listDataActivitySection1').html(result.data_activity_section_i);
			$('#listDataActivitySection2').html(result.data_activity_section_ii);
			/*$('#listDataActivitySection3').html(result.data_activity_section_iii);*/
		}
	});
};
function actionRunningListMin() {
	$('.act-section-1').hide(100);
	$('#btn-area-run-act-min').hide();
	$('#btn-area-run-act-plus').show();
};
function actionRunningListPlus() {
	$('.act-section-1').show(100);
	$('#btn-area-run-act-plus').hide();
	$('#btn-area-run-act-min').show();
};
function actionBereadyListMin() {
	$('.act-section-2').hide(100);
	$('#btn-area-br-act-min').hide();
	$('#btn-area-br-act-plus').show();
};
function actionBereadyListPlus() {
	$('.act-section-2').show(100);
	$('#btn-area-br-act-plus').hide();
	$('#btn-area-br-act-min').show();
};
function actionChangeStatusAct(id) {
	$('#activity_id_i').val(id);
	$('#modal-change-activity-status').modal('toggle');
	var res_act_i = actionGetDataActivity(id);
	select_act_status.setValue([res_act_i.actstatus]);
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
function actionAddActivities() {  
	$('#modal-add-activities').modal('toggle');
};
function actionUpdateActivity(id) {  
	window.open('{{ url("activity/activity-detail") }}/'+id);
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
function actionPreviewTodo(date) {
	$('#act-detail-no-display').hide();  
	$('#act-detail-display').fadeIn();
	$('#todo-table').DataTable().clear().destroy();
	$('#myTable_filter input').addClass('form-control custom-datatables-filter');
	$('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
	var tableTodo = $('#todo-table').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		stateSave: false,
		bServerSide: true,
		paging: false,
    scrollCollapse: true,
    scrollY: '390px',
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Activity"
		},
		ajax: {
			'url': '{!! route("source-data-activity-instant") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'date':date,
			}
		},
		columns: [
			{data: 'due_date', name: 'due_date', orderable: false, searchable: false},
			{data: 'customer', name: 'customer', orderable: true, searchable: true},
			{data: 'title', name: 'title', orderable: true, searchable: true},
			{data: 'assign', name: 'assign', orderable: true, searchable: true},
		],
	});
};
function actionResetDetailCalender() {  
	$('#act-detail-display').hide();
	$('#act-detail-no-display').fadeIn();  
	$('#todo-table').DataTable().clear().destroy();
};
function mainDataOpportunities() {
	var id = "";
	$('#customer-table').DataTable({
		processing: true,serverSide: true,responsive: true,
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Opportunity"
		},
		ajax: {
			'url': '{!! route("source-opportunities") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'id' : id
			}
		},
		columns: [
			// {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable:false},
			{data: 'customer', name: 'customer', orderable: true, searchable: true },
			{data: 'title', name: 'title', orderable: true, searchable: true },
			{data: 'status', name: 'status', orderable: true, searchable: true },
			{data: 'salesperson', name: 'salesperson', orderable: true, searchable: true },
			{data: 'activity', name: 'activity', orderable: true, searchable: true },
			{data: 'last_activity', name: 'last_activity', orderable: true, searchable: true },
			{data: 'menu', name: 'menu', orderable: false, searchable: false },
		]
	});	
};
function chartPieLead(data) {  
	var option = {
		plotOptions: {
			pie: {
				donut: {
					size: '70%',
					labels: {
						show: true,
						value: {
							show: true,
						},
						total: {
							show: true,
							color: '#373d3f',
						},
					},
				}
			}
		},
		chart: {
			type: "donut",
			fontFamily: 'inherit',
			height: 350,
			sparkline: {
				enabled: true
			},
			animations: {
				enabled: true
			},
		},
		fill: {
			opacity: 1,
		},
		series: data.ValSeries,
		labels: data.ValLabels,
		tooltip: {
			theme: 'dark'
		},
		grid: {
			strokeDashArray: 4,
		},
		legend: {
			show: true,
			position: 'bottom',
			offsetY: 0,
			markers: {
				width: 10,
				height: 10,
				radius: 100,
			},
			itemMargin: {
				horizontal: 8,
				vertical: 8
			},
		},
		tooltip: {
			fillSeriesColor: false
		},
	};
	var chart = new ApexCharts(document.querySelector('#project-chart'), option);
	chart.render();
};
function actionStatisticLead() {
	var id = '{{ auth_user()->id }}';
	var data;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('source-chart-lead') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			chartPieLead(result);
		}
	});
};
function chartBarOpportunity(data) {
	var options = {
		chart: {
			height: 320,
			type: 'bar'
		},
		xaxis:{
			type: 'status_opr',
			categories: data.ValLabels,
		},
		yaxis: {
			labels: {
				padding: 2,
				formatter: function(val){
					return val.toFixed(0);
				}
			},
		},
		colors: ['#009DDB','#F71F3F','#FF993C','#00A6B5','#C339C1','#00B557','#0067E1'],
		series: [{
			name: 'Value',
			data: data.ValSeries,
		}],
		plotOptions: {
			bar: {
				columnWidth: '40%'
			}
		},
		dataLabels: {
			enabled: true
		},
	};
	var chart = new ApexCharts(document.querySelector("#opportunity-chart"), options);
	chart.render();
};
function actionStatisticOpportunity() {
	var id = '{{ auth_user()->id }}';
	var data;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('source-chart-opportunity') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			chartBarOpportunity(result);
		}
	});
};
function chartBarPurchase(data) {
	var options = {
		series: [{
			name: 'Value',
      data: data.ValSeries
    }],
		chart: {
      type: 'bar',
      height: 350
    },
		plotOptions: {
      bar: {
				borderRadius: 4,
				horizontal: true,
      }
    },
		dataLabels: {
      enabled: true,
    },
		xaxis: {
      categories: data.ValLabels,
			// labels:{
			// 	formatter: function(val){
			// 		return val.toFixed(0);
			// 	}
			// }
    },
		yaxis:{
			show: true,
		}
	};
	var chart = new ApexCharts(document.querySelector("#purchase-chart"), options);
	chart.render();
};
function actionStatisticPurchase() {
	var id = '{{ auth_user()->id }}';
	var data;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('source-chart-purchase') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			chartBarPurchase(result);
		}
	});
};
</script>
<script>
	actionLoadDataActivities();
	mainDataOpportunities();
	mainDataActivity('act_total','all_status','all_user');
	actionStatisticLead();
	actionStatisticOpportunity();
	actionStatisticPurchase();
</script>
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
				actionLoadDataActivities();
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
				actionLoadDataActivities();
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already updated.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionLoadActivities('act-total');
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
			var date_parameter = info.dateStr;
			actionPreviewTodo(date_parameter);
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
@endpush