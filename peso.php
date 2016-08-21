<?php
ini_set("date.timezone", "America/New_York");

include_once("includes/Helper.php");
include_once("includes/Cron.php");

$path = $argv[1];
if (!$path)
die("File path not provided");

$file = file_get_contents($path);
$data = json_decode( $file, true);

$calendar = array();

foreach($data['accounts'] as $account) {
  usort($account['transactions']['reconciled'], function($a, $b) { return $a['date'] - $b['date']; });
  $account_start = strtotime($account['transactions']['reconciled'][0]['date']);

  $range = array(
    "start" => date('Y-m-\1', $account_start),
    "end"   => date('Y-m-t', strtotime("+1 year"))
  );
  $sum = 0;

  $appendTransaction = function(&$calendar, &$day, &$transaction) {
      $calendar[$day]['transactions'][] = array(
        "title"   => $transaction['title'],
        "amount"   => $transaction['amount']
      );
      $calendar[$day]['amount'] += $transaction['amount'];
  };

  foreach(Helper::getDateRangeArray($range['start'], $range['end']) as $day) {
    $calendar[$day] = array(
      "amount"        => $sum,
      "transactions"  => array()
    );

    foreach($account['transactions']['reconciled'] as $transaction) {
      if ($transaction['date'] == $day) {
        $appendTransaction($calendar, $day, $transaction);
      }
    }

    $i = 0;
    foreach($account['transactions']['scheduled'] as $transaction) {
      $instance_end = (array_key_exists('end', $transaction['range']) ? $transaction['range']['end'] : $range['end']);
      if ( $account_start <= strtotime($day) && Helper::dateInRange($transaction['range']['start'], $instance_end, $day) ) {

        if (array_key_exists('expressions', $transaction)) {
          foreach($transaction['expressions'] as $expression) {

            if (!array_key_exists('cron', $account['transactions']['scheduled'][$i]))
              $account['transactions']['scheduled'][$i]['cron'] = array();

            if (!array_key_exists($expression, $account['transactions']['scheduled'][$i]['cron']))
              $account['transactions']['scheduled'][$i]['cron'][$expression] = new Cron($expression, $transaction['range']['start'], $instance_end);

            if ($account['transactions']['scheduled'][$i]['cron'][$expression]->isDue($day)) {
              $appendTransaction($calendar, $day, $transaction);
              break 1;
            }
          }
        }

        if (array_key_exists('dates', $transaction)) {
          foreach($transaction['dates'] as $date) {
            if ($date == $day)
              $appendTransaction($calendar, $day, $transaction);
          }
        }
      }
      ++$i;
    }

    $sum = $calendar[$day]['amount'];
  }

  print_r(array_reverse($calendar));
}

?>
