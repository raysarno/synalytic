		<footer>
        	&copy; 2013 9K9
        	<?php
        	if(isset($_SESSION['username'])) {
        		db_connection("CLOSE");
        	}
        	?>
		</footer>
	</body>
</html>