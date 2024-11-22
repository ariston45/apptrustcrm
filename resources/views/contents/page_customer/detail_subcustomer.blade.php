@php
$activePage = app('request')->input('extpg');
@endphp
@extends('layout.app')
@section('title')
Customer
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title">Customer : {{ $company->cst_name }}</h4>
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
							<h2 align="left">Information Profile</h2>
						</div>
						<div class="col-auto ms-auto">
							<div align="right">
								<a href="{{ url('customer/detail-customer/company-update/'.$id) }}">
									<button class="btn btn-sm btn-success">
										<i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit
									</button>
								</a>
							</div>
						</div>
					</div>
					{{-- <h3 class="">Profile Details</h3> --}}
					<div class="row align-items-center mb-3">
						<div class="col-auto">
							<span class="avatar avatar-xl" style=""></span>
						</div>
						<div class="col-auto"> 
							<h1>{{ $company->cst_name }}</h1>
							<h3 class="text-muted">{{ $company->ins_name }}</h3>
						</div>
					</div>
					{{-- <h3 class="mt-2">Business Profile</h3> --}}
					<hr class="mb-0 mt-0">
					<div class="row mb-2">
						<div class="col-xl-6 col-sm-12">
							<div class="form-label info-title-cst"> <strong>Business Field</strong> </div>
							<span class="text-muted info-text-cst">
								@if ($company->ins_business_field == null)
								-
								@else
								{{ $company->ins_business_field }}
								@endif
							</span>
						</div>
						<div class="col-xl-6 col-sm-12">
							<div class="form-label info-title-cst"> <strong>Location</strong> </div>
							<span class="text-muted info-text-cst">
								@if (count($location_ar) == 0)
									-
								@elseif ($location_ar[0] == "" && $location_ar[1] == "" && $location_ar[2] == "" && $location_ar[3] == "" )
									-
								@else
									Street: @if($location_ar[0] == null) - @else {{ $location_ar[0] }} @endif <br>
									District: @if($location_ar[1] == null) - @else {{ $location_ar[1] }} @endif <br>
									City: @if($location_ar[2] == null) - @else {{ $location_ar[2] }} @endif <br>
									Province: @if($location_ar[3] == null) - @else {{ $location_ar[3] }} @endif 
								@endif
							</span>
						</div>
					</div>
					<hr class="mb-0 mt-0">
					<div class="row mb-2">
						<div class="col-xl-6 col-sm-12">
							<div class="form-label info-title-cst"><strong>Email</strong> </div>
							<span class="text-muted info-text-cst">
								@if (count($email) == null)
									-
								@else
									@foreach ($email as $em)
										{{ $em->eml_address }} <br>
									@endforeach
								@endif
							</span>
						</div>
						<div class="col-xl-6 col-sm-12">
							<div class="form-label"> <strong>Telephone</strong> </div>
							<span class="text-muted info-text-cst info-title-cst">
								@if (count($phone) == null)
									-
								@else
									@foreach ($phone as $ph)
										{{ $ph->pho_number }} <br>
									@endforeach
								@endif
							</span>
						</div>
					</div>
					<hr class="mb-0 mt-0">
					<div class="row mb-2">
						<div class="col-xl-6 col-sm-12">
							<div class="form-label info-title-cst"> <strong>Notes</strong> </div>
							<span class="text-muted info-text-cst">
								@if ($company->cst_notes == null)
								-
								@else
								{!! html_entity_decode($company->cst_notes) !!}
								@endif
							</span>
						</div>
						<div class="col-xl-6 col-sm-12">
							<div class="form-label info-title-cst"> <strong>Lastest Project</strong> </div>
							<span class="text-muted info-text-cst">
								Salesperson : <br>
								Project : <br>
								Status : <br>
								Closing : <br>
							</span>
						</div>
					</div>
					<hr class="mb-2 mt-0">
					<div class="row mb-1">
						<div class="col">
							<div class="form-label"> <strong>Contacts</strong> </div>
						</div>
						<div class="col-auto ms-auto">
							<a href="{{ url('customer/create-customer') }}">
								<button class="btn btn-sm btn-primary">Add New Contact</button>
							</a>
						</div>
					</div>
					<span class="card-subtitle">
						<div class="table-responsive">
							<table class="table table-vcenter card-table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Role</th>
										<th>Mobile</th>
										<th>Email</th>
										<th class="w-1"></th>
									</tr>
								</thead>
								<tbody>
									@if (count($dataContact) == 0)
									<tr>
										<td colspan="5" style="text-align: center;">Data not available.</td>
									</tr>
									@else	
									@foreach ($dataContact as $item)
									<tr>
										<td style="width: 30%;">{{ $item['name'] }}</td>
										<td class="text-muted" style="width: 25%">
											{{ $item['role'] }}
										</td>
										<td class="text-muted" style="width: 20%">
											@foreach ($item['phone'] as $i)
												{{ $i }} <br>
											@endforeach
										</td>
										<td class="text-muted" style="width: 25%">
											@foreach ($item['email'] as $i)
												{{ $i }} <br>
											@endforeach
										</td>
										<td style="width: 30%;">
											{!! html_entity_decode($item['option']) !!}
										</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</span>
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
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-typeahead/jquery.typeahead.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-lite.min.css') }}">
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
	.info-title-cst{
		margin: 0px;
	}
	.info-text-cst{
	}
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/jquery-typeahead/jquery.typeahead.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-session/jquery.session.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalender-scheduler/dist/index.global.js') }}"></script>
{{-- fullcalender --}}
<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calender');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialDate: '2023-01-07',
      height: 300,
      editable: true,
      selectable: true,
      nowIndicator: true,
      dayMaxEventRows: true,
      aspectRatio: 1,
      scrollTime: '00:00',
      headerToolbar: {
        left: 'today prev,next',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,resourceTimelineDay'
      },
      initialView: 'dayGridMonth',
      views: {
        // resourceTimelineThreeDays: {
        //   type: 'resourceTimeline',
        //   duration: { days: 3 },
        //   buttonText: '3 days'
        // },
        timeGrid: {
          dayMaxEventRows: 4 // adjust to 6 only for timeGridWeek/timeGridDay
        }
      },
      resourceGroupField: 'building',
      resources: [
        { id: 'a', building: '460 Bryant', title: 'Auditorium A' },
        { id: 'b', building: '460 Bryant', title: 'Auditorium B', eventColor: 'green' },
        { id: 'c', building: '460 Bryant', title: 'Auditorium C', eventColor: 'orange' },
        { id: 'd', building: '460 Bryant', title: 'Auditorium D', children: [
          { id: 'd1', title: 'Room D1', occupancy: 10 },
          { id: 'd2', title: 'Room D2', occupancy: 10 }
        ] },
        { id: 'e', building: '460 Bryant', title: 'Auditorium E' },
        { id: 'f', building: '460 Bryant', title: 'Auditorium F', eventColor: 'red' },
        { id: 'g', building: '564 Pacific', title: 'Auditorium G' },
        { id: 'h', building: '564 Pacific', title: 'Auditorium H' },
        { id: 'i', building: '564 Pacific', title: 'Auditorium I' },
        { id: 'j', building: '564 Pacific', title: 'Auditorium J' },
        { id: 'k', building: '564 Pacific', title: 'Auditorium K' },
        { id: 'l', building: '564 Pacific', title: 'Auditorium L' },
        { id: 'm', building: '564 Pacific', title: 'Auditorium M' },
        { id: 'n', building: '564 Pacific', title: 'Auditorium N' },
        { id: 'o', building: '564 Pacific', title: 'Auditorium O' },
        { id: 'p', building: '564 Pacific', title: 'Auditorium P' },
        { id: 'q', building: '564 Pacific', title: 'Auditorium Q' },
        { id: 'r', building: '564 Pacific', title: 'Auditorium R' },
        { id: 's', building: '564 Pacific', title: 'Auditorium S' },
        { id: 't', building: '564 Pacific', title: 'Auditorium T' },
        { id: 'u', building: '564 Pacific', title: 'Auditorium U' },
        { id: 'v', building: '564 Pacific', title: 'Auditorium V' },
        { id: 'w', building: '564 Pacific', title: 'Auditorium W' },
        { id: 'x', building: '564 Pacific', title: 'Auditorium X' },
        { id: 'y', building: '564 Pacific', title: 'Auditorium Y' },
        { id: 'z', building: '564 Pacific', title: 'Auditorium Z' }
      ],
      events: [
        { id: '1', resourceId: 'b', start: '2023-01-07T02:00:00', end: '2023-01-07T07:00:00', title: 'event 1' },
        { id: '2', resourceId: 'c', start: '2023-01-07T05:00:00', end: '2023-01-07T22:00:00', title: 'event 2' },
        { id: '3', resourceId: 'd', start: '2023-01-06', end: '2023-01-08', title: 'event 3' },
        { id: '4', resourceId: 'e', start: '2023-01-07T03:00:00', end: '2023-01-07T08:00:00', title: 'event 4' },
        { id: '5', resourceId: 'f', start: '2023-01-07T00:30:00', end: '2023-01-07T02:30:00', title: 'event 5' }
      ]
    });

    calendar.render();
  });

</script>
{{-- Moving Page / load page --}}
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
	$(document).ready(function() {
		var page = GetURLParameter('extpg');
		// alert(page);
		// ******** //
		$('#dynamic-menu-information').on('click', function() {
			activeInformationPage();
		});
		$('#dynamic-menu-activities').on('click', function() {
			activeActivitiesPage();
		});
		$('#dynamic-menu-leads').on('click', function() {
			activeLeadsPage();
		});
		$('#dynamic-menu-opportunities').on('click', function() {
			activeOpportunitiesPage();
		});
		$('#dynamic-menu-po').on('click', function() {
			activePOPage();
		});
		// ******* //
		if (page == "information") {
			loadAjaxInformation();
		}else if (page == 'activities') {
			loadAjaxActivities();
		}else if (page == 'leads') {
			loadAjaxLeads();
		}else if (page == 'opportunities'){
			loadAjaxOpportunities();
		}else if (page == 'purchasing') {
			loadAjaxPurchased();
		}else {
			console.log(page);
		}
	});
</script>
{{-- feature: view person data details --}}
<script>
	function previewContactCustomer(id) {
		$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
			});
		$.ajax({
			type: 'POST',
			url: "{{ route('source-data-individu') }}",
			data: {param:id},
			cache: false,
			// contentType: false,
			// processData: false,
				success: function(result) {
					$('#individu-name').html(result.name);
					$('#jobrole').html(result.role);
					$('#individu-phone').html(result.phone);
					$('#individu-mobile').html(result.mobile);
					$('#individu-email').html(result.email);
					$('#individu-address').html(result.location);
					$('#individu-note').html(result.note);
				}
		});
		$('#modal-view-detail-cst').modal('toggle');
		// alert(id);
	}
</script>
@endpush