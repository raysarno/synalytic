<?php
	function dataClean($value, $dataType) {
		global $db_conn;

		switch ($dataType) {
			case 'date':
				$intValue = intval($value);

				if ($intValue > 20000 && $intValue <90000) { //Date value is an excel 'days since 1900-01-01' value
					$excelDateOrigin = "1900-01-01";
					return date('Ymd', strtotime($excelDateOrigin . ' + ' . ($value - 2) . ' days'));
				}
				else if ($intValue > 1000000) { //Date value is in YYYYMMDD format
					$value = substr_replace($value,'-',6,0);
					$value = substr_replace($value,'-',4,0);
					return $value;
				}
			break;
			case 'TFN':
				$invalidChars = array("(",")"," ","-");

				$cleanTFN = str_replace($invalidChars,"",$value);
				$cleanTFN = substr_replace($cleanTFN,'-',6,0);
				$cleanTFN = substr_replace($cleanTFN,'-',3,0);
				return $cleanTFN;
			break;
			case 'time':
				if (floatval($value) < 1) { //CONVERT TIME AS DECIMAL FRACITON OF DAY INTO H:i:s FORMAT
					$timeInSeconds = round(($value * 3600 * 24));
					$hours = floor(($timeInSeconds / 3600));
					$minutes = floor((($timeInSeconds - ($hours * 3600)) / 60));
					$seconds = round(($timeInSeconds - ($hours * 3600) - ($minutes * 60)));
					$value = str_pad(strval($hours),2,'0',STR_PAD_LEFT) . ':' . str_pad(strval($minutes),2,'0',STR_PAD_LEFT) . ':' . str_pad(strval($seconds),2,'0',STR_PAD_LEFT);
				}
				return $value;
			break;
			default:
				if($dataType == "string")  {$value = str_replace('"',"",$value);}
				
				$value = mysqli_real_escape_string($db_conn, $value);
				return $value;
		}
	}

	/*
	function dataCheck($value, $dataType) {
		switch ($dataType) {
			case 'date':
				if (!is_numeric($value)) {
					return false;
				}
				else {
					return true;
				}
	/*
				if ($value > 20000 && $value <90000) {
					//Date value is an excel 'days since 1900-01-01' value
					$excelDateOrigin = new dateTime('01/01/1900');
					$dateInterval = new dateInterval('P' . $value . 'a');
					$cleanDate = $excelDateOrigin->add($dateInterval);
					return $cleanDate;
				}
			break;
			case 'int':
				if (is_int(intval($value,10))) {
					return true;
				}
				else {
					return false;
				}
			break;
		}
	}
	/*/

?>