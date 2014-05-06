<?php

$SQLstr = 'SELECT MONTHNAME(T1.call_date), YEAR(T1.call_date), SUM(T1.call_count) FROM ( (SELECT call_date, call_count FROM CTL_DAT_r3_callsDM) UNION ALL (SELECT call_date, call_count FROM CTL_DAT_r4a_callsMM) UNION ALL (SELECT call_date, call_count FROM CTL_DAT_r4b_callsMM) ORDER BY call_date ) T1 GROUP BY MONTHNAME(T1.call_date) ORDER BY call_date;';

	$chartColumnLabels = array('Month','Number of Calls');

if(db_connection()) {

	$dataStr = "data.addRows([";

	$dataQuery = mysqli_query($db_conn,$SQLstr);

	while ($row = mysqli_fetch_row($dataQuery)) {
		$monthYear = ($row[0] . ', ' . $row[1]);
		$numCalls = intval($row[2]);

		$dataStr .= "['" . $monthYear . "'," . $numCalls . "]," ;
	}

	$dataStr = rtrim($dataStr,",");
	$dataStr .= "]);";

	echo $dataStr;
}

?>