<html>
	<head>
        <title>Registration Page</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <h1>InfoTech Services</h1>

        <form action="process_register.php" method="post">
            <!--
                Login Form
            -->
            Username : <input type="text" name="username" value="" placeholder="Your username here">
            Password : <input type="text" name="password" value="" placeholder="Your password here">
        </form>


        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

