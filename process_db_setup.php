<?php
    /*
     * Setup DB
     */
    require("mysqli_conn.php");

    // Try and make connection if doesnt exist
    $conn = db_conn(DBHOST, DBUSER, DBPASS);
    $verify_res = db_conn_verify($conn);

    if($verify_res)
    {
        /* 
         * Connection Successful
         */

        // Check if database exists
        $db_exists = chk_db_exists($conn, $DBNAME);
        if(!$db_exists)
        {
            // Create Database if doesnt exist 
            $db_create_res = create_db($conn, $DBNAME);

            if($db_create_res)
            {
                // Database created successfully
                echo "Database created successfully / already exists.", "<br/>";
            }
            else
            {
                echo "Error creating database [$DBNAME]", "<br/>";
            }
        }

        // Close connection after use
        close_db($conn); 
    }

 
    // Create a new connection after database is created
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);
    $verify_res = db_conn_verify($conn);

    if($verify_res)
    {
        // Check if table exists
        $tb_name = "users";
        $column_definition = "ROW_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            u_name nvarchar(50) NOT NULL,
            u_pass nvarchar(50) NOT NULL";
        $table_exists = chk_table_exists($conn, $tb_name);
        if(!$table_exists)
        {
            // Create Table if doesnt exist
            $table_create_res = create_table($conn, $tb_name, $column_definition);

            if($table_create_res)
            {
                // Table created successfully
                echo "Table $tb_name created successfully";
            }
            else
            {
                echo "Error creating table [$tb_name]...", "<br/>";
            }
        }

        // Close connection after use
        close_db($conn);
    }
?>
