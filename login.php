<html>
	<head>
        <title>Login Page</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <h1>InfoTech Services</h1>

        <div>
            <p>Login</p>
            <form action="process_login.php" method="post">
                <!--
                    Login Form
                -->
                Username : <input type="text" name="username" value="" placeholder="Your username here">
                Password : <input type="text" name="password" value="" placeholder="Your password here">
                <input type="submit" name="Login" value="Login">
            </form>
        </div>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

