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
    <li><a href="#" class="active">All</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>All <span class="semi-bold">Offer Types</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="offer_types_list_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="20%">Offers</th>
                <th width="15%">Projects</th>
                <th width="20%">Placements</th>
                <th width="15%">General Offer Type</th>
                <th width="15%">Detailed Offer Type</th>
                <th width="15%">Offer Type Category</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th width="20%">Offers</th>
                <th width="15%">Projects</th>
                <th width="20%">Placements</th>
                <th width="15%">General Offer Type</th>
                <th width="15%">Detailed Offer Type</th>
                <th width="15%">Offer Type Category</th>
              </tr>
            </tfoot>
            <tbody>
              @foreach($offers as $offer)
                <tr>
                  <td><a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{ $offer->id }}">{{ $offer->title_en or $offer->title_de }}</a></td>
                  <td>
                    @if(Auth::user()->user_type == "viewer")
                      {{-- $offer->projects->name --}}
                      <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$offer->projects->id}}">{{ $offer->projects->name }}</a>
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
                  <td>
                    @if($offer->general_offer_type_masterlist)
                      {{$offer->general_offer_type_masterlist->label}}
                    @else
                      N/A
                    @endif
                  </td>
                  <td>
                    @if($offer->detailed_offer_type_masterlist)
                      {{$offer->detailed_offer_type_masterlist->label}}
                    @else
                      N/A
                    @endif
                  </td>
                  <td>
                    @if($offer->offer_type_category_masterlist)
                      {{$offer->offer_type_category_masterlist->label}}
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
  $('#offer_types_list_table tfoot th').each( function () {
    var title = $('#offer_types_list_table thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#offer_types_list_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#offer_types_list_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  // $('#offer_types_list_table tfoot th input').eq(4).hide();
})
</script>