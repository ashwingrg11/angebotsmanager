{{-- <p>{{ $text }}</p> --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Offer Created!</title>
</head>
<body style="background-color: #F6F6F6; padding: 20px 0px;">
  <div class="mail-wrapper" style="width: 70%; height: auto; margin: 0 auto; background-color: white; border-radius: 5px; padding: 20px; border: 1px solid #eee;">
    <p>Offer has been created successfully.</p>
    <p>Follow the link below to activate the offer created in JI Angebotsmanager.</p><br>
    <a href="<?php echo URL::to('/').'/offer/activate/'.$data['offer_code'] ?>" target="_blank" style="background-color: #348EDA; color: white; padding: 10px 15px; border-radius: 2px; font-weight:bold; text-decoration: none;">Activate Offer</a><br><br>
    <p>Please disregard this email if you have not created an offer.</p>
    <p>Thanks, Ashwin Gurung</p>
  </div>
</body>
</html>
