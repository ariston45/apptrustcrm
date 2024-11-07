@php
$activePage = app('request')->input('extpg');
@endphp
@extends('layout.app')
@section('title')
Customer
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
<h4 class="page-title"></h4>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Step one</a></li>
<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="col-md-12 ">
	<div class="card" style="margin-bottom:150px;">
		<div class="card-header card-header-custom card-header-light">
			<h3 class="card-title">Information</h3>
			<div class="card-actions" style="padding-right: 10px;">
				<a href="{{ url('management') }}">
					<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
						<div style="font-weight: 700;">
							<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
						</div>
					</button>
				</a>
			</div>
		</div>
		<div class="row g-0">
			@include('contents.page_management.mgn_user_menu',['id' => $id])
			<div id="" class="col d-flex flex-column">
				<div class="card-body p-3">
					<div class="row mb-2">
						<div class="col">
							<h2 align="left">Information</h2>
						</div>
						<div class="col-auto ms-auto">
							<div align="right">
								<a href="{{ url('setting/user').'/'.$id }}">
									<button class="btn btn-sm btn-success">
										<i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit
									</button>
								</a>
							</div>
						</div>
					</div>
					<div class="row align-items-center mb-2">
						<div class="col-auto">
							<span class="avatar avatar-md" style=""></span>
						</div>
						<div class="col-4"> 
							<h3>{{ $user->name }}</h3>
							<span class="text-muted"><strong>{{ $user->uts_team_name }}</strong></span>
						</div>
						<div class="col ml-3">
							<div class="row">
								<div class="col-3">
									<label>Phone</label>
								</div>
								<div class="col-5">
									@if (!$user->phone)
										--
									@else
									{{ $user->phone }}
									@endif
								</div>
							</div>
							<div class="row">
								<div class="col-3">
									<label>Email</label>
								</div>
								<div class="col-5">
									@if (!$user->email)
										--
									@else
									{{ $user->email }}
									@endif
								</div>
							</div>
						</div>
					</div>
					<hr class="mb-3 mt-4">
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-12" style="text-align: center;">
									<h3>Sales Activity</h3>
								</div>
								<div class="col-12">
									<div id="activity-chart" class="chart-lg"></div>
								</div>
							</div>
						</div>
					</div>
					<hr class="mb-3 mt-4">
					<div class="row">
						<div class="col-xl-5 col-md-6">
							<div class="col-12" style="text-align: center;">
								<h3>Active Leads Project</h3>
							</div>
							<div class="col-12">
								<div id="project-chart" class="chart-lg"></div>
							</div>
						</div>
						<div class="col-xl-7 col-md-6">
							<div class="row">
								<div class="col-12" style="text-align: center;">
									<h3>Active Opportunities Project</h3>
								</div>
								<div class="col-12">
									<div id="opportunity-chart" class="chart-lg"></div>
								</div>
							</div>
						</div>
					</div>
					<hr class="mb-3 mt-4">
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-12" style="text-align: center;">
									<h3>Purchase</h3>
								</div>
								<div class="col-12">
									<div id="purchase-chart" class="chart-lg"></div>
								</div>
							</div>
						</div>
					</div>
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
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
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
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
{{-- <script src="{{ asset('plugins/apexcharts/apexcharts.min.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
{{-- Varibles --}}
<script>
</script>
{{-- Function --}}
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
function chartSalesActivity(data) {
	var options_chartActivity = {
		chart: {
			type: "area",
			fontFamily: 'inherit',
			height: 240,
			parentHeightOffset: 0,
			toolbar: {
				show: false,
			},
			animations: {
				enabled: false
			},
		},
		dataLabels: {
			enabled: false,
		},
		fill: {
			opacity: .16,
			type: 'solid'
		},
		stroke: {
			width: 2,
			lineCap: "round",
			curve: "smooth",
		},
		series: data.ValSeries,
		tooltip: {
			theme: 'dark'
		},
		grid: {
			padding: {
				top: -20,
				right: 0,
				left: -4,
				bottom: -4
			},
			strokeDashArray: 4,
		},
		xaxis: {
			labels: {
				padding: 0,
			},
			tooltip: {
				enabled: false
			},
			axisBorder: {
				show: false,
			},
			type: 'datetime',
		},
		yaxis: {
			labels: {
				padding: 4,
				formatter: function(val){
					return val.toFixed(0);
				}
			},
		},
		labels: data.DtLabels,
		legend: {
			show: true,
		},
	};
	var chartActivity = new ApexCharts(document.querySelector('#activity-chart'), options_chartActivity);
	chartActivity.render();
}
function chartPieLead(data) {  
	var option = {
		plotOptions: {
			pie: {
				donut: {
					size: '70%',
					labels: {
						show: true,
						value: {
							show: true,
						},
						total: {
							show: true,
							color: '#373d3f',
						},
					},
				}
			}
		},
		chart: {
			type: "donut",
			fontFamily: 'inherit',
			height: 350,
			sparkline: {
				enabled: true
			},
			animations: {
				enabled: true
			},
		},
		fill: {
			opacity: 1,
		},
		series: data.ValSeries,
		labels: data.ValLabels,
		tooltip: {
			theme: 'dark'
		},
		grid: {
			strokeDashArray: 4,
		},
		legend: {
			show: true,
			position: 'bottom',
			offsetY: 0,
			markers: {
				width: 10,
				height: 10,
				radius: 100,
			},
			itemMargin: {
				horizontal: 8,
				vertical: 8
			},
		},
		tooltip: {
			fillSeriesColor: false
		},
	};
	var chart = new ApexCharts(document.querySelector('#project-chart'), option);
	chart.render();
};
function chartBarOpportunity(data) {
	var options = {
		chart: {
			height: 320,
			type: 'bar'
		},
		xaxis:{
			type: 'status_opr',
			categories: data.ValLabels,
		},
		yaxis: {
			labels: {
				padding: 4,
				formatter: function(val){
					return val.toFixed(0);
				}
			},
		},
		colors: ['#009DDB','#F71F3F','#FF993C','#00A6B5','#C339C1','#00B557','#0067E1'],
		series: [{
			name: 'Value',
			data: data.ValSeries,
		}],
		plotOptions: {
			bar: {
				columnWidth: '60%'
			}
		},
		dataLabels: {
			enabled: true
		},
	};
	var chart = new ApexCharts(document.querySelector("#opportunity-chart"), options);
	chart.render();
}
function chartColumnPurchase(data) {  
	var options_4 = {
		series: [{
			name: 'Purchase',
			data: data.ValSeries,
		}],
		chart: {
			height: 350,
			type: 'bar',
		},
		plotOptions: {
			bar: {
				borderRadius: 10,
				dataLabels: {
					position: 'top', // top, center, bottom
				},
			}
		},
		dataLabels: {
			enabled: true,
			offsetY: -20,
			style: {
				fontSize: '12px',
				colors: ["#304758"]
			}
		},
		xaxis: {
			categories: data.ValLabels,
			position: 'top',
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false
			},
			crosshairs: {
				fill: {
					type: 'gradient',
					gradient: {
						colorFrom: '#D8E3F0',
						colorTo: '#BED1E6',
						stops: [0, 100],
						opacityFrom: 0.4,
						opacityTo: 0.5,
					}
				}
			},
			tooltip: {
				enabled: true,
			}
		},
		yaxis: {
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false,
			},
			labels: {
				show: false,
				formatter: function (val) {
					return val + "%";
				}
			}
		},
		title: {
			text: 'Monthly Purchase Order',
			floating: true,
			offsetY: 330,
			align: 'center',
			style: {
				color: '#444'
			}
		}
	};
	var chart_4 = new ApexCharts(document.querySelector("#purchase-chart"), options_4);
	chart_4.render();
};
function actionStatisticActivity() {
	var id = '{{ $id }}';
	var data;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('source-statistic-activity-user') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			chartSalesActivity(result);
		}
	});
}
function actionStatisticLead() {
	var id = '{{ $id }}';
	var data;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('source-statistic-lead-user') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			chartPieLead(result);
		}
	});
}
function actionStatisticOpportunity() {
	var id = '{{ $id }}';
	var data;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('source-statistic-opportunity-user') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			chartBarOpportunity(result);
		}
	});
}
function actionStatisticPurchase() {
	var id = '{{ $id }}';
	var data;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('source-statistic-purchase-user') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			chartColumnPurchase(result);
		}
	});
}
</script>
{{-- Declarate function --}}
<script>
actionStatisticActivity();
actionStatisticLead();
actionStatisticOpportunity();
actionStatisticPurchase();
</script>
{{-- Ready Fucntion --}}
<script>

</script>
@endpush