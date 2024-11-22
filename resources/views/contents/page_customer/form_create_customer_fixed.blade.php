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
				<h3 class="card-title">Add Contact</h3>
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
					<a href="{{ url('customer/contacts/'.$id) }}">
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
					<div class="row mb-2">
						<div class="col-xl-3 mb-3">
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
						<div class="col-xl-9">
							<h2 class="page-title">{{ $company->ins_name }}</h2>
							<small class="form-hint">{{ $company->cst_business_field }}</small>
							<div class="mb-3" id="person-name-area">
								<label class="col-form-label">Person Names</label>
								<input name="person_name" id="person-name" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Name of person" value="{{ old('person_name') }}" required>
								<input type="hidden" name="cst_id" value="{{ $id }}" >
								<input type="hidden" name="cststatus" value="INDIVIDUAL" >
								<div class="col">
								</div>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-xl-6">
							<div class="mb-3 row" id="job-position-area">
								<label class="col-3 col-form-label custom-label">Sub Customer</label>
								<div class="col">
									<select type="text" class="form-select " name="sub_customer" placeholder="Select sub customer" id="select-subcustomer" value="" required>
										<option value="{{ null }}"></option>
										@foreach ($subcustomer as $list)
											<option value="{{ $list->cst_id }}">{{ $list->cst_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="mb-3 row" id="job-position-area">
								<label class="col-3 col-form-label custom-label">Job Position</label>
								<div class="col">
									<input name="job_position" id="job-position" type="text" class="form-control" placeholder="Type job position here" required>
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
							<div class="mb-3 row" id="web-name-area" style="display: none;">
								<label class="col-3 col-form-label custom-label">Web</label>
								<div class="col">
									<input name="web" id="web" type="text" class="form-control" placeholder="Type web address here" >
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label">Notes</label>
								<div class="col">
									<textarea rows="5" class="form-control" name="note"></textarea>
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							{{-- <div class="mb-3 row">
								<label class="col-3 col-form-label custom-label required">Address</label>
								<div class="col">
									<div class="typeahead__container">
										<div class="typeahead__field">
											<div class="typeahead__query">
												<input id="addr-street" name="addr_street" type="text" class="form-control mb-2 typeahead-street" placeholder="Street" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="typeahead__container">
										<div class="typeahead__field">
											<div class="typeahead__query">
												<input id="addr-district" name="addr_district" type="text" class="form-control form-control-cst mb-2 typeahead-district" placeholder="District" autocomplete="off">
											</div>
											<div class="typeahead__button">
												<button type="button" onclick="clearAddrDistrict()"><i class="ri-eraser-line"></i></button>
											</div>
										</div>
									</div>
									<div class="typeahead__container">
										<div class="typeahead__field">
											<div class="typeahead__query">
												<input id="addr-city" name="addr_city" type="text" class="form-control mb-2 typeahead-city" placeholder="City" autocomplete="off">
											</div>
											<div class="typeahead__button">
												<button type="button" onclick="clearAddrCity()"><i class="ri-eraser-line"></i></button>
											</div>
										</div>
									</div>
									<div class="typeahead__container">
										<div class="typeahead__field">
											<div class="typeahead__query">
												<input id="addr-province" name="addr_province" type="text" class="form-control typeahead-province" placeholder="Province">
											</div>
											<div class="typeahead__button">
												<button type="button" onclick="clearAddrProvince()"><i class="ri-eraser-line"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div> --}}
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
	<script>
		var select_subcustomer = new TomSelect("#select-subcustomer",{
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
		addr_province.on('change',function () {
			var prov_id = addr_province.getValue();
			actionGetCity(prov_id);
		});
		addr_city.on('change',function () {
			var city_id = addr_city.getValue();
			actionGetDistrict(city_id);
		});
	</script>
	<script type="text/javascript">
		function preventPage(p) {
			if (p == 1) {
				window.addEventListener('beforeunload', function(e) {
					e.preventDefault();
					e.returnValue = '';
				});
			}
		}
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
	<script>
		$(document).ready(function() {
			$('#formContent1').submit(function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: "{{ route('store-contact') }}",
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
									goToCustomer:{
										text:"Go To Customer",
										action:function () {  
											window.location.href = "{{ url('customer/contacts/detail') }}/"+result.idcnt;
										}
									}
								}
							});
						}else{
							$.alert({
								title: 'Warning.',
								content: result.msg,
								type: 'red',
							});
						}
					}
				});
			});
		});
	</script>
	<script>
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
	</script>
	<script>
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
@endpush
