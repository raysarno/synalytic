<?php
	$pageTitle = "Edit Group"; 
	include 'header.php'; 
?>
<div class="fullWidth">
		<div class="titleBar">
			<h2>Group Details - </h2>
		</div>
		<div class="details">
			<div class="sidebar">
				<div id="createdupdated">
					Created:  <br>
					Updated: 
				</div>
			</div>
			<div class="mainInfo">
				<div class="infoFieldWrapper">
					<label for="grp_name">Group Name</label>
					<input id="grp_name" name="grp_name" form="editGroup" type="text" value="" disabled="disabled" />
				</div>
			</div>
			<div class="mainInfo">
				<div class="infoFieldWrapper double">
					<label for="grp_description">Group Description</label>
					<textarea id="grp_description" name="grp_description" form="editGroup" placeholder="No Group Description" disabled="disabled"></textarea>
				</div>
				<div class="infoFieldWrapper double">
					<button id="editInfoButton" style="width:150px;">Edit Info</button>

					<form id="editGroup" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?ID=' . $row['ID']; ?>" >
						<input type="text" id="editInfo_grp_ID" name="grp_ID" value="<?php// echo $row['ID'] ; ?>" class="hidden" />
						<input type="submit" id="saveInfoButton" name="saveInfo" value="Save Changes" class="hidden buttonStyle" style="width:150px;" />
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="tabList">
		<div class="tab selected" id="rul_tab">Rules</div>
		<div class="tab" id="ctt_tabl">Contacts</div>
		<div class="tab" id="cat_tab">Categories / Tags</div>
	</div>
	<div class="listWrapper" id="rul_listWrapper">
		<div class="innerListWrapper">
			<table class="rules" id="inclusion">
				<tr class="titleBar">
					<td colspan="3">
						Rules
					</td>
				</tr>
				<tr>
					<td class="hierarchy">
						Hierarchy
					</td>
					<td class="relation">
					</td>
					<td class="rule">
						Rule / Criterion
					</td>
				</tr>
				<tr>
					<td class="hierarchy">
					</td>
					<td class="relation">
					</td>
					<td class="rule">
						<button class="addRule">Add Rule Set</button>
					</td>
				</tr>
			</table>
			<table class="rules" id="exclusion">
				<tr class="titleBar">
					<td colspan="3">
						Exclusion Rules
					</td>
				</tr>
				<tr>
					<td class="hierarchy">
						Hierarchy
					</td>
					<td class="relation">
					</td>
					<td class="rule">
						Rule / Criterion
					</td>
				</tr>
				<tr>
					<td class="hierarchy">
					</td>
					<td class="relation">
					</td>
					<td class="rule">
						<button class="addRule">Add Exclusion Rule</button>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="listWrapper hidden" id="ctt_listWrapper">
	</div>
	<div class="listWrapper hidden" id="cat_listWrapper">
	</div>
<?php include 'footer.php'; ?>