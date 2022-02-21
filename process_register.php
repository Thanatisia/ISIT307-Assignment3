<?php
    /*
     * Registration Processing Page
     */
    require("./assets/scripts/mysqli_conn.php");     // Import MySQLi details

    // Make connection
    $conn = db_conn($DBHOST, $DBUSER, $DBPASS, $DBNAME);

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
            $duplicates_exist = chk_record_exists($conn, "users", "username", "username = '" . $_POST["username"] . "'"); 
            if(!$duplicates_exist)
            {
                // Duplicate does not exist
            
                $sql_stmt = "INSERT INTO users (username, password) VALUES (" . 
                    "'" . $_POST["username"] . "', " . 
                    "'" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "'" .
                ");";
                $res = stmt_exec($conn, $sql_stmt);
                echo "Result : " . $res['return_code'] . ", Size : " . $res['size'] . "<br/>";

                // Check if user has been inserted
                // $sql_stmt = "SELECT " . $_POST["username"] . "FROM user WHERE username = '" . $_POST["username"] . "'";
                $exists = chk_record_exists($conn, "users", "username", "username = '" . $_POST["username"] . "'");

                if($exists)
                {
                    // Registration Success
                    echo "User has been registered! <br/>";
                    echo "Returning to home page...";
                    header("refresh: 0, url=index.php");
                }
            }
            else
            {
                echo "<script>alert('User already exists');</script>";
            }
        }
    }
    
    // Close connection after use
    $conn->close();
?>
