<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="partner" data-mode="index">
          Go
          <span class="semi-bold">Back</span>
        </span>
      </h3>
    </div>
    <li>
      <p>Partners</p>
    </li>
    <li>
      <a href="#" class="active">Assign Projects</a>
    </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      <div class="grid simple ">
        <div class="grid-title">
          <h4>
            Assign
            <span class="semi-bold">Projects</span>
          </h4>
          <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="#grid-config" data-toggle="modal" class="config"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
          </div>
        </div>
        <div class="grid-body ">
          <form class="form-no-horizontal-spacing" id="assign_partner_project" data-controller="partner" data-type="normal" data-method="assign-partner-project-to" method="post">
            @if(!$projects->isEmpty())
              <?php $sn = 1; ?>
              @foreach ($projects as $project)
                <input type="hidden" name="assign_project_id[]" class="checkbox{{ $sn }}" value="{{ $project->id }}">
                <?php $sn++; ?>
              @endforeach
            @endif
            <table class="table datatable" id="partner_project_assign_table" data-page-length='50'>
              <thead>
                <tr>
                  <th>Project</th>
                  <th>Actions [Select]</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Project</th>
                  <th>Actions [select]</th>
                </tr>
              </tfoot>
              <tbody>
                @if(!$projects->isEmpty())
                  <?php $sn = 1; ?>
                  @foreach ($projects as $project)
                  <tr>
                    <td>{{ $project->name or 'N/A' }}</td>
                    <td>
                      <div class="checkbox check-success  ">
                        <input id="checkbox{{ $sn }}" type="checkbox" style="margin-left:-5px;" name="porject_id[]" class="porject_id" value="{{ $project->id }}">
                        <label for="checkbox{{ $sn }}"></label>
                      </div>
                    </td>
                  </tr>
                  <?php $sn = $sn+1; ?>
                  @endforeach
                @endif
              </tbody>
            </table>
            <br/>
            <input type="hidden" name="partner_id" value="{{ $partner_id  }}">
            <div class="form-actions">
              <div class="">
                <button class="btn btn-danger btn-cons" id="submit_contact" type="submit"
                @if($projects->isEmpty())
                  disabled="disabled"
                @endif
                >SUBMIT</button>
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
        $('#partner_project_assign_table tfoot th').each( function () {
            var title = $('#partner_project_assign_table thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } );
        // DataTable
        var table = $('#partner_project_assign_table').DataTable();
        // Apply the search
        table.columns().every( function () {
            var that = this;
            var table = $('#partner_project_assign_table').dataTable();
            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                            .search( this.value )
                            .draw();
                }
            });
        });
        $('#partner_project_assign_table tfoot th input').eq(1).hide();
    })
</script>