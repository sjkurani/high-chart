<?php
$link = mysqli_connect("localhost", "root", "root", "cdr");
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$Start_dt = new DateTime($_GET['start']);
$Start_dt->setTime(0, 0,0);
$StartDate = $Start_dt->format('Y-m-d H:i:s');

$end_dt = new DateTime($_GET['end']);
$end_dt->setTime(23, 59);
$Enddate = $end_dt->format('Y-m-d H:i:s');

//Difference between selected start and end date.

$choosedDateDiff = $end_dt->diff( $Start_dt );
$chooseddiffIntVal = (integer)$choosedDateDiff->format( "%R%a" );

$today = new DateTime(); // This object represents current date/time
$today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

$diff = $today->diff( $Start_dt );
$diffIntVal = (integer)$diff->format( "%R%a" );

//If today or yesterday load data hourly.
if($chooseddiffIntVal == 0) {

$sqlCommand = "SELECT `answeredTime`, count(`answeredTime`) AS Total,SUM( IF( STATUS =0, 1, 0 ) ) AS NotAnswered, SUM( IF( STATUS =1, 1, 0 ) ) AS Answered FROM cdr WHERE `answeredTime` between '".$StartDate."' and  '".$Enddate ."' GROUP BY day( `answeredTime` ),hour( `answeredTime` )";

}
else {
$sqlCommand = "SELECT COUNT(`answeredTime` ) AS Total, SUM( IF( STATUS =0, 1, 0 ) ) AS NotAnswered, SUM( IF( STATUS =1, 1, 0 ) ) AS Answered, DATE(  `answeredTime` ) AS DateofAnswer FROM cdr WHERE `answeredTime` between '".$StartDate."' and  '".$Enddate ."' GROUP BY DATE( `answeredTime` )";


}

$query = mysqli_query($link, $sqlCommand);
//print_r($sqlCommand);
$ResponceArray = array();
$AnsweredObject =  new stdClass();
$NoAnsweredObject = new stdClass(); 
$TotalObject = new stdClass();
$allObjects = new stdClass();
$xaxisObject = new stdClass();

$xaxisObject->title->text = ($chooseddiffIntVal == 0) ? "TimeWise" : "DateWise";


$AnsweredObject->type = $NoAnsweredObject->type = 'column';
$TotalObject->type = 'line';
$AnsweredObject->name = 'Answered Calls';
$NoAnsweredObject->name = 'Missed Calls';
$TotalObject->name = 'Total Responses(Calls)';


$AnsweredObject->pointWidth = $NoAnsweredObject->pointWidth = $TotalObject->pointWidth = 10;

$dbdataArray = array();
$total_array = array();
$answered_array = array();
$missed_array = array();
$dataRangeArray = array();
$timeRangeArray = array();



	if($chooseddiffIntVal == 0) {

		while ($row = mysqli_fetch_array($query)) {
		$newObj = new stdClass();
		//print_r($row);
			$newObj->total = $row['Total'];
			$newObj->NotAnswered = $row['NotAnswered'];
			$newObj->Answered = $row['Answered'];
		
			$Hourtime = date('h',strtotime($row['answeredTime']));
			$dbdataArray[$Hourtime] = $newObj;

		}
	}
	else {

		while ($row = mysqli_fetch_array($query)) {
		$newObj = new stdClass();
		//print_r($row);
			$newObj->total = $row['Total'];
			$newObj->NotAnswered = $row['NotAnswered'];
			$newObj->Answered = $row['Answered'];
			
			$dbdataArray[$row['DateofAnswer']] = $newObj;

		}
	}

$finalArray = array();
if($chooseddiffIntVal == 0) {

		$interval = date_interval_create_from_date_string('1 hour');
		foreach (new DatePeriod($Start_dt, $interval, $end_dt) as $dt) {
		    $temp_time = $dt->format('H');
	    	array_push($timeRangeArray, $temp_time);

		    if (array_key_exists($temp_time, $dbdataArray)) {
				array_push($total_array, intval($dbdataArray[$temp_time]->total));
				array_push($missed_array, intval($dbdataArray[$temp_time]->NotAnswered));
				array_push($answered_array, intval($dbdataArray[$temp_time]->Answered));
			}
			else {
				array_push($total_array, intval(0));
				array_push($missed_array, intval(0));
				array_push($answered_array, intval(0));
			}
		}
}
else {
	$daterange = new DatePeriod($Start_dt, new DateInterval('P1D'), $end_dt);
	foreach($daterange as $date){
	    $temp_date = $date->format("Y-m-d");
	    array_push($dataRangeArray, $temp_date);
	    if (array_key_exists($temp_date, $dbdataArray)) {

		array_push($total_array, intval($dbdataArray[$temp_date]->total));
		array_push($missed_array, intval($dbdataArray[$temp_date]->NotAnswered));
		array_push($answered_array, intval($dbdataArray[$temp_date]->Answered));
		}
		else {
		array_push($total_array, intval(0));
		array_push($missed_array, intval(0));
		array_push($answered_array, intval(0));
		}
	}
}

$AnsweredObject->data = $answered_array;
$NoAnsweredObject->data = $missed_array;
$TotalObject->data = $total_array;

array_push($ResponceArray,$AnsweredObject);
array_push($ResponceArray,$NoAnsweredObject);
array_push($ResponceArray,$TotalObject);
$xaxisObject->categories = ($chooseddiffIntVal == 0)? $timeRangeArray:$dataRangeArray;

$finalArray['json_data'] = $ResponceArray;
$finalArray['xaxis'] = $xaxisObject;

echo json_encode($finalArray);

mysqli_close($link);
?>