<?php
    /*
     * Processing of Product Rental
     */

    // Start session for use
    session_start();

    // Require mysqli_conn for db functions
    require_once("./assets/scripts/mysqli_conn.php");

    if(isset($_SESSION["prod_id"]))
    {
        /*
         * Check For Product basing off selected ID
         * - Retrieve product info
         * - Calculate
         */
        $prod_id = $_SESSION["prod_id"];
        
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
                $records = filter_products($conn, "products", "*", $search_condition);
                $size = $records[0];
                $rows = $records[1];

                if($size > 0)
                {
                    // Get Product Info
                    
                }
                else
                {
                    echo "There are no products.";
                }
            }
            else
            {
                echo "ERROR: Table doesnt exist";
            }

            // Close Database after use
            close_db($conn);
        }
        else
        {
            echo "ERROR : Error connecting to Database";
        }
    }
?>
