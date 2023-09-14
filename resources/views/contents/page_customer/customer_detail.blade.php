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
    <div class="card">
      <div class="card-header card-header-custom card-header-light">
        <h3 class="card-title">Create Customer</h3>
        <div class="card-actions" style="padding-right: 10px;">
          <a href="{{ url('customer') }}">
            <button class="btn btn-sm btn-danger btn-pill" style="vertical-align: middle;">
              <div style="font-weight: 700;">
                <i class="ri-close-circle-line icon"
                  style="font-size: 14px; vertical-align: middle;margin-right: 0px;"></i>
              </div>
            </button>
          </a>
        </div>
      </div>
      <div class="card-body card-body-custom">
        <form action="" id="formContent1" enctype="multipart/form-data" action="javascript:void(0)" method="POST"
          autocomplete="off">
          @csrf
          <div class="row align-items-center mb-3">
            <div class="col">
            </div>
            <div class="col-auto ms-auto d-print-none">
              <div class="d-flex">
                <button type="reset" class="btn btn-default btn-square" style="font-weight: 700; margin-right: 3px;">
                  <i class="ri-delete-bin-5-line icon" style="font-size: 14px;margin-right: 4px;"></i> Clear
                </button>
                <button type="submit" class="btn btn-default btn-square" style="font-weight: 700;">
                  <i class="ri-save-3-line icon" style="font-size: 14px;margin-right: 4px;"></i> Save
                </button>
              </div>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-xl-3 mb-3">
              <label class="form-label" style="text-align: center;">Add Image Here</label>
              <input type="file" name="fileImage" class="input_file" id="inputFile" style="display: none;">
              <input type="hidden" class="input_text" id="inputFileName">
              <div id="photo-frame" class="browse">
                <img src="{{ asset('static/user_edit.png') }}" id="inputAvatar" class="rounded-3 ">
                <img src="" id="inputPreview" class="rounded-2" style="display: none;">
              </div>
              <div id="areaCloseImage">
                <button id="closeImageProfile" class="badge" style="display: none;"><i
                    class="ri-close-circle-fill icon"></i></button>
              </div>
            </div>
            <div class="col-xl-9">
              <div class="mb-3 row">
                <div class="form-selectgroup">
                  <label class="form-selectgroup-item">
                    <input type="radio" name="cststatus" value="individual" class="form-selectgroup-input opt-status"
                      checked="">
                    <span class="form-selectgroup-label" style="font-weight: 500;padding: 6px 12px 3px 12px; ">
                      <i class="ri-user-fill icon"
                        style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                      Invidual
                    </span>
                  </label>
                  <label class="form-selectgroup-item" style="">
                    <input type="radio" name="cststatus" value="institution" class="form-selectgroup-input opt-status">
                    <span class="form-selectgroup-label" style="font-weight: 500;padding: 6px 12px 3px 12px; ">
                      <i class="ri-community-fill icon"
                        style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                      Institution
                    </span>
                  </label>
                </div>
              </div>
              <div class="mb-3" id="person-name-area">
                <label class="form-label required">Person Names</label>
                <input name="person_name" id="person-name" type="text" class="form-control"
                  aria-describedby="emailHelp" placeholder="Name of person" value="{{ old('person_name') }}" required>
              </div>
              <div class="mb-3" id="institution-name-area">
                <label class="form-label required">Institution Names</label>
                <div class="typeahead__container">
                  <div class="typeahead__field">
                    <div class="typeahead__query">
                      <input class="form-control js-typeahead" id="institution-name" name="institution_name"
                        autocomplete="off" placeholder="Name of istitution or companies" required>
                      {{-- <input type="text" id="val-com-available" name="company_availabled"> --}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-xl-6">
              <div class="mb-3 row" id="job-position-area">
                <label class="col-3 col-form-label">Job Position</label>
                <div class="col">
                  <input name="job_position" id="job-position" type="text" class="form-control"
                    placeholder="Type job position here">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-3 col-form-label" style="text-align: right;">Mobile</label>
                <div id="multiInputMobile" class="col">
                  <div class="input-group">
                    <input name="mobile[]" id="mobile" type="text" class="form-control"
                      placeholder="Type mobile number or whatsapp number here">
                    <button id="add-button-one" class="btn btn-addons" type="button"><i
                        class="ri-add-line custom-icon-min"></i></button>
                  </div>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-3 col-form-label">Phone</label>
                <div id="multiInputPhone" class="col">
                  <div class="input-group">
                    <input name="phone[]" id="phone" type="text" class="form-control" placeholder="Type phone number here">
                    <button id="add-button-two" class="btn btn-addons" type="button"><i class="ri-add-line custom-icon-min"></i></button>
                  </div>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-3 col-form-label">Email</label>
                {{-- <div class="col">
                  <input name="email" id="email" type="email" class="form-control"
                    placeholder="Type email address here">
                </div> --}}
                <div id="multiInputEmail" class="col">
                  <div class="input-group">
                    <input name="email[]" id="email" type="email" class="form-control"
                      placeholder="Type email address here">
                    <button id="add-button-three" class="btn btn-addons" type="button"><i
                        class="ri-add-line custom-icon-min"></i></button>
                  </div>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-3 col-form-label">Web</label>
                <div class="col">
                  <input name="web" id="web" type="text" class="form-control"
                    placeholder="Type web address here">
                </div>
              </div>
            </div>
            <div class="col-xl-6">
              <div class="mb-3 row">
                <label class="col-3 col-form-label required">Address</label>
                <div class="col">
                  <div class="typeahead__container">
                    <div class="typeahead__field">
                      <div class="typeahead__query">
                        <input id="addr-street" name="addr_street" type="text"
                          class="form-control mb-2 typeahead-street" placeholder="Street" autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <div class="typeahead__container">
                    <div class="typeahead__field">
                      <div class="typeahead__query">
                        <input id="addr-district" name="addr_district" type="text"
                          class="form-control form-control-cst mb-2 typeahead-district" placeholder="District"
                          autocomplete="off">
                      </div>
                      <div class="typeahead__button">
                        <button type="button" onclick="clearAddrDistrict()"><i class="ri-eraser-line"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="typeahead__container">
                    <div class="typeahead__field">
                      <div class="typeahead__query">
                        <input id="addr-city" name="addr_city" type="text" class="form-control mb-2 typeahead-city"
                          placeholder="City" autocomplete="off">
                      </div>
                      <div class="typeahead__button">
                        <button type="button" onclick="clearAddrCity()"><i class="ri-eraser-line"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="typeahead__container">
                    <div class="typeahead__field">
                      <div class="typeahead__query">
                        <input id="addr-province" name="addr_province" type="text"
                          class="form-control typeahead-province" placeholder="Province">
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
        </form>
      </div>
      <div class="card" style="border: none;">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li id="button-one" class="nav-item" role="presentation">
              <button href="#tabs-contacts" class="nav-link @if ($a == 'tab1' || $a == 'none') active @endif"
                data-bs-toggle="tab" aria-selected="true"role="tab">
                <i class="ri-contacts-line" style="margin-right: 6px;"></i>
                Contacts
              </button>
            </li>
            <li id="button-two" class="nav-item" role="presentation">
              <button href="#tabs-leads" class="nav-link @if ($a == 'tab2') active @endif"
                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                <i class="ri-filter-2-line" style="margin-right: 6px;"></i>
                Leads
              </button>
            </li>
            <li id="button-three" class="nav-item" role="presentation">
              <button href="#tabs-notes" class="nav-link @if ($a == 'tab3') active @endif"
                data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                <i class="ri-file-text-line" style="margin-right: 6px;"></i>
                Notes
              </button>
            </li>
            <li id="button-setting" class="nav-item ms-auto" role="presentation">
              <button href="#tabs-setting" class="nav-link" title="Settings" data-bs-toggle="tab"
                aria-selected="false" tabindex="-1" role="tab" style="padding-top: 5px;padding-bottom: 11px;">
                <i class="ri-settings-3-line icon"></i>
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane @if ($a == 'tab1' || $a == 'none') active show @endif" id="tabs-contacts"
              role="tabpanel">
              <h4>Contacts</h4>
            </div>
            <div class="tab-pane @if ($a == 'tab2') active show @endif" id="tabs-leads" role="tabpanel">
              <h4></h4>
              <div>Fringilla egestas nunc quis tellus diam rhoncus ultricies tristique enim at diam, sem nunc amet,
                pellentesque id egestas velit sed</div>
            </div>
            <div class="tab-pane @if ($a == 'tab3') active show @endif" id="tabs-notes" role="tabpanel">
              <h4>Profile tab</h4>
              <div>Fringilla egestas nunc quis tellus diam rhoncus ultricies tristique enim at diam, sem nunc amet,
                pellentesque id egestas velit sed</div>
            </div>
            <div class="tab-pane @if ($a == 'set') active show @endif"" id="tabs-setting"
              role="tabpanel">
              <h4>Settings tab</h4>
              <div>Donec ac vitae diam amet vel leo egestas consequat rhoncus in luctus amet, facilisi sit mauris accumsan
                nibh habitant senectus</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('style')
  <link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('customs/css/style_datatables.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/jquery-typeahead/jquery.typeahead.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/jquery-confirm/jquery-confirm.min.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('dist/libs/dropzone/dist/dropzone.css') }}"> --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">

  <style>
    .nav-tabs .nav-link {
      border: none;
      color: #ffffffc7;
    }

    .form-control {
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

    .col-form-label {
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
    function clearAddrDistrict(){
      $('#addr-district').val("");
      $('#addr-district').attr('readonly', false);
    }
    function clearAddrCity(){
      $('#addr-city').val("");
      $('#addr-city').attr('readonly', false);
    }
    function clearAddrProvince(){
      $('#addr-province').val("");
      $('#addr-province').attr('readonly', false);
    }
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
      } else {
        $('#person-name-area').slideDown();
        $('#job-position-area').slideDown();
        $('#person-name').prop('disabled', false);
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
    $(document).ready(function() {
      $('#formContent1').submit(function(e) {
        e.preventDefault();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        var formData = new FormData(this);
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
        })
      });
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
