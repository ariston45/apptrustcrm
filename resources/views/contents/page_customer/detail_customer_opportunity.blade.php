@php
$activePage = app('request')->input('extpg');
@endphp
@extends('layout.app')
@section('title')
Customer
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title">Customer : {{ $company->ins_name }}</h4>
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
				<a href="{{ url('customer/create-new-opportunity-cst') }}/{{ $id }}">
					<button class="btn btn-sm btn-default btn-pill" style="vertical-align: middle;">
						<div style="font-weight: 700;">
							<i class="ri-add-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
							Create
						</div>
					</button>
				</a>
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
						<div class="col">
							<h2 align="left">Opportunities</h2>
						</div>
						<div class="col-auto">
							<div align="right">
								{{-- <a href="{{ url('customer/detail-customer/company-update/'.$id) }}" class="btn btn-sm btn-outline"><i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit</a> --}}
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div id="table-default" class="">
							<table class="table custom-datatables" id="opportunity-table" style="width: 100%;">
								<thead>
									<tr>
										{{-- <th></th> --}}
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
					<div id="row">
						
					</div>
				</div>
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
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalender-scheduler/dist/index.global.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
<script>
$('#myTable_filter input').addClass('form-control custom-datatables-filter');
$('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
function mainDataLeads() {
	var id = "{{ $id }}";
	$('#opportunity-table').DataTable({
		processing: true,serverSide: true,responsive: true,
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Opportunity"
		},
		ajax: {
			'url': '{!! route("source-opportunities-cst") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'id' : id
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
			// {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable:false},
			{data: 'title', name: 'title', orderable: true, searchable: true },
			{data: 'customer', name: 'customer', orderable: true, searchable: true },
			{data: 'status', name: 'status', orderable: true, searchable: true },
			// {data: 'datein', name: 'datein', orderable: true, searchable: true },
			{data: 'salesperson', name: 'salesperson', orderable: true, searchable: true },
			{data: 'menu', name: 'menu', orderable: false, searchable: false },
		]
	});	
}
</script>
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
		url: "{{ route('source-data-activity-calender-cst') }}",
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
			'url': '{!! route("source-data-activity") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'act_status' : status_act,
				'act_param': type_act
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
</script>
<script>
mainDataLeads();
</script>
@endpush