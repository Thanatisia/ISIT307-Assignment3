<!--
    ISIT307 Web Server Programming Assignment 3
-->

<?php
    // Start Session for use
    session_start();

    if(isset($_POST["logout"]) || !isset($_SESSION["role"]))
    {
        // Close Session if logout button is pressed
        session_destroy();
    }
?>

<html>
	<head>
		<title>A3 Guide</title>
	</head>

	<body>
        <p>
            - Create Database
            <a href="process_db_setup.php">Create Database</a>
            <br/>
        </p>
	</body>
</html>

