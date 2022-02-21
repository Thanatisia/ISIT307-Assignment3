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

    function stmt_exec($db_conn, $sql_stmt)
    {
        /*
         * Execute Query Statement
         *
         * :: Params
         *      $db_conn : Database Connection Object
         *      $sql_stmt : The SQL Statement to execute
         * :: Returns
         *      Type: Array
         *      Structure:
         *          array(
         *              'return_code' => type(int),
         *              'size' => type(int),
         *              'result' => type(string) OR         // IF there's no result
         *                       => type(array)             // If there are results
         *          )
         */
        //$exec_result = false;
        $return_val = array(
            'return_code' => 0,         // Return Code
            'size' => 0,                // Number of Changes
            'result' => ""              // Returned value (if any)
        );

        // Prepare Statement for use
        
        // Check if statement is valid
        $return_val['return_code'] = $db_conn->query($sql_stmt);

        // Get Type
        $query_res_type = gettype($return_val['return_code']);

        if($query_res_type == "array")
        {
            /*
             * Array result
             */
            // Statement is valid
            $return_val['size'] = $return_val['return_code']->num_rows;

            // Get Result
            $i = 0;                             // Counter for row number
            $return_val["result"] = array();    // If there are data to store - convert to array to store data
            while($row = $return_val['return_code']->fetch_assoc())
            {
                $return_val["result"][$i] = $row;
                $i++;
            }
        }
        
        return $return_val;
    }

    function get_row($db_conn, $table_name, $column, $condition)
    {
        /*
         * Get a cell value from a row in a column according to the condition
         */
        $sql_stmt = "SELECT $column FROM $table_name WHERE $condition";

        // Prepare and execute query
        $result = $db_conn->query($sql_stmt);

        // Get Number of rows returned
        // $row_count = $result->num_rows;

        $return_val = $result->fetch_assoc();

        return $return_val;
    }

    function get_value($db_conn, $table_name, $column, $condition)
    {
        $ret_val = "";
        $sql_stmt = "SELECT * FROM $table_name WHERE $condition";
        
        // Query and retrieve rows
        $result = $db_conn->query($sql_stmt);

        // Count number of rows affected
        $count = $result->num_rows;

        // Get all results
        $row = $result->fetch_assoc();

        // Check if records are found
        if($count > 0)
        {
            // Records Found
            $ret_val = $row[$column];
        }
        return $ret_val;
    }

    /*
     * Verification Functionalities
     */
    function chk_db_exists($db_conn, $db_name)
    {
        /*
         * Check if database exists
         */
        $db_exists = false;
        $sql_stmt = "SHOW DATABASES LIKE '$db_name';";

        // Check if database exists       
        $check_result = $db_conn->query($sql_stmt);    // Run query to check table
        $db_exists = $check_result->num_rows >= 1;                         // Get number of rows returned : if table has more than or equals to 1 row (if 0 = Does not exist)

        return $db_exists;
    }

    function chk_table_exists($db_conn, $table_name)
    {
        /*
         * Check if table exists in database
         */
        $table_exists = false;
        $sql_stmt = "SHOW TABLES LIKE '$table_name';";

        // Check if table exists       
        $check_result = $db_conn->query($sql_stmt);    // Run query to check table
        $table_exists = $check_result->num_rows >= 1;                         // Get number of rows returned : if table has more than or equals to 1 row (if 0 = Does not exist)

        return $table_exists;
    }

    function chk_record_exists($db_conn, $table_name, $column, $condition)
    {
        /*
         * Check if record element exists
         */
        $sql_stmt = "SELECT $column FROM $table_name WHERE $condition";
        $row_exist = false;

        // Prepare and execute query
        $result = $db_conn->query($sql_stmt);

        // Get number of rows returned
        $row_count = $result->num_rows; 

        if($row_count >= 1)
        {
            $row_exist = true;
        }
        return $row_exist;
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
                //echo "table $table_name is created.", "<br/>";
                $success_token = True;
            }
            //else
            //{
                //echo "Failed to create $table_name...", "<br/>";
            //}
        }
        return $success_token;
    }

    function insert_row($db_conn, $table_name, $columns, $values, $unique)
    {
        /*
         * Insert row into table
            $sql_stmt = "INSERT INTO users (username, password) VALUES (" . 
                "'" . $_POST["username"] . "', " . 
                "'" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "'" .
                ");";
         */
        $sql_stmt = "INSERT INTO $table_name ($columns) VALUES ($values)";
        //$success_token = false;

        // Append details
        
        if($unique)
        {
            // Check if row is unique                
        }

        $result = $db_conn->query($sql_stmt);
        return $result; 
    }
?>
