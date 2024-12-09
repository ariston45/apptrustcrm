@php
	use Illuminate\Support\Str;
@endphp
@extends('layout.app')
@section('title')
opportunitys
@endsection
@section('pagetitle')
<div class="page-pretitle"></div>
{{-- <h4 class="page-title">opportunity Detail</h4> --}}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Step one</a></li>
<li class="breadcrumb-item active"><a href="#">Step two</a></li>
@endsection
@section('content')
<div class="card">
	<div class="card-status-top bg-success"></div>
	<div class="card-header card-header-custom card-header-light">
		<h3 class="card-title">Detail Activity</h3>
		<div class="card-actions" style="padding-right: 10px;">
			<a href="{{ url('activity') }}">
				<button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
					<div style="font-weight: 700;">
						<i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
					</div>
				</button>
			</a>
		</div>
	</div>
	{{-- ======================================================================================================== --}}
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-1">
			<div class="col">
				<h2 class="mb-1"><b>{{ $customer->ins_name }}</b></h2>
				<div class="page-pretitle-custom mb-2">
					Sub Customer : 
					@if ($subcustomer->cst_name == null)
						-
					@else
						{{ $subcustomer->cst_name }}
					@endif
				</div>
			</div>
			<div class="col-auto">
				<div id="area-act-status">
					@if ($main_activity->act_run_status == 'beready')
					<button id="btn-status-id" class="badge bg-azure" onclick="actionChangeStatusAct()">
					@elseif ($main_activity->act_run_status == 'running')
					<button id="btn-status-id" class="badge bg-green" onclick="actionChangeStatusAct()">
					@else
					<button id="btn-status-id" class="badge bg-dark-lt" onclick="actionChangeStatusAct()">
					@endif
						{{ Str::title( $main_activity->act_run_status) }}
					</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="area-act-info" class="col-md-6 col-12">
				<button href="#" class="{{ $main_activity->aat_custom_class_2 }} mb-1"> 
					<i class="{{ $main_activity->aat_icon }}" style="margin-right: 8px;"></i> 
					{{ Str::title( $main_activity->aat_type_name) }}
				</button>
				<div class="badges-list mt-1 mb-2">
					<b>Pic:</b>
					@foreach ($cst_person as $list)
					<button class="badge" onclick="actionViewCnt({{ $list->cnt_id }})">{{ $list->cnt_fullname }}</button> 
					@endforeach
				</div>
				<hr class="mb-2 mt-0">
				<div class="row">
					<div class="col-4">
						<b>Lead Project</b>
					</div>
					<div class="col-8">
						{{ $main_activity->lds_title }}
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<b>Assign</b>
					</div>
					<div class="col-8">
						{{ $main_activity->name }}
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<b>Team Sales</b>
					</div>
					<div class="col-8">
						{{ $team }}
					</div>
				</div>
			</div>
			<div id="area-act-date" class="col-md-6 col-12">
				<em class="lh-base mb-0"><i>Created by <b>{{ $user_created->name }}</b> on</i></em>
				<div class="page-pretitle-custom mb-1">{{ $date_created }}</div>
				<em class="lh-base mb-0"><i>Follow Up Date</i></em>
				<div class="page-pretitle-custom mb-1">{{ $date_due }}</div>
			</div>
		</div>
	</div>
	{{-- ======================================================================================================== --}}
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-0">
			<div class="col-12 mb-2">
				<div class="row">
					<div class="col-12">
						<label class="form-label text-muted pb-0 pt-0">Describe Activity</label>		
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label class="form-check form-switch mb-1 mt-1" onclick="actionChangeSys1()">
							<input class="form-check-input" type="checkbox" value="checkOff" name="actionInputAct1" id="actionInputAct1">
							<span class="form-check-label" id="lbl-autocheck-act-describe">Auto Save</span>
						</label>
					</div>
					<div class="col-auto">
						<button type="submit" form="formContent2" class="btn btn-sm bg-blue text-blue-fg btn-pill">
							<i class="ri-save-line" style="margin-right: 6px;"></i> Save
						</button>
					</div>
				</div>
				<form id="formContent2" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<textarea id="textingContent1" class="form-control" name="todo_describe"
					rows="3" placeholder="Describe something ..." oninput="actionStorePreActivity()">{{ $main_activity->act_todo_describe }}</textarea>
				</form>
			</div>
		</div>
		<hr class="mt-2 mb-2">
		<div class="row mb-0">
			<div class="col-12 mb-2">
				<div class="row">
					<div class="col-12">
						<label class="form-label text-muted pb-0 pt-0">Result Activity</label>		
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label class="form-check form-switch mb-1 mt-1" onclick="actionChangeSys2()">
							<input class="form-check-input" type="checkbox" value="checkOff" name="actionInputAct2" id="actionInputAct2">
							<span class="form-check-label" id="lbl-autocheck-act-result">Auto Save</span>
						</label>
					</div>
					<div class="col-auto">
						<button type="submit" form="formContent3" class="btn btn-sm bg-blue text-blue-fg btn-pill">
							<i class="ri-save-line" style="margin-right: 6px;"></i> Save
						</button>
					</div>
				</div>
				<form id="formContent3" enctype="multipart/form-data" action="javascript:void(0)" method="post" autocomplete="off">
					<textarea id="textingContent2" class="form-control" name="todo_result"
					rows="3" placeholder="Describe something ..." oninput="actionStorePostActivity()">{{ $main_activity->act_todo_result }}</textarea>
				</form>
			</div>
		</div>
	</div>
	{{-- ======================================================================================================== --}}
	<div class="card-body pt-2" style="padding-left: 10px;padding-right: 10px;">
		<div class="row mb-1">
			<div id="area-action" class="col-12">
				<em class="text-muted lh-base mb-1"><i>Action</i></em>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<button href="#area-action" class="btn btn-outline-blue btn-pill" onclick="actionReschedule()">Reschedule</button>
				<button href="#area-action" class="btn btn-outline-blue btn-pill" onclick="actionCloseActivities()">Close</button>
				<button href="#area-action" class="btn btn-outline-blue btn-pill" onclick="actionCloseCreateAct()">Close And Create New Activity</button>
				<button href="#area-action" class="btn btn-outline-blue btn-pill" onclick="actionTicketCreateAct()">Create Ticket With New Activity</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-view-contact-detail" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Contact Information</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body pt-2 pb-2 pl-3 pr-3">
				<div class="row">
					<div class="col-3">
						Name
					</div>
					<div class="col-9">
						<span id="cnt-name">-</span>
					</div>
				</div>
				<div class="row">
					<div class="col-3">
						Job Position
					</div>
					<div class="col-9">
						<span id="cnt-position">-</span>
					</div>
				</div>
				<div class="row">
					<div class="col-3">
						Mobile
					</div>
					<div class="col-9">
						<span id="cnt-mobile">-</span>
					</div>
				</div>
				<div class="row">
					<div class="col-3">
						Email
					</div>
					<div class="col-9">
						<span id="cnt-email">-</span>
					</div>
				</div>
				<div class="row">
					<div class="col-3">
						Telephone
					</div>
					<div class="col-9">
						<span id="cnt-telephone">-</span>
					</div>
				</div>
				{{-- <div class="row">
					<div class="col-3">
						Address
					</div>
					<div class="col-9">
						<span id="cnt-address">-</span>
					</div>
				</div> --}}
			</div>
			<div class="modal-footer" style="padding-right: 12px;">
				<a id="btn-update-contact" href="" class="me-auto">
					<button type="button" class="btn btn-sm bg-dark-lt me-auto m-0" form="" data-bs-dismiss="modal">Update Contact</button>
				</a>
				<button type="button" class="btn btn-sm btn-primary m-0" form="" data-bs-dismiss="modal" style="width: 50px;">OK</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-change-activity-status" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Change Activity Status</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent1" name="form_content9" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="activity_id_i" name="act_id" value="{{ $id_activity }}">
					<select type="text" class="form-select ts-input-custom" name="status" id="select-act-status" value="">
						<option value="{{ null }}">{{ null }}</option>
						<option value="beready">Beready</option>
						<option value="running">Running</option>
						<option value="finished">Finished</option>
					</select>
				</form>
			</div>
			<div class="modal-footer" style="padding-left: 16px;padding-right: 16px;padding-bottom: 25px;">
				<button type="button" id="ResetButtonFormUpdateInfo" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
				<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent1"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-edit-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Reschedule Activity</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent4" name="formContent4" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<input type="hidden" id="act-id" name="act_id" value="{{ $id_activity }}" readonly>
					<input type="hidden" id="lead-id" name="lead_id" value="{{ $main_activity->act_lead_id }}" readonly>
					<div class="row">
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Date</label>
								<div class="col" style="padding: 0px;margin-left: 0px;">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_due_edit" name="due_date" class="form-control p-1" placeholder="Due Date" autocomplete="off" value="{{ $date_due }}">
									</div>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Type Activity</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-type-activity-i" class="form-select ts-input-custom" name="action_todo" placeholder="Select your type activity" >
										<option value="{{ null }}"></option>
										@foreach ($activity_type as $list)
											@if ($list->aat_id == $main_activity->act_todo_type_id)
											<option value="{{ $list->aat_id }}" selected>{{ $list->aat_type_name }}</option>
											@else
											<option value="{{ $list->aat_id }}">{{ $list->aat_type_name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-signed-user-i" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="">
										<option value="{{ $main_activity->userid }}">{{ $main_activity->name }}</option>
										@foreach ($all_user as $list)
										<option value="{{ $list->id }}">{{ $list->name }} - {{ $list->uts_team_name }}</option>
										@endforeach
									</select>
									<button type="button" id="btn-add-team-i" class="badge bg-cyan mt-1 mb-1" onclick="actionViewInputTeamI()">+ Team</button>
									<button type="button" id="btn-remove-team-i" class="badge bg-cyan mt-1 mb-1" style="display: none;">x Close</button>
									<div id="select-signed-user-team-area-i" style="display: none;">
										<select type="text" class="form-select ts-input-custom" multiple name="assignment_user_team[]" placeholder="User team assignment..." id="select-signed-user-team-i" value="">
											@foreach ($all_user as $list)
											@if (in_array($list->id,$team_id_ar))
											<option value="{{ $list->id }}" selected>{{ $list->name }}</option>
											@else
											<option value="{{ $list->id }}">{{ $list->name }} - {{ $list->uts_team_name }}</option>	
											@endif
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-2 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col" style="padding: 0px;">
									<select type="text" id="select-pic-user-i" class="form-select ts-input-custom" name="pic_user[]" placeholder="Select PIC..."  value="">
										@foreach ($cst_person_all as $list)
											@if (in_array($list->cnt_id,$cst_person_id))
											<option value="{{ $list->cnt_id }}" selected>{{ $list->cnt_fullname }}</option>	
											@else
											<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-12 col-md-12">
							<div class="mb-2" style="margin-right: 0px;">
								<label class="form-check">
									<input id="todo_done_i" class="form-check-input" type="checkbox" name="todo_status" value="done" onclick="actionTodo_i('todo_done')"
									@if ($main_activity->act_run_status == 'finished') checked @endif>
									<span class="form-check-label">It is already done.</span>
								</label>
								<label class="form-check">
									<input id="todo_running_i" class="form-check-input" type="checkbox" name="todo_status" value="running" onclick="actionTodo_i('todo_running')"
									@if ($main_activity->act_run_status == 'running') checked @endif>
									<span class="form-check-label">It is already running.</span>
								</label>
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
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent4"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">UPDATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-close-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
		<div class="modal-content">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			<div class="modal-status bg-warning"></div>
			<div class="modal-body text-center py-4">
				<i class="ri-error-warning-line icon mb-2 text-warning icon-lg"></i>
				<h3>Are you sure?</h3>
				<div class="text-muted">Do you really want to close activities?</div>
			</div>
			<div class="modal-footer">
				<div class="w-100">
					<div class="row">
						<div class="col">
							<button href="#" class="btn btn-sm w-100" data-bs-dismiss="modal"> Cancel</button>
						</div>
						<div class="col">
							<form id="formContent5" name="formContent5" enctype="multipart/form-data" action="javascript:void(0)" method="post">
								<input type="hidden" name="act_id" value="{{ $id_activity }}">
								<button type="submit" href="#" class="btn btn-sm bg-orange-lt w-100" data-bs-dismiss="modal"> Close Activity </button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Close & Create Activities</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent6" name="form_content6" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<input type="hidden" id="lead-status-id" name="lead_status_id" value="{{ $main_activity->lds_status }}" readonly>
						<input type="hidden" id="lead-id" name="lead_id" value="{{ $main_activity->lds_id }}" readonly>
						<input type="hidden" id="lead-id" name="customer" value="{{ $main_activity->act_cst }}" readonly>
						<input type="hidden" id="lead-id" name="subcustomer" value="{{ $main_activity->act_subcst }}" readonly>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Date</label>
								<div class="col">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_due" name="due_date" class="form-control p-1" placeholder="Due Date" autocomplete="off">
									</div>
								</div>
							</div>
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Type Activity</label>
								<div class="col">
									<select type="text" id="select-type-activity" class="form-select ts-input-custom" name="action_todo" placeholder="Select your type activity"  value="">
										<option value="{{ null }}"></option>
										@foreach ($activity_type as $list)
											<option value="{{ $list->aat_id }}">{{ $list->aat_type_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col">
									<select type="text" id="select-assigned-user" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="">
										<option value="{{ $user->id }}">{{ $user->name }}</option>
										@foreach ($all_user as $list)
										<option value="{{ $list->id }}">{{ $list->name }} - {{ $list->uts_team_name }}</option>
										@endforeach
									</select>
									<button type="button" id="btn-add-team" class="badge mt-1 mb-1" onclick="actionViewInputTeam()">+ Team</button>
									<button type="button" id="btn-remove-team" class="badge mt-1 mb-1" style="display: none;">x Close</button>
									<div id="select-signed-user-team-area" style="display: none;">
										<select type="text" class="form-select ts-input-custom" name="assignment_user_team[]" placeholder="User team assignment..." id="select-signed-user-team" value="">
											<option value="{{ null }}"></option>
											@foreach ($all_user as $list)
											<option value="{{ $list->id }}">{{ $list->name }} - {{ $list->uts_team_name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col">
									<select type="text" id="select-pic-user" class="form-select ts-input-custom" multiple name="pic_user[]" placeholder="Select PIC..."  value="">
										<option value=""></option>
										@foreach ( $cst_person_all as $list)
											<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-12 col-md-12">
						<div class="mb-2" style="margin-right: 0px;">
							<label class="col-12 col-form-label">Activty Describe</label>
							<textarea id="notesActivitiesDescribe" name="activity_describe"></textarea>
						</div>
						<div class="mb-2" style="margin-right: 0px;">
							<label class="col-12 col-form-label">Activty Result</label>
							<textarea id="notesActivitiesResult" name="activity_result"></textarea>
						</div>
						<div class="mb-2" style="margin-right: 0px;">
							<label class="form-check">
								<input id="todo_done" class="form-check-input" type="checkbox" name="todo_status" value="done" onclick="actionTodo('todo_done')">
								<span class="form-check-label">It is already done.</span>
							</label>
							<label class="form-check">
								<input id="todo_running" class="form-check-input" type="checkbox" name="todo_status" value="running" onclick="actionTodo('todo_running')">
								<span class="form-check-label">It is already running.</span>
							</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-auto">
					<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent6"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- ===================================================================================================== --}}
<div id="modal-add-ticket-activities" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-full-width-custom modal-dialog-centered mt-1" role="document">
		<div class="modal-content">
			<div class="modal-header" style="min-height: 2.5rem;padding-left: 1rem;">
				<h5 class="modal-title">Close & Create Ticket</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="height: 2.5rem;"></button>
			</div>
			<div class="modal-body p-3">
				<form id="formContent7" name="form_content6" enctype="multipart/form-data" action="javascript:void(0)" method="post">
					@csrf
					<div class="row">
						<input type="hidden" id="lead-status-id" name="lead_status_id" value="{{ $main_activity->lds_status }}" readonly>
						<input type="hidden" id="lead-id" name="lead_id" value="{{ $main_activity->lds_id }}" readonly>
						<input type="hidden" id="lead-id" name="customer" value="{{ $main_activity->act_cst }}" readonly>
						<input type="hidden" id="lead-id" name="subcustomer" value="{{ $main_activity->act_subcst }}" readonly>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Date</label>
								<div class="col">
									<div class="input-group">
										<span class="input-group-text p-1">
											<i class="ri-calendar-2-line"></i>
										</span>
										<input type="text" id="datepicker_due_2st" name="due_date" class="form-control p-1" placeholder="Due Date" autocomplete="off">
									</div>
								</div>
							</div>
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Type Activity</label>
								<div class="col">
									<select type="text" id="select-type-activity-2st" class="form-select ts-input-custom" name="action_todo" placeholder="Select your type activity"  value="">
										<option value="{{ null }}"></option>
										@foreach ($activity_type as $list)
											<option value="{{ $list->aat_id }}">{{ $list->aat_type_name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xl-6 col-md-12">
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Assigned to</label>
								<div class="col">
									<select type="text" id="select-assigned-user-2st" class="form-select ts-input-custom" name="assignment_user" placeholder="User assignment..."  value="">
										<option value="{{ $user->id }}">{{ $user->name }}</option>
										@foreach ($all_user as $list)
										<option value="{{ $list->id }}">{{ $list->name }} - {{ $list->uts_team_name }}</option>
										@endforeach
									</select>
									<button type="button" id="btn-add-team-2st" class="badge mt-1 mb-1" onclick="actionViewInputTeam2st()">+ Team</button>
									<button type="button" id="btn-remove-team-2st" class="badge mt-1 mb-1" style="display: none;">x Close</button>
									<div id="select-signed-user-team-area-2st" style="display: none;">
										<select type="text" class="form-select ts-input-custom" name="assignment_user_team[]" placeholder="User team assignment..." id="select-signed-user-team-2st" value="">
											<option value="{{ null }}"></option>
											@foreach ($all_user as $list)
											<option value="{{ $list->id }}">{{ $list->name }} - {{ $list->uts_team_name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="mb-2 mt-0 row" style="margin-right: 0px;">
								<label class="col-3 col-form-label">Person in Charge</label>
								<div class="col">
									<select type="text" id="select-pic-user-2st" class="form-select ts-input-custom" multiple name="pic_user[]" placeholder="Select PIC..."  value="">
										<option value=""></option>
										@foreach ( $cst_person_all as $list)
											<option value="{{ $list->cnt_id }}">{{ $list->cnt_fullname }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-12 col-md-12">
						<div class="mb-2" style="margin-right: 0px;">
							<label class="col-12 col-form-label">Activty Describe</label>
							<textarea id="notesActivitiesDescribe" name="activity_describe"></textarea>
						</div>
						<div class="mb-2" style="margin-right: 0px;">
							<label class="col-12 col-form-label">Activty Result</label>
							<textarea id="notesActivitiesResult" name="activity_result"></textarea>
						</div>
						<div class="mb-2" style="margin-right: 0px;">
							<label class="form-check">
								<input id="todo_done_2st" class="form-check-input" type="checkbox" name="todo_status" value="done" onclick="actionTodo2st('todo_done')">
								<span class="form-check-label">It is already done.</span>
							</label>
							<label class="form-check">
								<input id="todo_running_2st" class="form-check-input" type="checkbox" name="todo_status" value="running" onclick="actionTodo2st('todo_running')">
								<span class="form-check-label">It is already running.</span>
							</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-auto">
					<button type="button" id="ResetButtonFormFolUp" class="btn btn-sm me-auto" style="margin: 0px; width: 50px;"><i class="ri-refresh-line"></i></button>
					<button type="submit" class="btn btn-sm btn-ghost-primary active" form="formContent7"  data-bs-dismiss="modal" style="margin:0px; padding-left: 20px;padding-right: 16px;">CREATE</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('customs/css/default.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
<style>
	p {
		margin-bottom: 0px;
	}
	@media only screen and (max-width: 1050px) {
		#btn-opportunity-status {
			margin-bottom: 10px;
		}
	}
	@media only screen and (max-width: 767px) {
		#area-act-date {
			text-align: left;
		}
		#area-act-info{
			margin-bottom: 8px;
		}
	}
	@media only screen and (min-width: 768px) {
		#area-act-date {
			text-align: right;
		}
	}
	.modal-full-width-custom {
		max-width: none;
		margin-top: 0px;
		margin-right: 8rem;
		margin-bottom: 0px;
		margin-left: 8rem;
	}
	#area-act-status {
		text-align: right;
	}
	.card-body-contact{
		padding: 4px 6px 4px 6px;
		/* padding-top: 2px;
		padding-left: 6px; */
	}
	.page-pretitle-custom {
		font-size: .725rem;
		font-weight: var(--tblr-font-weight-bold);
		text-transform: uppercase;
		letter-spacing: .04em;
		line-height: 1rem;
		color: var(--tblr-muted);
	}
	.btn-cst {
		text-decoration: none;
		--tblr-btn-icon-size: 0.25rem;
		--tblr-btn-bg: #ffffff;
		--tblr-btn-color: #0b1221;
		--tblr-btn-border-color: #e1e1e1;
		--tblr-btn-hover-bg: transparent;
		--tblr-btn-hover-border-color: var(--tblr-border-color-active);
		--tblr-btn-box-shadow: var(--tblr-shadow-button);
		--tblr-btn-active-color: #f8fffd;
		--tblr-btn-active-bg: #006dc0;
		--tblr-btn-active-border-color: #006dc0;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		white-space: nowrap;
		box-shadow: var(--tblr-btn-box-shadow);
	}
	.btn-cst {
		--tblr-btn-padding-x: 0.8rem;
		--tblr-btn-padding-y: 0.3rem;
	}
	.ts-control{
		padding-bottom: 0.28rem;
		padding-top: 0.28rem;
		padding-left: 0.39rem;
	}
	.ts-wraper{
		
	}
	.ts-input-custom{
		min-height: 0.53rem;
	}
	.custom-datatables tbody tr td{
		padding-top: 4px;
		padding-bottom: 4px;
		padding-left: 0.8rem;
		padding-right: 0.8rem;
	}
	.img-status {
		max-height: 200px;
	}
</style>
@endpush
@push('script')
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('plugins/tom-select/dist/js/tom-select.base.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/litepicker/bundle/index.umd.min.js') }}"></script>
{{-- ============================================================================================ --}}
<script>

</script>
{{-- ============================================================================================ --}}
<script>
var editorPreActivity = tinymce.init({
	selector : "textarea#textingContent1",
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function (editor) {  
		editor.on('init',function(e){
		});
		editor.on('input',function (e) {
			var content_data_1 = editor.getContent(); 
			actionStorePreActivity(content_data_1);
		});
	}
});
var editorPostActivity = tinymce.init({
	selector : "textarea#textingContent2",
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function (editor) {  
		editor.on('init',function(e){
		});
		editor.on('input',function (e) {
			var content_data_1 = editor.getContent(); 
			actionStorePostActivity(content_data_1);
		});
	}
});
var select_act_status = new TomSelect("#select-act-status",{
	persist: false,
	createOnBlur: true,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-act-status">'+escape(data.title)+'</div>';
		}
	}
});
const picker_a = new easepick.create({
	element: "#datepicker_due_edit",
	css: [ "{{ asset('plugins/litepicker/bundle/index.css') }}" ],
	zIndex: 10,
	format: "YYYY-MM-DD hh:mm a",
	plugins: [
		"TimePlugin","AmpPlugin"
	],
	TimePlugin: {
		format12: true
	},
	AmpPlugin: {
		resetButton: true,
		darkMode: false
	},
});
const picker_b = new easepick.create({
	element: "#datepicker_due",
	css: [ "{{ asset('plugins/litepicker/bundle/index.css') }}" ],
	zIndex: 10,
	format: "YYYY-MM-DD hh:mm a",
	plugins: [
		"TimePlugin","AmpPlugin"
	],
	TimePlugin: {
		format12: true
	},
	AmpPlugin: {
		resetButton: true,
		darkMode: false
	},
});
const picker_c = new easepick.create({
	element: "#datepicker_due_2st",
	css: [ "{{ asset('plugins/litepicker/bundle/index.css') }}" ],
	zIndex: 10,
	format: "YYYY-MM-DD hh:mm a",
	plugins: [
		"TimePlugin","AmpPlugin"
	],
	TimePlugin: {
		format12: true
	},
	AmpPlugin: {
		resetButton: true,
		darkMode: false
	},
});
var select_type_activity = new TomSelect("#select-type-activity-i",{
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
var select_type_activity = new TomSelect("#select-type-activity",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-type-activity">'+escape(data.title)+'</div>';
		}
	}
});
var select_type_activity_2st = new TomSelect("#select-type-activity-2st",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-type-activity">'+escape(data.title)+'</div>';
		}
	}
});
var select_signed_user_i = new TomSelect("#select-signed-user-i",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-i">'+escape(data.title)+'</div>';
		}
	}
});
var select_assigned_user = new TomSelect("#select-assigned-user",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-assigned-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_assigned_user_2st = new TomSelect("#select-assigned-user-2st",{
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-assigned-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_signed_user_team_i = new TomSelect("#select-signed-user-team-i",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-team-i">'+escape(data.title)+'</div>';
		}
	}
});
var select_pic_user_i = new TomSelect("#select-pic-user-i",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-i">'+escape(data.title)+'</div>';
		}
	}
});
var select_pic_user_i = new TomSelect("#select-pic-user",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-pic-user">'+escape(data.title)+'</div>';
		}
	}
});
var select_pic_user_i = new TomSelect("#select-pic-user-2st",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-pic-user-2st">'+escape(data.title)+'</div>';
		}
	}
});
var select_signed_user_team = new TomSelect("#select-signed-user-team",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-team">'+escape(data.title)+'</div>';
		}
	}
});
var select_signed_user_team_2st = new TomSelect("#select-signed-user-team-2st",{
	persist: false,
	createOnBlur: true,
	maxItems: 10,
	create: false,			
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	render: {
		option: function(data, escape) {
			return '<div><span class="title">'+escape(data.title)+'</span></div>';
		},
		item: function(data, escape) {
			return '<div id="select-signed-user-team-2st">'+escape(data.title)+'</div>';
		}
	}
});
</script>
<script>
function actionGetDataActivity(id) {
	var data ='';  
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var response = $.ajax({
		type: 'POST',
		url: "{{ route('lead-activities-detail') }}",
		async: false,
		data: {
			"id": id
		},
		success: function(result) {
			data = result;
		}
	});
	return data;
}
function actionChangeStatusAct() {  
	var act_id = "{{ $id_activity }}"; 
	$('#modal-change-activity-status').modal('toggle');
	var res_act_i = actionGetDataActivity(act_id);
	select_act_status.setValue([res_act_i.actstatus]);
};
function actionReplaceStatus(status) {  
	console.log(status);
	if (status == 'beready') {
		$('#btn-status-id').html('Beready');
		$('#btn-status-id').removeClass("bg-green").removeClass("bg-dark-lt").addClass('bg-azure');
	} else if(status == 'running') {
		$('#btn-status-id').html('Running');
		$('#btn-status-id').removeClass("bg-azure").removeClass("bg-dark-lt").addClass('bg-green');
	}else{
		$('#btn-status-id').html('Finished');
		$('#btn-status-id').removeClass("bg-azure").removeClass("bg-green").addClass('bg-dark-lt');
	}
};
function actionViewCnt(id) {
	$('#modal-view-contact-detail').modal('toggle');
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		url: "{{ route('activity-pic-contact') }}",
		data: {
			'idcnt': id,
		},
		success: function(result) {
			$('#cnt-name').html(result.contact.cnt_name);
			$('#cnt-position').html(result.contact.cnt_position);
			$('#cnt-mobile').html(result.contact.cnt_mobile);
			$('#cnt-email').html(result.contact.cnt_email);
			$('#cnt-telephone').html(result.contact.cnt_telephone);
			$('#cnt-address').html(result.contact.cnt_address);
			$("#btn-update-contact").attr("href","{{ url('customer/detail-customer/person-update') }}/"+result.contact.cnt_id);
		}
	});
};
function actionChangeSys1() {
	var checkUp1 = $('#actionInputAct1').val();
	if (checkUp1 == 'checkOn') {
		$('#actionInputAct1').val('checkOff');
	}else{
		$('#actionInputAct1').val('checkOn');
	}
};
function actionChangeSys2() {
	var checkUp1 = $('#actionInputAct2').val();
	if (checkUp1 == 'checkOn') {
		$('#actionInputAct2').val('checkOff');
	}else{
		$('#actionInputAct2').val('checkOn');
	}
};
function actionAutoSaveActDescribe() {
	setInterval(function () {
		var check1 = $('#actionInputAct1').val();
		if (check1 == 'checkOn') {
			$('#lbl-autocheck-act-describe').html('Saving...');
			var editorValue1 = tinyMCE.get('textingContent1').getContent();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: "POST",
				url: "{{ route('update-describe-activity') }}",
				data: {
					"id": {{ $id_activity }},
					"todo_describe": editorValue1
				},
				async: false,
				success: function(result) {					
				}
			});
		};
		setTimeout(function () {
			$('#lbl-autocheck-act-describe').html('Auto Save');
		},1000 );
	},5000);
};
function actionAutoSaveActResult() {
	setInterval(function () {
		var check2 = $('#actionInputAct2').val();
		if (check2 == 'checkOn') {
			$('#lbl-autocheck-act-result').html('Saving...');
			var editorValue2 = tinyMCE.get('textingContent2').getContent();
			
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				type: "POST",
				url: "{{ route('update-result-activity') }}",
				data: {
					"id": {{ $id_activity }},
					"todo_result": editorValue2
				},
				async: false,
				success: function(result) {
				}
			});
		};
		setTimeout(function () {
			$('#lbl-autocheck-act-result').html('Auto Save');
		},1000 );
	},5000);
};
function actionStorePreActivity(data) {  
	// console.log(data);
};
function actionStorePostActivity(data) {  
	// console.log(data);
};
function actionStoreDescribeAct() {
	// console.log(editorPreActivity);
	// alert(1);
};
function actionReschedule() { 
	$('#modal-edit-activities').modal('toggle');
};
function actionViewInputTeam() {
	$('#btn-add-team').hide();
	$('#btn-remove-team').show(); 
	$('#select-signed-user-team-area').slideDown();
};
function actionViewInputTeam2st() {
	$('#btn-add-team-2st').hide();
	$('#btn-remove-team-2st').show(); 
	$('#select-signed-user-team-area-2st').slideDown();
};
function actionViewInputTeamI() {
	$('#btn-add-team-i').hide();
	$('#btn-remove-team-i').show(); 
	$('#select-signed-user-team-area-i').slideDown();
};
function actionRemoveInputTeamI(x) {
	x.clear();
	$('#btn-remove-team-i').hide();  
	$('#btn-add-team-i').show();
	$('#select-signed-user-team-area-i').slideUp();
};
function actionRemoveInputTeam(x) {
	x.clear();
	$('#btn-remove-team').hide();  
	$('#btn-add-team').show();
	$('#select-signed-user-team-area').slideUp();
};
function actionRemoveInputTeam2st(x) {
	x.clear();
	$('#btn-remove-team-2st').hide();  
	$('#btn-add-team-2st').show();
	$('#select-signed-user-team-area-2st').slideUp();
};
function actionParamTeam() {  
	var countTeam = "{{ $count_team }}";
	if (countTeam > 0) {
		$('#btn-remove-team-i').show();
		$('#select-signed-user-team-area-i').show();
		$('#btn-add-team-i').hide();
	}else{
		$('#btn-remove-team-i').hide();
		$('#btn-add-team-i').hide();
		$('#select-signed-user-team-area-i').hide();
	}
};
function actionTodo_i(x) { 
	if (x == 'todo_done') {
		document.getElementById("todo_running_i").checked = false;
	}else{
		document.getElementById("todo_done_i").checked = false;
	}
};
function actionTodo(x) { 
	if (x == 'todo_done') {
		document.getElementById("todo_running").checked = false;
	}else{
		document.getElementById("todo_done").checked = false;
	}
};
function actionTodo2st(x) { 
	if (x == 'todo_done') {
		document.getElementById("todo_running_2st").checked = false;
	}else{
		document.getElementById("todo_done_2st").checked = false;
	}
};
function actionCloseActivities() {  
	$('#modal-close-activities').modal('toggle');
};
function actionCloseCreateAct() {  
	$('#modal-add-activities').modal('toggle');
};
function actionTicketCreateAct() {  
	$('#modal-add-ticket-activities').modal('toggle');
};
</script>
{{-- ============================================================================================ --}}
<script>
/*Running Function*/
actionParamTeam();
actionAutoSaveActDescribe();
actionAutoSaveActResult();
</script>
{{-- ============================================================================================ --}}
<script>
$(document).ready(function() {
	$('#formContent1').submit(function(e) {
		e.preventDefault();
		var formData1 = new FormData(this);
		formData1.append("lead_id", "");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-status-lead-activities') }}",
			data: formData1,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already updated.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionReplaceStatus(result.status);
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
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
		formData2.append("id", "{{ $id_activity }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('update-describe-activity') }}",
			data: formData2,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$.alert({
					type: 'green',
					title: 'Success',
					content: 'Data already store to database.',
					animateFromElement: false,
					animation: 'opacity',
					closeAnimation: 'opacity'
				});
			}
		});
	});
	$("#formContent3").submit(function(e) {
		e.preventDefault();
		var formData3 = new FormData(this);
		formData3.append("id", "{{ $id_activity }}");
		$.ajaxSetup({
			headers: {
				"X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
			}
		});
		$.ajax({
			type: "POST",
			url: "{{ route('update-result-activity') }}",
			data: formData3,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				$.alert({
					type: 'green',
					title: 'Success',
					content: 'Data already store to database.',
					animateFromElement: false,
					animation: 'opacity',
					closeAnimation: 'opacity'
				});
			}
		});
	});
	$('#formContent4').submit(function(e) {
		e.preventDefault();
		var formData4 = new FormData(this);
		formData4.append("lead_id", "");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-schedule-activities') }}",
			data: formData4,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already store to database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionLoadDataActivities();
					resetFormConten6(select_signed_user_team,select_signed_user,select_type_activity);
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	$('#formContent5').submit(function(e) {
		e.preventDefault();
		var formData5 = new FormData(this);
		formData5.append("lead_id", "");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('update-close-activities') }}",
			data: formData5,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already store to database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionReplaceStatus('finished');
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	$('#formContent6').submit(function(e) {
		e.preventDefault();
		var formData6 = new FormData(this);
		formData6.append("act_id", "{{ $id_activity }}");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-close-activity') }}",
			data: formData6,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already store to database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionReplaceStatus('finished');
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	$('#formContent7').submit(function(e) {
		e.preventDefault();
		var formData7 = new FormData(this);
		formData7.append("act_id", "{{ $id_activity }}");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: "{{ route('store-ticket-activity') }}",
			data: formData7,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result) {
				if (result.param == true) {
					$.alert({
						type: 'green',
						title: 'Success',
						content: 'Data already store to database.',
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
					actionReplaceStatus('finished');
				}else{
					$.alert({
						type: 'red',
						title: 'Something error!',
						content: result.message,
						animateFromElement: false,
						animation: 'opacity',
						closeAnimation: 'opacity'
					});
				}
			}
		});
	});
	/*=================================================================================================================*/
});
</script>
{{-- ============================================================================================ --}}
<script>
$('#btn-remove-team').click(function () {
	actionRemoveInputTeam(select_signed_user_team);
});
$('#btn-remove-team-2st').click(function () {
	actionRemoveInputTeam2st(select_signed_user_team);
});
$('#btn-remove-team-i').click(function () {
	actionRemoveInputTeamI(select_signed_user_team_i);
});
</script>
{{-- ============================================================================================ --}}
<script type="text/javascript">
var notesStoredLOC =  localStorage.getItem('notesInputActivities');
var notesEitor = ""; 
var notes_a = tinymce.init({
	selector: 'textarea#notesActivities',
	height : "300",
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
			localStorage.setItem('notesInputActivities', notes);
		});
	}
});
</script>
{{-- ============================================================================================ --}}
<script type="text/javascript">
var notesOpportunityNotesLOC = localStorage.getItem('notesInputOpportunity');
var notesEitorOppor = ""; 
var notes_b = tinymce.init({
	selector: 'textarea#notesOpportunity',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
		editor.on('init',function(e) {
			if (notesOpportunityNotesLOC != null) {
				editor.setContent(notesOpportunityNotesLOC);
			}
		});
		editor.on('input', function(e) {
			notes = editor.getContent();
			localStorage.setItem('notesInputOpportunity', notes);
		});
	}
});
</script>
{{-- ============================================================================================ --}}
<script type="text/javascript"> 
var notes_b = tinymce.init({
	selector: 'textarea#notesActivitiesDescribe',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
</script>
{{-- ============================================================================================ --}}
<script type="text/javascript"> 
var notes_b = tinymce.init({
	selector: 'textarea#notesActivitiesResult',
	height : "300",
	promotion: false,
	statusbar: false,
	setup: function(editor) {
	}
});
</script>
{{-- ============================================================================================ --}}
@endpush