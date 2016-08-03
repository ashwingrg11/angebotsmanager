<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Placements</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Placements</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="placements_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="20%">Placements</th>
                <th width="15%">Projects</th>
                <th width="15%">Channels</th>
                <th width="15%">Start Date</th>
                <th width="25%">Offers</th>
                @if(Auth::user()->user_type != "viewer")
                  <th width="10%">Actions</th>
                @endif
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Placements</th>
                <th>Projects</th>
                <th>Channels</th>
                <th>Start Date</th>
                <th>Offers</th>
                @if(Auth::user()->user_type != "viewer")
                  <th>Actions</th>
                @endif
              </tr>
            </tfoot>
            <tbody>
              @if(!$placements->isEmpty())
                @foreach ($placements as $placement)
                  <tr>
                    <td>
                      <a href="javascript:;" class="body-link" data-controller="placement" data-mode="show" data-param="{{$placement->id}}">{{ $placement->title }}</a>
                    </td>
                    <td>
                      <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$placement->projects->id}}">{{ $placement->projects->name }}</a>
                    </td>
                    <td>{{ $placement->channels->name }}</td>
                    <td>{{ Carbon\Carbon::createFromFormat('d/m/Y', $placement->start_date)->format('Y-m-d') }}</td>
                    <td>
                      @if($placement->offers->isEmpty())
                        N/A
                      @else
                        <?php $sn=1; ?>
                        @foreach ($placement->offers as $offer)
                          @if($sn != 1) | @endif
                          <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">
                            @if($offer->title_en)
                              {{$offer->title_en}}
                            @else
                              {{$offer->title_de}}
                            @endif
                          </a>
                          <?php $sn++; ?>
                        @endforeach
                      @endif
                    </td>
                    @if(Auth::user()->user_type != "viewer")
                      <td>
                        <a class="edit_placement" style="cursor:pointer" data-id="{{ $placement->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="placement"><i class="fa fa-edit"></i></a>
                        @if(Auth::user()->user_type == "admin")
                          <a class="delete_placement" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="placement" data-id="{{ $placement->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                        @endif
                      </td>
                    @endif
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
    $('#placements_table tfoot th').each( function () {
      var title = $('#placements_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#placements_table').DataTable();
    // Apply the search
    table.columns().every( function () {
    // table.columns().every( function () {
      var that = this;
      var table = $('#placements_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    $('#placements_table tfoot th input').eq(5).hide();
  })
</script>