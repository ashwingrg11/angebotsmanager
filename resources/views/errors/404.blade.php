@if($exception->getMessage() == "commn_no_en")
  <h1>The page could not be found. This access link is no longer valid.</h1>
  <h1>Please get in touch with your contact person for the offer manager.</h1>
@endif
@if($exception->getMessage() == "commn_no_de")
  <h1>Die Seite konnte nicht gefunden werden. Dieser Link ist leider nicht mehr gültig.</h1>
  <h1>Bitte kontaktieren Sie Ihren Ansprechpartner für den Angebotsmanager.</h1>
@endif