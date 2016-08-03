@if(isset($client_contacts_by_client))
  @foreach($client_contacts_by_client[0]->contacts as $contact)
    <option value="{{ $contact->id }}"
      @if(Session::has('project_details'))
        @foreach (session('project_details')[0]->contacts as $client_contact)
          @if($client_contact->id == $contact->id)
            selected="selected"
          @endif
        @endforeach
      @else
        @if(Session::has('client_contact'))
          @if(in_array($contact->id, session('client_contact')))
            selected="selected"
          @endif
        @endif
      @endif
    >{{ $contact->first_name.' '.$contact->last_name }}</option>
  @endforeach
@else
  <option disabled>N/A</option>
@endif
