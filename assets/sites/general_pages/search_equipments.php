<html>
	<head>
		<title>Index Page</title>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div>
            <p>Registration</p>
            <form action="process_register.php" method="post">
                <!--
                    Login Form
                -->
                Username : <input type="text" name="username" value="" placeholder="Your username here">
                Password : <input type="text" name="password" value="" placeholder="Your password here">
                <input type="submit" name="Register" value="Register">
            </form>
        </div>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

