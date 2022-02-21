<?php
    /*
     * Login Processing Page
     */
    require("./assets/scripts/mysqli_conn.php");     // Import MySQLi details

    // Start Session for use
    session_start();

    // Make connection
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

    // Handle FORM data
    if(isset($_POST["username"]) && isset($_POST["password"]))
    {
        // If form data are all filled appropriately

        // Get values
        $u_name = $_POST["username"];

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
            if(password_hash($_POST["password"], PASSWORD_DEFAULT) === $conn->query($conn, "SELECT 'password' FROM users WHERE 'username' = '$u_name';")) 
            {
                // user input password is the same as database-stored password
                echo "<script>alert('Login Successful');</script>";

                // Get Role and store in session
                $u_Roles = get_value($conn, "users", "username", "'username' = '$u_name' AND 'password' = '" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "'");
                $_SESSION["role"] = $u_Roles[0];    // Get First/only result (no duplicates)
            }
        }
    }

    // Close connection after use
    $conn->close();
?>
