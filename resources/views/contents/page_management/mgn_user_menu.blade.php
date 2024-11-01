<div class="col-3 d-md-block border-end">
  <div class="card-body">
    <h4 class="subheader">Sales Management</h4>
    <div class="list-group list-group-transparent">
      <a href="{{ url('management/user-information/'.$id.'?extpg=information') }}">
        <button type="button" 
          class="list-group-item list-group-item-action d-flex align-items-center @if ($activePage == 'information') active @endif">
          Information
        </button>
      </a>
      <a href="{{ url('management/user-activities/'.$id.'?extpg=activities') }}">
        <button type="button" 
          class="list-group-item list-group-item-action d-flex align-items-center @if ($activePage == 'activities') active @endif">
          Activities
        </button>
      </a>
      <a href="{{ url('management/user-leads/'.$id.'?extpg=leads') }}">
        <button type="button" 
          class="list-group-item list-group-item-action d-flex align-items-center @if ($activePage == 'leads') active @endif">
          Leads
        </button>
      </a>
      <a href="{{ url('management/user-opportunities/'.$id.'?extpg=opportunities') }}">
        <button type="button"
          class="list-group-item list-group-item-action d-flex align-items-center @if ($activePage == 'opportunities') active @endif">
          Opportunities
        </button>
      </a>
      <a href="{{ url('management/user-purchases/'.$id.'?extpg=purchasing') }}">
        <button type="button"
          class="list-group-item list-group-item-action d-flex align-items-center @if ($activePage == 'purchasing') active @endif">
          Purchased Order
        </button>
      </a>
    </div>
  </div>
</div>