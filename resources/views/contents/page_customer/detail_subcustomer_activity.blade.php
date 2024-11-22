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
			@include('contents.page_customer.detail_subcustomer_menu',['id' => $id])
			<div id="" class="col d-flex flex-column">
				<div class="card-body">
					<div class="row mb-2">
						<div class="col">
							<h2 align="left">Activities</h2>
						</div>
						<div class="col-auto">
							<div align="right">
								{{-- <a href="{{ url('customer/detail-customer/company-update/'.$id) }}" class="btn btn-sm btn-outline"><i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit</a> --}}
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-xl-8 mb-sm-2 mb-xl-0">
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
												<div class="col-md-5 col-xl-12">
													<strong>Customer</strong>
												</div>
												<div class="col-auto">
													<span id="value-act-customer">-</span>
												</div>
											</div>
											<div class="row pb-2">
												<div class="col-md-5 col-xl-12">
													<strong>Activity</strong>
												</div>
												<div class="col-auto">
													<span id="value-act-type">-</span>
												</div>
											</div>
											<div class="row pb-2">
												<div	div class="col-md-5 col-xl-12">
													<strong>Due Date</strong>
												</div>
												<div	div class="col-auto">
													<span id="value-act-due-date">-</span>
												</div>
											</div>
											<div class="row pb-2">
												<div class="col-md-5 col-xl-12">
													<strong>Assign</strong>
												</div>
												<div class="col-auto">
													<span id="value-act-assign">-</span>
												</div>
											</div>
											<div class="row pb-2">
												<div class="col-md-5 col-xl-12">
													<strong>Team</strong>
												</div>
												<div class="col-auto">
													<span id="value-act-team">-</span>
												</div>
											</div>
											<div class="row pb-2">
												<div class="col-md-5 col-xl-12">
													<strong>PIC</strong>
												</div>
												<div class="col-auto">
													<span id="value-act-personal">-</span>
												</div>
											</div>
											<div class="row pb-2">
												<div class="col-md-5 col-xl-12">
													<strong>Completion</strong>
												</div>
												<div class="col-auto">
													<span id="value-act-completion">-</span>
												</div>
											</div>
											<div class="row pb-2">
												<div class="col-12">
													<u><strong>Information Describe : </strong></u>
												</div>
												<div class="col-auto">
													<span id="value-act-describe">-</span>
												</div>
												<div class="col-12">
													<u><strong>Information Result : </strong></u>
												</div>
												<div class="col-auto">
													<span id="value-act-result">-</span>
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
					<div id="row">
						<div class="col-12">
							<div id="init-page-activities" class="row mb-2">
								<em class="col text-muted lh-base mb-1"><i>Activities</i></em>
								<div class="col-auto">
									<input type="hidden" id="param-code-status" name="code_status" value="act_total" readonly>
								</div>
							</div>
							<div id="contact-area" class="row">
								<div class="col-6 col-sm-4 col-md-2 col-xl py-3 pt-1 pb-2">
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
								<div class="col-6 col-sm-4 col-md-2 col-xl py-3 pt-1 pb-2">
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
												<th style="text-align: center; width: 15%;">Due date</th>
												<th style="text-align: center; width: 8%;">Activity</th>
												<th style="text-align: center; width: 15%;">Customer</th>
												<th style="text-align: center; width: 31%;">Info</th>
												<th style="text-align: center; width: 15%;">Project</th>
												{{-- <th style="text-align: center; width: 15%;">Assign</th> --}}
												<th style="text-align: center; width: 9%;">Completion</th>
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
{{-- <link rel="stylesheet" href=""> --}}
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
	.table tr:nth-child(even){background-color: #f2f2f2;}
	.table tr:hover {background-color: #ddd;}
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
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalender-scheduler/dist/index.global.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
{{-- Variables --}}
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
</script>
{{-- fullcalender --}}
{{-- ============================================================ --}}
<script>
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
function sourceDataActivityCalender(start,end) {
	var passData = "";
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('source-data-activity-calender-subcst') }}",
		async: false,
		data: {
			'start':start,
			'end':end,
			'cst_id': "{{ $id }}"
		},
		success: function(result) {
			passData = result.activities;
		}
	});
	return passData;
};
function mainDataActivity(type_act,status_act) {
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
			'url': '{!! route("source-data-activity-subcst") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'act_status' : status_act,
				'act_param': type_act,
				'cst_id': '{{ $id }}'
			}
		},
		columnDefs: [
			{
				"targets": 2, 
				"className": "text-center",
			},
			{
				"targets": 3, 
				"className": "text-center",
			}
		],
		order:[[0,'asc']],
		columns: [
			{data: 'menu', name: 'menu', orderable: false, searchable: false },
			{data: 'due_date', name: 'due_date', orderable: true, searchable: true },
			{data: 'title', name: 'title', orderable: true, searchable: true },
			{data: 'customer', name: 'customer', orderable: true, searchable: true },
			{data: 'info', name: 'info', orderable: true, searchable: true },
			{data: 'project', name: 'project', orderable: true, searchable: true },
			/*
			{data: 'assign', name: 'assign', orderable: true, searchable: true },
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
      }
		},
	});
	return tableAct;
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
	var cr = mainDataActivity(type_act,'all_status');
};
function actioFilterCompletion(param) {
	var type = $('#param-code-status').val();
	var status = param.value;
	$('#activity-table').DataTable().clear().destroy();
	mainDataActivity(type,status);
};
function actionResetDetailCalender() {  
	$('#act-detail-display').hide();  
	$('#act-detail-no-display').fadeIn();
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
</script>
<script>
mainDataActivity('act_total','all_status');
</script>
<script>
$(document).ready(function() {
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