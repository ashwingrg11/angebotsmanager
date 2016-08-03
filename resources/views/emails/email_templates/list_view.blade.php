<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Email Templates</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Email Templates</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="email_templates_table" data-page-length='50'>
            <thead>
              <tr>
                <th>Templates</th>
                <th>Projects</th>
                <th>Sender Email</th>
                <th>CC Emails</th>
                <th>Subject</th>
                <th>Language</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Templates</th>
                <th>Projects</th>
                <th>Sender Email</th>
                <th>CC Emails</th>
                <th>Subject</th>
                <th>Language</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($email_templates as $key => $email_template)
              <tr>
                <td>{{ $email_template->type }}</td>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$email_template->projects->id}}">{{ $email_template->projects->name }}</a>
                </td>
                <td>{{ $email_template->email_contents->sender_email or 'N/A' }}</td>
                <td>
                  @if(empty($email_template->email_contents->cc_emails))
                    N/A
                  @else
                    <?php $sn=1; ?>
                    @foreach($email_template->email_contents->cc_emails as $cc_email)
                      @if($sn != 1) | @endif{{ $cc_email }}
                      <?php $sn++; ?>
                    @endforeach
                  @endif
                </td>
                <td>{{ $email_template->email_contents->subject }}</td>
                <td>{{ strtoupper($email_template->language) }}</td>
                <td>
                  <a class="body-link" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-controller="email-template" data-mode="show" data-param="{{$email_template->id}}" data-original-title="View Details"  rel="tooltip"><i class="fa fa-eye"></i></a>
                  <a class="edit_email_template" style="cursor:pointer" data-id="{{ $email_template->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="email-template" rel="tooltip"><i class="fa fa-edit"></i></a>
                  @if(Auth::user()->user_type == "admin")
                    <a class="delete_email_template" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="email-template" data-id="{{ $email_template->id }}" data-method="delete" rel="tooltip"><i class="fa fa-trash-o"></i></a>
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
    $('#email_templates_table tfoot th').each( function () {
      var title = $('#email_templates_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#email_templates_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#email_templates_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    $('#email_templates_table tfoot th input').eq(6).hide();
  })
</script>