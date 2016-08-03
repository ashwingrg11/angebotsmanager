<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Channels</p>
    </li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Channels</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          @if(Auth::user()->user_type != "viewer")
            <!--channel form wrapper-->
            <div class="channelForm" id="channelForm">
            @include('channels.channel_form_partial')
            </div><!--channel form wrapper-->
          @endif
          <table class="table datatable" id="channels_table" data-page-length='50'>
            <thead>
              <tr>
                <th>Channels</th>
                <th>Project</th>
                <th width="40%">Placements</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Channels</th>
                <th>Project</th>
                <th>Placements</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @if(!$channels->isEmpty())
              @foreach ($channels as $channel)
                <tr>
                  <td>{{ $channel->name }}</td>
                  <td>
                    <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$channel->projects->id}}">{{ $channel->projects->name }}</a>
                  </td>
                  <td>
                    @if(!$channel->placements->isEmpty())
                      <?php $sn = 1; ?>
                      @foreach($channel->placements as $placement)
                      @if($sn != 1) | @endif <a href="javascript:;" class="body-link" data-controller="placement" data-mode="show" data-param="{{$placement->id}}">{{$placement->title}}</a>
                        <?php $sn++; ?>
                      @endforeach
                    @else
                      N/A
                    @endif
                  </td>
                  <td>
                    @if(Auth::user()->user_type == "viewer")
                      N/A
                    @else
                      <a class="editchannel" style="cursor:pointer" data-id="{{ $channel->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="channel" data-partial="channelForm"><i class="fa fa-edit"></i></a>
                      @if(Auth::user()->user_type == "admin")
                        <a class="deletechannel" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="channel" data-id="{{ $channel->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                      @endif
                    @endif
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
  $('#channels_table tfoot th').each( function () {
    var title = $('#channels_table thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#channels_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#channels_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  $('#channels_table tfoot th input').eq(3).hide();
})
</script>