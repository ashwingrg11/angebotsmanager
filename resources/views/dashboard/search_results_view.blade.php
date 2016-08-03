<div class="content">
  <div class="row-fluid">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back" data-from="search">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <div class="span12">
      <div class="grid simple ">
        <!--grid title-->
        <div class="grid-title">
          <h4>Entire Search Results for <span class="semi-bold">'{{$data['search_by']}}'</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div><!--grid title ends here-->
        <!--grid body starts-->
        <div class="grid-body">
          @if($data['records_flag'])
            <!--search results-table-->
            <table class="table datatable" id="search_results_table" data-page-length='50'>
              <thead>
                <tr>
                  <th>Content</th>
                  <th>Type</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Content</th>
                  <th>Type</th>
                </tr>
              </tfoot>
              <tbody>
                @foreach($search_results as $records)
                  @foreach($records as $key => $rows)
                    @foreach($rows as $row)
                        <tr>
                          <td>
                            <a href="javascript:;" class="table-link-search" data-controller="{{$row['controller']}}" data-mode="{{$row['mode'] or 'index'}}" data-search="{{$row['search_content']}}">{{$row['search_content']}}</a>
                          </td>
                          <td>{{$row['custom_type'] or $key}}</td>
                        </tr>
                    @endforeach
                  @endforeach
                @endforeach
                <?php //echo "<pre>";print_r($search_records); ?>
              </tbody>
            </table><!--search results table-->
          @else
            <p>No records found, please try a different search.</p>
          @endif
        </div><!--grid body ends here-->
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(function(){
    $('#search_results_table tfoot th').each( function () {
      var title = $('#search_results_table thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
    // DataTable
    var table = $('#search_results_table').DataTable({
      "aaSorting": [[ 1, "asc" ]]
    });
    // Apply the search
    table.columns().every( function () {
      var that = this;
      var table = $('#search_results_table').dataTable();
      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
          .search( this.value )
          .draw();
        }
      });
    });
  })
</script>