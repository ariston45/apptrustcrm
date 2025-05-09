@extends('layout.app')
@section('title')
	Customer
@endsection
@section('pagetitle')
	<div class="page-pretitle"></div>
	<h4 class="page-title">Principle</h4>
@endsection
@section('breadcrumb')
	<li class="breadcrumb-item"><a href="#">Step one</a></li>
	<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="col-md-12 ">
	<div class="card">
		<div class="card-header card-header-custom card-header-light">
			<h3 class="card-title">Products Data</h3>
			<div class="card-actions" style="padding-right: 10px;">
				<button class="btn btn-sm btn-primary btn-pill btn-light" style="vertical-align: middle;" onclick="actionCreatePrinciple()">
					<div style="font-weight: 700;">
						<i class="ri-add-circle-line icon" style="font-size: 14px; vertical-align: middle;"></i> Create
					</div>
				</button>
			</div>
		</div>
		<div class="card-body card-body-custom">
			<div id="table-default" class="">
				<table class="table custom-datatables" id="principle-table" style="width: 100%;">
					<thead>
						<tr>
							<th style="width: 35%;">Principle</th>
							<th style="text-align: left; width: 65%;">Describe</th>
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
<div id="modal-create-principle" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Create Principle</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent1" name="form_content1" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<div class="col-sm-12 col-xl-8">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label pt-1 pb-1">Principle Name</label>
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<input type="text" id="inp-product" name="principle" class="form-control p-1" placeholder="Input product name" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-md-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Describe Product</label>
								<textarea id="describePrinciple" name="describe_principle"></textarea>
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
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent1"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ****************************************************************************************************************************************************** --}}
<div id="modal-create-principle-update" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Update Product</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent2" name="form_content2" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<div class="col-sm-12 col-xl-8">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label pt-1 pb-1">Principle Name</label>
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<input type="text" id="inp-principle" name="principle" class="form-control p-1" placeholder="Input product name" autocomplete="off">
									<input type="hidden" id="id-principle" name="id" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-md-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="col-12 col-form-label">Describe Product</label>
								<textarea id="describePrincipleUpdate" name="describe_principle"></textarea>
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
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent2"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
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
	p {
		margin-bottom: 0px;
	}
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
var notes_principle = tinymce.init({
	selector: 'textarea#describePrinciple',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
var notes_principle_update = tinymce.init({
	selector: 'textarea#describePrincipleUpdate',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
</script>
<script>
function mainDataPrinciple() {
	$('#myTable_filter input').addClass('form-control custom-datatables-filter');
	$('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
	var id = "";
	$('#principle-table').DataTable({
		processing: true,serverSide: true,responsive: true,
		pageLength: 15,
		lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
		language: {
			lengthMenu : "Show  _MENU_",
			search: "Find Lead"
		},
		ajax: {
			'url': '{!! route("source-principle") !!}',
			'type': 'POST',
			'data': {
				'_token': '{{ csrf_token() }}',
				'id' : id
			}
		},
		order:[[0,'asc']],
		columns: [
			{data: 'principle', name: 'principle', orderable: true, searchable: true },
			{data: 'describe', name: 'describe', orderable: true, searchable: true },
			{data: 'menu', name: 'menu', orderable: false, searchable: false },
		]
	});	
};
function actionCreatePrinciple() {
	$('#modal-create-principle').modal('toggle');
};
function actionGetDataPrinciple(id) {
	var dataPrinReturn;  
	$.ajaxSetup({
		headers: {
			"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('action-check-principle-item') }}",
		data:{
			id:id
		},
		async:false,
		success: function(result) {
			dataPrinReturn = result;
		}
	});
	return dataPrinReturn;
};
function actionDetailPrinciple(id) {
	var dataPrin = actionGetDataPrinciple(id); 
	$('#id-principle').val(dataPrin.prd_id);
	$('#inp-principle').val(dataPrin.name);
	tinymce.remove('#describePrincipleUpdate');
	tinymce.init({
		selector: 'textarea#describePrincipleUpdate',
		height : "300",
		promotion: false,
		statusbar: false,
		setup: function(editor) {
			editor.on('init',function(e) {
				editor.setContent(dataPrin.describe);
			});
		}
	});
	$('#modal-create-principle-update').modal('toggle');
};
</script>
{{-- Call Function --}}
<script>
mainDataPrinciple();
</script>
{{-- Storege data --}}
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
			url: "{{ route('store-data-principle') }}",
			data: formData1,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#principle-table').DataTable().ajax.reload();
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
			url: "{{ route('store-data-principle-update') }}",
			data: formData2,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$('#principle-table').DataTable().ajax.reload();
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
