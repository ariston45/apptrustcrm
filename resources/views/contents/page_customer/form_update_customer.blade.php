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
					<a href="{{ url('customer/detail-customer/'.$id.'?extpg=information') }}">
						<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
							<div style="font-weight: 700;">
								<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
							</div>
						</button>
					</a>
				</div>
			</div>
			<form id="formContent1" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
				@csrf
				@method('PATCH')
				<div class="card-body card-body-custom">
					<div class="row align-items-center mb-3">
						<div class="col">
						</div>
					</div>
					<div class="row">
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
							<div class="mb-3 row" id="institution-name-area">
								<label class="col-3 col-form-label custom-label">Institution Name</label>
								<div class="col">
									<input name="institution_name" id="institution-name" type="text" class="form-control" aria-describedby="" placeholder=""value="{{ $company->cst_name }}" >
								</div>
							</div>
							<div class="mb-3 row" id="institution-category-area">
								<label class="col-3 col-form-label custom-label">Business Field Categories</label>
								<div class="col">
									<select type="text" class="form-select select-categories" name="business_category" id="select-category" value="">
										<option value="{{ $company->cst_business_field }}">{{ $company->cst_business_field }}</option>
										@foreach ($business_fields as $item)
										<option value="{{ $item->bus_name }}">{{ $item->bus_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-6">
							<div class="row">
								<label class="col-3 col-form-label custom-label" style="text-align: right;">Mobile</label>
								<div id="multiInputMobile" class="col">
									@if (count($mobile) == 0)
										<div class="input-group mb-2">
											<input name="mobile[]" id="mobile" type="text" class="form-control" placeholder="Type mobile number or whatsapp number here">
											<button class="btn btn-addons add-button-one" type="button"><i class="ri-add-line custom-icon-min"></i></button>	
										</div>
									@else
										@foreach ($mobile as $i => $item)
										<div class="input-group mb-2">
											<input name="mobile[]" id="mobile" type="text" class="form-control" placeholder="Type mobile number or whatsapp number here" value="{{ $item }}">
											@if ($i == 0)
											<button class="btn btn-addons add-button-one" type="button"><i class="ri-add-line custom-icon-min"></i></button>	
											@else
											<button class="btn btn-addons btn-close-one" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button>
											@endif
										</div>
										@endforeach
									@endif
								</div>
							</div>
							<div class="row">
								<label class="col-3 col-form-label custom-label">Phone</label>
								<div id="multiInputPhone" class="col">
									@if (count($phone) == 0)
										<div class="input-group mb-2">
											<input name="phone[]" id="phone" type="text" class="form-control" placeholder="Type phone number here">
											<button class="btn btn-addons add-button-two" type="button"><i class="ri-add-line custom-icon-min"></i></button>
										</div>
									@else
										@foreach ($phone as $i => $item)
										<div class="input-group mb-2">
											<input name="phone[]" id="phone" type="text" class="form-control" placeholder="Type phone number here" value="{{ $item }}">
											@if ($i == 0)
											<button class="btn btn-addons add-button-two" type="button"><i class="ri-add-line custom-icon-min"></i></button>
											@else
											<button class="btn btn-addons btn-close-two" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button>
											@endif
										</div>
										@endforeach
									@endif
								</div>
							</div>
							<div class="row">
								<label class="col-3 col-form-label custom-label">Email</label>
								<div id="multiInputEmail" class="col">
									@if (count($email) == 0)
										<div class="input-group mb-2">
											<input name="email[]" id="email" type="email" class="form-control" placeholder="Type email address here" value="">
											<button class="btn btn-addons add-button-three" type="button"><i class="ri-add-line custom-icon-min"></i></button>
										</div>
									@else
										@foreach ($email as $i => $item)
										<div class="input-group mb-2">
											<input name="email[]" id="email" type="email" class="form-control" placeholder="Type email address here" value="{{ $item }}">
											@if ($i == 0)
											<button class="btn btn-addons add-button-three" type="button"><i class="ri-add-line custom-icon-min"></i></button>
											@else
											<button class="btn btn-addons btn-close-three" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button>
											@endif
										</div>
										@endforeach
									@endif
								</div>
							</div>
							<div class="mb-3 row" id="web-name-area" style="display: none;">
								<label class="col-3 col-form-label custom-label">Web</label>
								<div class="col">
									<input name="web" id="web" type="text" class="form-control" placeholder="Type web address here" >
								</div>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="mb-3 row">
								<label class="col-3 col-form-label custom-label required">Address</label>
								<div class="col">
									<input type="text" class="form-control mb-2" name="addr_street" placeholder="Street" autocomplete="off" 
									@if (isset($location->loc_street))
										value="{{ $location->loc_street }}"
									@endif >
									<div id="district-option-area">
										<select type="text" id="select-district" class="form-select mb-2 cls-district" name="district" placeholder="Select district ..">
											@if (isset($location->loc_district))
												@if ($location->loc_district != null OR $location->loc_district != '')
												<option value="Test">{{ $location->loc_district }}</option>
												@endif
											@endif
										</select>
									</div>
									<select type="text" id="select-city" class="form-select mb-2" name="city" placeholder="Select city ..">
										@if (isset($location->loc_city))
											@if ($location->loc_city != null OR $location->loc_city != '')
												<option value="Test">{{ $location->loc_city }}</option>
											@endif
										@endif
									</select>
									<select type="text" id="select-province" class="form-select mb-2" name="province" placeholder="Select province ..">
										@if (isset($location->loc_province))
											@if ($location->loc_province != null OR $location->loc_province != '')
												<option value="Test">{{ $location->loc_province }}</option>
											@endif
										@endif
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card" style="border: none;">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
							<li id="button-three" class="nav-item" role="presentation">
								<button type="button" href="#tabs-notes" class="nav-link nav-button-tab @if ($a == 'tab3') active @endif" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
									<i class="ri-file-text-line" style="margin-right: 6px;"></i> Notes
								</button>
							</li>
							<li id="button-setting" class="nav-item ms-auto" role="presentation">
								<button type="button" href="#tabs-setting" class="nav-link nav-button-tab  @if ($a == 'set') active @endif" title="Settings" data-bs-toggle="tab"aria-selected="false" tabindex="-1" role="tab" style="padding-top: 5px;padding-bottom: 11px;">
									<i class="ri-settings-3-line icon"></i>
								</button>
							</li>
						</ul>
					</div>
					<div class="card-body" id="tab-card-body" style="@if ($a == 'none') display:none; @endif">
						<div class="tab-content">
							<div class="tab-pane @if ($a == 'tab3') active show @endif" id="tabs-notes" role="tabpanel">
								<div class="mb-3">
									<h4>Add a Note About This Customer</h4>
									<textarea id="notesInput">{{ $company->cst_notes }}</textarea>
								</div>
							</div>
							<div class="tab-pane @if ($a == 'set') active show @endif" id="tabs-setting"	role="tabpanel">
								<div class="mb-3">
                  <div class="form-label">Setting for data view customrt</div>
									<select type="text" class="form-select val-input-option mb-2" name="view_option" placeholder="Select lead status for now ..." id="select-option-view" value="">
										<option value="{{ $company->view_option }}">{{ Str::of($company->view_option)->title() }}</option>
										<option value="MODERATE">Moderate</option>
										<option value="PRIVATE">Private</option>
										<option value="PUBLIC">Public</option>
									</select>
									<span id="opt_public" @if ($company->view_option == 'PUBLIC') style="display: true;" @else style="display: none;" @endif >
										Other marketers can view data customer including your activities, your leads, your opportunity, and your sales with this customer.
									</span>
									<span id="opt_moderate" @if ($company->view_option == 'MODERATE') style="display: true;" @else style="display: none;" @endif>
										Other marketers can view data customer but they can not view activities, your leads, your opportunity, and your sales with this customer.
									</span>
									<span id="opt_private" @if ($company->view_option == 'PRIVATE') style="display: true;" @else style="display: none;" @endif>
										This Customer Profile is only visible to the marketer who created this customer profile.
									</span>
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
	</style>
@endpush
@push('script')
<script type="text/javascript" src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jquery-session/jquery.session.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/tinymce/tinymce.min.js') }}"> </script>
<script>
</script>
{{-- Editor Text --}}
<script type="text/javascript">
	var notesStoredLOC =  localStorage.getItem('notesValue');
	var notesEitor = ""; 
	var x = tinymce.init({
		selector: 'textarea#notesInput',
		promotion: false,
		statusbar: false,
		setup: function(editor) {
			editor.on('init',function(e) {
				if (notesStoredLOC != null) {
					editor.setContent(notesStoredLOC);
				}
			});
			editor.on('input', function(e) {
				notes = editor.getContent();
				localStorage.setItem('notesValue', notes);
			});
		}
	});
</script>
<script type="text/javascript">
	$('#btn-action-reset').click(function () {
		document.getElementById("formContent1").reset();
		localStorage.removeItem("notesValue");
	});
</script>
{{-- Tabs --}}
	<script>
		$('.nav-button-tab').click(function () {
			$('#tab-card-body').show();
		});
	</script>
	<script>
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
	</script>
	<script>
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
	</script>
	{{-- Picture Logo --}}
	<script>
		$(document).on("click", ".browse", function() {
			var file = $(this)
				.parent()
				.parent()
				.parent()
				.find("#inputFile");
			file.trigger("click");
		});
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
		$('#closeImageProfile').on("click", function() {
			$('#inputPreview').hide();
			$('#inputAvatar').fadeIn();
			$('#inputFile').val("");
			$('#closeImageProfile').hide();
		});
	</script>
	<script>
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
		
	</script>
	<script>
		$(document).ready(function() {
			$('#formContent1').submit(function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				formData.append('notes', tinymce.get("notesInput").getContent());
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: "{{ route('store-update-customer',['id'=>$id]) }}",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result) {
						if (result == true) {
							$.alert({
								type: 'green',
								title: 'Success',
								content: 'Data stored in database.',
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
	{{-- Add new input service --}}
	<script>
		$('.add-button-one').click(function() {
			$("#multiInputMobile").append(
				'<div class="input-group mb-2">' +
				'<input name="mobile[]" id="phone" type="text" class="form-control" placeholder="Type mobile number or whatsapp number here">' +
				'<button class="btn btn-addons btn-close-one" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button></div>'
			);
		});
		$(document).on('click', '.btn-close-one', function() {
			$(this).parents('div.input-group').remove();
		});
		$('.add-button-two').click(function() {
			$("#multiInputPhone").append(
				'<div class="input-group mb-2">' +
				'<input name="phone[]" id="phone" type="text" class="form-control" placeholder="Type phone number here">' +
				'<button class="btn btn-addons btn-close-two" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button></div>'
			);
		});
		$(document).on('click', '.btn-close-two', function() {
			$(this).parents('div.input-group').remove();
		});
		$('.add-button-three').click(function() {
			$("#multiInputEmail").append(
				'<div class="input-group mb-2">' +
				'<input name="email[]" id="email" type="text" class="form-control" placeholder="Type email address here">' +
				'<button class="btn btn-addons btn-close-three" type="button"><i class="ri-close-fill custom-icon-min-add"></i></button></div>'
			);
		});
		$(document).on('click', '.btn-close-three', function() {
			$(this).parents('div.input-group').remove();
		});
	</script>
	{{-- Tom Select Service --}}
	<script>
		var option_districts = [];
		var districts = new TomSelect("#select-district",{
			create: true,
			valueField: 'id',
			labelField: 'title',
			searchField: 'title',
			render: {
				option: function(data, escape) {
					return '<div>' +
					'<span class="title">' + escape(data.title) + '</span>' +
					'</div>';
				},
				item: function(data, escape) {
					return '<div id="district-selected">' + escape(data.title) + '</div>';
				}
			}
		});
		document.getElementById('select-district-ts-control').addEventListener('click', function() {
			districts.refreshItems();
			var varcity = $('#city-selected').attr('data-value');
			var varprovince = $('#province-selected').attr('data-value');
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'POST',
				url: "{{ route('source-addr-districts') }}",
				data: {
					city: varcity,
					province: varprovince
				},
				cache: false,
				success: function (data) {
					for (let index = 0; index < data.length; index++) {
						option_districts.push({id:data[index].id,title:data[index].title}) ;
					}
					districts.addOption(option_districts);
				}
			});
		});

		var option_cities = [];
		var cities = new TomSelect("#select-city",{
			create: true,
			valueField: 'id',
			labelField: 'title',
			searchField: 'title',
			render: {
				option: function(data, escape) {
					return '<div>' +
					'<span class="title">' + escape(data.title) + '</span>' +
					'</div>';
				},
				item: function(data, escape) {
					return '<div id="city-selected">' + escape(data.title) + '</div>';
				}
			}
		});
		document.getElementById('select-city-ts-control').addEventListener('click', function() {
			var vardistrict = $('#district-selected').attr('data-value');
			var varprovince = '';
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'POST',
				url: "{{ route('source-addr-cities') }}",
				data: {
					district: vardistrict,
					province: varprovince
				},
				cache: false,
				success: function (data) {
					for (let index = 0; index < data.length; index++) {
						option_cities.push({id:data[index].id,title:data[index].title}) ;
					}
					cities.addOption(option_cities);
				}
			});
		});

		var option_province = [];
		var provincies = new TomSelect("#select-province",{
			create: true,
			valueField: 'id',
			labelField: 'title',
			searchField: 'title',
			render: {
				option: function(data, escape) {
					return '<div>' +
					'<span class="title">' + escape(data.title) + '</span>' +
					'</div>';
				},
				item: function(data, escape) {
					return '<div id="province-selected">' + escape(data.title) + '</div>';
				}
			}
		});
		document.getElementById('select-province-ts-control').addEventListener('click', function() {
			var vardistrict = $('#district-selected').attr('data-value');
			var varcity = $('#city-selected').attr('data-value');
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: 'POST',
				url: "{{ route('source-addr-province') }}",
				data: {
					district: vardistrict,
					city: varcity
				},
				cache: false,
				success: function (data) {
					for (let index = 0; index < data.length; index++) {
						option_province.push({id:data[index].id,title:data[index].title}) ;
					}
					provincies.addOption(option_province);
				}
			});
		});
		var optionSeting = new TomSelect("#select-option-view",{
			copyClassesToDropdown: false,
			dropdownClass: 'dropdown-menu ts-dropdown',
			optionClass: 'dropdown-item',
			controlInput: '<input>',
			render: {
				item: function(data, escape) {
					if (data.customProperties) {
						return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +escape(data.text) + '</div>';
					}
					return '<div>' + escape(data.text) + '</div>';
				},
				option: function(data, escape) {
					if (data.customProperties) {
						return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
					}
					return '<div>' + escape(data.text) + '</div>';
				},
			},
		});
		var bussinesCategory = new TomSelect("#select-category",{
			copyClassesToDropdown: false,
			dropdownClass: 'dropdown-menu ts-dropdown',
			optionClass: 'dropdown-item',
			controlInput: '<input>',
			create: true,
			render: {
				create: function(input){
					return {value:input,text:input}
				},
				item: function(data, escape) {
					if (data.customProperties) {
						return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
					}
					return '<div>' + escape(data.text) + '</div>';
				},
				option: function(data, escape) {
					if (data.customProperties) {
						return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
					}
					return '<div>' + escape(data.text) + '</div>';
				},
			},
		});
	</script>
@endpush
