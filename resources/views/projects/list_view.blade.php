<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Projects</p>
    </li>
    <li><a href="javascript:;" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Projects</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="projects_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="200">Projects</th>
                <th>JI Contacts</th>
                <th width="25%">Placements</th>
                <th width="25%">Channels</th>
                @if(Auth::user()->user_type == "admin")
                  <th>Actions</th>
                @endif
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Projects</th>
                <th>JI Contacts</th>
                <th>Placements</th>
                <th>Channels</th>
                @if(Auth::user()->user_type == "admin")
                  <th>Actions</th>
                @endif
              </tr>
            </tfoot>
            <tbody>
            @foreach ($projects as $project)
              <tr>
                <td><a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{ $project->id }}">{{ $project->name }}</a></td>
                <!--ji contacts-->
                <td>
                  @if($project->users->isEmpty())
                    N/A
                  @else
                    <?php $sn=1; ?>
                    @foreach ($project->users as $user)
                      @if($sn != 1) | @endif
                        @if(\Auth::user()->user_type == "viewer")
                          @if(\Auth::user()->id == $user->id)
                            <a href="javascript:;" class="body-link" data-controller="user" data-mode="show" data-param="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</a>
                          @else
                            {{$user->first_name.' '.$user->last_name}}
                          @endif
                        @else
                          <a href="javascript:;" class="body-link" data-controller="user" data-mode="show" data-param="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</a>
                        @endif
                      <?php $sn++; ?>
                    @endforeach
                  @endif
                </td><!--ji contacts-->
                <!--placements-->
                <td>
                  @if($project->placements->isEmpty())
                    N/A
                  @else
                    <?php $sn=1; $placements = Helper::sort_arr_of_obj($project->placements, 'title', 'asc');?>
                    @foreach ($placements as $placement)
                      @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="placement" data-mode="show" data-param="{{$placement->id}}">{{$placement->title}}</a>
                      <?php $sn++; ?>
                    @endforeach
                  @endif
                </td><!--placements-->
                <!--channels-->
                <td>
                  @if($project->channels->isEmpty())
                    N/A
                  @else
                    <?php $sn=1; $channels = Helper::sort_arr_of_obj($project->channels, 'name', 'asc'); ?>
                    @foreach ($channels as $key => $value)
                      @if($sn != 1) | @endif{{ $value->name }}
                      <?php $sn++; ?>
                    @endforeach
                  @endif
                </td><!--channels-->
                @if(Auth::user()->user_type == "admin")
                  <td>
                    <a class="editproject" style="cursor:pointer" data-id="{{ $project->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="project"><i class="fa fa-edit"></i></a>
                      <a class="deleteproject" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="project" data-id="{{ $project->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                  </td>
                @endif
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
  $('#projects_table tfoot th').each( function () {
      var title = $('#projects_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#projects_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#projects_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  $('#projects_table tfoot th input').eq(4).hide();
})
</script>