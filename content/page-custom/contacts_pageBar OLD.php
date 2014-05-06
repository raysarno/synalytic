<? ?>
<form id="contactSearch" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input id="first" type="text" name="f_name" size="20" placeholder="First Name" value="<?php echo (isset($_SESSION['filters']['contacts']['f_name']) ? $_SESSION['filters']['contacts']['f_name'] : ''); ?>" />
	<input id="last" type="text" name="l_name" size="20" placeholder="Last Name" value="<?php echo (isset($_SESSION['filters']['contacts']['l_name']) ? $_SESSION['filters']['contacts']['l_name'] : ''); ?>" />
	<input id="company" type="text" name="company" size="20" placeholder="Company" value="<?php echo (isset($_SESSION['filters']['contacts']['company']) ? $_SESSION['filters']['contacts']['company'] : ''); ?>" />
	<input id="title" type="text" name="title" size="20" placeholder="Title" value="<?php echo (isset($_SESSION['filters']['contacts']['title']) ? $_SESSION['filters']['contacts']['title'] : ''); ?>" />
	<input id="workemail" type="text" name="workemail" size="20" placeholder="Work E-Mail" value="<?php echo (isset($_SESSION['filters']['contacts']['workemail']) ? $_SESSION['filters']['contacts']['workemail'] : ''); ?>" />
	<input id="location" type="text" name="location" size="20" placeholder="Location" value="<?php echo (isset($_SESSION['filters']['contacts']['location']) ? $_SESSION['filters']['contacts']['location'] : ''); ?>" />
	<input id="role" type="text" name="role" size="20" placeholder="Role" value="<?php echo (isset($_SESSION['filters']['contacts']['role']) ? $_SESSION['filters']['contacts']['role'] : ''); ?>" />
	<input id="formType" type="text" name="formType" value="filter" style="display:none;" />
	<input id="formSubject" type="text" name="formSubject" value="ctt" style="display:none;" />
	<input id="searchSubmit" type="submit" name="applyFilters" value="Apply Filters"></input>
	<input id="searchSubmit" type="submit" name="clearFilters" value="Clear Filters"></input>
</form>