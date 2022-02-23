<!-- 
    Admin Page
-->
<?php
    // Start Session for use
    session_start();
?>

<html>
	<head>
		<title>Admin Page</title>
	</head>

	<body>
        <?php include './header.inc.php' ?> <!-- Header -->

        <hr/>

        <h1>Admin's Page</h1>

        <?php
            // Client Validation
            if($role !== "admin")
            {
                echo "<script>alert('Error : You are not permitted to enter this page');</script>";
                header("refresh: 0, url=my_account.php");
            }
        ?>

        <div>Functions
            <ul>Database Management
                <li>
                    <a href="process_db_setup.php">Setup Database</a>
                </li>
            </ul>

            <ul>Products
                <li>
                    <a href="./assets/sites/admin_pages/products/list_all_available_equipments.php">List all available Equipments</a>
                </li>
                <li>
                    <a href="./assets/sites/admin_pages/products/list_rented_equipments.php">List all rented Equipments</a>
                </li>
                <li>
                    <a href="./assets/sites/admin_pages/products/insert_equipments.php">Insert Equipment</a>
                </li>
                <li>
                    <a href="./assets/sites/admin_pages/products/view_equipments.php">View Equipment</a>
                </li>
                <li>
                    <a href="./assets/sites/admin_pages/products/search_equipments.php">Search Equipment</a>
                </li>
            </ul>
        </div>

        <hr/>

        <?php include './footer.inc.php' ?> <!-- Footer -->
	</body>
</html>

