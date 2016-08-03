<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Contacts</p>
    </li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Contacts</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="contacts_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="10%">First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th width="15%">Company</th>
                <th width="15%">Partners</th>
                <th width="15%">Clients</th>
                <th width="15%">Projects</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Partners</th>
                <th>Clients</th>
                <th>Projects</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @if(!$contacts->isEmpty())
              @foreach ($contacts as $contact)
                <?php $contact_projects = []; $flag_project_ids = []; ?>
                <tr>
                  <td>
                    {{ $contact->first_name or 'N/A' }}
                    @if(!$contact->projects->isEmpty())
                      @foreach($contact->projects as $contact_project)
                        <?php if(!in_array($contact_project->id, $flag_project_ids)){
                          $flag_project_ids[] = $contact_project->id;
                          $contact_projects[] = $contact_project->name;
                        }?>
                      @endforeach
                    @endif
                  </td>
                  <td>{{ $contact->last_name }}</td>
                  <td>
                    <a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$contact->id}}">{{$contact->email}}</a>
                  </td>
                  <td>{{$contact->company or 'N/A'}}</td>
                  <!--td-partner-->
                  <td>
                    @if($contact->partners->isEmpty())
                      N/A
                    @else
                      <?php $sn=1;?>
                      @foreach ($contact->partners as $key => $value)
                        <?php $partner_flag = false; ?>
                        @foreach ($value->projects as $partner_project)
                          @if(in_array($partner_project->id, $project_ids))
                            <?php $partner_flag = true; ?>
                          @endif
                          <?php if(!in_array($partner_project->id, $flag_project_ids)){
                            $flag_project_ids[] = $partner_project->id;
                            $contact_projects[] = $partner_project->name;
                          }?>
                        @endforeach
                        @if(Auth::user()->user_type == "general" && $partner_flag == false)
                          @if($sn != 1) | @endif{{$value->partner_name}}
                        @else
                          @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="partner" data-mode="show" data-param="{{$value->id}}">{{$value->partner_name}}</a>
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                      @foreach($contact->projects as $con_project)
                        <?php if(!in_array($con_project->id, $flag_project_ids)){
                          $flag_project_ids[] = $con_project->id;
                          $contact_projects[] = $con_project->name;
                        }?>
                      @endforeach
                    @endif
                  </td><!--td-partner-->
                  <!--td-clients-->
                  <td>
                    @if($contact->clients->isEmpty())
                      N/A
                    @else
                      <?php $sn=1; ?>
                      @foreach ($contact->clients as $key => $value)
                        @if(Auth::user()->user_type == "admin")
                          @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="client" data-mode="show" data-param="{{$value->id}}">{{$value->name}}</a>
                        @else
                          @if(Helper::verifyUserClient($value->id))
                            @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="client" data-mode="show" data-param="{{$value->id}}">{{$value->name}}</a>
                          @else
                            @if($sn != 1) | @endif{{$value->name}}
                          @endif
                        @endif
                        {{--@foreach($value->projects as $client_project)--}}
                          <?php //if(!in_array($client_project->id, $flag_project_ids)){
                            //$flag_project_ids[] = $client_project->id;
                            //$contact_projects[] = $client_project->name;
                          //}?>
                        {{--@endforeach--}}
                        <?php $sn++; ?>
                      @endforeach
                    @endif
                  </td><!--td-clients-->
                  <td>
                    @if(!empty($contact_projects))
                      <?php $sn = 1; ?>
                      @foreach ($contact_projects as $key => $project)
                        @if(Auth::user()->user_type == "general")
                          @if(Helper::verifyUserProject($flag_project_ids[$key]))
                            @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$flag_project_ids[$key]}}">{{ $project }} </a>
                          @else
                            @if($sn != 1) | @endif{{$project}}
                          @endif
                        @else
                          @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$flag_project_ids[$key]}}">{{ $project }} </a>
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                    @else
                      N/A
                    @endif
                  </td>
                  <td>
                    <a class="editcontact" style="cursor:pointer" data-id="{{ $contact->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="contact"><i class="fa fa-edit"></i></a>
                    @if(Auth::user()->user_type == "admin")
                      <a class="deletecontact" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="contact" data-id="{{ $contact->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
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
  $('#contacts_table tfoot th').each( function () {
      var title = $('#contacts_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#contacts_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#contacts_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  $('#contacts_table tfoot th input').eq(7).hide();
})
</script>