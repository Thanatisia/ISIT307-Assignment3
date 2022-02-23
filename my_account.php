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
        $u_firstname = $_SESSION["name"];
        $u_surname = $_SESSION["surname"];
        $u_role = $_SESSION["role"];
    }
?>
<html>
	<head>
		<title>User's Page</title>
	</head>

	<body>
        <?php include('./header.inc.php'); ?>

        <hr/>

        <?php
            echo "<h1>Your Page</h1>"    
        ?>

        <div>General Information
            <ul>
                <li>Username  : <?php echo $u_name ?></li>
                <li>First Name: <?php echo $u_firstname ?></li>
                <li>Surname   : <?php echo $u_surname ?></li>
                <li>Type      : <?php echo $u_role ?></li>
            </ul>
        </div>

        <div>Functions
            <ul> General
                <li>
                    <a href="./assets/sites/user_pages/products/products_list_all_available.php">List all available products</a>
                </li>
                <li>
                    <a href="./assets/sites/user_pages/products/products_list_rent_history.php">List rent history</a>
                </li>
                <li>
                    <a href="./assets/sites/user_pages/products/products_search.php">Search Product</a>
                </li>
                <li>
                    <a href="./assets/sites/user_pages/products/products_rent.php">Rent Product</a>
                </li>
            </ul>

            <ul> Rented Products
                <li>
                    <a href="./assets/sites/user_pages/products/products_list_renting.php">List renting products</a>
                </li>
                <li>
                    <a href="./assets/sites/user_pages/products/products_extend_rent_period.php">Extend renting period</a>
                </li>
                <li>
                    <a href="./assets/sites/user_pages/products/products_return.php">Return Product</a>
                </li>
            </ul>
        </div>

        <hr/>

        <?php include('./footer.inc.php'); ?>
	</body>
</html>

