@php
$activePage = app('request')->input('extpg');
@endphp
@extends('layout.app')
@section('title')
Customer
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title">Customer : {{ $company->cst_name }}</h4>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Step one</a></li>
<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="col-md-12 ">
	<div class="card" style="margin-bottom:150px;">
		<div class="card-header card-header-custom card-header-light">
			<h3 class="card-title">Detail Customer</h3>
			<div class="card-actions" style="padding-right: 10px;">
				<button id="btn-create-lead" class="btn btn-sm btn-default btn-pill" style="vertical-align: middle;">
					<div style="font-weight: 700;">
						<i class="ri-add-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 4px;"></i> Create Lead
					</div>
				</button>
				<a href="{{ url('customer') }}">
					<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
						<div style="font-weight: 700;">
							<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
						</div>
					</button>
				</a>
			</div>
		</div>
		<div class="row g-0">
			@include('contents.page_customer.detail_customer_menu',['id' => $id])
			<div id="" class="col d-flex flex-column">
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-6">
							<h2 align="left">Leads</h2>
						</div>
						<div class="col-6">
							<div align="right">
								{{-- <a href="{{ url('customer/detail-customer/company-update/'.$id) }}" class="btn btn-sm btn-outline"><i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit</a> --}}
								<div style="text-align:right;">
									{{-- <button type="button" class="btn btn-sm btn-pill btn-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<i class="ri-settings-3-fill"></i>&nbsp;&nbsp;<strong>SETTING</strong>
									</button> --}}
									{{-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="ri-settings-3-fill"></i></button> --}}
									<div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(339px, 110px);">
										<a id="btn-config-lead-status" class="dropdown-item" href="#"><i class="ri-booklet-line" style="margin-right:6px;"></i>Config Lead Status</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-12">
							<table class="table custom-datatables" id="lead-table" style="width: 100%;">
								<thead>
									<tr>
										{{-- <th></th> --}}
										<th style="text-align: left;width: 45%;">Project Name</th>
										<th style="text-align: left;width: 15%;">Status</th>
										<th style="text-align: left;width: 30%;">Salesperson</th>
										<th style="text-align: left;width: 10%;">Date Created</th>
										{{-- <th style="text-align: center;">Menus</th> --}}
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
<div class="modal modal-blur fade" id="modal-view-create-lead-form" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-custom-width modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Create Lead</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formContent1" name="form_content1" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<div class="col-xl-6">
							<div class="mb-2" id="">
								<label class="form-label mb-1">Lead Title</label>
								<input name="lead_title" id="lead-title" type="text" class="form-control val-input-lead p-1" placeholder="Type title of lead here ...">
								<input type="hidden" name="idcustomer" value="{{ $id }}">
								<input type="hidden" id="parameter_input" value="">
							</div>
							<div class="mb-2" id="">
								<label class="form-label mb-1">Base Value</label>
								<input name="base_value" id="base-value" type="text" class="form-control p-1" placeholder="Rp 0,00" oninput="fcurrencyInput('base-value')">
							</div>
							<div class="mb-2" id="">
								<label class="form-label m-1">Target Value</label>
								<input name="target_value" id="target-value" type="text" class="form-control p-1" placeholder="Rp 0,00" oninput="fcurrencyInput('target-value')">
							</div>
						</div>
						<div class="col-xl-6">
							<div class="mb-2">
								<label class="form-label mb-1">Select PIC Contact</label>
								<select type="text" class="form-select val-input-lead p-1" name="customer_pic_contact[]" multiple id="select-customer-contact" value="" placeholder="Select customer here">
									<option value="{{ null }}"></option>
									@foreach ($personal_contact as $list)
										<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
									@endforeach
								</select>
							</div>
							<div class="mb-2">
								<label class="form-label mb-1">Assign User</label>
								<select type="text" class="form-select val-input-lead p-1" name="assign_sales" id="select-assign-user" placeholder="Select marketing or sales here">
									<option value="{{ null }}"></option>
									@foreach ($all_users as $list)
										@if ($list->id == $user->id)
										<option value="{{ $list->id }}" selected>{{ $list->name }}</option>	
										@else
										<option value="{{ $list->id }}">{{ $list->name }}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="mb-2">
								<label class="form-label mb-1">Colaborator</label>
								<select type="text" class="form-select val-input-lead p-1" name="assign_team[]" id="select-team-user" placeholder="Select marketing or sales here">
									<option value="{{ null }}"></option>
									@foreach ($all_users as $list)
									<option value="{{ $list->id }}">{{ $list->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="padding-right: 25px;">
				<button type="button" id="ResetButton" class="btn btn-sm me-auto"><i class="ri-refresh-line" style="width: 50px;"></i></button>
				<button type="submit" data-id="save_and_again" onclick="submitFunc('save_and_again')" class="btn btn-sm btn-ghost-secondary" form="formContent1" >Save & Create Again </button>
				<button type="submit" data-id="save_and_close" onclick="submitFunc('save_and_close')" class="btn btn-sm btn-ghost-primary active" form="formContent1" style="padding-left: 20px;padding-right: 20px;">SAVE</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-blur fade" id="modal-view-config-lead-status" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-custom-width modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Leads Status Configuration</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formContent2" name="form_content1" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th>Status Title</th>
										<th>Color</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($status_configs as $item)
									<tr>
										<td> <input name="status_title[]" type="text" class="form-control p-1 {{ $item->pls_status_color }}" value="{{ $item->pls_status_name }}"> </td>
										<td>
											<select class="form-select p-1">
												<option value="{{ $item->pls_status_color }}" >{{ Str::title(Str::between($item->pls_status_color, '-', '-')) }}</option>
												<option class="bg-blue-lt" value="bg-blue-lt">Blue</option>
												<option class="bg-azure-lt" value="bg-azure-lt">Azure</option>
												<option class="bg-indigo-lt" value="bg-indigo-lt">Indigo</option>
												<option class="bg-purple-lt" value="bg-purple-lt">Purple</option>
												<option class="bg-pink-lt" value="bg-pink-lt">Pink</option>
												<option class="bg-red-lt" value="bg-red-lt">Red</option>
												<option class="bg-blue-lt" value="bg-orange-lt">Orange</option>
												<option class="bg-yellow-lt" value="bg-yellow-lt">Yellow</option>
												<option class="bg-lime-lt" value="bg-lime-lt">Lime</option>
												<option class="bg-green-lt" value="bg-green-lt">Green</option>
												<option class="bg-teal-lt" value="bg-teal-lt">Teal</option>
												<option class="bg-cyan-lt" value="bg-cyan-lt">Cyan</option>
											</select>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="padding-right: 12px;">
				<button type="button" id="ResetButton" class="btn btn-sm me-auto"><i class="ri-refresh-line"></i></button>
				{{-- <button type="submit" data-id="save_and_again" onclick="submitFunc('save_and_again')" class="btn btn-sm btn-ghost-secondary" form="formContent2" >Save & Create Again </button> --}}
				<button type="submit" data-id="save_and_close" onclick="submitFunc('save_and_close')" class="btn btn-sm btn-ghost-primary active" form="formContent2" style="padding-left: 20px;padding-right: 20px;">SAVE</button>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-blur fade" id="modal-view-action-followup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-custom-width modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Follow Up Action - Phone</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formContent2" name="form_content2" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="action_todo" name="action" value="" readonly>
					<input type="hidden" id="lead_id" name="lead_id" value="" readonly>
					<input type="hidden" id="lead_status_id" name="lead_status_id" value="" readonly>
					<div class="mb-2 mt-3 row" style="margin-right: 20px;">
						<label class="col-3 col-form-label">Date</label>
						<div class="col" style="padding: 0px; margin-right: 10px;">
							<div class="input-group" id="datetimepicker1">
								<span class="input-group-text p-1">
									<i class="ri-calendar-2-line"></i>
								</span>
								<input type="text" id="datepicker_start" name="start_date" class="form-control p-1" placeholder="Start Date" autocomplete="off">
							</div>
						</div>
						<div class="col" style="padding: 0px;margin-left: 10px;">
							<div class="input-group">
								<span class="input-group-text p-1">
									<i class="ri-calendar-2-line"></i>
								</span>
								<input type="text" id="datepicker_due" name="due_date" class="form-control p-1" placeholder="Due Date" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="mb-2 row" style="margin-right: 20px;">
						<label class="col-3 col-form-label">Assigned to</label>
						<div class="col" style="padding: 0px;">
							<select type="text" class="form-select val-input-lead p-1" name="assignment_user" placeholder="User assignment..." id="select-signed-user" value="">
								<option value="{{ null }}"></option>
								@foreach ($all_users as $list)
								<option value="{{ $list->id }}">{{ $list->name }}</option>
								@endforeach
							</select>
							<button type="button" id="btn-add-team" class="badge mt-1 mb-1" onclick="actionViewInputTeam()">+ Team</button>
							<button type="button" id="btn-remove-team" class="badge mt-1 mb-1" style="display: none;">x Close</button>
							<div id="select-signed-user-team-area" style="display: none;">
								<select type="text" class="form-select val-input-lead p-1" name="assignment_user_team[]" placeholder="User team assignment..." id="select-signed-user-team" value="">
									<option value="{{ null }}"></option>
									@foreach ($all_users as $list)
									<option value="{{ $list->id }}">{{ $list->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="mb-2" style="margin-right: 20px;">
						<label class="col-12 col-form-label">Summary</label>
						<textarea id="notesInputLeadFolUp" name="activity_summary"></textarea>
					</div>
					<div class="mb-2" style="margin-right: 20px;">
						<label class="form-check">
							<input id="todo_done" class="form-check-input" type="checkbox" name="todo_status" value="done" onclick="actionTodo('todo_done')">
							<span class="form-check-label">It is already done.</span>
						</label>
						<label class="form-check">
							<input id="todo_running" class="form-check-input" type="checkbox" name="todo_status" value="running" onclick="actionTodo('todo_running')">
							<span class="form-check-label">It is already running.</span>
						</label>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="padding-right: 25px;">
				<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent2" style="padding-left: 20px;padding-right: 20px;">CREATE</button>
			</div>
		</div>
	</div>
</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<style>
	.list-group-transparent .list-group-item.active {
		background: rgb(35 196 32 / 24%);
	}
	.card-subtitle{
		margin-bottom: 12px;
	}
	/* table */
	.table {
		border-collapse: collapse;
		width: 100%;
		font-size: 13px;
	}
	.table td, .table th {
		border: none;
		padding: 2px;
	}
	.table th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
	}
	.table td {
		padding-top: 6px;
		padding-bottom: 6px;
		text-align: left;
	}
	.custom-datatables tr:nth-child(even){background-color: #f2f2f2;}
	.custom-datatables tr:hover {background-color: #ddd;}
	/* Tom select */
	.ts-control{
		padding-bottom: 0.28rem;
		padding-top: 0.28rem;
		padding-left: 0.39rem;
	}
	.ts-wraper{
		
	}
	.val-input-lead{
		min-height: 0.53rem;
	}
	
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
{{-- Datas AJAX--}}
<script>
$('#myTable_filter input').addClass('form-control custom-datatables-filter');
$('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
$(document).ready(function() {
	var idx = "{{ $id }}";
	var lead_table = $('#lead-table').DataTable({
		processing: true,serverSide: true,responsive: true,
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Lead "
		},
		ajax: {
			'url': '{!! route("source-data-lead-cst") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'id' : idx
			}
		},
		order:[[0,'asc']],
		columnDefs : [
			{ "type": "datetime ", "targets": 3 }
		],
		columns: [
			// {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable:false},
			{data: 'title', name: 'title', orderable: true, searchable: true },
			{data: 'status', name: 'status', orderable: true, searchable: true },
			{data: 'salesperson', name: 'salesperson', orderable: true, searchable: true },
			{data: 'created', name: 'created', orderable: true, searchable: true },
		],
	});
});
</script>
{{-- Variable --}}
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
			return '<div id="select-signed-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_customer_contact = new TomSelect("#select-customer-contact",{
	create: true,
	maxItems: 5,
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-customer-contact">'+escape(data.title)+'</div>';
		}
	}
});
var select_assign_sales = new TomSelect("#select-assign-user",{
	create: true,
	maxItems: 1,
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-assign-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_team_sales = new TomSelect("#select-team-user",{
	create: true,
	maxItems: 1,
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-team-user">'+escape(data.title)+'</div>';
		}
	}
});
/************************************************************************/
var notesStoredLOC =  localStorage.getItem('notesInputLeadFolUp');
var notesEitor = ""; 
var notes_a = tinymce.init({
	selector: 'textarea#notesInputLeadFolUp',
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
			localStorage.setItem('notesInputLeadFolUp', notes);
		});
	}
});
/************************************************************************/
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
</script>
{{-- Function --}}
<script>
function ResetFormLead(x,y) {
	var frm = document.getElementsByName('form_content2')[0];
	frm.reset();
	x.clear();
	y.clear();
	$('#estimate-base-value').html('0,00');
};
function ResetFormFolUp(x,y) {
	var actionFollow = $('#action_todo').val();
	picker_a.clear();
	picker_b.clear();
	x.clear();
	y.clear();
	$('#btn-remove-team').hide();  
	$('#select-signed-user-team-area').slideUp();
	if (actionFollow == 'act_phone' || actionFollow == 'act_email') {
		$('#btn-add-team').hide();
	}else{
		$('#btn-add-team').show();
	}
	localStorage.removeItem("notesInputLeadFolUp");
	tinyMCE.activeEditor.setContent('');
}
function submitFunc (param) { 
	$('#parameter_input').val(param);
};
function actionDoing(x,a,y) {
	$('#lead_id').val(x);
	$('#action_todo').val(a);
	$('#lead_status_id').val(y);
	if (a == 'act_email' || a == 'act_phone') {
		$('#btn-add-team').hide();
	}else{
		$('#btn-add-team').show();
	}
	$('#modal-view-action-followup').modal('toggle');
};
function actionViewInputTeam() {
	$('#btn-add-team').hide();
	$('#btn-remove-team').show(); 
	$('#select-signed-user-team-area').slideDown();
};
function actionRemoveInputTeam(x) {
		x.clear();
		$('#btn-remove-team').hide();  
		$('#btn-add-team').show();
		$('#select-signed-user-team-area').slideUp();
};
function actionTodo(x) { 
	if (x == 'todo_done') {
		document.getElementById("todo_running").checked = false;
	}else{
		document.getElementById("todo_done").checked = false;
	}
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
function GetURLParameter(sParam){
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam) {
			return sParameterName[1];
		}
	}
}
function activeInformationPage() {
	$("#dynamic-menu-information").addClass("active");
	$("#dynamic-menu-activities").removeClass("active");
	$("#dynamic-menu-leads").removeClass("active");
	$("#dynamic-menu-opportunities").removeClass("active");
	$("#dynamic-menu-po").removeClass("active");
	window.history.replaceState(null, null, "?extpg=information");
}
function activeActivitiesPage() {
	$("#dynamic-menu-activities").addClass("active");
	$("#dynamic-menu-information").removeClass("active");
	$("#dynamic-menu-leads").removeClass("active");
	$("#dynamic-menu-opportunities").removeClass("active");
	$("#dynamic-menu-po").removeClass("active");
	window.history.replaceState(null, null, "?extpg=activities");
}
function activeLeadsPage() {
	$("#dynamic-menu-activities").removeClass("active");
	$("#dynamic-menu-information").removeClass("active");
	$("#dynamic-menu-leads").addClass("active");
	$("#dynamic-menu-opportunities").removeClass("active");
	$("#dynamic-menu-po").removeClass("active");
	window.history.replaceState(null, null, "?extpg=leads");
}
function activeOpportunitiesPage() {
	$("#dynamic-menu-activities").removeClass("active");
	$("#dynamic-menu-information").removeClass("active");
	$("#dynamic-menu-leads").removeClass("active");
	$("#dynamic-menu-opportunities").addClass("active");
	$("#dynamic-menu-po").removeClass("active");
	window.history.replaceState(null, null, "?extpg=opportunities");
}
function activePOPage() {
	$("#dynamic-menu-activities").removeClass("active");
	$("#dynamic-menu-information").removeClass("active");
	$("#dynamic-menu-leads").removeClass("active");
	$("#dynamic-menu-opportunities").removeClass("active");
	$("#dynamic-menu-po").addClass("active");
	window.history.replaceState(null, null, "?extpg=purchasing");
}
</script>
{{-- store data --}}
<script>
$('#formContent1').submit(function(e) {
	e.preventDefault();
	var formData = new FormData(this);
	var px = document.getElementById("parameter_input").value;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('store-lead-data-ver2') }}",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		success: function(result) {
			if (result.param == true) {
				$.alert({
					type: 'green',
					title: 'Success',
					content: result.message,
					animateFromElement: false,
					animation: 'opacity',
					closeAnimation: 'opacity'
				});
				if (px == 'save_and_again') {
					ResetFormLead(lead_status,product_interest);
				}else{
					ResetFormLead(lead_status,product_interest);
					$('#modal-view-create-lead-form').modal('hide');
				}
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
$('#formContent2').submit(function(e) {
	e.preventDefault();
	var formData2 = new FormData(this);
	// console.log(formData2[0].date_start);
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('store-lead-data-follow-up') }}",
		data: formData2,
		cache: false,
		contentType: false,
		processData: false,
		success: function(result) {
			if (result == true) {
				$.alert({
					type: 'green',
					title: 'Success',
					content: 'Data already store to database.',
					animateFromElement: false,
					animation: 'opacity',
					closeAnimation: 'opacity'
				});
				ResetFormFolUp(select_signed_user_team,select_signed_user);
				$('#modal-view-action-followup').modal('hide');
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
</script>
{{-- Action Button --}}
<script>
$('#btn-create-lead').click(function () {
	$('#modal-view-create-lead-form').modal('toggle');
});
$('#btn-config-lead-status').click(function () {
	$('#modal-view-config-lead-status').modal('toggle');
});
/*====================================================*/
$('#ResetButton').click(function () {
	ResetFormLead(lead_status,product_interest);
});
$('#ResetButtonFormFolUp').click(function () {  
	ResetFormFolUp(select_signed_user_team,select_signed_user);
});
$('#btn-remove-team').click(function () { 
	actionRemoveInputTeam(select_signed_user_team);
});
/*=====================================================*/
</script>
{{-- Tom Select --}}
@endpush

