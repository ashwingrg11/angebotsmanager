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
          <h4>List <span class="semi-bold">Offer Types</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="all_offer_types_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="20%">Offer Type Name</th>
                <th width="20%">Offer Type</th>
                <th width="30%">Projects</th>
                <th width="30%">Offers</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Offer Type Name</th>
                <th>Offer Type</th>
                <th width="200">Projects</th>
                <th>Offers</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach ($offer_types as $type_key => $offer_type)
              @foreach ($offer_type as $value)
                <tr>
                  <td>
                    {{$value->label}}
                  </td>
                  <td>
                    {{$type_key}}
                  </td>
                  <td>
                    @if(Auth::user()->user_type == "general")
                      {{ $value->projects->name }}
                    @else
                      <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$value->projects->id}}">{{ $value->projects->name }}</a>
                    @endif
                  </td>
                  <td>
                    @if(!$value->offers->isEmpty())
                      <?php $sn = 1; ?>
                      @foreach($value->offers as $offer)
                        @if(!empty($offer))
                          @if($sn != 1) | @endif <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">{{$offer->title_en or $offer->title_de}}</a>
                        @else
                          N/A
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                    @else
                      N/A
                    @endif
                  </td>
                </tr>
              @endforeach
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
  $('#all_offer_types_table tfoot th').each( function () {
    var title = $('#all_offer_types_table thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  } );
  // DataTable
  var table = $('#all_offer_types_table').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    var table = $('#all_offer_types_table').dataTable();
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  // $('#all_offer_types_table tfoot th input').eq(4).hide();
})
</script>