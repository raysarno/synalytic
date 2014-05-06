<?php include $_SERVER['DOCUMENT_ROOT'] . '/include/header.php'; ?>
<div id="content">
	<?php //include $_SERVER['DOCUMENT_ROOT'] . '/include/importProgressTable.php'; ?>

	<?php 
	function createImportSession() {
		global $db_conn, $db_importSeshTbl;

		unset($_SESSION['import']['varTransfer']);

		if (db_connection()) {
			//INITIALIZE IMPORT SESSION VARS
			$_SESSION['import']['ID'] 				= getMaxID($db_importSeshTbl) + 1;
			$_SESSION['import']['user'] 			= $_SESSION['user']['ID'];
			$_SESSION['import']['start_dt']			= date( 'Y-m-d H:i:s');
			$_SESSION['import']['end_dt']			= date( 'Y-m-d H:i:s', mktime(0,0,0,1,1,2000));
			$_SESSION['import']['client_ID']		= $_SESSION['user']['account'];
			$_SESSION['import']['file_path']		= "notset";
			$_SESSION['import']['log_file_path']	= $_SERVER['DOCUMENT_ROOT'] . '/data/logs/importLog-' . $_SESSION['import']['ID'] . '-' .  $_SESSION['import']['client_ID'] . '-' . $_SESSION['import']['reportType'] . '-' .  str_replace(':','',str_replace(' ','-',str_replace('-','',$_SESSION['import']['start_dt']))) . '.txt';
			$_SESSION['import']['status']			= "Initialized";			//DEV: CHANGE TO 'incomplete' after DEV

			//BUILD SQL AND SAVE IMPORT SESSION TO DATABASE
			$SQLstr = 	'INSERT INTO ' . 
						$db_importSeshTbl . 
						' VALUES("' . 
						$_SESSION['import']['ID'] 			. '","' .
						$_SESSION['import']['user'] 		. '","'	.	//User ID
						$_SESSION['import']['start_dt'] 	. '","' .	//start date time
						$_SESSION['import']['end_dt'] 		. '","' .	//end date time
						$_SESSION['import']['client_ID'] 	. '","' . 	//ACCOUNT (CLIENT) IDNG
						$_SESSION['import']['reportType'] 	. '","' .	//reportType (set in importProgress as a POST var from importInit form)
						$_SESSION['import']['file_path'] 	. '","' .	//not set until file upload and save is complete
						$_SESSION['import']['log_file_path']. '","' .	//not set until log file is create (which is dependent on vars created in this function)
						$_SESSION['import']['status'] 		. '");' ;	//status is incomplete until its {error, cancelled, success}
			mysqli_query($db_conn, $SQLstr);

			//CREATE LOG FILE WITH IMPORT SESSION INITIALIZATION INFO
			$logStr = 	'Ametrix Data Import Session Log' 							. "\n" . 
						'Import Session ID:     '. $_SESSION['import']['ID']		. "\n" .
						'Session Start:         '. $_SESSION['import']['start_dt'] 	. "\n" .
						'User:                  '. $_SESSION['import']['user'] 		. "\n" .
						'Client ID:             '. $_SESSION['import']['client_ID'] . "\n" .
						'Source Data Type:      '. $_SESSION['import']['reportType']. "\n\n" ;
			file_put_contents($_SESSION['import']['log_file_path'], $logStr, FILE_APPEND); //This creates the log file

			return true; //DEV: ROBUSTINESS!
		}
		else {
			//DEV: ERROR HANDLING
		}
	}

	//DEV: put try/catch statements here
	require './data/importFunctions.php';
	require './data/dataRules/CTL.php';				//DEV: This will need to be generalized

	$_SESSION['import']['reportType'] = $_REQUEST['reportType'];

	if(createImportSession()) {
		importLog(1,4,$_SESSION['import']['ID']);

		if ($_FILES["file"]["error"] > 0) {
			echo "There was an error with your upload: <br>";
			echo "Error: " . $_FILES["file"]["error"];
			//DEV: IMPORT STATUS UPDATE NEEDED HERE
		}
		else {
			$fileName = implode('-' . $_SESSION['import']['reportType'] . '-' . date('Ymd-His') . '.', explode('.',$_FILES["file"]["name"]));    //Add timestamp to file name  by splitting the file at the period before the file extension and then adding the information in between the split components of the original file name
			$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/data/upload/' . $fileName;    //DEV: REPLACE THIS EVERYWHERE WITH SESSION VARIABLE AND GET RID OF IT
			$_SESSION['import']['file_path'] = $uploadPath;

			if(move_uploaded_file($_FILES["file"]["tmp_name"], $_SESSION['import']['file_path'])) {
				singleField($db_importSeshTbl, 'file_path', $_SESSION['import']['ID'], "SET", $_SESSION['import']['file_path']);

				importLog(2,4);
			}
			else {
				//DEV: FILE NOT ABLE TO BE SAVED ERROR HANDLER
			}	
		}
	}

	?>

	<script type="text/javascript">
		$(document).ready(function() {
			var lastStepIndex;
			var lastStepStatus;
			var lastStepValue;
			var logUpdater = setInterval(function() {
				$.post("importMessenger.php", function( data ) {
					if (data.timestamp &&  
							(
								(data.stepIndex != lastStepIndex) ||
								(data.stepIndex == lastStepIndex && 
									(data.stepValue != lastStepValue || data.stepStatus != lastStepStatus)
								)
							)
						) {
						$("#content").append( data.timestamp + ' - ' + data.stepIndex + ' - ' + data.stepText + ' - ' + data.stepStatus + ' - ' + data.stepValue + '<br>' );

						lastStepIndex = data.stepIndex;
						lastStepStatus = data.stepStatus;
						lastStepValue = data.StepValue;

						if((data.stepIndex == 10 && data.stepStatus == 4) || data.stepIndex == 99) {
							clearInterval(logUpdater);
						}
						else if ((data.stepIndex == 7 && data.stepStatus == 4) || (data.stepIndex == 8 && data.stepStatus == 7)) {
							$.ajax({
								url:"importDuplCheck.php",
								type:"POST",
								global:false,
								timeout:100,
								complete:function() {}
							});
						}

					}
				}, "json");
			}, 500);

			$.ajax({
				url:"importData.php",
				type:"POST",
				global:false,
				timeout:100,
				complete:function() {}
			});
		});
			

	</script>
	
</div>
<?php include ('include/footer.php'); ?>