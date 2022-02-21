<?php
    /*
     * Logout Processing Page
     */

    // Clear all sessions
    if(isset($_SESSION["role"]))
    {
        $_SESSION["role"] = "";
        session_unset();
        session_destroy();
    }

    // Redirect to index page
    header("Location: index.php");
?>
