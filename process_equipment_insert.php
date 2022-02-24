<?php
    /*
     * Processing - Product Insert
     *  - Insert into Database
     */
    require_once("./assets/scripts/mysqli_conn.php");     // Import MySQLi details
    require_once("./assets/scripts/extlib.php");          // Import extlib

    if(
        isset($_POST["prodID"]) &&
        isset($_POST["prodCategory"]) &&
        isset($_POST["prodBrand"]) &&
        isset($_POST["prodDesc"]) &&
        isset($_POST["prodRegCostPerDay"]) &&
        isset($_POST["prodExtCostPerDay"])
    )
    {
        $sql_stmt = "";
        // Check if connection to MySQL works
        $conn = db_conn(DBHOST, DBUSER, DBPASS);

        // Check if database exists
        $verify_conn = db_conn_verify($conn);

        if($verify_conn)
        {
            // Connection successful
            
            // Check if database exists 
            if(!chk_db_exists($conn, $DBNAME))
            {
                /*
                 * Database doesnt exist
                 * - Create database
                 */
                // $result = create_db($conn, $DBNAME);
                require_once('process_db_setup.php');
            }

            // Close database after use
            close_db($conn);
        }

        // Make connection
        $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

        $verify_conn = db_conn_verify($conn);

        // Verify connection
        if($verify_conn)
        {
            /*
             * Database connection successful
             *  - Insert into database
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
                $tbl_Size = get_table_size($conn, "products");
                $product_ID = sanitize_input($_POST["prodID"]);                                 // Text / String
                $product_Category = sanitize_input($_POST["prodCategory"]);                     // Text / String
                $product_Brand = sanitize_input($_POST["prodBrand"]);                           // Text / String
                $product_Description = sanitize_input($_POST["prodDesc"]);                      // Text / String
                $product_regular_cost_per_day = sanitize_input($_POST["prodRegCostPerDay"]);    // Float
                $product_extended_cost_per_day = sanitize_input($_POST["prodExtCostPerDay"]);   // Float

                /* 
                 * Security Protocol : Password Validation
                 *  - Verify if the hashed password stored in the database ===
                 *              the hashed value after hashing user's password input
                 *
                 *  - Do not store password nor password hash into variable for security purposes
                 */

                // Check if user exists
                $duplicates_exist = chk_record_exists($conn, "products", "prod_ID", "prod_ID = '" . sanitize_input($_POST["prodID"]) . "'"); 
                if(!$duplicates_exist)
                {
                    // Duplicate does not exist
                    $curr_date = date("Ymd"); 
                
                    $sql_stmt = "INSERT INTO products (prod_ID, prod_Category, prod_Brand, prod_Description, prod_regular_cost_per_day, prod_extended_cost_per_day) VALUES (" . 
                        "'" . $product_ID . "', " .
                        "'" . $product_Category . "', " . 
                        "'" . $product_Brand . "', " .
                        "'" . $product_Description . "', " .
                        $product_regular_cost_per_day . ", " .
                        $product_extended_cost_per_day .
                    ");";
                    $res = stmt_exec($conn, $sql_stmt);
                    // echo "Result : " . $res['return_code'] . ", Size : " . $res['size'] . "<br/>";
                    
                    // Check if user has been inserted
                    // $sql_stmt = "SELECT " . $_POST["username"] . "FROM user WHERE username = '" . $_POST["username"] . "'";
                    $exists = chk_record_exists($conn, "products", "prod_ID", "prod_ID = '" . $product_ID . "'");

                    // Close connection after use
                    $conn->close();

                    if($exists)
                    {
                        // Registration Success
                        echo "<script>alert('Product [$product_ID : $product_Category : $product_Brand] has been uploaded! Returning to insert page...');</script>";
                        header("refresh: 0, url=products_insert.php");
                    }
                    else
                    {
                        echo "Error inserting product, please try again";
                        header("refresh: 4, url=products_insert.php");
                    }
                }
                else
                {
                    echo "<script>alert('Product [$product_ID]] already exists');</script>";
                    header("refresh: 0, url=products_insert.php");
                }
            }
            else
            {
                echo "Error Detected : Database Table not found, returning to insert page in 4 seconds...";
                header("refresh: 4, url=products_insert.php");
            }
        }
    }
    else
    {
        // Debug Input Error
        $err_Fields = array();
        if(!isset($_POST["prodID"]) || !strlen($_POST["prodID"]) > 0)
        {
            array_push($err_Fields, "prodID");
        }
        if(!isset($_POST["prodCategory"]) || !strlen($_POST["prodCategory"]) > 0)
        {
            array_push($err_Fields, "prodCategory");
        }
        if(!isset($_POST["prodBrand"]) || !strlen($_POST["prodBrand"]) > 0)
        {
            array_push($err_Fields, "prodBrand");
        }
        if(!isset($_POST["prodDesc"]) || !strlen($_POST["prodDesc"]) > 0)
        {  
            array_push($err_Fields, "prodDesc");
        }
        if(!isset($_POST["prodRegCostPerDay"]) || !strlen($_POST["prodRegCostPerDay"]) > 0)
        {
            array_push($err_Fields, "prodRegCostPerDay");
        }
        if(!isset($_POST["prodExtCostPerDay"]) || !strlen($_POST["prodExtCostPerDay"]) > 0)
        {
            array_push($err_Fields, "prodExtCostPerDay");
        } 

         // Invalid, return back to registration page
        echo "Error with the following fields: ";

        for($i=0; $i < sizeof($err_Fields); $i++)
        {
            echo "$i : $err_Fields[i] <br/>";
        }

        echo "returning back to register page in 4 seconds...";
        header('Location: products_insert.php', TRUE, 307); 
    }
?>
