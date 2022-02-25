<!--
    Rental Page
-->

<?php
    // Start Session for use
    session_start();

    // Include external files for use
    require_once("./assets/scripts/mysqli_conn.php");
    require_once("./assets/scripts/extlib.php");


    /*
     * Functions
     */
    function filter_products($conn, $table_name, $columns="*", $condition="")
    {
        // Retrieve all records from Products
        $sql_stmt = "SELECT $columns FROM $table_name";

        if(!$condition == "")
        {
            $sql_stmt .= " WHERE $condition ";
        }
            
        $results = stmt_exec($conn, $sql_stmt);
        $size = $results["size"];
        $rows = $results["result"];
        return array($size, $rows); 
    }

    if(isset($_POST["Rent"]))
    {
        if(
            isset($_POST["prodID"]) && 
            isset($_POST["rent_regular_Period"]) &&
            isset($_SESSION["userID"]) && 
            isset($_SESSION["username"])
        )
        {
            /*
             * Get Product Information from products page as reference
             */
            $prod_id = sanitize_input($_POST["prodID"]);                            // Product ID
            $rent_regular_Period = sanitize_input($_POST["rent_regular_Period"]);   // Number of Days user is renting
            $client_ID = sanitize_input($_SESSION["userID"]);
            $client_name = sanitize_input($_SESSION["username"]);

            // Get Product Info
            $prod_Category = "";
            $prod_Brand = "";
            $prod_Description = "";
            $prod_regular_cost_per_day = 0;
            $prod_extended_cost_per_day = 0;

            $is_Rented = False; // Check if rental status is updated

            $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

            $verify_conn = db_conn_verify($conn);

            // Verify connection
            if($verify_conn)
            {
                /*
                 * Database connection successful
                 *  - Search and retrieve from database all products
                 *  $sql_stmt = "SELECT * FROM products";
                 */

                /*
                 * Check if Table Exists
                 */
                $table_exists = chk_table_exists($conn, "products");
                if($table_exists)
                {
                    /* 
                     * Table Exists
                     */
                    $search_condition = "prod_ID = '" . $prod_id . "'";
                    $sql_stmt = "SELECT * FROM products WHERE $search_condition";
                    $records = $conn->query($sql_stmt);
                    $size = $records->num_rows;
                    $rows = $records->fetch_assoc();
                    if($size > 0)
                    {
                        // Get Product Info
                        $prod_Category = $rows["prod_Category"];
                        $prod_Brand = $rows["prod_Brand"];
                        $prod_Description = $rows["prod_Description"];
                        $prod_regular_cost_per_day = $rows["prod_regular_cost_per_day"];
                        $prod_extended_cost_per_day = $rows["prod_extended_cost_per_day"];
                        $prod_curr_Status = $rows["prod_Status"];

                        $rent_regular_cost = ((int)$rent_regular_Period * $prod_regular_cost_per_day);
                        $new_status = "Rented";

                        $rental_Date = date('Y-m-d');
                        $expiry_Date = date("Y-m-d", strtotime("+$rent_regular_Period days"));

                        /*
                         * Update Status of the product to 'Rented'
                         */
                        if($prod_curr_Status == "Available")
                        {
                            // Update Status
                            $sql_stmt = "UPDATE products SET prod_Status='" . $new_status . "' WHERE prod_ID=" . "'" . $prod_id . "'";

                            if($conn->query($sql_stmt) === TRUE)
                            {
                                echo "<script>alert('Rental successful! Your Rental expires after $rent_regular_Period Days on $expiry_Date');</script>";
                            }
                            else
                            {
                                echo "Error renting : " . $conn->error;
                            }

                            // Write Rental to Table
                            // Open Table
                             /*
                             * Check if Table Exists
                             */
                            $table_exists = chk_table_exists($conn, "rentals");
                            if($table_exists)
                            {
                                /* 
                                 * Table Exists
                                 */

                                // Default
                                if($rent_regular_Period == "")
                                {
                                    $rent_regular_Period = '30';
                                }

                                // Check if product's current status is 'Rented'
                                // - if rented, dont add
                                if(!$is_Rented)
                                {
                                    /*
                                     * If it was still 'Available' => Just getting rented
                                     */
                                    $values = 
                                        "'" . $client_ID . "', " .
                                        "'" . $client_name . "', " .
                                        "'" . $prod_id . "', " .
                                        "'" . $rental_Date . "', " . 
                                        "'" . $rent_regular_Period . "', " .
                                        floatval($rent_regular_cost);
                                    $columns = "client_ID, client_name, prod_ID, rent_start_date, rent_regular_period, rent_regular_cost";
                                    insert_row($conn, "rentals", $columns, $values);
                                }
                            }
                        }
                        else
                        {
                            $is_Rented = True;
                            echo "<script>alert('This product is unavailable for rental, please choose another product.');</script>";
                        }
                    }
                }

                // Close Database after use
                close_db($conn);
            }
            else
            {
                echo "ERROR : Error connecting to Database";
            }
        }
        else
        {
            header("Location: products_rent.php", TRUE, 307);
        }
    }
?>

<html>
	<head>
        <title>Rental Page</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/> 

        <div>
            <p><b><u>Rentals</u></b></p> 
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                Product ID             : <input type="text" name="prodID"               value="<?php if(isset($_POST['prodID'])) echo $_POST['prodID']; ?>" placeholder="yyyy-mm-dd">
                Regular Period (Days)  : <input type="number" name="rent_regular_Period"  value="<?php if(isset($_POST['rent_regular_Period'])) echo $_POST['rent_regular_Period']; ?>"  placeholder="30">
                <input type="submit" name="Rent" value="Rent">
            </form>
        </div>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

