<?php
    /*
     * Search Page
     */
    
    // Require and Import external libraries
    require_once("./assets/scripts/mysqli_conn.php");     // Import MySQLi details
    require_once("./assets/scripts/extlib.php");          // Import extlib

    // Start Session for use
    session_start();

    // Functions
    function display_all_products($conn)
    {
        // Retrieve all records from Products
        $results = stmt_exec($conn, "SELECT * FROM products;");
        $size = $results["size"];
        $rows = $results["result"];
        if($size > 0)
        {
            for($i=0; $i < $size; $i++)
            {
                // Remove First Column
                array_shift($rows[$i]);

                foreach($rows[$i] as $col => $value)
                {
                    echo "$col : $value <br/>";
                }
                echo "<br/>";
            }
        }
        else
        {
            echo "There are no products.";
        }
    }

    function get_all_rented($conn)
    {
        /* 
         * Retrieve all rented products
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
            $table_exists = chk_table_exists($conn, "products");
            if($table_exists)
            {
                /* 
                 * Table Exists
                 */

                // Retrieve all records from Products
                $search_condition = "prod_Status = '" . "Rented" . "'";
                $results = get_table_contents($conn, "products", "*", $search_condition);
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
     * If Button 'View is pressed'
     * - Redirect to products_view.php
     */
    if(isset($_POST["View"]))
    {
        if(isset($_POST["id"]))
        {
            $_SESSION["prod_id"] = $_POST["id"];
        }

        //header("Location: products_view.php", TRUE, 307);
        header("Location: products_view.php");
    }
?>
<html>
	<head>
		<title>List all available products</title>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div>
            <br/> 
            <u><b>List all available products</b></u> 
            <br/>
            <div>
                <p>Records: </p>
                <div>
                    <?php
                        /*
                         * List all products here
                         */
                        
                        // Check if connection to MySQL works
                        // Make connection
                        $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

                        $rows = get_all_rented($conn);

                        $size = $rows["size"];
                        $records = $rows["records"];

                        if($size > 0)
                        {
                            echo "Number of products : [$size]";
                            echo "<br/><br/>";
                            for($i=0; $i<$size; $i++)
                            {
                                $row = $records[$i];

                                /*
                                foreach($row as $col=>$value)
                                {
                                    echo "$col : $value <br/>";
                                }
                                */
                                echo "[" . $i+1 . "] : " . $row["prod_ID"] . ", " . $row["prod_Category"] . ", " . $row["prod_Brand"];
                    ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row["prod_ID"]; ?>">
                                    <input type="submit" name="View" value="View Product">
                                </form>
                    <?php
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
            </div>
        </div>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>
