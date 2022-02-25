<!--
    List all equipment client is currently renting
-->
<?php
    // Start session for use
    session_start();
 
    // Require and Import external libraries
    require_once("./assets/scripts/mysqli_conn.php");     // Import MySQLi details
    require_once("./assets/scripts/extlib.php");          // Import extlib
    include_once("./assets/scripts/client_info.php");

    $user_info = new ClientInfo();

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

?>


<html>
	<head>
		<title>List Current Rentals</title>
	</head>

	<body>
         <?php include './header.inc.php' ?> <!-- Header -->

        <hr/>

        <h1>Admin's Page</h1>

        <hr/> 

        <div>
            <p><b><u>List Current Rentals</u></b></p>
            <?php
                /*
                 * List Client's Current Rentals
                 */
                
                // Check if connection to MySQL works
                // Make connection
                $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

                $rows = get_rentals($conn);

                $size = $rows["size"];
                $records = $rows["records"];

                if($size > 0)
                {
                    echo "Number of products : [$size]";
                    echo "<br/><br/>";
                    echo "Product ID, Rent Regular Period, Rent Extended Period, Rent Regular Cost, Rent Extended Cost";
                    echo "<br/>";
                    for($i=0; $i<$size; $i++)
                    {
                        $row = $records[$i];

                        /*
                        foreach($row as $col=>$value)
                        {
                            echo "$col : $value <br/>";
                        }
                        */
                        echo "[" . $i+1 . "] : " . 
                            $row["prod_ID"] . ", " . 
                            $row["rent_regular_period"] . ", ";
                            if($row["rent_extended_period"] == "")
                            {
                                echo '0' . ", ";
                            }
                            echo $row["rent_regular_cost"] . ", ";
                            if($row["rent_extended_cost"] == "")
                            {
                                echo '0' . ", ";
                            }
                        echo "<br/>";
                    }
                }
                else
                {
                    echo "No products available.";
                }

                // Close connection after use
                $conn->close();
            ?>
        </div>

        <hr/>

        <?php include './footer.inc.php' ?> <!-- Footer -->
    </body>
</html>

