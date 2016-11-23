<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include_once("includes/Peso/Peso.php");

  include_once("includes/Twig/Autoloader.php");
  Twig_Autoloader::register();

  $loader = new Twig_Loader_Filesystem('templates');
  $twig = new Twig_Environment($loader, array());

  $json = file_get_contents("data.json");
  $peso = new peso($json);

  echo $twig->render('events.json', array('data' => $peso->fetchEvents()));
?>
