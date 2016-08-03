<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li><p>Reporting</p></li>
    <li><a href="#" class="active">Microsite1 Feedback</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4><span class="semi-bold">Microsite1 Feedback Summary</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <table class="table datatable" id="microsite1_feedback_table" data-page-length='50'>
            <thead>
              <tr>
                <th width="10%">Month</th>
                <th>Projects</th>
                <th>Partner Contacts</th>
                <th>Offers</th>
                <th>Last Reporting Date</th>
                <th>Enquiries Nos.</th>
                <th>Bookings Nos.</th>
                <th>Packages Sold Nos.</th>
                <th>Extension Req.</th>
                <th width="10%">Link to <br>microsite</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Month</th>
                <th>Projects</th>
                <th>Partner Contacts</th>
                <th>Offers</th>
                <th>Last Reporting Date</th>
                <th>Enquiries Nos.</th>
                <th>Bookings Nos.</th>
                <th>Packages Sold Nos.</th>
                <th>Extension Req./th>
                <th>Link to microsite</th>
              </tr>
            </tfoot>
            <tbody>
            @foreach($offer_reports as $offer_report)
              <tr>
                <td>
                  <a class="body-link" style="cursor:pointer" data-controller="report" data-mode="show" data-param="{{ $offer_report->id }}">
                    {{ $offer_report->for_month or '--' }} {{ $offer_report->for_year or '--'}}
                  </a>
                </td>
                <td>
                  <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{ $offer_report->offers->projects->id }}">{{ $offer_report->offers->projects->name }}</a>
                </td>
                <td>
                <a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{ $offer_report->offers->contacts->id }}">{{ $offer_report->offers->contacts->first_name.' '.$offer_report->offers->contacts->last_name }}</a>
                </td>
                <td><a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{ $offer_report->offers->id }}">{{ $offer_report->offers->title_en or $offer_report->offers->title_de }}</a></td>
                <td>{{ $offer_report->report_date or '--' }}</td>
                <td>{{ $offer_report->no_of_requests or '--' }}</td>
                <td>{{ $offer_report->no_of_final_bookings or '--' }}</td>
                <td>{{ $offer_report->no_of_packages_sold or '--' }}</td>
                <td>
                  @if($offer_report->extension_request != 'n-a')
                    {{ucfirst($offer_report->extension_request)}}
                  @else
                    --
                  @endif
                </td>
                <td>
                  @if($offer_report->report_type == "microsite1")
                  <ul class="list-inline" style="padding:0px;">
                    <li class="table-li"><a href="microsite/en/1/{{ $offer_report->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">1(EN)</a></li><br>
                    <li class="table-li"><a href="microsite/de/1/{{ $offer_report->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">1(DE)</a></li>
                  </ul>
                  @endif
                  @if($offer_report->report_type == "microsite2")
                    <ul class="list-inline" style="padding:0px;">
                      <li class="table-li"><a href="microsite/en/2/{{ $offer_report->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">2(EN)</a></li><br>
                      <li class="table-li"><a href="microsite/de/2/{{ $offer_report->report_code }}" class="btn btn-sucess btn-xs btn-mini table-mc-btn" target="_blank">2(DE)</a></li>
                    </ul>
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
    $('#microsite1_feedback_table tfoot th').each( function () {
      var title = $('#microsite1_feedback_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#microsite1_feedback_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#microsite1_feedback_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
    // $('#microsite1_feedback_table tfoot th input').eq(8).hide();
  })
</script>