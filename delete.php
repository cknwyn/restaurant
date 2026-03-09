<?php
include "db_conn.php";
if (isset($_GET['id']) && isset($_GET['type'])) {
    
    $id = (int)$_GET['id']; 
    $type = $_GET['type']; 

    if ($type === 'products') {
        $sql = "DELETE FROM products WHERE id = $id";
        $msg = "Product deleted.";
    } elseif ($type === 'menus') {
        $sql = "UPDATE menus SET DateDeleted = NOW() WHERE id = $id";
        $msg = "Menu item archived successfully.";
    } else {
        die("Error: Invalid type provided.");
    }

    // 3. Execute the query
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?msg=" . urlencode($msg));
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Error: Missing parameters.";
}
?>
