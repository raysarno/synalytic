<tr>		
	<td class="selector">
		<input type="radio" name="addNew" value="<?php echo $row['phone_id']?>" />
	</td>
	<td class="number">
		<input type="text" placeholder="-" name="phone_number" value="<?php echo $row['phone_number']?>" disabled />
	</td>
	<td class="ext">
		<input type="text" placeholder="-" name="phone_number" value="<?php echo $row['extension']?>" disabled />
	</td>
	<td class="note">
		<input type="text" placeholder="-" name="note" value="<?php echo $row['note']?>" disabled />
	</td>
	<td class="contact">
		<input type="text" placeholder="-" name="contact" value="<?php echo $row['contactID']?>" disabled />
	</td>
	<td class="company">
		<input type="text" placeholder="-" name="company" value="<?php echo $row['companyID']?>" disabled />
	</td>
	<td class="location">
		<input type="text" placeholder="-" name="location" value="<?php echo $row['location_id']?>" disabled />
	</td>
</tr>