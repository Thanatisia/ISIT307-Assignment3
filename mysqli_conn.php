<?php
    /*
     * Connection Details for MySQL Database
     * Usage:
     *  require('mysqli_conn.php');
     */

    // Definitions
    DEFINE("DBHOST", "localhost");
    DEFINE("DBUSER", "root");
    DEFINE("DBPASS", "");

    // Global Variables
    $DBNAME = "myDB";

    /* 
     * Getter/Setter Functions
     */
    function get_dbname()
    {
        global $DBNAME;
        return $DBNAME;
    }
    function set_dbname($DB_NAME)
    {
        global $DBNAME;
        $DBNAME = $DB_NAME;
    }
    
    /*
     * Utilities Functions
     */
    function db_conn_verify($mysqli_conn)
    {
        /*
         * Check if connection has error
         * :: Returns 'bool' type
         */
        $curr_conn = $mysqli_conn;

        $valid = false;
        if(!$curr_conn->connect_error)
        {
            // Error connecting
            $valid = true;
        }
        else
        {
            echo $mysqli_conn->connect_error;
        }

        // Return validity
        return $valid;
    }

    function db_conn($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME="", $DB_PORT=0, $DB_SOCKET="")
    {
        // Make MySQL connection object
        $mysqli_conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, $DB_PORT, $DB_SOCKET);

        // Verify connection
        if($mysqli_conn->connect_error)
        {
            // Error connecting
            // echo $mysqli_conn->connect_error;
            unset($mysqli_conn);
            return $mysqli_conn->connect_error;
        }
        else
        {
            // Return Connection Object
            return $mysqli_conn;
        }
    }

    function close_db($db_conn)
    {
        // close database if not null
        if(!$db_conn === NULL)
        {
            // If connection is not empty
            $db_conn->close();
            unset($db_conn);
        }
    }

    /*
     * Verification Functionalities
     */
    function chk_db_exists($db_conn, $db_name)
    {
        $db_exists = false;
        $sql_stmt = "SHOW DATABASES LIKE '$db_name';";

        // Check if database exists       
        $check_result = $db_conn->query($sql_stmt);    // Run query to check table
        $db_exists = $check_result->num_rows >= 1;                         // Get number of rows returned : if table has more than or equals to 1 row (if 0 = Does not exist)

        return $db_exists;
    }

    function chk_table_exists($db_conn, $table_name)
    {
        $table_exists = false;
        $sql_stmt = "SHOW TABLES LIKE '$table_name';";

        // Check if database exists       
        $check_result = $db_conn->query($sql_stmt);    // Run query to check table
        $table_exists = $check_result->num_rows >= 1;                         // Get number of rows returned : if table has more than or equals to 1 row (if 0 = Does not exist)

        return $table_exists;
    }


    /*
     * CRUD Functionalities
     */
    function create_db($db_conn, $db_name, $check_if_exist=true)
    {
        /* 
         * Create Database
         */

        // Local Variables
        $sql_stmt = "CREATE DATABASE";
        $success_token = False;

        // Process if to check
        if($check_if_exist)
        {
            // If to check
            $sql_stmt .= " IF NOT EXISTS ";
        }

        // Append details
        $sql_stmt .= " $db_name ";

        // Prepare Statement for use
        if($db_conn->query($sql_stmt) === TRUE) // Check if statement is valid
        {
            // Statement is valid
            // Database is created
            // echo "Database [$db_name] has been created/already exists", "<br/>";
            $success_token = True;
        }
        /*
        else
        {
            // Database is not created
            echo "Error creating database [$db_name]";
        }
         */

        return $success_token;
    }

    function create_table($db_conn, $table_name, $column_definition)
    {
        /*
         * Create Table
         */

        // Local Variables
        $sql_stmt = "CREATE TABLE";
        $success_token = False;

        // Append Details
        $sql_stmt .= " $table_name ($column_definition); ";

        // Check if table exists       
        $table_check_result = $db_conn->query("SHOW TABLES LIKE '$table_name';");    // Run query to check table
        $table_exists = $table_check_result->num_rows >= 1;                         // Get number of rows returned : if table has more than or equals to 1 row (if 0 = Does not exist)

        // Prpare statement for use
        if(!$table_exists)
        {
            // If table doesnt exist - create
            
            if($db_conn->query($sql_stmt) === True)
            {
                // If statement is correct
                echo "table $table_name is created.", "<br/>";
                $success_token = True;
            }
            else
            {
                echo "Failed to create $table_name...", "<br/>";
            }
        }
        return $success_token;
    }
?>
