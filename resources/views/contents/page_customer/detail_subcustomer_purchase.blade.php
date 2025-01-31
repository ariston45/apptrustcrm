@php
$activePage = app('request')->input('extpg');
@endphp
@extends('layout.app')
@section('title')
Customer
@endsection
@section('pagetitle')
<h4 class="page-title">Sub Customer : {{ $company->cst_name }}</h4>
<div class="page-pretitle">Customer : {{ $company->ins_name }}</div>
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
			@include('contents.page_customer.detail_subcustomer_menu',['id' => $id])
			<div id="" class="col d-flex flex-column">
				<div class="card-body">
					<div class="row mb-2">
						<div class="col">
							<h2 align="left">Purchase</h2>
						</div>
						<div class="col-auto">
							<div align="right">
								{{-- <a href="{{ url('customer/detail-customer/company-update/'.$id) }}" class="btn btn-sm btn-outline"><i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit</a> --}}
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div id="table-default" class="">
							<table class="table custom-datatables" id="customer-table" style="width: 100%;">
								<thead>
									<tr>
										<th style="width: 40%;">Project Title</th>
										<th style="width: 25%;">Customer</th>
										<th style="width: 10%;">Status</th>
										<th style="width: 15%;">Salesperson</th>
										<th style="text-align:center; width: 5%">Menus</th>
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
function mainDataPurchase() {
	var id = "{{ $id }}";
	$('#customer-table').DataTable({
		processing: true,serverSide: true,responsive: true,
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Lead"
		},
		ajax: {
			'url': '{!! route("source-data-purchased-bycst") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'id' : id
			}
		},
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
mainDataPurchase();
</script>
@endpush