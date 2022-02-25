<!--
    View Product Detail of a specific product 
    - Specified by user
-->
<?php
    // Start session for use
    session_start();

    // Require mysqli_conn for db functions
    require_once("./assets/scripts/mysqli_conn.php");

    // Global Variables 
    // Format Output
    $col_names = array(
        "prod_ID" => "Product ID",
        "prod_Category" => "Product Category",
        "prod_Brand" => "Product Brand",
        "prod_Description" => "Product Description",
        "prod_Status" => "Product Status",
        "prod_regular_cost_per_day" => "Product Rental Cost Per Day (Regular)",
        "prod_extended_cost_per_day" => "Product Rental Cost Per Day (Extended)"
    );

    // Functions
    function display_all_products($conn)
    {
        // Retrieve all records from Products
        $results = stmt_exec($conn, "SELECT * FROM products;");
        $size = $results["size"];
        $rows = $results["result"];
        if($size > 0)
        {
            echo "Number of products : [$size]";
            echo "<br/> <br/>";
            echo "<b>Product ID, Product Category, Product Brand, Product Description, Product Status, Product Regular Cost Per Day, Product Extended Cost Per Day</b>";
            echo "<br/>";
            for($i=0; $i < $size; $i++)
            {
                // Remove First Column
                array_shift($rows[$i]);

                $curr_row = $rows[$i];
                echo $curr_row["prod_ID"] . ", ";
                echo $curr_row["prod_Category"] . ", ";
                echo $curr_row["prod_Brand"] . ", ";
                echo $curr_row["prod_Description"] . ", ";
                echo $curr_row["prod_Status"] . ", ";
                echo $curr_row["prod_regular_cost_per_day"] . ", ";
                echo $curr_row["prod_extended_cost_per_day"] . ", ";
            
                echo "<br/>";
            }
            echo "<br/>";
        }
        else
        {
            echo "There are no products.";
        }
    }
    
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
?>

<html>
	<head>
		<title>Index Page</title>
	</head>

	<body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div>
            <p>Data</p>
            <?php
                if(isset($_SESSION["prod_id"]))
                {
                    // If 'View' button is pressed
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
                                echo "Number of products : [$size]";
                                echo "<br/> <br/>";
                                echo "<b>Product ID, Product Category, Product Brand, Product Description, Product Status, Product Rental Cost Per Day (Regular), Product Rental Cost Per Day (Extended)</b>";
                                echo "<br/>";
                                for($i=0; $i < $size; $i++)
                                {
                                    // Remove First Column
                                    array_shift($rows[$i]);

                                    $row = $rows[$i];
                                    foreach($row as $col => $value)
                                    {
                                        echo "$col_names[$col] : $value <br/>";
                                    }
                                    echo "<br/>";
                                }
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
                else
                {
                    echo "<p> Display all products </p>";
                    
                    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

                    $verify_conn = db_conn_verify($conn);

                    if($verify_conn)
                    {
                        /*
                         * Database is connected
                         */


                        // Check if table exists
                        $table_exists = chk_table_exists($conn, "products");

                        if($table_exists)
                        {
                            /*
                             * Table Exists
                             */
                            display_all_products($conn);
                        }
                    }

                    // Close Database after use
                    close_db($conn);
                }
            ?>
        </div>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
    </body>
</html>

