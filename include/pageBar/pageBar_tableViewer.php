<?php
if(db_connection())
{
	$SQLstr = 'SHOW TABLES;';
	
	$query = mysqli_query($db_conn,$SQLstr);
}
?>

<form name="chooseTable" action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">
	<select name="tables">
	<?php
    while ($row = $query->fetch_array()) {
			echo '<option value="' . $row[0] . '">' . $row[0] . '</option>"';
	}
	?>
	</select>
	
	<input type="submit" name="chooseTable" value="Display Table"/>

</form>
<?php
db_connection("CLOSE");
?>