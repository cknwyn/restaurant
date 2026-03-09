<?php
include "db_conn.php";

if (isset($_POST["submit"])) {
    if (empty($_POST["menu_id"])) {
        header("Location: add-new-menuproducts.php?msg=Please select a menu");
        return;
    }

    if (empty($_POST["product_id"])) {
        header("Location: add-new-menuproducts.php?msg=Please select a product");
        return;
    }

    $menu_id = $_POST['menu_id'];
    $product_id = $_POST['product_id'];

    // Check if this menu-product combination already exists
    $check_sql = "SELECT * FROM `menuproducts` WHERE menuID='$menu_id' AND productID='$product_id'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        header("Location: add-new-menuproducts.php?msg=This product is already associated with this menu");
        return;
    }

    $sql = "INSERT INTO `menuproducts`(`menuID`, `productID`) VALUES ('$menu_id','$product_id')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: index.php?msg=New menu product created successfully");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}

// Get all menus for dropdown
$sql_menus = "SELECT ID, name FROM `menus` WHERE DateDeleted IS NULL";
$result_menus = mysqli_query($conn, $sql_menus);

// Get all products for dropdown
$sql_products = "SELECT ID, name FROM `products`";
$result_products = mysqli_query($conn, $sql_products);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>PHP CRUD Application</title>
</head>

<body>
    <nav class="navbar justify-content-center text-white fs-3 mb-5" style="background-color: #04b31e;">
        <a href="display_index.php"><img src="images/Logo-Stacked-Mascot.png" class="ms-3 me-3" width="125" height="50" alt="Potato Corner Logo"></a>
        Potato Corner Menu CRUD Application
    </nav>

    <div class="container">
        <?php
        if (isset($_GET["msg"])) {
            $msg = $_GET["msg"];
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            ' . $msg . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        ?>

        <div class="text-center mb-4">
            <h3>Add New Menu Product</h3>
            <p class="text-muted">Complete the form below to add a new product</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Select Menu:</label>
                        <select class="form-control" name="menu_id" required>
                            <option value="">-- Choose a menu --</option>
                            <?php while ($menu = mysqli_fetch_assoc($result_menus)) { ?>
                                <option value="<?php echo $menu['ID']; ?>"><?php echo $menu['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Select Product:</label>
                        <select class="form-control" name="product_id" required>
                            <option value="">-- Choose a product --</option>
                            <?php while ($product = mysqli_fetch_assoc($result_products)) { ?>
                                <option value="<?php echo $product['ID']; ?>"><?php echo $product['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>
