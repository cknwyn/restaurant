<?php
include "db_conn.php";
if (isset($_GET['type'])) {
    $type = $_GET['type'];

    // products and menus use single id parameter
    if ($type === 'products' || $type === 'menus') {
        if (!isset($_GET['id'])) {
            die("Error: Missing id parameter.");
        }
        $id = (int)$_GET['id'];
    }

    if ($type === 'products') {
        $sql = "DELETE FROM products WHERE id = $id";
        $msg = "Product deleted.";
    } elseif ($type === 'menus') {
        // soft-delete menu
        $sql = "UPDATE menus SET DateDeleted = NOW() WHERE id = $id";
        $msg = "Menu item archived successfully.";
        // remove any associated menuproducts (hard delete)
        $cleanup = "DELETE FROM menuproducts WHERE menuID = $id";
        mysqli_query($conn, $cleanup);
    } elseif ($type === 'menuproducts') {
        $menuID = isset($_GET['menuID']) ? (int)$_GET['menuID'] : null;
        $productID = isset($_GET['productID']) ? (int)$_GET['productID'] : null;

        if ($menuID && $productID) {
            $sql = "DELETE FROM menuproducts WHERE menuID = $menuID AND productID = $productID";
            $msg = "Menu product association deleted.";
        } else {
            die("Error: Missing menuID or productID for menuproducts deletion.");
        }
    } else {
        die("Error: Invalid type provided.");
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=" . urlencode($msg));
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Error: Missing parameters.";
}
?>
