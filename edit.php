<?php
include "db_conn.php";
$id = $_GET["id"];

if (isset($_POST["submit"])) {
  if (empty($_POST["name"]) || empty($_POST["price"]) || empty($_POST["ImagePath"])) {
      header("Location: edit.php?id=$id&msg=Please fill in all fields");
      return;
  }

  if (!is_numeric($_POST["price"])) {
      header("Location: edit.php?id=$id&msg=Price must be a number");
      return;
  }

  if (!preg_match("/^[a-zA-Z\s]+$/", $_POST["name"])) {
      header("Location: edit.php?id=$id&msg=Name must only contain letters and spaces");
      return;
  }

  $name = $_POST['name'];
  $price = $_POST['price'];
  $ImagePath = $_POST['ImagePath'];

  $sql = "UPDATE `products` SET `name`='$name',`price`='$price',`ImagePath`='$ImagePath' WHERE ID = $id";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    header("Location: index.php?msg=Data updated successfully");
  } else {
    echo "Failed: " . mysqli_error($conn);
  }
}
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
      <h3>Edit Product Information</h3>
      <p class="text-muted">Click update after changing any information</p>
    </div>

    <?php
    $sql = "SELECT * FROM `products` WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>

    <div class="container d-flex justify-content-center">
      <form action="" method="post" style="width:50vw; min-width:300px;">
        <div class="row mb-3">
          <div class="col">
            <label class="form-label">Name:</label>
            <input type="text" class="form-control" name="name" value="<?php echo $row['name'] ?>">
          </div>

          <div class="col">
            <label class="form-label">Price:</label>
            <input type="text" class="form-control" name="price" value="<?php echo $row['price'] ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Image Path:</label>
          <input type="text" class="form-control" name="ImagePath" value="<?php echo $row['ImagePath'] ?>">
        </div>

        <div>
          <button type="submit" class="btn btn-success" name="submit">Update</button>
          <a href="index.php" class="btn btn-danger">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>