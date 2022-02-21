<?php
    /*
     * Login Processing Page
     */
    require("./assets/scripts/mysqli_conn.php");     // Import MySQLi details
    require("./assets/scripts/extlib.php");             // Import External Library

    // Start Session for use
    session_start();

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
            echo "Error 404 : Database not found!<br/>";
        }

        // Close database after use
        close_db($conn);
    }

    // Make connection
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

    // Handle FORM data
    if(isset($_POST["username"]) && isset($_POST["password"]))
    {
        // If form data are all filled appropriately

        // Get values
        $u_name = sanitize_input($_POST["username"]);

        /* 
         * Security Protocol : Password Validation
         *  - Verify if the hashed password stored in the database ===
         *              the hashed value after hashing user's password input
         */
        // Check if user exists
        $uname_Exists = chk_record_exists($conn, "users", "username", "username = '$u_name';");

        if($uname_Exists)
        {
            /*
             * If username exists
             * - Check password 
             */
            $sql_stmt = "SELECT * FROM users WHERE username='$u_name'";
            $result = $conn->query($sql_stmt);
            $count = $result->num_rows;
            $row = $result->fetch_assoc();
            if($count > 0)
            {
                // Records Found
                // echo "<script>alert('" . $_POST["password"] . " : " . hash("sha512", $_POST["password"]) . " : " . $row["password"] .  "');</script>";
                if(password_verify(sanitize_input($_POST["password"]), $row["password"]))
                {
                    // Get Role and store in session
                    $_SESSION["role"] = $row["role"];    // Get First/only result (no duplicates)
                    $_SESSION["username"] = $u_name;
                    
                    // user input password is the same as database-stored password
                    echo "<script>alert('Login Successful');</script>";

                    // Redirect to home page
                    header("refresh: 0, url=index.php");
                }
            } 
        }
        else
        {
            echo "<script>alert('Invalid user');</script>";

            // Redirect  to login page
            header("refresh: 0, url=login.php");
        }
    }

    // Close connection after use
    $conn->close();
?>
