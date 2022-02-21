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
        <title>Index Page</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <h1>InfoTech Services</h1>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

