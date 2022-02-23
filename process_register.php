<?php
    /*
     * Registration Processing Page
     */
    require_once("./assets/scripts/mysqli_conn.php");     // Import MySQLi details
    require_once("./assets/scripts/extlib.php");          // Import extlib

    // Handle Form Data
    if( isset($_POST["username"]) && 
        isset($_POST["password"]) && 
        isset($_POST["name"]) && 
        isset($_POST["surname"]) && 
        isset($_POST["phone"]) && 
        isset($_POST["email"]) 
    )
    {
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
            $table_exists = chk_table_exists($conn, "users");
            if($table_exists)
            {
                /* 
                 * Table Exists
                 */
                $tbl_Size = get_table_size($conn, "users");
                $client_name = sanitize_input($_POST["name"]);
                $client_surname = sanitize_input($_POST["surname"]);
                $client_phone_No = sanitize_input($_POST["phone"]);
                $client_email = sanitize_input($_POST["email"]);

                /* 
                 * Security Protocol : Password Validation
                 *  - Verify if the hashed password stored in the database ===
                 *              the hashed value after hashing user's password input
                 *
                 *  - Do not store password nor password hash into variable for security purposes
                 */

                // Check if user exists
                $duplicates_exist = chk_record_exists($conn, "users", "username", "username = '" . sanitize_input($_POST["username"]) . "'"); 
                if(!$duplicates_exist)
                {
                    // Duplicate does not exist
                    $curr_date = date("Ymd"); 
                
                    $sql_stmt = "INSERT INTO users (userID, username, password, name, surname, phone, email) VALUES (" . 
                        "'" . $curr_date . "_r$tbl_Size" . "', " .
                        "'" . sanitize_input($_POST["username"]) . "', " . 
                        "'" . password_hash(sanitize_input($_POST["password"]), PASSWORD_DEFAULT) . "', " .
                        "'" . $client_name . "', " .
                        "'" . $client_surname . "', " .
                        "'" . $client_phone_No . "', " . 
                        "'" . $client_email . "'" .
                    ");";
                    $res = stmt_exec($conn, $sql_stmt);
                    // echo "Result : " . $res['return_code'] . ", Size : " . $res['size'] . "<br/>";
                    
                    // Check if user has been inserted
                    // $sql_stmt = "SELECT " . $_POST["username"] . "FROM user WHERE username = '" . $_POST["username"] . "'";
                    $exists = chk_record_exists($conn, "users", "username", "username = '" . sanitize_input($_POST["username"]) . "'");

                    // Close connection after use
                    $conn->close();

                    if($exists)
                    {
                        // Registration Success
                        echo "<script>alert('User has been registered! Returning to homepage...');</script>";
                        header("refresh: 0, url=index.php");
                    }
                    else
                    {
                        echo "Error creating user, please try again";
                        header("refresh: 4, url=register.php");
                    }
                }
                else
                {
                    echo "<script>alert('User already exists');</script>";
                    header("refresh: 4, url=register.php");
                }
            }
            else
            {
                echo "Error Detected : Database Table not found, returning to registration page in 4 seconds...";
                header("refresh: 4, url=register.php");
            }
        }
    }
    else
    {
        // Debug Input Error
        $err_Fields = array();
        if(!isset($_POST["username"]) || !strlen($_POST["username"]) > 0)
        {
            array_push($err_Fields, "username");
        }
        if(!isset($_POST["password"]) || !strlen($_POST["password"]) > 0)
        {
            array_push($err_Fields, "password");
        }
        if(!isset($_POST["name"]) || !strlen($_POST["name"]) > 0)
        {
            array_push($err_Fields, "name");
        }
        if(!isset($_POST["surname"]) || !strlen($_POST["surname"]) > 0)
        {  
            array_push($err_Fields, "surname");
        }
        if(!isset($_POST["phone"]) || !strlen($_POST["phone"]) > 0)
        {
            array_push($err_Fields, "phone");
        }
        if(!isset($_POST["email"]) || !strlen($_POST["email"]) > 0)
        {
            array_push($err_Fields, "email");
        } 

         // Invalid, return back to registration page
        echo "Error with the following fields: ";

        for($i=0; $i < sizeof($err_Fields); $i++)
        {
            echo "$i : $err_Fields[i] <br/>";
        }

        echo "returning back to register page in 4 seconds...";
        header('Location: register.php', TRUE, 307); 
    }
?>
