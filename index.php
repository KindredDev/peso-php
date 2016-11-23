<!DOCTYPE HTML>
<html lang="en">
  <head>
    <title>Peso</title>
    <link rel="stylesheet" href="assets/css/monthly.css">
    <link rel="stylesheet" href="assets/css/calendar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset='utf-8'>
  </head>
  <body>
    <div class="monthly" id="calendar"></div>

    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/monthly.js"></script>
    <script type="text/javascript">
      $(window).load( function() {
        $('#calendar').monthly({
          mode: 'event',
          data: <?php include('events.php'); ?>
        });
      });
    </script>
  </body>
</html>
