<div class="content">
  <!--content header-->
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Queue Emails</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul><!--content header section ends-->
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <!--content grid-title-->
        <div class="grid-title">
          <h4>List <span class="semi-bold">Queue Emails
            <span>
              <label for="email_queue_calender" id="queue_calender_label"><i class="fa fa-calendar calender-ico" style="font-size:50px;cursor:pointer"></i></label>
              <input type="text" name="sendout_datetime" id="email_queue_calender" class="form-control email_queue_calender" size="1">
            </span>
          </span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div><!--content grid-title ends here-->
        <div class="grid-body ">
          <!--emails table-->
          <table class="table datatable" id="emails_queue_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="10%">Email Send Date</th>
                <th>Email Type</th>
                <th>Projects</th>
                <th>Offers</th>
                <th>Recipient's Email</th>
                <th>CC Emails</th>
                <th width="5%">Commn Package.</th>
                <th width="10%">Actions</th>
                <th width="10%">Confirm Send Out</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Email Send Date</th>
                <th>Email Type</th>
                <th>Projects</th>
                <th>Offers</th>
                <th>Recipient's Email</th>
                <th>CC Emails</th>
                <th>Commn Package.</th>
                <th>Actions</th>
                <th>Confirm Send Out</th>
              </tr>
            </tfoot>
            <tbody id="list_queue_table_body">
            @include('emails.list_queue_emails_partial')
            </tbody>
          </table><!--emails table ends-->
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#emails_queue_table tfoot th').each( function () {
      var title = $('#emails_queue_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#emails_queue_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#emails_queue_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    $('#emails_queue_table tfoot th input').eq(7).hide();
    $('#emails_queue_table tfoot th input').eq(8).hide();
  })
</script>