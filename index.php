<?php
include "db_conn.php";
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

    <a href="add-new-menu.php" class="btn btn-success mb-3">Add New Menu</a>

    <!-- menu table and add new menu -->
    <table class="table table-striped table-hover text-center" style="background-color: #e7ece7;">
      <thead class="table-dark">
        <tr>
          <th scope="col">Menu ID</th>
          <th scope="col">Menu Name</th>
          <th scope="col">Date Created</th>
          <th scope="col">Date Updated</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM menus WHERE DateDeleted IS NULL";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $row["ID"] ?></td>
            <td><?php echo $row["Name"] ?></td>
            <td><?php echo $row["DateCreated"] ?></td>
            <td><?php echo $row["DateUpdated"] ?></td>
            <td>
              <a href="edit-menu.php?id=<?php echo $row["ID"] ?>&type=menus" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
              <a href="delete.php?id=<?php echo $row["ID"] ?>&type=menus" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

    <br>
    <!-- product table and add new products -->
    <a href="add-new.php" class="btn btn-success mb-3">Add New Item</a>

    <table class="table table-striped table-hover text-center" style="background-color: #e7ece7;">
      <thead class="table-dark">
        <tr>
          <th scope="col">Product ID</th>
          <th scope="col">Product Name</th>
          <th scope="col">Price</th>
          <th scope="col">Image Path</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM `products`";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo isset($row["ID"]) ? $row["ID"] : ''; ?></td>
            <td><?php echo isset($row["name"]) ? $row["name"] : ''; ?></td>
            <td><?php echo isset($row["price"]) ? $row["price"] : ''; ?></td>
            <td><?php echo isset($row["ImagePath"]) ? $row["ImagePath"] : ''; ?></td>
            <td>
              <a href="edit.php?id=<?php echo $row["ID"] ?>&type=products" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
              <a href="delete.php?id=<?php echo $row["ID"] ?>&type=products" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

    <br>
    <a href="add-new-menuproducts.php" class="btn btn-success mb-3">Add New Menu Products</a>
    
    <table class="table table-striped table-hover text-center" style="background-color: #e7ece7;">
      <thead class="table-dark">
        <tr>
          <th scope="col">Menu Name</th>
          <th scope="col">Product Name</th>
          <th scope="col">Price</th>
          <th scope="col">Image Path</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT mp.menuID, mp.productID, m.Name as menu_name, p.name as product_name, p.price, p.ImagePath 
                FROM `menuproducts` mp 
                JOIN `menus` m ON mp.menuID = m.ID AND m.DateDeleted IS NULL
                JOIN `products` p ON mp.productID = p.ID 
                ORDER BY m.Name, p.name";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $row["menu_name"] ?></td>
            <td><?php echo $row["product_name"] ?></td>
            <td><?php echo $row["price"] ?></td>
            <td><?php echo $row["ImagePath"] ?></td>
            <td>
              <a href="edit-menu-products.php?menuID=<?php echo $row["menuID"] ?>&productID=<?php echo $row["productID"] ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
              <a href="delete.php?menuID=<?php echo $row["menuID"] ?>&productID=<?php echo $row["productID"] ?>&type=menuproducts" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>