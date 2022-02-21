<!--
    User's Account Page
-->
<?php
    // Start session for use
    session_start();

    // Get Sessions
    if(isset($_SESSION["username"]) && isset($_SESSION["role"]))
    {
        $u_name = $_SESSION["username"];
        $u_role = $_SESSION["role"];
    }
?>
<html>
	<head>
		<title>User's Page</title>
	</head>

	<body>
        <?php include('./header.inc.php'); ?>

        <?php
            echo "<h1>Welcome, $u_name!</h1>"    
        ?>

        <?php include('./footer.inc.php'); ?>
	</body>
</html>

