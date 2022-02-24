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

    /*
     * List all products here
     */
    
    // Check if connection to MySQL works
    // Make connection
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

    $verify_conn = db_conn_verify($conn);

    $searching = false;
    $search_results = array();
    $search_condition = "";
    $search_Errors = "";

    if(isset($_POST["search_by_ID"]))
    {
        /*
         * Search by Product ID
         */
        if(isset($_POST['search']))
        {
            $searching = True;
            $sql_stmt = "SELECT * FROM products WHERE prod_ID = '" . $_POST["search"] . "'";
            $search_condition = "prod_ID = '" . $_POST["search"] . "'";
        }
    }
    elseif(isset($_POST["search_by_Category"]))
    {
        /*
         * Search by Category (Laptop|Router|Modem...)
         */
        if(isset($_POST['search']))
        {
            $searching = True;
            $sql_stmt = "SELECT * FROM products WHERE prod_Category = '" . $_POST["search"] . "'";
            $search_condition = "prod_Category = '" . $_POST["search"] . "'";
        }
    }
    elseif(isset($_POST["search_by_Brand"]))
    {
        /*
         * Search by Brand (Samsung|Mitsubishi etc.)
         */
        if(isset($_POST['search']))
        {
            $searching = True;
            $sql_stmt = "SELECT * FROM products WHERE prod_Brand = '" . $_POST["search"] . "'";
            $search_condition = "prod_Brand = '" . $_POST["search"] . "'";
        }
    }
    elseif(isset($_POST["search_by_Status"]))
    {
        /*
         * Search by Status (Available | Rented)
         */
        if(isset($_POST['search']))
        {
            $searching = True;
            $sql_stmt = "SELECT * FROM products WHERE prod_Status = '" . $_POST["search"] . "'";
            $search_condition = "prod_Status = '" . $_POST["search"] . "'";
        }
    }

    // Verify connection
    if($verify_conn)
    {
        /*
         * Database connection successful
         *  - Search and retrieve from database all products
         *  $sql_stmt = "SELECT * FROM products";
         */
        if($searching)
        {
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
                $results = get_table_contents($conn, "products", "*", $search_condition);
                $search_results_size = $results[0];
                $search_results = $results[1]; 
            }
        }
    }
    else
    {
        $search_Errors = "Error connecting to MySQL Server";
    }

    // Close connection after use
    $conn->close();


?>
<html>
	<head>
		<title>Product Search</title>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div> Search By
            <ul> 
                <li>
                    <!-- Form to Search by ID -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        Product ID: <input type="text" name="search" value="" placeholder="yyyy-mm-dd-X>">
                        <input type="submit" name="search_by_ID" value="Search by ID">
                    </form>
                </li>

                <li>
                    <!-- Form to Search by Category -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        Category: <input type="text" name="search" value="" placeholder="Laptop | Router | Modem | ...">
                        <input type="submit" name="search_by_Category" value="Search by Category">
                    </form>
                </li>

                <li>
                    <!-- Form to Search by Brand -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        Brand: <input type="text" name="search" value="" placeholder="Samsung">
                        <input type="submit" name="search_by_Brand" value="Search by Brand">
                    </form>
                </li>

                <li>
                    <!-- Form to Search by Status (Availability) -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        Status [Available | Rented]: 
                        <select name="search" id="search">
                        <!-- value="<?php if(isset($_POST["search"])) echo $_POST['search']; ?>" placeholder="Available | Rented" -->
                            <option value="Available">Available</option>
                            <option value="Rented">Rented</option>
                        </select>
                        <input type="submit" name="search_by_Status" value="Search by Status">
                    </form>
                </li>
            </ul>
        </div>

        <div>Other Functions
            <ul>
                <li>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <input type="submit" name="Reset" value="Reset">
                    </form>
                </li>
            </ul>
        </div>

        <?php
            /*
             * List all products here
             */
            
            // Check if connection to MySQL works
            // Make connection
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

                    if($searching)
                    {
                        // If searching
                        // Print out searched products
                        if($search_results_size > 0 )
                        {
                            for($i=0; $i<$search_results_size; $i++)
                            {
                                foreach($search_results[$i] as $col => $value)
                                {
                                    echo "$col : $value <br/>";
                                }
                                echo "<br/>";
                            }
                        }
                        else
                        {
                            echo "No products found.";
                        }
                    }
                    else
                    {
                        display_all_products($conn);
                    }
                }
            }

            // Close connection after use
            $conn->close();
        ?>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

