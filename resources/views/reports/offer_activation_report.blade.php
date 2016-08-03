<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Reporting</p></li>
    <li><a href="#" class="active">Activation Links</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          {{-- <h4>Reporting <span class="semi-bold">Microsite Feedback Summary</span></h4> --}}
          <h4><span class="semi-bold">Offer Activation Links</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="offer_activation_table" data-page-length='50'>
            <thead>
              <tr>
                <th>Offers</th>
                <th>Partners</th>
                <th>Partner Contact Email</th>
                <th>Projects</th>
                <th width="15%">Placements</th>
                <th width="20%">Activation Links</th>
                <th width="10%">Link Sent Out</th>
                <th>Activation Status</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Offers</th>
                <th>Partners</th>
                <th>Partner contact email</th>
                <th>Projects</th>
                <th>Placements</th>
                <th>Activation links</th>
                <th>Link Sent Out</th>
                <th>Activation Status</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach($offers as $offer)
              <tr>
                <td><a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{ $offer->id }}">{{ $offer->title_en or $offer->title_de }}</a></td>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="partner" data-mode="show" data-param="{{$offer->partners->id}}">{{ $offer->partners->partner_name }}</a>
                </td>
                <td>{{ $offer->contacts->email }}</td>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$offer->projects->id}}">{{$offer->projects->name}}</a>
                </td>
                <td>
                  <?php $sn = 1; ?>
                  @foreach($offer->placements as $placement)
                    @if($sn != 1) |  @endif <a href="javascript:;" class="body-link" data-controller="placement" data-mode="show" data-param="{{$placement->id}}">{{$placement->title}}</a>
                    <?php $sn++; ?>
                  @endforeach
                </td>
                <td style="word-break: break-all;"><a href="{{ URL::to('/').'/offer/activate/'.$offer->offer_code }}" target="_blank">{{ URL::to('/').'/offer/activate/'.$offer->offer_code }}</a></td>
                <td>
                  @if($offer->activation_email == "sent")
                    Yes
                  @else
                    No
                  @endif
                </td>
                <td>
                  @if($offer->activation_flag)
                    Active
                  @else
                    Inactive
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
    $('#offer_activation_table tfoot th').each( function () {
      var title = $('#offer_activation_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#offer_activation_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#offer_activation_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    // $('#offer_activation_table tfoot th input').eq(7).hide();
  })
</script>