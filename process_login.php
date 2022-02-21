<?php
    /*
     * Login Processing Page
     */
    require("mysqli_conn.php");     // Import MySQLi details

    // Make connection
    $conn = db_conn($DBHOST, $DBUSER, $DBPASS, $DBNAME);

    // Close connection after use
    $conn->close();
?>
