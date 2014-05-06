		</div> <!-- END OF #CONTENT, THIS TAG IS OPENED IN header.php-->
		<footer>
			&copy; 2013 
            <?php if(isset($_SESSION['username'])) {db_connection("CLOSE");} ?>
		</footer>
	</body>
</html>