@extends('layout.app')
@section('title')
	Customer
@endsection
@section('pagetitle')
	<div class="page-pretitle"></div>
	<h4 class="page-title">Opportunity</h4>
@endsection
@section('breadcrumb')
	<li class="breadcrumb-item"><a href="#">Step one</a></li>
	<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="col-md-12 ">
	<div class="card">
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
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
<style>
	.custom-datatables tbody tr td{
		padding-top: 4px;
		padding-bottom: 4px;
	}
	.paginate_button .current {
		background-color: red;
	}
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
{{-- Datatables --}}
<script>
$('#myTable_filter input').addClass('form-control custom-datatables-filter');
$('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
function mainDataLeads() {
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
		order:[[0,'asc']],
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
}
</script>
<script>
mainDataLeads();
</script>
@endpush
