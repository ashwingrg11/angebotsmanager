<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Partners</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Partners</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="partners_table" data-page-length='50'>
            <thead>
            <tr>
              <th width="160">Partners</th>
              <th>Region</th>
              <th>Offers</th>
              <th>Projects</th>
              <th width="25%">Contacts</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Partners</th>
                <th>Region</th>
                <th>Offers</th>
                <th width="200">Projects</th>
                <th>Contacts</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              @if(!$partners->isEmpty())
                @foreach ($partners as $partners)
                  <tr>
                    <td>
                      <a href="javascript:;" class="body-link" data-controller="partner" data-mode="show" data-param="{{$partners->id}}">{{$partners->partner_name}}</a>
                    </td>
                    <td>{{$partners->region or 'N/A'}}</td>
                    <td>
                      @if($partners->offers->isEmpty())
                        N/A
                      @else
                        <?php $sn_offer=1; ?>
                        @foreach($partners->offers as $offer)
                          @if($sn_offer != 1) | @endif
                          @if (Auth::user()->user_type == "general")
                            @if(Helper::verifyUserProject($offer->project_id))
                              <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">
                                @if($offer->title_en)
                                  {{$offer->title_en}}
                                @else
                                  {{$offer->title_de}}
                                @endif
                              </a>
                            @else
                              @if($offer->title_en)
                                {{$offer->title_en}}
                              @else
                                {{$offer->title_de}}
                              @endif
                            @endif
                          @else
                            <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">
                              @if($offer->title_en)
                                {{$offer->title_en}}
                              @else
                                {{$offer->title_de}}
                              @endif
                            </a>
                          @endif
                          <?php $sn_offer++; ?>
                        @endforeach
                      @endif
                    </td>
                    <td>
                      <?php $sn=1; ?>
                      @foreach($partners->projects as $project)
                        @if (Auth::user()->user_type == "general")
                          @if($sn != 1) | @endif
                          @if(Helper::verifyUserProject($project->id))
                            <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{ $project->name }}</a>
                          @else
                            {{ $project->name }}
                          @endif
                        @else
                          @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{ $project->name }}</a>
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                    </td>
                    <td>
                      @if($partners->contacts->isEmpty())
                        N/A
                      @else
                        <?php $sn=1; ?>
                        @foreach ($partners->contacts as $key => $value)
                          @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$value->id}}">{{ $value->first_name.' '.$value->last_name }}</a>
                          <?php $sn++; ?>
                        @endforeach
                      @endif
                    </td>
                    <td>
                      @if(Auth::user()->user_type == "viewer")
                        N/A
                      @else
                        <a class="editpartner" style="cursor:pointer" data-id="{{ $partners->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="partner"><i class="fa fa-edit"></i></a>
                        <a class="assigncontact" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="top" title="Partner Contacts" data-controller="partner" data-id="{{ $partners->id }}" data-method="assign-contact"><i class="fa fa-user"></i></a>
                        @if(Auth::user()->user_type == "general")
                        <a class="assign_project" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Partner Projects" data-controller="partner" data-id="{{ $partners->id }}" data-method="assign-project"><i class="fa fa-files-o"></i></a>
                        @endif
                        @if(Auth::user()->user_type == "admin")
                          <a class="deletepartner" style="cursor:pointer" data-userid="" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="partner" data-id="{{ $partners->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
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
    $('#partners_table tfoot th').each( function () {
      var title = $('#partners_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#partners_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#partners_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
            .search( this.value )
            .draw();
        }
      });
    });
    $('#partners_table tfoot th input').eq(5).hide();
  })
</script>