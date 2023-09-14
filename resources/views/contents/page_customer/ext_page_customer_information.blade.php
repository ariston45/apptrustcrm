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
		<div class="col-auto"> <h1>{{ $company->cst_name }}</h1></div>
	</div>
	{{-- <h3 class="mt-2">Business Profile</h3> --}}
	<hr class="mb-0 mt-0">
	<div class="row g-2">
		<div class="col-md-6">
			<div class="form-label"> <strong>Business Field</strong> </div>
			<p class="card-subtitle">
				@if ($company->cst_business_field == null)
				-
				@else
				{{ $company->cst_business_field }}</p>
				@endif
		</div>
		<div class="col-md">
			<div class="form-label"> <strong>Location</strong> </div>
			<p class="card-subtitle">
				@if (count($location_ar) == 0)
					-
				@else
					@foreach ($location_ar as $i => $loc)
						@if ($i == 0)
							{{ $loc }}, <br>
						@elseif ($i < 3)
							{{ $loc }},  	
						@else
							{{ $loc }}
						@endif
					@endforeach
				@endif
			</p>
		</div>
	</div>
	<hr class="mb-0 mt-0">
	<div class="row g-2">
		<div class="col-md-6">
			<div class="form-label"><strong>Email</strong> </div>
			<p class="card-subtitle">
				@if (count($email) == null)
					-
				@else
					@foreach ($email as $em)
						{{ $em->eml_address }} <br>
					@endforeach
				@endif
			</p>
		</div>
		<div class="col-md-6">
			<div class="form-label"> <strong>Telephone</strong> </div>
			<p class="card-subtitle">
				@if (count($phone) == null)
					-
				@else
					@foreach ($phone as $ph)
						{{ $ph->pho_number }} <br>
					@endforeach
				@endif
			</p>
		</div>
	</div>
	<hr class="mb-0 mt-0">
	<div class="form-label"> <strong>Notes</strong> </div>
	<p class="card-subtitle">
		@if ($company->cst_notes == null)
		-
		@else
		{!! html_entity_decode($company->cst_notes) !!}
		@endif
	</p>
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