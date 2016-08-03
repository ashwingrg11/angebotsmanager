<div class="content">
    <ul class="breadcrumb">
        <div class="page-title"> <i class="icon-custom-left"></i>
            <h3>
                <span class="add-back-link body-link"
                @if($from == 'client')
                    data-controller="client"
                @elseif ($from == 'partner')
                    data-controller="partner"
                @endif
                data-mode="index">Go <span class="semi-bold">Back</span></span>
          </h3>
        </div>
        <li>
            <p>
                @if($from == 'client')
                    Clients
                @elseif ($from == 'partner')
                    Partners
                @endif
            </p>
        </li>
        <li><a href="#" class="active">Assign Contacts</a> </li>
    </ul>
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <div class="grid-title">
                    <h4>Assign <span class="semi-bold">Contacts</span></h4>
                    <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                </div>
                <div class="grid-body ">
                    <form class="form-no-horizontal-spacing" id="contact_assign_form" data-controller="contact" data-type="normal" data-method="assign-contact-to" method="post">
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
                        </tbody>
                    </table><br/>
                        <input type="hidden" name="from" value="{{ $from  }}">
                        <input type="hidden" name="primary_id" value="{{ $id  }}">
                        <div class="form-actions">
                            <div class="">
                                <button class="btn btn-danger btn-cons" id="submit_contact" type="submit">SUBMIT</button>
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