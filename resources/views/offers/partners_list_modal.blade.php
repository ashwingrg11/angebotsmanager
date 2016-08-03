<div class="content" id="partners_list_modal">
  <div class="row-fluid" id="partners_list_modal_row">
    <div class="span12">
      <div class="grid simple ">
        <div class="grid-title">
          <h4>List <span class="semi-bold">Partners</span> - Assign <span class="semi-bold">Contacts</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-target="#list_offer_partners">×</button>
        </div>
        <div class="grid-body ">
          @include('shared.notifications')
          <table class="table datatable" id="partners_table_modal" data-page-length='50'>
            <thead>
            <tr>
              <th width="160">Partners</th>
              <th>Offers</th>
              <th>Projects</th>
              <th width="150">Contacts</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Partners</th>
                <th>Offers</th>
                <th width="200">Projects</th>
                <th>Contacts</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
            @if (isset($partners_list))
              @if(!$partners_list->isEmpty())
                @foreach ($partners_list as $partner)
                  <tr>
                    <td>
                      {{$partner->partner_name}}
                    </td>
                    <td>
                      @if($partner->offers->isEmpty())
                        N/A
                      @else
                        <?php $sn=1; ?>
                        @foreach($partner->offers as $offer)
                          @if($sn!=1) | @endif
                          @if($offer->title_en)
                            {{$offer->title_en}}
                          @else
                            {{$offer->title_de}}
                          @endif
                          <?php $sn++; ?>
                        @endforeach
                      @endif
                    </td>
                    <td>
                      <?php $sn=1; ?>
                      @foreach($partner->projects as $project)
                        @if($sn!=1) | @endif{{ $project->name }}
                        <?php $sn++; ?>
                      @endforeach
                    </td>
                    <td>
                      @if($partner->contacts->isEmpty())
                        N/A
                      @else
                        <?php $sn=1; ?>
                        @foreach ($partner->contacts as $key => $value)
                          @if($sn!=1) | @endif{{ $value->first_name.' '.$value->last_name }}
                          <?php $sn++; ?>
                        @endforeach
                      @endif
                    </td>
                    <td>
                      <a class="btn-mini assign_contact_modal" style="cursor:pointer;" data-toggle="modal" data-target="#assign_offer_partners" data-controller="partner" data-id="{{ $partner->id }}" data-method="assign-contact-by-offer" data-selector="#partners_list_modal"><i class="fa fa-user" data-toggle="tooltip" data-placement="right" title="Partner Contacts" data-controller="" rel="tooltip"></i></a>
                    </td>
                  </tr>
                @endforeach
              @endif
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
    $('#partners_table_modal tfoot th').each( function () {
      var title = $('#partners_table_modal thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#partners_table_modal').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#partners_table_modal').dataTable();
      $('input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
            .search( this.value )
            .draw();
        }
      });
    });
    $('#partners_table_modal tfoot th input').eq(4).hide();
  })
</script>