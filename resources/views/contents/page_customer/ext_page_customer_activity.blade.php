<div class="card-body">
	<div class="row mb-2">
		<div class="col-6">
			<h2 align="left">Activities</h2>
		</div>
		<div class="col-6">
			<div align="right">
				{{-- <a href="{{ url('customer/detail-customer/company-update/'.$id) }}" class="btn btn-sm btn-outline"><i class="ri-edit-2-line" style="margin-right: 5px;"></i> Edit</a> --}}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-8">
			Date table <br>
						
		</div>
		<div class="col-4">
			Information <br>
		</div>
	</div>
</div>
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