<?php 
	$pageType = 'login';
	include $_SERVER['DOCUMENT_ROOT'] . '/include/header.php'; 
?>
<div id="content">
	
		<table class="fileTypeRef">
			<?php if(!isset($_SESSION['user']['ID'])) : ?>
				<tr class="white-BG">
					<td>LOGIN TO AMETRIX</td>
				</tr>
				<tr>
					<td>
						<input form="login" id="email" type="text" name="email" size="20" placeholder="Email Address" value="" />
					</td>
				</tr>
				<tr>
					<td>
						<input form="login" id="password" type="password" name="password" size="20" placeholder="Password" value="" />
					</td>
				</tr>
				<tr>
					<td>
						<form id="login" action="login.php" method="POST">
							<input id="loginSubmit" type="submit" name="submit" value="Login" />
						</form>
					</td>
				</tr>
			<?php else: ?>
				<tr>
					<td>
						LOGIN SUCCESSFUL
					</td>
				</tr>
			<?php endif; ?>
		</table>
	
</div>
<?php include ('include/footer.php'); ?>