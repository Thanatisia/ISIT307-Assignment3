<!--
    Page to add Products into database
        - Insert products into database

    - Admin to include:
        - ID
        - Category (Laptop | Router | Modem...)
        - Brand
        - Description
        - Status (Default: Available)
        - Regular Cost Per Day
        - Extended Cost Per Day (For Extended Renting)
-->
<?php
    // Start Session for use
    session_start();

    // Handle Form submissions
    if(isset($_POST["Add"]))
    {
        // Submit (Add) button is pressed

        $message = "<ul>";    // For Displaying
        $chk_valid_prodID = False;
        $chk_valid_prodCategory = False;
        $chk_valid_prodBrand = False;
        $chk_valid_prodDesc = False;
        $chk_valid_regularCostPerDay = False;
        $chk_valid_extendedCostPerDay = False;

        // Check for empty values
        if(!strlen($_POST['prodID']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter the Product ID</li>";
        }
        else
        {
            $chk_valid_prodID = True;
        }

        if(!strlen($_POST['prodCategory']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter the Product Category</li>";
        }
        else
        {
            $chk_valid_prodCategory = True;
        }

        if(!strlen($_POST['prodBrand']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter the Product Brand</li>";
        }
        else
        {
            $chk_valid_prodBrand = True;
        }

        if(!strlen($_POST['prodDesc']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter the Product Description</li>";
        }
        else
        {
            $chk_valid_prodDesc = True;
        }

        if(!strlen($_POST['prodRegCostPerDay']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter the Regular Cost Per Day</li>";
        }
        else
        {
            $chk_valid_regularCostPerDay = True;
        }

        if(!strlen($_POST['prodExtCostPerDay']) > 0)
        {
            // Empty Value
            $message .= "<li>You forgot to enter the Extended Cost Per Day (For Extended Renting)</li>";
        }
        else
        {
            $chk_valid_extendedCostPerDay = True;
        }

        $message .= "</ul>";

        // Check for errors
        $errors_Found = True;
        if($chk_valid_prodID && $chk_valid_prodCategory && $chk_valid_prodBrand && $chk_valid_prodDesc && $chk_valid_regularCostPerDay && $chk_valid_extendedCostPerDay)
        {
            // All OK
            $errors_Found = False; 
            // header("refresh: 0, url=process_register.php");
            header("Location: ./process_equipment_insert.php", TRUE, 307);    // True and permission 307 will keep $_POST data while redirecting
        }
    }

?>

<html>
	<head>
		<title>Products Rent</title>
	</head>

    <body>
        <?php include("./header.inc.php"); ?> <!-- Include Header -->

        <hr/>

        <h1>InfoTech Services</h1>

        <?php
            // Client Validation
            if($role !== "admin")
            {
                echo "<script>alert('Error : You are not permitted to enter this page');</script>";
                header("refresh: 0, url=index.php");
            }
        ?>

        <div>
            <p><b><u>Add Product</u></b></p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <p>Product ID                      : <input type="text"    name="prodID"               value="<?php if(isset($_POST['prodID'])) echo $_POST['prodID']; ?>"                         placeholder="yyyy-mm-dd"></p>
                <p>Product Category                : <input type="text"    name="prodCategory"         value="<?php if(isset($_POST['prodCategory'])) echo $_POST['prodCategory']; ?>"             placeholder="Laptop | Router | Modem..."></p>
                <p>Product Brand                   : <input type="text"    name="prodBrand"            value="<?php if(isset($_POST['prodBrand'])) echo $_POST['prodBrand']; ?>"                   placeholder="Samsung | Mitsubishi..."></p>
                <p>Product Description             : <input type="text"    name="prodDesc"             value="<?php if(isset($_POST['prodDesc'])) echo $_POST['prodDesc']; ?>"                     placeholder="This product is a [Brand] [Category]. It's Product ID is [ID]"></p>
                <p>Product Regular Cost Per Day    : <input type="number"  name="prodRegCostPerDay"    value="<?php if(isset($_POST['prodRegCostPerDay'])) echo $_POST['prodRegCostPerDay']; ?>"   placeholder="500.00"></p>
                <p>Product Extended Cost Per Day   : <input type="number"  name="prodExtCostPerDay"    value="<?php if(isset($_POST['prodExtCostPerDay'])) echo $_POST['prodExtCostPerDay']; ?>"   placeholder="500.00"></p>
                <input type="submit" name="Add" value="Add Product">
            </form>
        </div>

        <hr/>
        
        <?php include("./footer.inc.php"); ?> <!-- Include Footer -->
	</body>
</html>

