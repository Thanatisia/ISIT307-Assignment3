<!--
    Extend Rental Period
-->
<?php
    // Start session for use
    session_start();

    // Include external libraries for use
    require_once("./assets/scripts/mysqli_conn.php");
    require_once("./assets/scripts/extlib.php");

    /*
     * Check If user clicked on 'Extend button'
     */
    if(isset($_POST['Extend']))
    {
        /*
         * Get $_POST values
         */
        if(
            isset($_POST["prod_id"]) && 
            isset($_POST['extension_period'])
        )
        {
            $prod_id = $_POST["prod_id"];
            $ext_Period = $_POST["extension_period"];
            $ext_Cost = 0;
            $msg = "";

            /*
             * Open Database 
             * - Open Table 'rentals'
             * - Update column 'rent_extended_period' with $ext_Period
             * - Update column 'rent_extended_cost' with $ext_Cost
             */

            // Connect to database
            $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

            // Try to connect to database
            $conn_verify = db_conn_verify($conn);

            if($conn_verify)
            {
                /*
                 * Database opened successfully
                 * - Check table
                 */

                // Get Extension Cost Reference from 'products'
                $sql_stmt = "SELECT * FROM products WHERE prod_ID = '$prod_id'";
                $res = $conn->query($sql_stmt);
                $size = $res->num_rows;
                $row = $res->fetch_assoc();
                // Calculate Extension Cost
                if($size > 0)
                {
                    $rent_extended_cost_per_day = $row["prod_extended_cost_per_day"];
                }
                else
                {
                    echo "<script>alert('Product $prod_id not found');</script>";
                }

                $sql_stmt = "SELECT * FROM rentals WHERE prod_ID = '$prod_id' AND rent_Status = 'Rented'";
                $res = $conn->query($sql_stmt);
                $size = $res->num_rows;
                $row = $res->fetch_assoc();

                $rent_start_date = $row["rent_start_date"];
                $rent_period_regular = $row["rent_regular_period"];
                $rent_period_extended = $row["rent_extended_period"];
                $rent_cost_regular = $row["rent_regular_cost"];
                $rent_cost_extended = $row["rent_extended_cost"];

                // Extension
                $ext_Cost = $rent_cost_extended + ($rent_extended_cost_per_day * $ext_Period);
                $new_Date = strtotime($rent_start_date);
                $new_Date = strtotime("+$rent_period_regular day", $new_Date);
                $new_Date = strtotime("+$rent_period_extended day", $new_Date);
                $new_Date = date("Y-m-d", $new_Date);

                $table_exists = chk_table_exists($conn, "rentals");
                if($table_exists)
                {
                    /*
                     * Table exists
                     */

                    // Update 
                    //  column 'rent_extended_period' with $ext_Period AND
                    //  column 'rent_extended_cost' with $ext_Cost
                    $new_rent_period_extended = $ext_Period + $rent_period_extended;
                    $condition = "prod_ID = '$prod_id' AND rent_Status = 'Rented' AND client_ID = '" . $_SESSION["userID"] . "'";
                    $sql_stmt = "UPDATE rentals SET rent_extended_period = '" . $new_rent_period_extended . "', rent_extended_cost = $ext_Cost WHERE $condition";

                    // Try and Query
                    if($conn->query($sql_stmt) === True)
                    {
                        echo "<script>alert('Product deadline has been extended by $ext_Period days. The next expiry date will be $new_Date. New Total Rent Cost (Extended) is : $ext_Cost');</script>";
                        echo "<script>alert('Overall total is " . $rent_cost_regular + $ext_Cost . "');</script>";
                        $msg =  "Product deadline has been extended by $ext_Period days." . "<br/>" . 
                                "The next expiry date will be $new_Date.". "<br/>" .
                                "The New Total Rent Cost (Extended) is : $ext_Cost" . "<br/>" .
                                "Overall total is now : " . $rent_cost_regular + $ext_Cost;
                    }
                } 

                // Close Database after use
                close_db($conn);
            }
        }
    }

?>
<html>
	<head>
		<title>Index Page</title>
	</head>

    <body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <hr/>

        <div>
            <?php     
                if(!$msg == "")
                {
                    echo $msg;
                }
            ?>
        </div>

        <?php
            // Check if records found
            $conn = db_conn(DBHOST, DBUSER, DBPASS, $DBNAME);

            $verify_conn = db_conn_verify($conn);

            if($verify_conn)
            {
                // Database connected
                $sql_stmt = "SELECT * FROM rentals WHERE client_ID = '" . $_SESSION["userID"] . "' AND rent_Status = 'Rented'";
                $ret = stmt_exec($conn, $sql_stmt);
                $size = $ret['size'];
                $rows = $ret['result'];

                if($size > 0)
                {
                    // Data is found
        ?>
                    <div>
                        <p><b><u>Rental Extension</u></b></p>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <p>Product ID : <input type="text" name="prod_id" value="<?php if(isset($_POST['prod_id'])) echo $_POST['prod_id']; ?>" placeholder="yyyy-mm-dd"></p>
                            <p>Period to extend : <input type="text" name="extension_period" value="<?php if(isset($_POST['extension_period'])) echo $_POST['extension_period']; ?>" placeholder="30"></p>
                            <input type="submit" name="Extend" value="Extend Rent Period">
                        </form>
                    </div>
        <?php
                }
                else
                {
                    echo "No Rentals found.";
                }
            }

            // Close Database after use
            close_db($conn);
        ?>

        <hr/>

        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

