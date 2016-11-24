<?php
  error_reporting(E_ALL); ini_set('display_errors', 1);

  include_once("includes/Twig/Autoloader.php");
  Twig_Autoloader::register();

  $loader = new Twig_Loader_Filesystem('templates');
  $twig = new Twig_Environment($loader, array());

  $postJSON = function($json) {
    $ch = curl_init('http://peso.rerainc.com/api/public/');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($json))
    );
    return curl_exec($ch);
  };

  // get events from peso rest service
  $json = file_get_contents("demo.json");
  $data = $postJSON($json);
  $events = json_decode($data, true);

  // print_r("JSON:\n".$events);
  //print_r($data);

  echo $twig->render('dashboard.html', array('events' => $events));
?>
