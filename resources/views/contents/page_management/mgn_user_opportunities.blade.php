@php
$activePage = app('request')->input('extpg');
@endphp
@extends('layout.app')
@section('title')
Customer
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title"></h4>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Step one</a></li>
<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="col-md-12 ">
	<div class="card" style="margin-bottom:150px;">
		<div class="card-header card-header-custom card-header-light">
			<h3 class="card-title">Management</h3>
			<div class="card-actions" style="padding-right: 10px;">
				<a href="{{ url('management') }}">
					<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
						<div style="font-weight: 700;">
							<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
						</div>
					</button>
				</a>
			</div>
		</div>
		<div class="row g-0">
			@include('contents.page_management.mgn_user_menu',['id' => $id])
			<div id="" class="col d-flex flex-column">
				<div class="card-body p-3">
					<div class="row mb-2">
						<div class="col">
							<h2 align="left">Opportunities</h2>
						</div>
						<div class="col-auto ms-auto">
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div id="table-default" class="">
								<table class="table custom-datatables" id="lead-table" style="width: 100%;">
									<thead>
										<tr>
											<th style="width: 25%;">Customer</th>
											<th style="width: 25%;">Project Title</th>
											<th style="width: 13%;">Status</th>
											<th style="width: 10%;">Follow Up</th>
											<th style="width: 12%;">Last Activity</th>
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
<div class="modal modal-blur fade" id="modal-view-detail-cst" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Customer</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-8">
						<div class="mb-2">
							<small class="form-hint">Name</small>
							<h4 id="individu-name"></h4>
						</div>
						<div class="mb-2">
							<small class="form-hint">Job Role</small>
							<h4 id="jobrole"></h4>
						</div>
						<div class="mb-2">
							<small class="form-hint">Phone</small>
							<h4 id="individu-phone"></h4>
						</div>
						<div class="mb-2">
							<small class="form-hint">Mobile</small>
							<h4 id="individu-mobile"></h4>
						</div>
						<div class="mb-2">
							<small class="form-hint">Email</small>
							<h4 id="individu-email"></h4>
						</div>
						<div class="mb-2">
							<small class="form-hint">Address</small>
							<h4 id="individu-address"></h4>
						</div>
						<div class="mb-2">
							<small class="form-hint">Notes</small>
							{!! html_entity_decode('<h4 id="individu-note"></h4>') !!}
						</div>
					</div>
					<div class="col" style="text-align: right;">
						<a href="#" class="avatar avatar-upload rounded">
							<span class="avatar-upload-text">Add</span>
						</a>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				{{-- <button type="button" class="btn me-auto btn-sm" data-bs-dismiss="modal">Close</button> --}}
				<button type="button" class="btn btn-sm" data-bs-dismiss="modal">Close</button>
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
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
{{-- Varibles --}}
<script>
</script>
{{-- Function --}}
<script>
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
function mainDataLeads() {
	var id = "{{ $id }}";
	$('#myTable_filter input').addClass('form-control custom-datatables-filter');
	$('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
	$('#lead-table').DataTable({
		processing: true,serverSide: true,responsive: true,
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Lead"
		},
		ajax: {
			'url': '{!! route("source-data-opportunities-user") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'id' : id
			}
		},
		order:[[0,'asc']],
		columns: [
			// {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable:false},
			{data: 'customer', name: 'customer', orderable: true, searchable: true },
			{data: 'title', name: 'title', orderable: true, searchable: true },
			{data: 'status', name: 'status', orderable: true, searchable: true },
			// {data: 'salesperson', name: 'salesperson', orderable: true, searchable: true },
			{data: 'activity', name: 'activity', orderable: true, searchable: true },
			{data: 'last_activity', name: 'last_activity', orderable: true, searchable: true },
			// {data: 'menu', name: 'menu', orderable: false, searchable: false },
		]
	});	
}
</script>
{{-- Declarate function --}}
<script>
mainDataLeads();
</script>
{{-- Ready Fucntion --}}
<script>

</script>
@endpush