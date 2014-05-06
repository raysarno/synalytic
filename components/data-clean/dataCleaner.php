<?php include $_SERVER['DOCUMENT_ROOT'] . '/include/header.php'; ?>
<div id="content">

	<table class="dataCleaner">
		<tr class="white-BG">
			<td colspan="2"><h3>Ametrix Data Cleaning Tool</td>
		</tr>
		<tr>
			<td>Select Table to Clean:</td>
			<td>
				<select form="cleanTypeSelect" name="tableToClean">
					<option value="CTL_DAT_r1b_mediaBuyMM">Report 1b - Media Buy Detail (Mass Media)(Planned)</option>
					<option value="CTL_DAT_r2_mediaBuyDM">Report 2 - Media Buy Detail (Direct Mail)</option>
					<option value="CTL_DAT_r4a_callsMM">Report 4A - Call Logs</option>
					<option value="CTL_DAT_r4b_callsMM">Report 4B - Call Logs</option>
					<option value="LKP_population">LKP_population</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Select Field to Clean:</td>
			<td>
				<select form="cleanTypeSelect" name="fieldToClean">
					<option value="raw_market">Market (DMA/Location)</option>
					<option value="raw_media" disabled>Media Type (Media Type Cleaner Coming Soon)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<form id="cleanTypeSelect" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
					<input type="submit" name="cleanTypeSelectSubmit" value="Start Cleaning Selected Table">
				</form>
			</td>
		</tr>
		<?php 
			if (isset($_REQUEST['cleanTypeSelectSubmit']) || isset($_REQUEST['cleanedEntrySubmit'])) {
				//DO THIS IF THIS IS NOT THE FIRST ITERATION OF CLEANING
				if (isset($_REQUEST['cleanedEntrySubmit'])) {
					$updateTableSQL = 'UPDATE ' . $_REQUEST['tableToClean'] . ' SET ' . ltrim($_REQUEST['fieldToClean'],'raw_') . ' = ' . $_REQUEST[ltrim($_REQUEST['fieldToClean'],'raw_')] . ' WHERE ' . $_REQUEST['fieldToClean'] . ' = "' . $_REQUEST['valueToClean'] . '";';

					if(db_connection()) {
						if(mysqli_query($db_conn, $updateTableSQL)) {
							$updatedRows = mysqli_affected_rows($db_conn);

							echo '<tr style="background-color:#E5FAEB;"><td colspan="2">Successfully Normalized <b>' . $updatedRows . ' ' . ltrim($_REQUEST['fieldToClean'],'raw_') . '</b> entries of Value: <b>' . $_REQUEST['valueToClean'] . '</b> in Table: ' . $_REQUEST['tableToClean'] . '</td></tr>';
						}
					}
				}

				if(db_connection()) {
					//PUT LOOKUP VALUES INTO AN ARRAY
					$lookupSQL = 'SELECT * FROM LKP_' . ltrim($_REQUEST['fieldToClean'],"raw_") . ';';
					$lookupQuery = mysqli_query($db_conn, $lookupSQL);
					$lookupArray = array();

					while ($row = mysqli_fetch_array($lookupQuery, MYSQLI_ASSOC)) {
						$lookupArray[] = $row;
					}

					/*/GET COUNT OF ITEMS NEEDING CLEANING
					$notCleanCountSQL = 'SELECT COUNT(*) FROM (SELECT ' . 
									$_REQUEST['fieldToClean'] . 
									' FROM ' . 
									$_REQUEST['tableToClean'] . 
									' WHERE ' . 
									$_REQUEST['fieldToClean'] . ' <> "" AND ' .
									ltrim($_REQUEST['fieldToClean'],'raw_') . ' = 0 GROUP BY ' . $_REQUEST['fieldToClean'] . ') T1;';

					$notCleanCountQuery = mysqli_query($db_conn, $notCleanCountSQL);
					$notCleanCountArray = mysqli_fetch_array($notCleanCountQuery);
					$notCleanCount = $notCleanCountArray[0];*/

					//GET NEXT VALUE TO CLEAN
					$needsCleanSQL = 'SELECT ' . 
									$_REQUEST['fieldToClean'] . 
									' FROM ' . 
									$_REQUEST['tableToClean'] . 
									' WHERE ' . 
									$_REQUEST['fieldToClean'] . ' <> "" AND ' .
									ltrim($_REQUEST['fieldToClean'],'raw_') . ' = 0' . 
									' GROUP BY ' . $_REQUEST['fieldToClean'] . ';';

					$needsCleanQuery = mysqli_query($db_conn, $needsCleanSQL);
					$needsCleanCount = mysqli_num_rows($needsCleanQuery);
					$needsCleanArray = mysqli_fetch_array($needsCleanQuery);
					$needsClean = $needsCleanArray[0];

					if ($needsCleanCount == 0) {
						echo '<tr><td colspan="2">All <b>' . ltrim($_REQUEST['fieldToClean'],'raw_') . '</b> entries are clean in table: <b>' . $_REQUEST['tableToClean'] . '</b></td></tr>';
					}
					else {
						

						//FIND THE BEST MATCH: Levenshtein Method
						$bestMatchVal = 999999;
						foreach($lookupArray as $value) {
							$similarity = levenshtein($value['market_name'],$needsClean);
							
							if($similarity < $bestMatchVal) {
								$bestMatchVal = $similarity;
								$bestMatchID = $value['ID'];
							}
						}

						//FIND THE BEST MATCHES: State Extraction Method
						//Extract the State
						echo '<tr><td colspan="2">'; //DEV
						preg_match('([A-Z][A-Z])', $needsClean, $stateRegexMatches);
						preg_match('(^[[:alpha:]]+\b)', $needsClean, $firstWordMatches);
						echo var_dump($firstWordMatches) . '      ' . implode('","',$firstWordMatches); //DEV
						echo '</td></tr>'; //DEV

						//QUERY TO GET DATA FOR SUGGESTED ENTRY
						$suggestedSQL = 'SELECT ID, market_name, state_code FROM LKP_market WHERE ID = ' . $bestMatchID . ' OR (state_code IN("' . implode('","',$stateRegexMatches) . '") AND market_name LIKE "%' . $firstWordMatches[0] . '%");';
						$suggestedQuery = mysqli_query($db_conn, $suggestedSQL);




						echo '<tr><td colspan="2">' . $suggestedSQL . '</td></tr>'; //DEV

						//BUILD TABLE: CURRENTLY CLEANING
						echo '<tr style="border-top:1px solid #39F;"><td colspan="2">Currently cleaning <b>' . ltrim($_REQUEST['fieldToClean'],'raw_') . '</b> entries in Table: <b>' . $_REQUEST['tableToClean'] . '</b></td></tr>';
						echo '<tr style="border-top:1px solid #39F;"><td colspan="2">';
						echo 'There are <b>' . $needsCleanCount . '</b> unique unclean <b>' . $_REQUEST['fieldToClean'] . '</b> entries';
						echo '</td></tr>';
						echo '<tr><td>Current Entry to Clean:</td><td>' . $needsClean . '</td></tr>';
						echo '<tr style="border-top:1px solid #CCC;"><td colspan="2">Suggested Normalized <b>' . ltrim($_REQUEST['fieldToClean'],'raw_') . '</b> entry:</td></tr>';

						//BUILD LIST OF BEST MATCHES
						echo '<tr><td colspan="2">';
							echo '<table class="filterSelectTable">';

							while ($row = mysqli_fetch_row($suggestedQuery)) {
								echo '<tr style="background:#DDD;text-align:left !important;">';
								echo '<td><input type="radio" form="cleanedEntry" name="' . ltrim($_REQUEST['fieldToClean'],'raw_') . '" value="' . $row[0] . '" checked /></td>';
								echo '<td>' . $row[0] . '</td>';
								echo '<td>' . $row[1] . '</td>';
								echo '<td>' . $row[2] . '</td>';
								echo '</tr>';
							}

							echo '</table>';
						echo '</td></tr>';

						echo '<tr><td><br>Select a normalized value.<br></td>';
						echo '<td><br><form id="cleanedEntry" action="' . htmlentities($_SERVER['PHP_SELF']) .'" method="post"">';
						echo '<input type="text" form="cleanedEntry" name="valueToClean" value="' . $needsClean . '" style="display:none;" />';
						echo '<input type="text" form="cleanedEntry" name="tableToClean" value="' . $_REQUEST['tableToClean'] . '" style="display:none;" />';
						echo '<input type="text" form="cleanedEntry" name="fieldToClean" value="' . $_REQUEST['fieldToClean'] . '" style="display:none;" />';
						echo '<input type="submit" name="cleanedEntrySubmit" value="Normalize all occurences of this value"></form><br></td></tr>';
					
						echo '<tr><td colspan="2">';
							buildFilterSelectTable('market','LKP_market',array('ID','market_name','state_code'),'radio','cleanedEntry');
						echo '</td></tr>';
					}
				}
			}
		?>
	</table>
</div>
<?php include ('include/footer.php'); ?>