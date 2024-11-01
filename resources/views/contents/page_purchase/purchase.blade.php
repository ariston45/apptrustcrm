@extends('layout.app')
@section('title')
	Customer
@endsection
@section('pagetitle')
	<div class="page-pretitle"></div>
	<h4 class="page-title">Purchased</h4>
@endsection
@section('breadcrumb')
	<li class="breadcrumb-item"><a href="#">Step one</a></li>
	<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="col-md-12 ">
	<div class="card">
		<div class="card-header card-header-custom card-header-light">
			<h3 class="card-title">Purchased Data</h3>
			<div class="card-actions" style="padding-right: 10px;">
				<button class="btn btn-sm btn-primary btn-pill btn-light" style="vertical-align: middle;" onclick="actionCreatePurchase()">
					<div style="font-weight: 700;">
						<i class="ri-add-circle-line icon" style="font-size: 14px; vertical-align: middle;"></i> Create
					</div>
				</button>
				{{-- <a href="{{ url('leads/create-lead') }}">
				</a> --}}
			</div>
		</div>
		<div class="card-body card-body-custom">
			<div id="table-default" class="">
				<table class="table custom-datatables" id="customer-table" style="width: 100%;">
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
	</div>
</div>
{{-- ****************************************************************************************************************************************************** --}}
<div id="modal-create-purchased" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Create Purchase Order</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent2" name="form_content2" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						{{-- <input type="hidden" id="opportunity_status_id" name="opportunity_status_id" value="{{ $opportunity->lds_status }}" readonly> --}}
						<div class="col-sm-12 col-xl-6">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label pt-1 pb-1">Opportunity</label>
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<select type="text" id="select-opportunity" class="form-select ts-input-custom" name="opportunity" placeholder="Select opportunity">
										<option value="{{ null }}">Select principle</option>
									</select>
								</div>
							</div>
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label pt-1 pb-1">Purchased Date</label>
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_purchase" name="date_purchase" class="form-control p-1" placeholder="Purchase date" autocomplete="off">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-xl-6">
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label pt-1 pb-1">Invoice Number</label>
								<div class="col" style="padding: 0px;">
									<input type="text" id="inp_purchase" name="number_invoice" class="form-control p-1" placeholder="Invoice number" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-md-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Notes Purchase</label>
								<textarea id="notesPurchase" name="note_purchase"></textarea>
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
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent2"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ****************************************************************************************************************************************************** --}}
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<style>
	.custom-datatables tbody tr td{
		padding-top: 4px;
		padding-bottom: 4px;
	}
	.paginate_button .current {
		background-color: red;
	}
	.modal-full-width-custom {
    max-width: none;
    margin-top: 0px;
    margin-right: 8rem;
    margin-bottom: 0px;
    margin-left: 8rem;
	}
	.ts-control{
		padding-bottom: 0.28rem;
		padding-top: 0.28rem;
		padding-left: 0.39rem;
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
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
<script>
var select_purchase = new TomSelect("#select-opportunity",{
	persist: false,
	createOnBlur: true,
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {},
});
var notes_purchase = tinymce.init({
	selector: 'textarea#notesPurchase',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
const picker_a = new easepick.create({
	element: "#datepicker_purchase",
	css: [ "{{ asset('plugins/litepicker/bundle/index.css') }}" ],
	zIndex: 10,
	format: "YYYY-MM-DD",
	AmpPlugin: {
		resetButton: true,
		darkMode: false
	},
});
</script>
<script>
function actionCreatePurchase() {
	select_purchase.clear();
	select_purchase.clearOptions();
	$.ajaxSetup({
		headers: {
			"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('action-check-win-opportunity') }}",
		data: {},
		success: function(result) {
			var Fdata = [];
			for (let i = 0; i < result.data.length; i++) {
				Fdata.push({id:result.data[i].id,title:result.data[i].title});
			}
			select_purchase.addOptions(Fdata);
		}
	});
	$('#modal-create-purchased').modal('toggle');
};
</script>
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
			search: "Find Lead"
		},
		ajax: {
			'url': '{!! route("source-data-purchased") !!}',
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
mainDataLeads();
</script>
<script>
$(document).ready(function() {
	$("#formContent2").submit(function(e) {
		e.preventDefault();
		var formData2 = new FormData(this);
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('store-data-purchase-ii') }}",
			data: formData2,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#customer-table').DataTable().ajax.reload();
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already created.',
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
@endpush
