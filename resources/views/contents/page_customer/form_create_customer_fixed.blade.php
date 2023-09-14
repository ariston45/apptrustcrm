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
							<h2 class="page-title">{{ $company->cst_name }}</h2>
							<small class="form-hint">{{ $company->cst_business_field }}</small>
							<div class="mb-3" id="person-name-area">
								<label class="col-form-label">Person Names</label>
								<input name="person_name" id="person-name" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Name of person" value="{{ old('person_name') }}" >
								<input type="hidden" name="idperson" id="idperson" value="{{ genIdPerson() }}">
								<input type="hidden" name="institution_name" value="{{ $company->cst_name }}">
								<input type="hidden" name="business_category" value="{{ $company->cst_business_field }}">
								<input type="hidden" name="cststatus" value="individual" >
								<div class="col">
								</div>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-xl-6">
							<div class="mb-3 row" id="job-position-area">
								<label class="col-3 col-form-label custom-label">Job Position</label>
								<div class="col">
									<input name="job_position" id="job-position" type="text" class="form-control" placeholder="Type job position here">
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
						</div>
						<div class="col-xl-6">
							<div class="mb-3 row">
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
							</div>
						</div>
					</div>
				</div>
				<div class="card" style="border: none;">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
							{{-- <li id="button-one" class="nav-item" role="presentation">
								<button href="#tabs-contacts" class="nav-link @if ($a == 'tab1' || $a == 'none') active @endif" data-bs-toggle="tab" aria-selected="true"role="tab">
									<i class="ri-contacts-line" style="margin-right: 6px;"></i> Contacts
								</button>
							</li> --}}
							<li id="button-two" class="nav-item " role="presentation">
								<button type="button" href="#tabs-leads" class="nav-link nav-button-tab @if ($a == 'tab2') active @endif" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
									<i class="ri-filter-2-line" style="margin-right: 6px;"></i> Leads
								</button>
							</li>
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
							{{-- <div class="tab-pane @if ($a == 'tab1' || $a == 'none') active show @endif" id="tabs-contacts"role="tabpanel">
								<h4>Contacts</h4>
							</div> --}}
							<div class="tab-pane @if ($a == 'tab2') active show @endif" id="tabs-leads" role="tabpanel">
								{{-- <h4>Create New Lead</h4> --}}
								<div class="col-xl-9">
									<div class="mb-3 row" id="">
										<label class="col-3 col-form-label">Lead Title</label>
										<div class="col">
											<input name="lead_title" id="lead-title" type="text" class="form-control val-input-lead" placeholder="Type title of lead here ...">
										</div>
									</div>
									<div class="mb-3 row" id="">
										<label class="col-3 col-form-label">Lead status</label>
										<div class="col">
											<select type="text" class="form-select val-input-lead" name="lead_status" placeholder="Select lead status for now ..." id="select-lead-status" value="">
												<option value="{{ null }}">Select lead status for now ...</option>
												@foreach ($lead_status as $item)
												<option value="{{ $item->pls_id }}">{{ $item->pls_status_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="mb-3 row" id="">
										<label class="col-3 col-form-label">Product Interest</label>
										<div class="col">
											<select type="text" class="form-select val-input-lead" name="product_interest" placeholder="select products that interest to customers" id="select-product-interest" value="">
												<option value="">Select products that interest to customers</option>
												@foreach ($products as $item)
												<option value="{{ $item->psp_id }}">{{ $item->psp_subproduct_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="mb-3 row" id="">
										<label class="col-3 col-form-label">Product Quantity</label>
										<div class="col">
											<input name="product_quantity" id="product-quantity" type="number" class="form-control val-input-lead" placeholder="Input product quantity here ...">
										</div>
									</div>
									<div class="mb-3 row" id="">
										<label class="col-3 col-form-label">Base Value</label>
										<div class="col val-text-param">
											<p id="estimate-base-value">Rp0,00</p>
											{{-- <input name="base_value" id="base-value" type="number" class="form-control val-input-lead" readonly> --}}
										</div>
									</div>
									<div class="mb-3 row" id="">
										<label class="col-3 col-form-label">Target Value</label>
										<div class="col">
											<input name="target_value" id="target-value" type="number" class="form-control val-input-lead" placeholder="Input target sales value here ...">
										</div>
									</div>
									<div class="mb-3 row" id="">
										<label class="col-3 col-form-label">Describe</label>
										<div class="col">
											<textarea rows="5" class="form-control val-input-lead" placeholder="Here can be your description" name="lead_describe"></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane @if ($a == 'tab3') active show @endif" id="tabs-notes" role="tabpanel">
								<div class="mb-3">
									<h4>Add a Note About This Customer</h4>
									<textarea name="notes" id="idnotes" class="cnotes"></textarea>
								</div>
							</div>
							<div class="tab-pane @if ($a == 'set') active show @endif" id="tabs-setting"	role="tabpanel">
								<div class="mb-3">
                  <div class="form-label">Setting for data view customrt</div>
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
	<script type="text/javascript">
		function preventPage(p) {
			if (p == 1) {
				window.addEventListener('beforeunload', function(e) {
					e.preventDefault();
					e.returnValue = '';
				});
			}
		}
	</script>
	<script>
		$.typeahead({
			input: '.js-typeahead',
			minLength: 1,
			order: "asc",
			offset: true,
			hint: true,
			source: {
				customer: {
					ajax: {
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type: "POST",
						url: "{{ route('source-data-customer') }}",
						data: {
							myKey: "myValue"
						},
					}
				}
			},
			callback: {
				onClick: function() {
					$('#institution-name').attr('readonly', true);
					var val1 = $('#institution-name').val();
				},
				onCancel: function() {
					$('#institution-name').attr('readonly', false);
				}
			}
		});
		$('#addr-district').click(function() {
			var value_district = document.getElementById('addr-city').value;
			var value_province = document.getElementById('addr-province').value;
			autocompleteDistrict(value_district, value_province);
		});
		$('#addr-city').click(function() {
			var value_district = document.getElementById('addr-district').value;
			var value_province = document.getElementById('addr-province').value;
			autocompleteCity(value_district, value_province);
		});
		$('#addr-province').click(function() {
			var value_district = document.getElementById('addr-district').value;
			var value_city = document.getElementById('addr-city').value;
			autocompleteProvince(value_district, value_city);
		});
		// var value_district,value_city;
		function autocompleteDistrict(value_district, value_province) {
			$.typeahead({
				input: '.typeahead-district',
				minLength: 1,
				highlight: true,
				order: "asc",
				offset: true,
				hint: true,
				cache: false,
				dynamic: true,
				cancelButton: false,
				source: {
					district: {
						ajax: {
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							type: "POST",
							url: "{{ route('source-data-district') }}",
							data: {
								data_city: value_district,
								data_province: value_province,
							},
						}
					}
				},
				callback: {
					onClickAfter: function(item) {
						$('#addr-district').attr('readonly', true);
					},
					onCancel: function() {
						$('#addr-district').attr('readonly', false);
					},
				}
			});
		}
		function autocompleteCity(value_district, value_province) {
			$.typeahead({
				input: '.typeahead-city',
				minLength: 1,
				order: "asc",
				offset: true,
				hint: true,
				cache: false,
				dynamic: true,
				cancelButton: false,
				source: {
					city: {
						ajax: {
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							type: "POST",
							url: "{{ route('source-data-city') }}",
							data: {
								data_district: value_district,
								data_province: value_province,
							},
						}
					}
				},
				callback: {
					onClickAfter: function() {
						$('#addr-city').attr('readonly', true);
					},
				}
			});
		}
		function autocompleteProvince(value_district, value_city) {
			$.typeahead({
				input: '.typeahead-province',
				minLength: 1,
				order: "asc",
				offset: true,
				hint: true,
				cache: false,
				dynamic: true,
				cancelButton: false,
				source: {
					city: {
						ajax: {
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							type: "POST",
							url: "{{ route('source-data-province') }}",
							data: {
								data_district: value_district,
								data_city: value_city,
							},
						}
					}
				},
				callback: {
					onClickAfter: function() {
						$('#addr-province').attr('readonly', true);
					},
				},
				debug: true,
			});
		}

		function clearAddrDistrict() {
			$('#addr-district').val("");
			$('#addr-district').attr('readonly', false);
		}

		function clearAddrCity() {
			$('#addr-city').val("");
			$('#addr-city').attr('readonly', false);
		}

		function clearAddrProvince() {
			$('#addr-province').val("");
			$('#addr-province').attr('readonly', false);
		}
	</script>
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
				$('#person-name').prop('disabled', true);
				$('#web-name-area').slideDown();
				$('#web').prop('disabled', false);
				$('#idperson').prop('disabled', true);
			} else {
				$('#person-name-area').slideDown();
				$('#job-position-area').slideDown();
				$('#person-name').prop('disabled', false);
				$('#web-name-area').slideUp();
				$('#web').prop('disabled', true);
				$('#idperson').prop('disabled', false);
			}
		});
	</script>
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
			$('#idnotes').summernote({
				height: 150,
				codemirror: {
					theme: 'monokai'
				},
			});
			$('#idnotes').on('summernote.change', function(we, contents, $editable) {
				var inData = $('#idnotes').summernote('code');
				localStorage.setItem('noteData',inData);
			});
			var outdata = localStorage.getItem('noteData');
			$('#idnotes').summernote('pasteHTML', outdata);
		});

	</script>
	<script>
		$(document).ready(function() {
			$('#formContent1').submit(function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				formData.append('notes', $('#idnotes').summernote('code'));
				var leadArr = [
					formData.get('lead_title'),formData.get('lead_status'),formData.get('product_interest'),
					formData.get('product_quantity'),formData.get('lead_notes'),formData.get('estimate_close')
				];
				if (formData.get('lead_title') != "") {
					var strErr = [];
					var keyInputs = ["Lead Titles","Lead Status","Product Interest", "Product quatity", "Lead Notes","Estimate Close"];
					for (let i = 0; i < leadArr.length; i++) {
						if (leadArr[i] == "") {
							strErr[i] = keyInputs[i];
						}
					}
					if (strErr.length > 0) {
						var str = strErr.join(', ');
						var strVal = str.substring(1); 
						$.alert({
							type: 'red',
							title: 'Error Input',
							content: 'If you want create lead, columns '+strVal+' can not gift null value.',
							animateFromElement: false,
							animation: 'opacity',
							closeAnimation: 'opacity'
						});
					}else{
						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							}
						});
						$.ajax({
							type: 'POST',
							url: "{{ route('store-lead-data') }}",
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
					}
				}
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: "{{ route('store-customer') }}",
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
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var el;
			window.TomSelect && (new TomSelect(el = document.getElementById('select-tags'), {
				copyClassesToDropdown: false,
				dropdownClass: 'dropdown-menu ts-dropdown',
				optionClass: 'dropdown-item',
				controlInput: '<input>',
				render: {
					item: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
					option: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
				},
			}));
		});
		document.addEventListener("DOMContentLoaded", function() {
			var el;
			window.TomSelect && (new TomSelect(el = document.getElementById('select-lead-status'), {
				copyClassesToDropdown: false,
				dropdownClass: 'dropdown-menu ts-dropdown',
				optionClass: 'dropdown-item',
				controlInput: '<input>',
				render: {
					item: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
					option: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
				},
			}));
		});
		document.addEventListener("DOMContentLoaded", function() {
			var el;
			window.TomSelect && (new TomSelect(el = document.getElementById('select-product-interest'), {
				copyClassesToDropdown: false,
				dropdownClass: 'dropdown-menu ts-dropdown',
				optionClass: 'dropdown-item',
				controlInput: '<input>',
				render: {
					item: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
					option: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
				},
			}));
		});
		document.addEventListener("DOMContentLoaded", function() {
			var el;
			window.TomSelect && (new TomSelect(el = document.getElementById('select-option-view'), {
				copyClassesToDropdown: false,
				dropdownClass: 'dropdown-menu ts-dropdown',
				optionClass: 'dropdown-item',
				controlInput: '<input>',
				render: {
					item: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
					option: function(data, escape) {
						if (data.customProperties) {
							return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' +
								escape(data.text) + '</div>';
						}
						return '<div>' + escape(data.text) + '</div>';
					},
				},
			}));
		});
	</script>
@endpush
