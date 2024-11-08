@extends('layout.app')
@section('title')
  Customer
@endsection
@section('pagetitle')
  <div class="page-pretitle"></div>
  <h4 class="page-title">Customer</h4>
@endsection
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Customer</a></li>
  <li class="breadcrumb-item active"><a href="#">Contacts</a></li>
@endsection
@section('content')
  <div class="col-md-12 ">
    <div class="card">
      <div class="card-header card-header-custom card-header-light">
        <h3 class="card-title">Contacts Data</h3>
        <div class="card-actions" style="padding-right: 10px;">
          <a href="{{ url('customer/create-contact/'.$id) }}">
            <button class="btn btn-sm btn-primary btn-pill btn-light" style="vertical-align: middle;">
              <div style="font-weight: 700;">
                <i class="ri-add-circle-line icon" style="font-size: 14px; vertical-align: middle;"></i> Create
              </div>
            </button>
          </a>
          <a href="{{ url('customer') }}">
            <button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
              <div style="font-weight: 700;">
                <i class="ri-close-circle-line icon" style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
              </div>
            </button>
          </a>
        </div>
      </div>
      <div class="card-body card-body-custom">
        <div id="table-default" class="">
          <table class="table custom-datatables" id="contact-table" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 28%;">Nama</th>
                <th style="width: 22%; text-align: left;">Phone</th>
                <th style="width: 22%; text-align: left;">Email</th>
                <th style="width: 22%; text-align: left;">Job Rule</th>
                <th style="width: 5%; text-align: left;">Menus</th>
              </tr>
            </thead>
            <tbody class="table-tbody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('style')
  <link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
  <style>
    .custom-datatables tbody tr td{
      padding-top: 4px;
      padding-bottom: 4px;
    }
    .paginate_button .current {
      background-color: red;
    }
  </style>
@endpush
@push('script')
  <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
  <script>
    $('#myTable_filter input').addClass('form-control custom-datatables-filter');
    $('#myTable_length select').addClass('form-control form-select custom-datatables-leght');
    $(document).ready(function() {
      var id = "{{ $id }}";
      $('#contact-table').DataTable({
        processing: true,serverSide: true,responsive: true,
        pageLength: 15,
        lengthMenu: [ [15, 30, 60, -1], [15, 30, 60, "All"] ],
        language: {
          lengthMenu : "Show  _MENU_",
          search: "Find Customer"
        },
        ajax: {
          'url': '{!! route("source-data-contact") !!}',
          'type': 'POST',
          'data': {
            '_token': '{{ csrf_token() }}',
            'id' : id
          }
        },
        order:[[0,'asc']],
        columns: [
          // {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable:false},
          {data: 'name', name: 'name', orderable: true, searchable: true },
          {data: 'phone', name: 'phone', orderable: true, searchable: true },
          {data: 'email', name: 'email', orderable: true, searchable: true },
          // {data: 'datein', name: 'datein', orderable: true, searchable: true },
          {data: 'job', name: 'job', orderable: true, searchable: true },
          {data: 'menu', name: 'menu', orderable: false, searchable: false },
        ]
      });
    });
  </script>
@endpush
