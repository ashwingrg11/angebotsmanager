<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Communication Package Send Dates</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Communication Package Send Dates</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="communication_package_table" data-page-length='50'>
            <thead>
              <tr>
                <th>Projects</th>
                <th>Offer Activation (Days)</th>
                <th>Microsite1 Monthly (Days)</th>
                <th>Microsite2 Final Email (Days)</th>
                <th>Microsite2 Reminder Email (Days)</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Projects</th>
                <th>Offer Activation (Days)</th>
                <th>Microsite1 Monthly (Days)</th>
                <th>Microsite2 Final Email (Days)</th>
                <th>Microsite2 Reminder Email (Days)</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($communications as $communication)
              <tr>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$communication->project->id}}">{{ $communication->project->name }}</a>
                </td>
                <td>{{ $communication->activation or 'N/A' }}</td>
                <td>{{ $communication->microsite1_monthly or 'N/A' }}</td>
                <td>{{ $communication->microsite2_final or 'N/A' }}</td>
                <td>{{ $communication->microsite2_reminder or 'N/A' }}</td>
                <td>
                    <a class="edit_communication_package" style="cursor:pointer" data-id="{{ $communication->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="communication-package"><i class="fa fa-edit"></i></a>
                  @if(Auth::user()->user_type == "admin")
                    <a class="delete_communication_package" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="communication-package" data-id="{{ $communication->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                  @endif
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#communication_package_table tfoot th').each( function () {
      var title = $('#communication_package_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#communication_package_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#communication_package_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    $('#communication_package_table tfoot th input').eq(5).hide();
  })
</script>