<?php
include "db_conn.php";

if (isset($_GET["menuID"]) && isset($_GET["productID"])) {
    $menuID = (int)$_GET["menuID"];
    $productID = (int)$_GET["productID"];
} else {
    header("Location: index.php?msg=Invalid parameters");
    exit();
}

if (isset($_POST["submit"])) {
    $new_menuID = $_POST['menu_id'];
    $new_productID = $_POST['product_id'];

    // Check if the new combination already exists (excluding current one)
    $check_sql = "SELECT * FROM `menuproducts` WHERE menuID='$new_menuID' AND productID='$new_productID' AND NOT (menuID='$menuID' AND productID='$productID')";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: edit-menu-products.php?menuID=$menuID&productID=$productID&msg=This menu-product association already exists");
        exit();
    }

    // Update the association
    $sql = "UPDATE `menuproducts` SET menuID='$new_menuID', productID='$new_productID' WHERE menuID='$menuID' AND productID='$productID'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: index.php?msg=Menu product association updated successfully");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
    exit();
}

// Get current association data
$sql = "SELECT mp.menuID, mp.productID, m.Name as menu_name, p.name as product_name
        FROM `menuproducts` mp
        JOIN `menus` m ON mp.menuID = m.ID
        JOIN `products` p ON mp.productID = p.ID
        WHERE mp.menuID = $menuID AND mp.productID = $productID";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: index.php?msg=Menu product association not found");
    exit();
}

// Get all menus for dropdown
$sql_menus = "SELECT ID, Name FROM `menus` WHERE DateDeleted IS NULL ORDER BY Name";
$result_menus = mysqli_query($conn, $sql_menus);

// Get all products for dropdown
$sql_products = "SELECT ID, name FROM `products` ORDER BY name";
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
        <a class="navbar-brand" href="display_index.php"><i class="text-white fa-solid fa-home fs-5"></i></a>
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
            <h3>Edit Menu Product Association</h3>
            <p class="text-muted">Change the menu or product for this association</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Select Menu:</label>
                        <select class="form-control" name="menu_id" required>
                            <option value="">-- Choose a menu --</option>
                            <?php
                            while ($menu = mysqli_fetch_assoc($result_menus)) {
                                $selected = ($menu['ID'] == $row['menuID']) ? "selected" : "";
                                echo "<option value='" . $menu['ID'] . "' $selected>" . $menu['Name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Select Product:</label>
                        <select class="form-control" name="product_id" required>
                            <option value="">-- Choose a product --</option>
                            <?php
                            while ($product = mysqli_fetch_assoc($result_products)) {
                                $selected = ($product['ID'] == $row['productID']) ? "selected" : "";
                                echo "<option value='" . $product['ID'] . "' $selected>" . $product['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Current Association:</strong><br>
                    Menu: <?php echo htmlspecialchars($row['menu_name']); ?><br>
                    Product: <?php echo htmlspecialchars($row['product_name']); ?>
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Update Association</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>
