@if(!$queue_emails->isEmpty())
  <?php $sn = 1; ?>
  @foreach ($queue_emails as $email)
    <tr>
      <td>
        {{ Carbon\Carbon::createFromFormat('d/m/Y', $email->send_date)->format('Y-m-d') }}
      </td>
      <td>{{ $email->type or 'N/A' }}</td>
      <td>
        @if(Auth::user()->user_type == "general")
          {{ $email->offers->projects->name }}
        @else
          <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{ $email->offers->project_id }}">{{ $email->offers->projects->name }}</a>
        @endif
      </td>
      <td>
        <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{ $email->offer_id }}">{{ $email->offers->title_en or $email->offers->title_de }}</a>
      </td>
      <td>
        {{ $email->offers->contacts->email }}
      </td>
      <td>
        @if(empty($email->email_contents->cc_emails))
          N/A
        @else
          <?php $sn2=1; ?>
          @foreach($email->email_contents->cc_emails as $cc_email)
            @if($sn2 != 1) | @endif{{ $cc_email }}
            <?php $sn2++; ?>
          @endforeach
        @endif
      </td>
      <td>
        {{ ucfirst($email->offers->has_communication_package) }}
      </td>
      <td>
        <a class="body-link" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-controller="email" data-mode="show" data-param="{{$email->id}}" data-original-title="View Details" rel="tooltip"><i class="fa fa-eye"></i></a>
        <a class="edit_email" style="cursor:pointer" data-id="{{ $email->id }}" data-toggle="tooltip" data-placement="top" title="Edit" data-method="edit" data-controller="email"><i class="fa fa-edit"></i></a>
        @if(Auth::user()->user_type == "admin")
          <a class="delete_email" style="cursor:pointer" data-toggle="tooltip" data-placement="right" title="Delete" data-controller="email" data-id="{{ $email->id }}" data-method="delete"><i class="fa fa-trash-o"></i></a>
        @endif
      </td>
      <td>
        <div class="checkbox check-success email_confirm_send">
          <input id="checkbox{{ $sn }}" type="checkbox" data-eid="{{ $email->id }}" style="margin-left:-5px;" name="confirm_send[]" class="confirm_send"
          @if($email->confirm_send == true)
            checked="checked"
          @endif
          >
          <label for="checkbox{{ $sn }}"></label>
        </div>
      </td>
    </tr>
    <?php $sn++; ?>
  @endforeach
@endif