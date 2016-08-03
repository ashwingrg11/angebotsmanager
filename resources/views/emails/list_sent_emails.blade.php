<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Sent Emails</p>
    </li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Sent Emails</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="emails_sent_table" data-page-length='50'>
            <thead>
              <tr>
                <th>Email Sent Date</th>
                <th>Email Type</th>
                <th>Sender Email</th>
                <th>Receiver Email</th>
                <th width="20%">Projects</th>
                <th width="20%">Offers</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Email Sent Date</th>
                <th>Email Type</th>
                <th>Sender Email</th>
                <th>Receiver Email</th>
                <th>Projects</th>
                <th>Offers</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @if(!$sent_emails->isEmpty())
              @foreach ($sent_emails as $email)
                <tr>
                  <td>{{ Carbon\Carbon::createFromFormat('d/m/Y', $email->send_date)->format('Y-m-d') }}</td>
                  <td>{{ $email->type or 'N/A' }}</td>
                  <td>{{ $email->email_contents->sender_email or 'N/A' }}</td>
                  <td>{{ $email->email_contents->receiver_email or 'N/A' }}</td>
                  <td>
                    @if(Auth::user()->user_type == "general")
                      {{ $email->offers->projects->name }}
                    @else
                      <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{ $email->offers->project_id }}">{{ $email->offers->projects->name }}</a>
                    @endif
                  </td>
                  <td>
                    <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{ $email->offer_id }}">{{ $email->offers->title_en or $email->offers->title_de }}</a>
                  </td>
                  <td>
                    <a class="body-link" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-controller="email" data-mode="show" data-param="{{$email->id}}" data-original-title="View Details" rel="tooltip"><i class="fa fa-eye"></i></a>
                  </td>
                </tr>
              @endforeach
            @endif
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
  $('#emails_sent_table tfoot th').each( function () {
    var title = $('#emails_sent_table thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#emails_sent_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#emails_sent_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  $('#emails_sent_table tfoot th input').eq(6).hide();
})
</script>