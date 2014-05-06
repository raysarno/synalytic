<?php
	require 'include/common/db.php';

	if(db_connection()) {
		$contact_ID		= $_POST['contactID'];
		$newTel_number = $_POST['newTel_number'];
		$newTel_ext = $_POST['newTel_ext'];
		$newTel_note = $_POST['newTel_note'];
		$evt_tel_callDate = $_POST['evt_tel_callDate'];
		$evt_tel_callTime = $_POST['evt_tel_callTime'];
		$evt_tel_nextActionDate = $_POST['evt_tel_nextActionDate'];
		$evt_tel_note = $_POST['evt_tel_note'];

		$evt_tel_maxID = getMaxID('events_phone') + 1;

		$SQLstr = 'INSERT INTO events_phone(
					phone_event_id,
					phone_number,
					extension,
					call_date,
					call_time,
					contact_id,
					note,
					next_action_date) VALUES(' . $evt_tel_maxID . ', "' . 
					$newTel_number . '", "' .
					$newTel_ext . '", "' .
					$evt_tel_callDate . '", "' .
					$evt_tel_callTime . '", "' .
					$contact_ID . '", "' .
					$evt_tel_note . '", "' .
					$evt_tel_nextActionDate . '");'; 

		echo $SQLstr;

		$query = mysqli_query($db_conn, $SQLstr);
	}
?>