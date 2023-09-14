@php
	$act = Request::get('act');
	if ($act == null) {
		$a = 'none';
	} else {
		$a = $act;
	}
@endphp
@extends('layout.app')
@section('title')
	Customer
@endsection
@section('pagetitle')
	<div class="page-pretitle"></div>
	<h4 class="page-title">Customer</h4>
@endsection
@section('breadcrumb')
	<li class="breadcrumb-item"><a href="#">Step one</a></li>
	<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
	<div class="col-md-12 ">
		<div class="card" style="margin-bottom:150px;">
			<div class="card-header card-header-custom card-header-light">
				<h3 class="card-title">Create Lead</h3>
				<div class="card-actions" style="padding-right: 10px;">
					<button id="btn-action-reset" class="btn btn-sm btn-light btn-pill" style="vertical-align: middle;" onclick="actionResetFormContent()">
						<div style="font-weight: 700;">
							<i class="ri-eraser-line icon" style="font-size: 14px;margin-right: 4px; vertical-align: middle;"></i> Clear
						</div>
					</button>
					<button id="btn-action-save" class="btn btn-sm btn-light btn-pill" style="vertical-align: middle;" form="formContent1">
						<div style="font-weight: 700;">
							<i class="ri-save-3-line icon" style="font-size: 14px;margin-right: 4px; vertical-align: middle;"></i> Save
						</div>
					</button>
					<a href="{{ url('leads') }}">
						<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
							<div style="font-weight: 700;">
								<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
							</div>
						</button>
					</a>
				</div>
			</div>
			<form id="formContent1" enctype="multipart/form-data" action="javascript:void(0)" method="Get" autocomplete="off">
				@csrf
				<div class="card-body card-body-custom">
					<div class="row mb-2">
						<div class="col-xl-6">
							<div class="mb-3 row" id="job-position-area">
								<label class="col-3 col-form-label custom-label">Project Title</label>
								<div class="col">
									<input name="lead_title" id="lead-title" type="text" class="form-control" placeholder="Type project title here">
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label" style="text-align: right;">Select Customer</label>
								<div id="select-customer-area" class="col">
									<select type="text" class="form-select" name="customer" id="select-customer" value="" placeholder="Select customer here">
										<option value="{{ null }}"></option>
										@foreach ($customer as $list)
											<option value="{{ $list->cst_id }}">{{ $list->cst_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label">Base Value</label>
								<div id="multiInputPhone" class="col">
									<div class="input-group">
										<input name="base_value" id="base-value" type="text" class="form-control" placeholder="Rp 0,00" oninput="fcurrencyInput('base-value')">
									</div>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label">Target Value</label>
								<div id="multiInputEmail" class="col">
									<div class="input-group">
										<input name="target_value" id="target-value" type="text" class="form-control" placeholder="Rp 0,00" oninput="fcurrencyInput('target-value')">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label required">Select PIC Contact</label>
								<div class="col">
									<select type="text" class="form-select" name="customer_pic_contact[]" multiple id="select-customer-contact" value="" placeholder="Select customer here">
										<option value="{{ null }}"></option>
									</select>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label required">Assign Lead</label>
								<div class="col">
									<select type="text" class="form-select" name="assign_sales" id="select-assign-sales" value="{{ $user->id }}" placeholder="Select marketing or sales here">
										<option value="{{ null }}"></option>
										@foreach ($sales as $list)
											@if ($list->id == $user->id)
											<option value="{{ $list->id }}" selected>{{ $list->name }}</option>	
											@else
											<option value="{{ $list->id }}">{{ $list->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label">Add sales team</label>
								<div class="col">
									<select type="text" class="form-select" name="assign_team[]" multiple id="select-assign-team" value="{{ $user->id }}" placeholder="Select marketing or sales here">
										<option value="{{ null }}"></option>
										@foreach ($sales as $list)
										<option value="{{ $list->id }}">{{ $list->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</form>
		</div>
	</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-typeahead/jquery.typeahead.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<style>
	.val-text-param {
		position: relative;
	}
	.val-text-param p {
		margin: 0px;
		position: absolute;
		top: 50%;
		-ms-transform: translateY(-50%);
		transform: translateY(-50%);
	}
	.nav-tabs .nav-link {
		border: none;
		color: #ffffffc7;
	}
	.form-control {
		border-radius: 0px;
	}
	.ts-control {
		border-radius: 0px;
	}
	.form-select {
		border-radius: 0px;
	}
	.custom-icon-min-add {
		font-size: 17px;
		color: red;
	}
	.card-header-tabs {
		background-color: #39656b;
	}
	.custom-icon-min {
		font-size: 17px;
	}
	.nav-tabs .nav-link{
		border-radius: 0px;
	}
	.btn-addons {
		padding-top: 2px;
		padding-bottom: 2px;
		border-radius: 0px;
		width: 30px;
		padding-right: 9px;
		padding-left: 9px;
	}
	.typeahead__cancel-button {
		font-size: 24px;
		padding-bottom: 2px;
		border-top-width: 2px;
		padding-top: 6px;
	}
	#photo-frame {
		color: var(--tblr-muted);
		box-sizing: border-box;
		border: 5px solid var(--tblr-border-color);
		padding: 6px;
		margin: auto;
		max-width: 100px;
		max-height: 100px;
		min-height: 122px;
		min-width: 122px;
		border-radius: 10px;
		text-align: center;
	}
	#areaCloseImage {
		text-align: center;
		margin-top: 3px;
	}
	#inputAvatar {
		margin: auto;
		width: 87px;
		height: 100px;
		object-fit: cover;
		z-index: 1;
		/* position: absolute; */
	}
	#closeButton {
		z-index: 2;
		position: ;
		left: 220px;
		top: 180px;
	}
	.custom-label {
		text-align: right;
	}
	.typeahead__field .typeahead__hint,
	.typeahead__field [contenteditable],
	.typeahead__field input,
	.typeahead__field textarea {
		display: block;
		width: 100%;
		padding: 0.4375rem 0.75rem;
		font-family: var(--tblr-font-sans-serif);
		font-size: .875rem;
		font-weight: 400;
		line-height: 1.4285714286;
		color: inherit;
		background-color: var(--tblr-bg-forms);
		background-clip: padding-box;
		border: var(--tblr-border-width) solid var(--tblr-border-color);
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
		border-radius: 0px;
		transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
	}
	.typeahead__container button {
		display: inline-block;
		margin-bottom: 0;
		text-align: center;
		-ms-touch-action: manipulation;
		touch-action: manipulation;
		cursor: pointer;
		background-color: #fff;
		border: 1px solid #e6e7e9;
		line-height: 1.25;
		/* padding: 0.5rem 0.75rem; */
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		color: #555;
		padding: 6.5px 7px;
	}
	.typeahead__button button {
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
	}
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/jquery-typeahead/jquery.typeahead.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-session/jquery.session.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
{{-- Selection Input --}}
<script>
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
		var cst_id = select_customer.getValue();
		actionGetContacts(cst_id);
	});
	/*=======================================================================*/
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
	/*=======================================================================*/
	var select_assign_sales = new TomSelect("#select-assign-sales",{
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
				return '<div id="select-assign-sales">'+escape(data.title)+'</div>';
			}
		}
	});
	/*=======================================================================*/
	var select_assign_sales_team = new TomSelect("#select-assign-team",{
		create: false,
		maxItems: 5,
		valueField: 'id',
		labelField: 'title',
		searchField: 'title',
		render: {
			option: function(data, escape) {
				return '<div><span class="title">'+escape(data.title)+'</span></div>';
			},
			item: function(data, escape) {
				return '<div id="select-assign-team">'+escape(data.title)+'</div>';
			}
		}
	});
</script>
{{-- All Class Js --}}
<script>
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
function actionGetContacts(id) {
	var dataOption_1 = [];  
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
				dataOption_1.push({
					id:result.data[n].id,
					title:result.data[n].title
				});
			}
		}
	});
	select_customer_contact.clear();
	select_customer_contact.clearOptions();
	select_customer_contact.addOptions(dataOption_1);
};
function actionResetFormContent() {  

};
</script>
{{-- Onload Js --}}
<script></script>
{{-- Store Action --}}
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
			url: "{{ route('store-new-lead') }}",
			data: formData1,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
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
								text:"Go To Lead",
								action:function () {  
									window.location.href = "{{ url('leads/detail-lead') }}/"+result.idlead;
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
	/*======================================================================*/
});
</script>
@endpush
