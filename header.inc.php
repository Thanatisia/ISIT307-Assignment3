<?php

    if(isset($_POST["Logout"]))
    {
        // Logout button pressed
        header("Location: process_logout.php"); 
    }
    
?>
<html>
	<head>
		<title>Header</title>
	</head>

    <body>
        <div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="submit" name="Logout" value="Logout">
            </form>
        </div>
	</body>
</html>

