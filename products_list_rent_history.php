<!--
    List all of current user's rent history 
-->
<?php
    // Start session for use
    session_start();
 
    // Require and Import external libraries
    require_once("./assets/scripts/mysqli_conn.php");     // Import MySQLi details
    require_once("./assets/scripts/extlib.php");          // Import extlib
    include_once("./assets/scripts/client_info.php");

    $user_info = new ClientInfo();

    function get_rent_history($conn)
    {
        /* 
         * Retrieve all available products
         */
        global $user_info;
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
            $table_exists = chk_table_exists($conn, "rent_history");
            if($table_exists)
            {
                /* 
                 * Table Exists
                 */

                // Retrieve all records from Products
                $search_condition = "client_ID = '" . $_SESSION["userID"] . "'";
                $results = get_table_contents($conn, "rent_history", "*", $search_condition);
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
		<title>List Rent History</title>
	</head>

	<body>
         <?php include './header.inc.php' ?> <!-- Header -->

        <hr/>

        <h1>Admin's Page</h1>

        <hr/> 

        <div>
            <p><b><u>List Rent History</u></b></p>
            <?php
                /*
                 * List Client's Rent History here
                 */
                
                // Check if connection to MySQL works
                // Make connection
                $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

                $rows = get_rent_history($conn);

                $size = $rows["size"];
                $records = $rows["records"];

                if($size > 0)
                {
                    echo "Number of products : [$size]";
                    echo "<br/><br/>";
                    echo "<b>Product ID, Rent Start Date, Rent Period (Regular), Rent Period (Extended), Rent Return Date, Rent Cost (Regular), Rent Cost (Extended), Rent Cost (Total)</b>", "<br/>";
                    for($i=0; $i<$size; $i++)
                    {
                        $row = $records[$i];

                        /*
                        foreach($row as $col=>$value)
                        {
                            echo "$col : $value <br/>";
                        }
                         */

                        // Default
                        if($row["rent_extended_period"] == "")
                        {
                            $row["rent_extended_period"] = "NIL";
                        }

                        if($row["rent_extended_cost"] == "")
                        {
                            $row["rent_extended_cost"] = 0;
                        }

                        echo "[" . $i+1 . "] : " . 
                            $row["prod_ID"] . ", " . 
                            $row["rent_start_date"] . ", " . 
                            $row["rent_regular_period"] . ", " . 
                            $row["rent_extended_period"] . ", " . 
                            $row["rent_return_date"] . ", " . 
                            $row["rent_regular_cost"] . ", " . 
                            $row["rent_extended_cost"] . ", " . 
                            $row["rent_regular_cost"] + $row["rent_extended_cost"];
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

