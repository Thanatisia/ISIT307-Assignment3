<?php
    /*
     * Setup DB
     */
    require_once("./assets/scripts/mysqli_conn.php");

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

    /*
     * Create Database Table 'users'
     * - Store all users
     * - Column Definition:
     *      ROW_ID          INT             PRIMARY_KEY NOT NULL AUTO_INCREMENT,
     *      userID          TEXT                        NOT NULL,
     *      username        TEXT                        NOT NULL,
     *      password        TEXT                        NOT NULL,
     *      name            TEXT                        NOT NULL,
     *      surname         TEXT                        NOT NULL,
     *      phone           TEXT                        NOT NULL,
     *      email           TEXT                        NOT NULL,
     *      role            nvarchar(50)                NOT NULL                DEFAULT "client",
     *      curr_rent       TEXT,
     *      rent_history    TEXT
     */


    // Create a new connection after database is created
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);
    $verify_res = db_conn_verify($conn);

    if($verify_res)
    {
        // Check if table exists
        $tb_name = "users";
        $column_definition = "ROW_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            userID   TEXT NOT NULL,   
            username TEXT NOT NULL,
            password TEXT NOT NULL,
            name     TEXT NOT NULL,
            surname  TEXT NOT NULL,
            phone    TEXT NOT NULL,
            email    TEXT NOT NULL,
            role nvarchar(50) NULL DEFAULT 'client'";
        $table_exists = chk_table_exists($conn, $tb_name);
        if(!$table_exists)
        {
            // Create Table if doesnt exist
            $table_create_res = create_table($conn, $tb_name, $column_definition);

            if($table_create_res)
            {
                // Table created successfully
                echo "Table created successfully <br/>";
            }
            else
            {
                echo "Error creating table...", "<br/>";
            }
        }

        // Close connection after use
        close_db($conn);
    }

    /*
     * Create Database Table 'products'
     * - Display all products
     * - Column Definition:
     *      ROW_ID                      INT             PRIMARY_KEY NOT NULL AUTO_INCREMENT,
     *      prod_ID                     TEXT                        NOT NULL,
     *      prod_Category               TEXT                        NOT NULL,
     *      prod_Brand                  TEXT                        NOT NULL,
     *      prod_Description            TEXT                        NOT NULL,
     *      prod_Status                 TEXT                        NOT NULL,               DEFAULT 'Available'             //--> Options : 'Available' | 'Loaned'; Change when user clicked on 'Return'
     *      prod_regular_cost_per_day   FLOAT                       NOT NULL,
     *      prod_extended_cost_per_day  FLOAT                       NOT NULL,
     *      prod_rent_client            TEXT,
     *      prod_rent_period            TEXT
     */

    // Create a new connection after database is created
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);
    $verify_res = db_conn_verify($conn);

    if($verify_res)
    {
        // Check if table exists
        $tb_name = "products";
        $column_definition = "ROW_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            prod_ID                     TEXT    NOT NULL,
            prod_Category               TEXT    NOT NULL,
            prod_Brand                  TEXT    NOT NULL,
            prod_Description            TEXT    NOT NULL,
            prod_Status                 TEXT        NULL DEFAULT 'Available',    
            prod_regular_cost_per_day   FLOAT   NOT NULL,
            prod_extended_cost_per_day  FLOAT   NOT NULL";
        $table_exists = chk_table_exists($conn, $tb_name);
        if(!$table_exists)
        {
            // Create Table if doesnt exist
            $table_create_res = create_table($conn, $tb_name, $column_definition);

            if($table_create_res)
            {
                // Table created successfully
                echo "Table created successfully <br/>";
            }
            else
            {
                echo "Error creating table...", "<br/>";
            }
        }

        // Close connection after use
        close_db($conn);
    }

    /*
     * Create Database Table 'rentals'
     * - To store all currently renting users and their items
     * TODO:
     *  - Will be moved from this table to 'rent_history' table after user returns
     *  - Update 'rent_period' if user clicks on 'extend period'
     * - Column Definition:
     *      ROW_ID                  INT             PRIMARY_KEY NOT NULL AUTO_INCREMENT,
     *      rent_ID                 TEXT                        NOT NULL,                   //for Rental Tracking
     *      client_ID               TEXT                        NOT NULL,
     *      client_name             TEXT                        NOT NULL,
     *      prod_ID                 TEXT                        NOT NULL
     *      prod_Category           TEXT                        NOT NULL,
     *      prod_Brand              TEXT                        NOT NULL,
     *      rent_regular_period     TEXT                        NOT NULL,
     *      rent_extended_period    TEXT
     *      rent_regular_cost       FLOAT                       NOT NULL,
     *      rent_extended_cost      FLOAT
     */


    // Create a new connection after database is created
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);
    $verify_res = db_conn_verify($conn);

    if($verify_res)
    {
        // Check if table exists
        $tb_name = "rentals";
        $column_definition = "ROW_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            rent_ID                 TEXT    NOT NULL,
            client_ID               TEXT    NOT NULL,
            client_name             TEXT    NOT NULL,
            prod_ID                 TEXT    NOT NULL,
            rent_regular_period     TEXT    NOT NULL,
            rent_extended_period    TEXT,
            rent_regular_cost       FLOAT   NOT NULL,
            rent_extended_cost      FLOAT";
        $table_exists = chk_table_exists($conn, $tb_name);
        if(!$table_exists)
        {
            // Create Table if doesnt exist
            $table_create_res = create_table($conn, $tb_name, $column_definition);

            if($table_create_res)
            {
                // Table created successfully
                echo "Table created successfully <br/>";
            }
            else
            {
                echo "Error creating table...", "<br/>";
            }
        }

        // Close connection after use
        close_db($conn);
    }

    /*
     * Create Database Table 'rent_history'
     * - To store all past rentals and their history
     * - Column Definition:
     *      ROW_ID                  INT             PRIMARY_KEY NOT NULL AUTO_INCREMENT,
     *      rent_ID                 TEXT                        NOT NULL,                   //=> To track rentals
     *      client_ID               TEXT                        NOT NULL,
     *      client_name             TEXT                        NOT NULL,
     *      prod_ID                 TEXT                        NOT NULL,
     *      rent_regular_period     TEXT                        NOT NULL,
     *      rent_extended_period    TEXT,
     *      rent_return_period      TEXT,
     *      rent_regular_cost       FLOAT                       NOT NULL,
     *      rent_extended_cost      FLOAT
     */


    // Create a new connection after database is created
    $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);
    $verify_res = db_conn_verify($conn);

    if($verify_res)
    {
        // Check if table exists
        $tb_name = "rent_history";
        $column_definition = "ROW_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            rent_ID                 TEXT    NOT NULL,
            client_ID               TEXT    NOT NULL,
            client_name             TEXT    NOT NULL,
            prod_ID                 TEXT    NOT NULL,
            rent_regular_period     TEXT    NOT NULL,
            rent_extended_period    TEXT,
            rent_return_period      TEXT,
            rent_regular_cost       FLOAT   NOT NULL,
            rent_extended_cost      FLOAT";
        $table_exists = chk_table_exists($conn, $tb_name);
        if(!$table_exists)
        {
            // Create Table if doesnt exist
            $table_create_res = create_table($conn, $tb_name, $column_definition);

            if($table_create_res)
            {
                // Table created successfully
                echo "Table created successfully <br/>";
            }
            else
            {
                echo "Error creating table...", "<br/>";
            }
        }

        // Close connection after use
        close_db($conn);
    }


    // After setup complete
    // Return to index page
    // header("Location: index.php");
?>
