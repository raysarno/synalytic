<?php ini_set("upload_max_filesize","10M"); ?>

<table class="fileTypeRef">
	<tr class="white-BG">
		<td colspan="8"><h3>Report Data File Type Reference Table</td>
	</tr>
	<tr class="white-BG">
		<td></td>
		<td>Data Type</td>
		<td>Media Type(s)</td>
		<td>Source</td>
		<td>Reporting Frequency</td>
		<td>Notes</td>
		<td>Upload Data</td>
		<td>View Data</td>
	</tr>
	<tr class="orange-BG">
		<td>1a</td>
		<td>Media Buy</td>
		<td>Mass media<br>(TV, Radio, Display)</td>
		<td>3rd Party</td>
		<td>Monthly</td>
		<td>Actual</td>
		<td><input type="radio" name="reportType" form="reportUpload" value="r1a" /></td>
		<td></td>
	</tr>
	<tr class="orange-BG">
		<td>1b</td>
		<td>Media Buy</td>
		<td>Mass media<br>(TV, Radio, Display)</td>
		<td>3rd Party</td>
		<td>Monthly</td>
		<td>Planned</td>
		<td><input type="radio" name="reportType" form="reportUpload" value="r1b" /></td>
		<td></td>
	</tr>
	<tr class="pink-BG">
		<td>2</td>
		<td>Media Buy</td>
		<td>Direct Mail (Mail)</td>
		<td>Client</td>
		<td>Weekly</td>
		<td></td>
		<td><input type="radio" name="reportType" form="reportUpload" value="r2" /></td>
		<td></td>
	</tr>
	<tr class="green-BG">
		<td>3</td>
		<td>Call Log Data</td>
		<td>Direct Mail (Mail)</td>
		<td>Client</td>
		<td>Weekly</td>
		<td></td>
		<td><input type="radio" name="reportType" form="reportUpload" value="r3" /></td>
		<td></td>
	</tr>
	<tr class="green-BG">
		<td>4a</td>
		<td>Call Log Data</td>
		<td>Mass Media<br>(TV, Radio, Display)</td>
		<td>Client</td>
		<td>Weekly</td>
		<td>File Name Contains "L_CTL"</td>
		<td><input type="radio" name="reportType" form="reportUpload" value="r4a" /></td>
		<td></td>
	</tr>
	<tr class="green-BG">
		<td>4b</td>
		<td>Call Log Data</td>
		<td>Mass Media<br>(TV, Radio, Display)</td>
		<td>Client</td>
		<td>Weekly</td>
		<td>File Name Contains "LQ_"</td>
		<td><input type="radio" name="reportType" form="reportUpload" value="r4b" /></td>
		<td></td>
	</tr>
	<tr class="blue-BG">
		<td>5</td>
		<td>Creative Look Up Table</td>
		<td>All Media</td>
		<td>Acento</td>
		<td>Monthly</td>
		<td>Links TFNs to ISCI</td>
		<td>
			<form id="reportUpload" action='<?php $_SERVER['DOCUMENT_ROOT']?>/importProgress.php' method="post" enctype="multipart/form-data">
				<label for="file">Filename:</label>
				<input type="file" name="file" id="file"><br>
				<input type="submit" name="submit" value="Submit">
			</form>
		</td>
		<td></td>
	</tr>
</table>