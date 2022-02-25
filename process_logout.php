<?php
    /*
     * Logout Processing Page
     */

    // Start session to use
    session_start();

    // Clear all sessions
    if(isset($_SESSION["role"]) && isset($_SESSION["username"]))
    {
        $_SESSION["role"] = "";
        $_SESSION["username"] = "";
        $_SESSION["prod_id"] = "";
        session_unset();
        session_destroy();
    }

    // Redirect to index page
    header("Location: index.php");
?>
