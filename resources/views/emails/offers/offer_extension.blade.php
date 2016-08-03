<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Offer Extension Request!</title>
</head>
<body style="background-color: #F6F6F6; padding: 20px 0px;">
  <div class="mail-wrapper" style="width: 70%; height: auto; margin: 0 auto; background-color: white; border-radius: 5px; padding: 20px; border: 1px solid #eee;">
    @if($email_data['content'])
      {!!$email_data['content']!!}
    @else
      <p>Offer extension request email, offer title: <strong>{{ $offer_info[0]->title_en }}</strong></p>
      <br>
      <p>Thanks,</p>
      <p>Partner: {{ $offer_info[0]->partners->partner_name }}</p>
      <p>Partner Contact: {{ $offer_info[0]->contacts->first_name.' '.$offer_info[0]->contacts->last_name }}</p>
    @endif
  </div>
</body>
</html>
