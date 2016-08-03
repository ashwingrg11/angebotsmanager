<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Placements</p>
    </li>
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      <div class="grid simple ">
        <div class="grid-title">
          <h3>Placement Details Summary</h3>
        </div>
        <div class="grid-body ">
          <div class="row">
            <div class="col-md-12">
              <p><strong>Placement Title:</strong> {{ $placement_info[0]->title }}</p>
              <p>
                <strong>Project:</strong>
                <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$placement_info[0]->projects->id}}">{{$placement_info[0]->projects->name}}</a>
              </p>
              <p><strong>Channel:</strong> {{ $placement_info[0]->channels->name }}</p>
              <p><strong>Placement Start Date:</strong> {{ Carbon\Carbon::createFromFormat('d/m/Y', $placement_info[0]->start_date)->format('Y-m-d') }}</p>
              <p><strong>Placement End Date:</strong>
                @if($placement_info[0]->end_date)
                  {{ Carbon\Carbon::createFromFormat('d/m/Y', $placement_info[0]->end_date)->format('Y-m-d') }}
                @else
                  N/A
                @endif
              </p>
              <!--projects-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Offers:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($placement_info[0]->offers->isEmpty())
                      N/A
                    @else
                      <?php $sn = 1; ?>
                      @foreach($placement_info[0]->offers as $offer)
                        {{$sn.') '}}
                        <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">{{$offer->title_en or $offer->title_de}}</a><br>
                        <?php $sn++; ?>
                      @endforeach
                    @endif
                  </p>
                </div>
              </div><!--projects-->
              </div><!--Display Address ends-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>