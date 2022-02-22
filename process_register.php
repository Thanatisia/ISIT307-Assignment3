<?php
    /*
     * Registration Processing Page
     */
    require_once("./assets/scripts/mysqli_conn.php");     // Import MySQLi details
    require_once("./assets/scripts/extlib.php");          // Import extlib

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

        // Handle Form Data
        if(isset($_POST["username"]) && isset($_POST["password"]))
        {
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
            
                $sql_stmt = "INSERT INTO users (username, password) VALUES (" . 
                    "'" . sanitize_input($_POST["username"]) . "', " . 
                    "'" . password_hash(sanitize_input($_POST["password"]), PASSWORD_DEFAULT) . "'" .
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
            }
            else
            {
                echo "<script>alert('User already exists');</script>";
            }
        }
    }
?>
