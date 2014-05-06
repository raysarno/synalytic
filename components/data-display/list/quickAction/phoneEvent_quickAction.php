<?php 
	require '../../include/db.php';
?>
<script>
	$(".addNumberTo").click(function() {
		$("#addNumberTo_location").prop("disabled",!$("#addNumberTo_company").prop("checked"));
	});

	function addPhoneEvent() {
		console.log($("#addNewNumber_number").val());
		console.log($("#addNewNumber_ext").val());
		console.log($("#addNewNumber_note").val());
		console.log($("#addNumberTo_contact").val());
		console.log($("#addNumberTo_company").val());
		console.log($("#addNumberTo_location").val());

		console.log($("#call_date").val());
		console.log($("#call_time").val());
		console.log($("#next_action_date").val());
		console.log($("#evt_tel_note").val());

		$.ajax({
			type:"POST",
			url:"list/quickaction.php",
			data: {
				contactID: 				<?php echo $_POST['contact_id']?>,
				addNewTel: 				true,
				newTel_number: 			$("#addNewNumber_number").val(),
				newTel_ext: 			$("#addNewNumber_ext").val(),
				newTel_note: 			$("#addNewNumber_note").val(),
				newTel_addTo_contact: 	true,
				newTel_addTo_company: 	false,
				newTel_addTo_location: 	false,
				evt_tel_callDate: 		$("#call_date").val(),
				evt_tel_callTime: 		$("#call_time").val(),
				evt_tel_nextActionDate: $("#next_action_date").val(),
				evt_tel_note: 			$("#evt_tel_note").val()
			},
			dataType:"html"
		}).done(function() {
			$(".quickAction").empty().append("Phone Event Saved Successfully");
			setTimeout(function() {$("div.actionClose").click();},1000);
		});
	}
</script>
<div class="phoneQA" style="display:none;">
	<table class="phoneNumberSelect">
		<tr>
			<td colspan="4">
				Select from Available Phone Numbers
			</td>
			<td colspan="3">
				Associated With:
			</td>
		</tr>
		<tr>
			<td class="selector">
			</td>
			<td class="number">
				Phone #
			</td>
			<td class="ext">
				ext.
			</td>
			<td class="note">
				Note
			</td>
			<td>
				Contact
			</td>
			<td>
				Company
			</td>
			<td>
				Location
			</td>
		</tr>
		<tr class="tableBody">
			<td colspan="7" class="tableBody">
				<table class="numberList">
				<?php

				if(db_connection()) {
					$phoneSQLstr = 'SELECT * FROM phone_number WHERE contactID=' . $_POST['contact_id'] . ';';
					$phoneQuery = mysqli_query($db_conn,$phoneSQLstr);

					while ( $row = $phoneQuery->fetch_assoc() ){
						include '../lineItem/phone_number_lineItem.php';
					}
					db_connection("CLOSE");
				}

				?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				OR Add New Phone Number
			</td>
			<td colspan="3">
				Associate With?
			</td>
		</tr>
		<tr>
			<td class="selector">
				<input id="addNewNumber" type="radio" name="addNewNumber" value="addNew" />
			</td>
			<td class="number">
				<input id="addNewNumber_number" type="text" placeholder="Phone #" name="phone_number" value=""/>
			</td>
			<td class="ext">
				<input id="addNewNumber_ext" type="text" placeholder="ext." name="phone_number" value=""/>
			</td>
			<td class="note">
				<input id="addNewNumber_note" type="text" placeholder="Note" name="note" value=""/>
			</td>
			<td>
				<input id="addNumberTo_contact" class="addNumberTo" type="radio" name="addNumberTo" value="contact" checked /> Contact
			</td>
			<td>
				<input id="addNumberTo_company" class="addNumberTo" type="radio" name="addNumberTo" value="company" /> Company
			</td>
			<td>
				<input id="addNumberTo_location" class="addNumberTo" type="checkbox" name="addToLocation" disabled /> <label for="addNumberTo_location">Location</label>
			</td>
		</tr>	
	</table>
	<table id="phoneEventInfo">
		<tr>
			<td colspan="4" style="text-align:center;">
				PHONE EVENT INFO
			</td>
		</tr>
		<tr>
			<td>Call Date:</td>
			<td><input id="call_date" type="text" name="date" value="<?php echo date('Y-m-d'); ?>" disabled /></td>
			<td colspan="2" rowspan="3">
				<textarea id="evt_tel_note" form="phoneEventQA" rows="4" cols="30" placeholder="Note" />
			</td>
		</tr>
		<tr>
			<td>Call Time:</td>
			<td><input id="call_time" type="text" name="time" value="<?php echo date('H:i:s'); ?>" disabled /></td>
		</tr>
		<tr>
			<td>Next Action Date:</td>
			<td><input id="next_action_date" type="text" value="<?php echo date('Y-m-d'); ?>"/></td>
		</tr>
		<tr>
			<td colspan="4">
				<button value="Add New Phone Event" onclick="addPhoneEvent();">Add New Phone Event</button>
			</td>
		</tr>
	</table>
</div>

