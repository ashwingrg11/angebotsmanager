<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Users</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
          <div class="grid-title">
            <h4>List <span class="semi-bold">Users
            </span></h4>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="user_list_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="10%">First Name</th>
                <th width="20%">Last Name</th>
                <th width="20%">Email</th>
                <th width="10%">User Type</th>
                <th width="30%">Projects</th>
                <th width="10%">Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Projects</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach($users as $user)
              <tr>
                <td>{{ $user->first_name or 'N/A' }}</td>
                <td>{{ $user->last_name or 'N/A' }}</td>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="user" data-mode="show" data-param="{{$user->id}}">{{ $user->email }}</a>
                </td>
                <td>
                  @if($user->user_type == 'external_partner')
                    External Partner
                  @else
                    {{ ucfirst($user->user_type) }}
                  @endif
                </td>
                <td>
                  @if(!$user->projects->isEmpty())
                    <?php $sn=1; ?>
                    @foreach ($user->projects as $project)
                      @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{$project->name}}</a>
                      <?php $sn++; ?>
                    @endforeach
                  @else
                    N/A
                  @endif
                </td>
                <td>
                  @if(Auth::user()->id != $user->id)
                  <a class="edit_user" style="cursor:pointer" data-id="{{ $user->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="user"><i class="fa fa-edit"></i></a>
                    <a class="delete_user" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="user" data-id="{{ $user->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                  @else
                    N/A
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
    $('#user_list_table tfoot th').each( function () {
      var title = $('#user_list_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#user_list_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#user_list_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    $('#user_list_table tfoot th input').eq(5).hide();
  })
</script>