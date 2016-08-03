<!-- Primary Project project Contact-->
<div class="col-md-6">
  <label for="offer_partner_contact_select">
    Primary Partner Contact
    <a class="list_offer_partners btn-mini" style="cursor:pointer;" id="assign_partner_contacts_id" data-toggle="modal" data-target="#list_offer_partners"><i class="fa fa-plus custom-add" data-toggle="tooltip" data-placement="right" title="Assign Partner Contacts" data-controller="" rel="tooltip"></i></a>
  </label>
  <div id="offer_partner_contact_select_wrapper">
    @if(isset($partner_info))
      @if(!$partner_info[0]->contacts->isEmpty())
        <select name="contact_id" id="offer_partner_contact_select" class="select2 form-control">
          <option value="">Select Below</option>
          @foreach ($partner_info[0]->contacts as $key => $partner_contact)
            <option value="{{ $partner_contact->id }}"
            @if(Session::has('offer_details'))
              @if(session('offer_details')[0]->contacts->id == $partner_contact->id)
                selected="selected"
              @endif
            @else
              @if(session('contact_id') == $partner_contact->id)
                selected="selected"
              @endif
            @endif
            >{{ $partner_contact->first_name.' '.$partner_contact->last_name }}</option>
          @endforeach
        </select>
      @else
        <p class="label">No matching records found.</p>
      @endif
    @else
      <p class="label">No matching records found.</p>
    @endif
  </div>
  <span class="error">
    <label for="contact_id" class="error">{{ session('contact_id_form_error') }}</label>
  </span>
</div><!--Primary partner Contact-->