<?php 
require $_SERVER['DOCUMENT_ROOT'] .  "/include/common/db.php";

$chartParams = json_decode($_POST['chartParams'], true);

class googleChartData {
	
	public $cols = array();
	public $rows = array();

	public function __construct($cols) {
		foreach ($cols as $col) {
			$this->cols[] = new googleChartCol($col);
		}
	}

	public function addRow($row) {
		$this->rows[] = $row;
	}
}

class googleChartCol {

	public $id = "";
	public $label;
	public $type = "string";

	public function __construct($col) {
		$this->label = $col['label'];
		$this->type = $col['type'];
	}
}

class googleChartRow {
	public $c = array();

	public function __construct($cells) {
		foreach ($cells as $cell) {
			$this->c[] = new googleChartCell($cell);
		}
	}
}

class googleChartCell {
	public $v;
	//public $f; //ADD THIS FUNCTIONALITY SO REGION NAMES CAN BE SEEN RATHER THAN REGION CODES

	public function __construct($value) { //f is for the tooltip (REMOVED UNTIL IT WORKS)
		$this->v = $value;

		/*if (isset($f)) {
			$this->f = $f;
		}*/
	}
}
/////
// BUILD SQL BASED ON FILTERS
/////

	//Report Type: Number of Calls
	if ($chartParams['reportType'] == 'numCalls') {
		if($chartParams['chartType'] == 'trend') {
			if($chartParams['primaryDim'] == 'date') {
				//BUILD SQL BASED ON GROUPING (MONTH OR WEEK)
				if($chartParams['filters']['date']['group'] == 'Month') {
					$SQL_fields 	= 'CONCAT(MONTHNAME(T1.call_date),", ", YEAR(T1.call_date)) AS "Month", SUM(T1.call_count) AS "Number of Calls"';
					$SQL_grouping	= 'MONTHNAME(T1.call_date)';
				}
				if($chartParams['filters']['date']['group'] == 'Week') {
					$SQL_fields 	= 'CONCAT("Week ",MID(YEARWEEK(T1.call_date,3),5,2),", ", MID(YEARWEEK(T1.call_date,3),1,4)) AS "Week", SUM(T1.call_count) AS "Number of Calls"';
					$SQL_grouping	= 'YEARWEEK(T1.call_date,3)';
				}
			}

			//Build Number of Calls Query SQL
			$SQLstr = 	'SELECT ' . $SQL_fields . 
						' FROM CTL_VEW_callsALL T1' . 
						/*'( (SELECT call_date, call_count FROM CTL_DAT_r3_callsDM)' . 
						' UNION ALL (SELECT call_date, call_count FROM CTL_DAT_r4a_callsMM)' . 
						' UNION ALL (SELECT call_date, call_count FROM CTL_DAT_r4b_callsMM) ORDER BY call_date ) T1' . */
						' WHERE  T1.call_date BETWEEN "' . $chartParams['filters']['date']['start'] . '" AND "' . $chartParams['filters']['date']['end'] . '"' . 
						' GROUP BY ' . $SQL_grouping . 
						' ORDER BY call_date;';

			//Build Data Columns
			$chartColumns = array( 
				array(
					'label'	=> $chartParams['filters']['date']['group'],
					'type' 	=> 'string'),
				array(
					'label'	=> "Number of Calls",
					'type'	=> 'number')
			);
		}
		else if($chartParams['chartType'] == 'map') {
			$SQLstr = 'SELECT CAST(market AS CHAR) AS market, SUM(call_count) FROM CTL_VEW_callsALL WHERE market != 0 GROUP BY market;';

			//$SQLstr = 'SELECT SUBSTRING_INDEX(T2.market_name,",",1) AS market, SUM(T1.call_count)  FROM CTL_VEW_callsALL T1, LKP_market T2 WHERE T1.market != 0 AND T1.market = T2.ID GROUP BY T1.market;';
			//$SQLstr = 'SELECT CONCAT("US-",CAST(market AS CHAR)) AS market, SUM(call_count) FROM CTL_VEW_callsALL WHERE market != 0 GROUP BY market;';

			//Build Data Columns
			$chartColumns = array( 
				array(
					'label'	=> 'DMA Code',
					'type' 	=> 'string'),
				array(
					'label'	=> "Number of Calls",
					'type'	=> 'number')
			);
		}
	}

if(db_connection()) {

	$chartData = new googleChartData($chartColumns);

	$dataQuery = mysqli_query($db_conn,$SQLstr);

	while ($row = mysqli_fetch_row($dataQuery)) {
		$monthYear = $row[0];
		$numCalls = intval($row[1]);

		$rowCells = array($monthYear, $numCalls);

		$chartRow = new googleChartRow($rowCells);

		$chartData->addRow($chartRow);
	}

	echo json_encode($chartData);
}
	


?>