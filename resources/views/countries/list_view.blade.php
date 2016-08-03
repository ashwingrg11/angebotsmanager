<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Countries</p>
    </li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Countries</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <!--channel form wrapper-->
          <div class="channelForm" id="channelForm">
          @include('countries.country_form_partial')
          </div><!--channel form wrapper-->
          <!--country table starts-->
          <table class="table datatable" id="countries_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="100">S-N</th>
                <th>Countries</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>S-N</th>
                <th>Countries</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <!--table body starts-->
            <tbody>
            <?php $sn=1; ?>
            @if(!$countries->isEmpty())
              @foreach ($countries as $country)
                <tr>
                  <td>{{$sn}}</td>
                  <td>{{ $country->label }}</td>
                  <td>
                    <a class="edit_country" style="cursor:pointer" data-id="{{ $country->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="country"><i class="fa fa-edit"></i></a>
                    @if(Auth::user()->user_type == "admin" && ($country->id != 1 && $country->id != 2))
                      <a class="delete_country" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="country" data-id="{{ $country->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                    @endif
                  </td>
                </tr>
              <?php $sn++; ?>
              @endforeach
            @endif
            </tbody>
            <!--table body ends here-->
          </table><!--country table ends here-->
        </div>
      </div><!--grid-simple ends here-->
    </div>
  </div>
</div>

<script type="text/javascript">
$(function(){
  $('[data-toggle="tooltip"]').tooltip();
  $('#countries_table tfoot th').each( function () {
    var title = $('#countries_table thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#countries_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#countries_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  // $('#countries_table tfoot th input').eq(0).hide();
  $('#countries_table tfoot th input').eq(2).hide();
})
</script>