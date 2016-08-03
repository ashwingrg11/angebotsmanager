<?php
/**
 * [$env description]
 * @var [type]
 */
// dd(base_path());
$env = $app->detectEnvironment(function () {
  switch (base_path()) {
    case '/var/www/html/angebotmanager':
      $env = 'development';
      break;
    default:
      $env = 'production';
      break;
  }
  $environmentPath = __DIR__ . '/../.' . $env . '.env';
  // echo $env;die;
  $setEnv = trim(file_get_contents($environmentPath));
  // print_r($setEnv);die;
  if (file_exists($environmentPath)) {
    putenv("APP_ENV=$setEnv");
  }
});
?>