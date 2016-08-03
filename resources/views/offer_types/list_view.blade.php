<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Offer Types</p>
    </li>
    <li><a href="#" class="active">List/Edit</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Offer Types</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          @if(Auth::user()->user_type != "viewer")
            <!--offer_type form wrapper-->
            <div class="offerTypeForm" id="offerTypeForm">
              @include('offer_types.offer_type_form_partial')
            </div><!--offer_type form wrapper-->
          @endif
          <table class="table datatable" id="offer_types_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="200">Projects</th>
                <th>General</th>
                <th>Detailed</th>
                <th>Category</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Projects</th>
                <th>General</th>
                <th>Detailed</th>
                <th>Category</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($projects as $project)
              <tr>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{ $project->name }}</a>
                </td>
                <td>
                  @if($project->general_offer_type_masterlist->isEmpty())
                    N/A
                  @else
                    @foreach ($project->general_offer_type_masterlist as $general_type)
                      {{ $general_type->label }}
                      @if(Auth::user()->user_type != "viewer")
                        <a class="edit_general_offer_type" style="cursor:pointer" data-id="{{ $general_type->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="general_offer_type" data-partial="offerTypeForm"><i class="fa fa-edit"></i></a>
                        @if(Auth::user()->user_type == "admin")
                          <a class="delete_general_offer_type" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="general_offer_type" data-id="{{ $general_type->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                        @endif
                      @endif
                      <br>
                    @endforeach
                  @endif
                </td>
                <td>
                  @if($project->detailed_offer_type_masterlist->isEmpty())
                    N/A
                  @else
                    @foreach ($project->detailed_offer_type_masterlist as $detailed_type)
                      {{ $detailed_type->label }}
                      @if(Auth::user()->user_type != "viewer")
                        <a class="edit_detailed_offer_type" style="cursor:pointer" data-id="{{ $detailed_type->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="detailed_offer_type" data-partial="offerTypeForm"><i class="fa fa-edit"></i></a>
                        @if(Auth::user()->user_type == "admin")
                          <a class="delete_detailed_offer_type" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="detailed_offer_type" data-id="{{ $detailed_type->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                        @endif
                      @endif
                      <br>
                    @endforeach
                  @endif
                </td>
                <td>
                  @if($project->offer_type_category_masterlist->isEmpty())
                    N/A
                  @else
                    @foreach ($project->offer_type_category_masterlist as $type_category)
                      {{ $type_category->label }}
                      @if(Auth::user()->user_type != "viewer")
                        <a class="edit_offer_type_category" style="cursor:pointer" data-id="{{ $type_category->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="offer_type_category" data-partial="offerTypeForm"><i class="fa fa-edit"></i></a>
                        @if(Auth::user()->user_type == "admin")
                          <a class="delete_offer_type_category" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="offer_type_category" data-id="{{ $type_category->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                        @endif
                      @endif
                      <br>
                    @endforeach
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
  $('#offer_types_table tfoot th').each( function () {
    var title = $('#offer_types_table thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#offer_types_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#offer_types_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  // $('#offer_types_table tfoot th input').eq(4).hide();
})
</script>