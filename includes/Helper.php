<?php

class Helper {

  static function getDateRangeArray($start, $end) {
    $range = array();

    $start = new DateTime($start);
    $end = new DateTime($end);
    $end = $end->modify( '+1 day' );

    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($start, $interval, $end);

    foreach ( $period as $dt )
      $range[] = $dt->format( "Y-m-d" );

    return $range;
  }

  static function dateInRange($start_date, $end_date, $date_from_user) {
    $start_ts = strtotime($start_date);
    $user_ts = strtotime($date_from_user);

    if (!$end_date)
      return ($user_ts >= $start_ts);

    $end_ts = strtotime($end_date);
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
  }

  static function getDaysOfWeekBetweenDates($startDate, $endDate, $weekdayNumber) {
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    $dateArr = array();

    do {
        if(date("w", $startDate) != $weekdayNumber)
        {
            $startDate += (24 * 3600); // add 1 day
        }
    } while(date("w", $startDate) != $weekdayNumber);


    while($startDate <= $endDate) {
        $dateArr[] = date('Y-m-d', $startDate);
        $startDate += (7 * 24 * 3600); // add 7 days
    }

    return($dateArr);
  }

}
