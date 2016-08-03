<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Clients</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Clients</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a></div>
        </div>
        <!--grid body starts-->
        <div class="grid-body ">
          <table class="table datatable" id="clients_table" data-page-length='50'>
            <thead>
            <tr>
              <th width="200">Clients</th>
              <th>Region</th>
              <th width="20%">Notes</th>
              <th width="25%">Contacts</th>
              <th width="15%">Actions</th>
            </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Clients</th>
                <th>Region</th>
                <th>Notes</th>
                <th>Contacts</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @if(!$clients->isEmpty())
              @foreach ($clients as $clients)
              <tr>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="client" data-mode="show" data-param="{{$clients->id}}">{{$clients->name}}</a>
                </td>
                <td>{{ $clients->region or 'N/A' }}</td>
                <td>
                  @if($clients->notes)
                    {{ substr($clients->notes, 0, 40) . '...' }}
                  @else
                    N/A
                  @endif
                </td>
                <td>
                  @if($clients->contacts->isEmpty())
                    N/A
                  @else
                    <?php $sn=1; ?>
                    @foreach ($clients->contacts as $key => $value)
                      @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$value->id}}">{{ $value->first_name.' '.$value->last_name }}</a>
                      <?php $sn++; ?>
                    @endforeach
                  @endif
                </td>
                <td>
                  <a class="editclient" style="cursor:pointer" data-id="{{ $clients->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="client"><i class="fa fa-edit"></i></a>
                  <a class="assigncontact" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="top" title="Client Contacts" data-controller="client" data-id="{{ $clients->id }}" data-method="assign-contact"><i class="fa fa-user"></i></a>
                  <a class="deleteclient" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="client" data-id="{{ $clients->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              @endforeach
            @endif
            </tbody>
          </table>
        </div><!--grid-body ends-->
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#clients_table tfoot th').each( function () {
      var title = $('#clients_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#clients_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#clients_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
            .search( this.value )
            .draw();
        }
      });
    });
    $('#clients_table tfoot th input').eq(4).hide();
  })
</script>