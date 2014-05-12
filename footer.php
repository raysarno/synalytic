		<footer>
        	<?php
        	if(isset($_SESSION['username'])) {
        		db_connection("CLOSE");
        	}
        	?>
		</footer>
	</body>
</html>