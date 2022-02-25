<!--
    Equipment Returns Page
-->

<?php
    // Start Session for use
    session_start();

    // Include external files for use
    require_once("./assets/scripts/mysqli_conn.php");
    require_once("./assets/scripts/extlib.php");

    /*
     * Returns
     * - Change Product Status back to 'Available'
     */
    function get_rentals($conn)
    {
        /* 
         * Retrieve all current rentals
         */
        $verify_conn = db_conn_verify($conn);

        $result = array(
            "size" => 0,
            "records" => ""
        );

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
            $table_exists = chk_table_exists($conn, "rentals");
            if($table_exists)
            {
                /* 
                 * Table Exists
                 */
                // Retrieve all records from Products
                $search_condition = "client_ID = '" . $_SESSION["userID"] . "' AND rent_Status = 'Rented'";
                $results = get_table_contents($conn, "rentals", "*", $search_condition);
                $search_results_size = $results[0];
                $search_results = $results[1]; 
                $result['size'] = $search_results_size;
                $result['records'] = $search_results;
            }
        }
        else
        {
            $search_Errors = "Error connecting to MySQL Server";
        }

        return $result;
    }

    /*
     * If 'Return' button is pressed
     *  - Change 'rent_Status' of rentals to 'Returned' after returning
     *  - Change 'rent_return_date' of rentals to today's date after returning
     *  - Change 'prod_Status' of product to 'Available' after returning
     *  - Get the row and INSERT into 'rent_history'
     */
    $update_Checklist = array(
        "rentals" => array(
            "rent_Status" => False,
            "rent_return_date" => False
        ),
        "products" => array(
            "prod_Status" => False
        ),
        "rent_history" => False
    );
    $size = 0;
    $rows = "";
    $return_date = "";
    $success_token = False;
    if(isset($_POST["Return"]))
    {
        if(isset($_POST["row_number"]) && isset($_POST["prod_id"]))
        {
            /*
             * ID is passed for use
             */
            $row_number = sanitize_input($_POST["row_number"]);
            $prod_id = $_POST["prod_id"];

            // Open Database for use
            $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

            // Verify connection
            $verify_conn = db_conn_verify($conn);
            if($verify_conn)
            {
                /*
                 * Database connection successful
                 */
                $return_date = date("Y-m-d");

                /*
                 * Check if Table [Rentals] Exists
                 */
                $table_exists = chk_table_exists($conn, "rentals");
                if($table_exists)
                {
                    $filter_condition = "prod_ID = '$prod_id' AND rent_Status = 'Rented'";

                    // Get all columns in target row
                    // before database values are changed
                    $sql_stmt = "SELECT * FROM rentals WHERE $filter_condition";
                    //$res = stmt_exec($conn, $sql_stmt);
                    //$size = $res['size'];
                    //$res = $res['result'];
                    $res = $conn->query($sql_stmt);
                    $size = $res->num_rows;
                    $rows = $res->fetch_assoc();
                    
                    // Update rent_return_date
                    $sql_stmt = "UPDATE rentals SET rent_return_date = '$return_date' WHERE $filter_condition";
                    if($conn->query($sql_stmt))
                    {
                       $update_Checklist["rentals"]["rent_return_date"] = True; 
                    }

                    // Update rent_Status
                    $sql_stmt = "UPDATE rentals SET rent_Status = 'Returned' WHERE $filter_condition";
                    if($conn->query($sql_stmt))
                    {
                       $update_Checklist["rentals"]["rent_Status"] = True; 
                    }
                }

                /*
                 * Check if Table [products] Exists
                 */
                $table_exists = chk_table_exists($conn, "products");
                if($table_exists)
                {
                    // echo "<script>alert('$row_number : $prod_id');</script>";
                    $filter_condition = "prod_ID = '$prod_id'";
                    $sql_stmt = "UPDATE products SET prod_Status = 'Available' WHERE $filter_condition";    
                    if($conn->query($sql_stmt))
                    {
                       $update_Checklist["products"]["prod_Status"] = True; 
                    }
                }

                /*
                 * Check if Table [rent_history] Exists
                 */
                $table_exists = chk_table_exists($conn, "rent_history");
                if($table_exists)
                {
                    if($size > 0)
                    {
                        // Get current row values and INSERT into table
                        $client_id = $rows["client_ID"];
                        $client_name = $rows["client_name"];
                        $rent_start_date = $rows["rent_start_date"];
                        $rent_return_date = $return_date;
                        $rent_regular_period = $rows["rent_regular_period"];
                        $rent_extended_period = $rows["rent_extended_period"];
                        $rent_regular_cost = floatval($rows["rent_regular_cost"]);
                        $rent_extended_cost = floatval($rows["rent_extended_cost"]);
                        $columns = "client_ID, client_name, prod_ID, rent_start_date, rent_return_date, rent_regular_period, rent_extended_period, rent_regular_cost, rent_extended_cost";
                        $values = 
                            "'" . $client_id                . "', " .
                            "'" . $client_name              . "', " . 
                            "'" . $prod_id                  . "', " .
                            "'" . $rent_start_date          . "', " .
                            "'" . $rent_return_date         . "', " .
                            "'" . $rent_regular_period      . "', " . 
                            "'" . $rent_extended_period     . "', " .
                            floatval($rent_regular_cost)    . ", "  . 
                            floatval($rent_extended_cost);
                        if(insert_row($conn, "rent_history", $columns, $values))
                        {
                            // Insert is successful
                            $update_Checklist["rent_history"] = True;
                        }
                    }
                    else
                    {
                        echo "No rental records found", "<br/>";
                    }
                }

                /*
                 * Check if all checklist tasks are done
                 */
                if(
                    $update_Checklist["rentals"]["rent_Status"] &&
                    $update_Checklist["rentals"]["rent_return_date"] &&
                    $update_Checklist["products"]["prod_Status"] && 
                    $update_Checklist["rent_history"]
                )
                {
                    // Success
                    $success_token = True;
                }
                else
                {
                    /*
                     * Error Detected
                     */
                    echo "Errors detected with the following:<br/>";
                    foreach($update_Checklist as $tables => $values)
                    {
                        $curr_table = $values;
                        if(gettype($curr_table) == "object")
                        {
                            foreach($curr_table as $col => $value)
                            {
                                if(!$value)
                                {
                                    echo "$col : $value";
                                }
                            }
                        }
                        else
                        {
                            if($values)
                            {
                                echo "$tables : $values";
                            }
                        }
                    }
                }

                // Close Database after use
                close_db($conn);
            }
        }
    }
?>

<html>
	<head>
        <title>Product Return</title>
        <link href="./assets/styles/css/index.css" type="text/stylesheet" rel="stylesheet"/>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div>
            <p><b><u>Your Current Rentals</u></b></p>
            <!--
                - Get all rentals made by current client
                - Display
                - Include Button to Return selected Product
            -->
            <div>
                <?php
                    if($success_token)
                    {
                        echo "<script>alert('Rental Equipment successfully returned!');</script>";
                        echo "<br/> Rental Equipment successfully returned! <br/>";
                    }
                ?>
            </div>

            <?php
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
                    $table_exists = chk_table_exists($conn, "rentals");
                    if($table_exists)
                    {
                        /*
                         * Table Exists
                         * - Get all rentals
                         */
                        $res = get_rentals($conn);
                        $size = $res['size'];
                        $rows = $res['records'];

                        if($size > 0)
                        {
                            // Format Output
                            $col_names = array(
                                "client_ID" => "Client ID",
                                "client_name" => "Client Name",
                                "prod_ID" => "Product ID",
                                "rent_start_date" => "Rent Date (Start)",
                                "rent_return_date" => "Rent Date (Return)",
                                "rent_regular_period" => "Rent Period (Regular)",
                                "rent_extended_period" => "Rent Period (Extended)",
                                "rent_regular_cost" => "Rent Cost (Regular)",
                                "rent_extended_cost" => "Rent Cost (Extended)",
                                "rent_Status" => "Rent Status"
                            );

                            for($i=0; $i<$size; $i++)
                            {
                                /*
                                 * Loop to get ID
                                 */
                                $curr_row = $rows[$i];
                                array_shift($curr_row); // Get Second Element of the array onwards

                                if($curr_row["rent_Status"] == "Rented")
                                {
                                    foreach($curr_row as $col=>$value)
                                    {
                                        if($value == "")
                                        {
                                            $value = "Empty";
                                        }
                                        echo "$col_names[$col] : $value";
                                        echo "<br/>";
                                    }
                ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                        <input type="hidden" name="row_number" value="<?php echo $i; ?>">
                                        <input type="hidden" name="prod_id" value="<?php echo $curr_row["prod_ID"]; ?>">
                                        <input type="submit" name="Return" value="Return Product">
                                    </form>
                <?php
                                    echo "<br/><br/>";
                                }
                            }
                        }
                        else
                        {
                            echo "No Products Rented", "<br/>";
                        }
                    }
                }       
            ?>
        </div> 

<!--
        <div>
            <p><b><u>Returns</u></b></p> 
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                Product ID : <input type="text" name="prodID" value="<?php if(isset($_POST['prodID'])) echo $_POST['prodID']; ?>" placeholder="yyyy-mm-dd">
                <input type="submit" name="Return" value="Return">
            </form>
        </div>
-->

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

