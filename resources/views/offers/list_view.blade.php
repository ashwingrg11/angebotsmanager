<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Offers</p></li>
    <li><a href="#" class="active">List</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Offers</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="offers_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="20%">Offers</th>
                <th width="15%">Projects</th>
                <th width="15%">Placements</th>
                <th width="10%">Market</th>
                <th width="10%">Valid From</th>
                <th width="10%">Valid Until</th>
                <th width="10%">Commn. Package</th>
                <th width="15%">Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Offers</th>
                <th>Projects</th>
                <th>Placements</th>
                <th>Market</th>
                <th>Valid From</th>
                <th>Valid Until</th>
                <th>Commn. Package</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              @foreach($offers as $offer)
                <tr>
                  <td><a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{ $offer->id }}">{{ $offer->title_en or $offer->title_de }}</a></td>
                  <td>
                    @if(Auth::user()->user_type == "viewer")
                      <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$offer->projects->id}}">{{ $offer->projects->name }}</a>
                      {{-- $offer->projects->name --}}
                    @else
                      <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$offer->projects->id}}">{{ $offer->projects->name }}</a>
                    @endif
                  </td>
                  <td>
                    @if(!$offer->placements->isEmpty())
                      <?php $sn =1 ; ?>
                      @foreach ($offer->placements as $placement)
                        @if(Auth::user()->user_type == "viewer")
                          @if($sn != 1) | @endif {{--$placement->title--}}
                          <a href="javascript:;" class="body-link" data-controller="placement" data-mode="show" data-param="{{$placement->id}}">{{$placement->title}}</a>
                        @else
                          @if($sn != 1) | @endif<a href="javascript:;" class="body-link" data-controller="placement" data-mode="show" data-param="{{$placement->id}}">{{$placement->title}}</a>
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                    @else
                      N/A
                    @endif
                  </td>
                  <td>{{$offer->market or 'N/A'}}</td>
                  <td>{{ Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from)->format('Y-m-d') }}</td>
                  <td>{{ Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until)->format('Y-m-d') }}</td>
                  <td>{{ ucfirst($offer->has_communication_package) }}</td>
                  <td>
                    @if(Auth::user()->user_type == "viewer")
                      N/A
                    @else
                      <a class="table-link" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="Disable Offer" data-controller="offer" data-param="{{ $offer->id }}" data-mode="disable"><i class="fa fa-ban"></i></a>
                      <a class="edit_offer" style="cursor:pointer" data-id="{{ $offer->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="offer"><i class="fa fa-edit"></i></a>
                      <a class="edit_offer_placement" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="Edit Placements" data-controller="offer" data-id="{{ $offer->id }}" data-method="edit-offer-placement"><i class="fa fa-file-o"></i></a>
                      @if(Auth::user()->user_type == "admin")
                        <a class="delete_offer" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="offer" data-id="{{ $offer->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
                      @endif
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
    $('#offers_table tfoot th').each( function () {
      var title = $('#offers_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#offers_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#offers_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    $('#offers_table tfoot th input').eq(7).hide();
  })
</script>