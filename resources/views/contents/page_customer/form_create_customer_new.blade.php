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
				<h3 class="card-title">Create Customer</h3>
				<div class="card-actions" style="padding-right: 10px;">
					<button id="btn-action-reset" class="btn btn-sm btn-light btn-pill" style="vertical-align: middle;">
						<div style="font-weight: 700;">
							<i class="ri-eraser-line icon" style="font-size: 14px;margin-right: 4px; vertical-align: middle;"></i> Clear
						</div>
					</button>
					<button id="btn-action-save" class="btn btn-sm btn-light btn-pill" style="vertical-align: middle;" form="formContent1">
						<div style="font-weight: 700;">
							<i class="ri-save-3-line icon" style="font-size: 14px;margin-right: 4px; vertical-align: middle;"></i> Save
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
			<form id="formContent1" enctype="multipart/form-data" action="javascript:void(0)" method="Get" autocomplete="off">
				@csrf
				<div class="card-body card-body-custom">
					<div class="row align-items-center mb-3">
						<div class="col">
						</div>
					</div>
					<div class="row" style="text-align: right;">
						<div class="col-xl-12 mb-2">
							<div class="mb-3 row">
								<div class="col">
									<a href="{{ url('customer/create-customer') }}" class="btn btn-primary btn-square" style="width: 300px;"> 
										<i class="ri-community-fill icon" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i> Customer 
									</a>
									<a href="{{ url('customer/create-subcustomer') }}" class="btn btn-light btn-square" style="width: 300px;"> 
										<i class="ri-community-fill icon" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i> Sub-Customer 
									</a>
								</div>
							</div>
							<hr class="mb-3 mt-0">
						</div>
					</div>
					<div class="row">
						<div class="col-xl-2">
							<input type="file" name="fileImage" class="input_file" id="inputFile" style="display: none;">
							<input type="hidden" class="input_text" id="inputFileName">
							<div id="photo-frame" class="browse">
								<img src="{{ asset('static/user_edit.png') }}" id="inputAvatar" class="rounded-3 ">
								<img src="" id="inputPreview" class="rounded-2" style="display: none;">
							</div>
							<div id="areaCloseImage">
								<button type="button" id="closeImageProfile" class="badge" style="display: none;"><i class="ri-close-circle-fill icon"></i></button>
							</div>
							<label class="form-label" style="text-align: center;">Add Image Here</label>
						</div>
						<div class="col-xl-6">
							<div class="mb-3 row" id="person-name-area">
								<label class="col-3 col-form-label custom-label">Name</label>
								<div class="col">
									<input name="customer_name" id="person-name" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Name of person" value="{{ old('person_name') }}" >
									<input type="hidden" name="cststatus" id="cststatus" value="CUSTOMER">
								</div>
							</div>
							<div class="mb-3 row" id="institution-category-area">
								<label class="col-3 col-form-label custom-label">Business Field </label>
								<div class="col">
									<select type="text" class="form-select select-categories" name="business_category[]" multiple placeholder="Select business categories of company" id="select-category" value="">
										<option value="{{ null }}"></option>
										@foreach ($business_fields as $item)
										<option value="{{ $item->bus_name }}">{{ $item->bus_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label" style="text-align: right;">Mobile</label>
								<div id="multiInputMobile" class="col">
									<div class="input-group">
										<input name="mobile[]" id="mobile" type="text" class="form-control" placeholder="Type mobile number or whatsapp number here">
										<button id="add-button-one" class="btn btn-addons" type="button"><i class="ri-add-line custom-icon-min"></i></button>
									</div>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label">Phone</label>
								<div id="multiInputPhone" class="col">
									<div class="input-group">
										<input name="phone[]" id="phone" type="text" class="form-control" placeholder="Type phone number here">
										<button id="add-button-two" class="btn btn-addons" type="button"><i class="ri-add-line custom-icon-min"></i></button>
									</div>
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label">Email</label>
								<div id="multiInputEmail" class="col">
									<div class="input-group">
										<input name="email[]" id="email" type="email" class="form-control" placeholder="Type email address here">
										<button id="add-button-three" class="btn btn-addons" type="button"><i class="ri-add-line custom-icon-min"></i></button>
									</div>
								</div>
							</div>
							<div class="mb-3 row" id="web-name-area">
								<label class="col-3 col-form-label custom-label">Web</label>
								<div class="col">
									<input name="web" id="web" type="text" class="form-control" placeholder="Type web address here" >
								</div>
							</div>
							<div class="mb-3 row" id="web-name-area">
								<label class="col-3 col-form-label custom-label">Notes</label>
								<div class="col">
									<textarea rows="5" class="form-control" name="cst_notes" placeholder="Here can be your description"></textarea>
								</div>
							</div>
						</div>
						<div class="col">
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label required">Address</label>
								<div class="col">
									<select type="text" class="form-select mb-2 " name="addr_province" placeholder="Select province" id="addr-province" value="">
										<option value="{{ null }}"></option>
										@foreach ($provincies as $list)
										<option value="{{ $list->prov_name }}">{{ $list->prov_name }}</option>
										@endforeach
									</select>
									<select type="text" class="form-select mb-2" name="addr_city" placeholder="Select city" id="addr-city" value="">
										<option value="{{ null }}"></option>
									</select>
									<select type="text" class="form-select mb-2" name="addr_district" placeholder="Select district" id="addr-district" value="">
										<option value="{{ null }}"></option>
									</select>
									<input id="addr-street" name="addr_street" type="text" class="form-control mb-2" placeholder="Street" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					
					{{-- <div class="row">
						<div class="col-5"></div>
						<div class="col">
							<div class="form-label" style="text-align: right;">Setting for data view custom</div>
							<select type="text" class="form-select val-input-option mb-2" name="view_option" placeholder="Select lead status for now ..." id="select-option-view" value="">
								<option value="{{ null }}">Select lead status for now ...</option>
								<option value="MODERATE">Moderate</option>
								<option value="PRIVATE">Private</option>
								<option value="PUBLIC">Public</option>
							</select>
							<span id="opt_public" style="display: none;">
								Other marketers can view data customer including your activities, your leads, your opportunity, and your sales with this customer.
							</span>
							<span id="opt_moderate" style="display: none;">
								Other marketers can view data customer but they can not view activities, your leads, your opportunity, and your sales with this customer.
							</span>
							<span id="opt_private" style="display: none;">
							</span>
						</div>
					</div> --}}
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
	<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-lite.min.css') }}">
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
<script src="{{ asset('plugins/summernote/summernote-lite.min.js') }}"></script>
{{-- Varibles --}}
<script>
/************************************************************************/

var select_category = new TomSelect("#select-category",{
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-category">'+escape(data.title)+'</div>';
		}
	}
});

var addr_province = new TomSelect("#addr-province",{
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="addr-province">'+escape(data.title)+'</div>';
		}
	}
});
var addr_city = new TomSelect("#addr-city",{
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="addr-city">'+escape(data.title)+'</div>';
		}
	}
});
var addr_district = new TomSelect("#addr-district",{
	create: true,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="addr-district">'+escape(data.title)+'</div>';
		}
	}
});

/************************************************************************/

addr_province.on('change',function () {
	var prov_id = addr_province.getValue();
	actionGetCity(prov_id);
});
addr_city.on('change',function () {
	var city_id = addr_city.getValue();
	actionGetDistrict(city_id);
});
</script>
{{-- Class/Function --}}
<script type="text/javascript">
function preventPage(p) {
	if (p == 1) {
		window.addEventListener('beforeunload', function(e) {
			e.preventDefault();
			e.returnValue = '';
		});
	}
}
function actionGetCustomer(id) {
	var dataOption_1 = [];  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('sub-customers') }}",
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
	select_customer.clear();
	select_customer.clearOptions();
	select_customer.addOptions(dataOption_1);
};
function actionGetCity(id) {  
	var dataOption_2 = [];  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('action-get-city-select') }}",
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
	addr_city.clear();
	addr_city.clearOptions();
	addr_city.addOptions(dataOption_2);
};
function actionGetDistrict(id) {
	var dataOption_3 = [];  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('action-get-district-select') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			for (let n = 0; n < result.data.length; n++) {
				dataOption_3.push({
					id:result.data[n].id,
					title:result.data[n].title
				});
			}
		}
	});
	addr_district.clear();
	addr_district.clearOptions();
	addr_district.addOptions(dataOption_3);
};
</script>
{{-- Action Trigger --}}
<script>
$('.nav-button-tab').click(function () {
	$('#tab-card-body').show();
});
/***********************************************************************/
$('#button-one').on('click', function() {
	window.history.replaceState(null, null, "?act=tab1");
});
$('#button-two').on('click', function() {
	window.history.replaceState(null, null, "?act=tab2");
});
$('#button-three').on('click', function() {
	window.history.replaceState(null, null, "?act=tab3");
});
$('#button-setting').on('click', function() {
	window.history.replaceState(null, null, "?act=set");
});
/***********************************************************************/
$('.opt-status').click(function() {
	var optVal1 = $("input[name='cststatus']:checked").val();
	if (optVal1 == 'institution') {
		$('#person-name-area').slideUp();
		$('#job-position-area').slideUp();
		$('#person-name').prop('disabled',true);
		$('#institution-category-area').slideDown();
		$('#select-category-ts-control').prop('disabled',false);
		$('#web-name-area').slideDown();
		$('#web').prop('disabled', false);
		$('#idperson').prop('disabled', true);
	} else {
		$('#person-name-area').slideDown();
		$('#person-name').prop('disabled', false);
		$('#institution-category-area').slideUp();
		$('#select-category-ts-control').prop('disabled',true);
		$('#job-position-area').slideDown();
		$('#web-name-area').slideUp();
		$('#web').prop('disabled', true);
		$('#idperson').prop('disabled', false);
	}
});
$(document).on("click", ".browse", function() {
	var file = $(this)
	.parent()
	.parent()
	.parent()
	.find("#inputFile");
	file.trigger("click");
});
/****************************************************************************/
$('input[class=input_file]').change(function(e) {
	$('#inputPreview').fadeIn();
	$('#closeImageProfile').fadeIn();
	$('#inputAvatar').hide();
	var fileName = e.target.files[0].name;
	$("#inputFileName").val(fileName);
	var reader = new FileReader();
	reader.onload = function(e) {
	document.getElementById("inputPreview").src = e.target.result;
	};
	reader.readAsDataURL(this.files[0]);
});
/****************************************************************************/
$('#closeImageProfile').on("click", function() {
	$('#inputPreview').hide();
	$('#inputAvatar').fadeIn();
	$('#inputFile').val("");
	$('#closeImageProfile').hide();
});
/****************************************************************************/
$('#product-quantity').on('input',function () {
	var productVal;
	var idproduct = $("#select-product-interest").val();
	var ctproduct = $("#product-quantity").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('ajaxlink-product-value') }}",
		data: {id:idproduct,cnt:ctproduct},
		success: function(result) {
			$("#estimate-base-value").html(result);
		}
	});
});
/****************************************************************************/
$('#select-product-interest').on('change',function () {
	var productVal;
	var idproduct = $("#select-product-interest option:selected").val();
	var ctproduct = $("#product-quantity").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('ajaxlink-product-value') }}",
		data: {id:idproduct,cnt:ctproduct},
		success: function(result) {
			$("#estimate-base-value").html(result);
		}
	});
});
/****************************************************************************/
$('#select-option-view').on('change', function () {
	var idoption = $("#select-option-view option:selected").val();
	if (idoption == 'PUBLIC') {
		$('#opt_public').fadeIn();
		$('#opt_moderate').hide();
		$('#opt_private').hide();
	}else if(idoption == 'MODERATE'){
		$('#opt_moderate').fadeIn();
		$('#opt_public').hide();
		$('#opt_private').hide();
	}else if(idoption == 'PRIVATE'){
		$('#opt_private').fadeIn();
		$('#opt_public').hide();
		$('#opt_moderate').hide();
	}
});
/****************************************************************************/
$('#add-button-one').click(function() {
	$("#multiInputMobile").append(
	'<div class="input-group mt-2">' +
	'<input name="mobile[]" id="phone" type="text" class="form-control" placeholder="Type mobile number or whatsapp number here">' +
	'<button class="btn btn-addons btn-close-one" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button></div>'
	);
});
$(document).on('click', '.btn-close-one', function() {
	$(this).parents('div.input-group').remove();
});
$('#add-button-two').click(function() {
	$("#multiInputPhone").append(
	'<div class="input-group mt-2">' +
	'<input name="phone[]" id="phone" type="text" class="form-control" placeholder="Type phone number here">' +
	'<button class="btn btn-addons btn-close-two" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button></div>'
	);
});
$(document).on('click', '.btn-close-two', function() {
	$(this).parents('div.input-group').remove();
});
$('#add-button-three').click(function() {
	$("#multiInputEmail").append(
	'<div class="input-group mt-2">' +
	'<input name="email[]" id="email" type="text" class="form-control" placeholder="Type email address here">' +
	'<button class="btn btn-addons btn-close-three" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button></div>'
	);
});
$(document).on('click', '.btn-close-three', function() {
	$(this).parents('div.input-group').remove();
});
</script>
{{-- Form Action --}}
<script>
$(document).ready(function() {
	$('#formContent1').submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			type: 'POST',
			url: "{{ route('store-customer') }}",
			data: formData,
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
								text:"Go To Customer",
								action:function () {  
									window.location.href = "{{ url('customer/detail-customer') }}/"+result.cst_id+"?extpg=information";
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
				} else{
					$.alert({
						type: 'red',
						title: 'Warning',
						content: 'Your input something wrong',
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
