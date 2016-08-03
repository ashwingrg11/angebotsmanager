<div class="content" id="assign_contact_modal_id">
  <div class="row-fluid">
    <div class="span12">
      <div class="grid simple ">
        <div class="grid-title">
          <div class="page-title"> <i class="icon-custom-left"></i>
            <h3>
              <span class="assign_contact_modal_back">Go <span class="semi-bold">Back</span></span>
            </h3>
            <button type="button" class="close" id="assign_contact_modal_close">×</button>
          </div>
          <h4>Assign <span class="semi-bold">Contacts</span></h4>
        </div>
        <div class="grid-body">
          <form class="form-no-horizontal-spacing" id="contact_assign_form_modal" data-controller="contact" data-type="normal" data-method="assign-contact-to" method="post" data-modal="yes">
          @if(isset($contacts))
            @if(!$contacts->isEmpty())
              <?php $sn = 1; ?>
              @foreach ($contacts as $contact)
                  @if(!empty($existing_records))
                      @foreach ($existing_records as $records)
                          @if($records->contact_id == $contact->id)
                              <input type="hidden" name="assign_contact_id[]" class="checkbox{{ $sn }}" value="{{ $contact->id }}">
                          @endif
                      @endforeach
                  @endif
                  <?php $sn++; ?>
              @endforeach
            @endif
          @endif
          <table class="table datatable" id="contact_assign_table" data-page-length='50'>
            <thead>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Company</th>
                <th>Actions [Select]</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Company</th>
                <th>Actions [select]</th>
              </tr>
            </tfoot>
            <tbody>
              @if(isset($contacts))
              @if(!$contacts->isEmpty())
                <?php $sn = 1; ?>
                @foreach ($contacts as $contact)
                  <tr>
                    <td>{{ $contact->first_name or 'N/A' }}</td>
                    <td>{{ $contact->last_name }}</td>
                    <td>{{ $contact->company }}</td>
                    <td>
                      <div class="checkbox check-success  ">
                        <input id="checkbox{{ $sn }}" type="checkbox" style="margin-left:-5px;" name="contact_id[]" class="contact_id" value="{{ $contact->id }}"
                          <?php if(!empty($existing_records)){foreach ($existing_records as $records) {
                              if($records->contact_id == $contact->id){
                                echo "checked='checked'";
                              }
                          } }?>
                        >
                        <label for="checkbox{{ $sn }}"></label>
                      </div>
                    </td>
                  </tr><?php $sn = $sn+1; ?>
                @endforeach
              @endif
              @endif
            </tbody>
          </table><br/>
            @if(isset($contacts))
              <input type="hidden" name="from" value="{{ $from  }}">
              <input type="hidden" name="primary_id" value="{{ $id  }}">
              <input type="hidden" name="is_modal" value="yes">
              <input type="hidden" id="contact_modal_project_id" name="contact_modal_project_id" value="">
            @endif
            <div class="form-actions">
              <div class="">
                <button class="btn btn-danger btn-cons" id="submit_contact" type="submit">SUBMIT</button>
                <button class="btn btn-danger btn-cons" id="assign_contact_modal_cancel">CANCEL</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function(){
    $('#contact_modal_project_id').val($('#offer_project_select').val());
    $('[data-toggle="tooltip"]').tooltip();
    $('#contact_assign_table tfoot th').each( function () {
      var title = $('#contact_assign_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#contact_assign_table').DataTable();
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#contact_assign_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
          if ( that.search() !== this.value ) {
            that
                    .search( this.value )
                    .draw();
          }
      });
    });
    $('#contact_assign_table tfoot th input').eq(3).hide();
  })
</script>