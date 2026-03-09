<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>

  <nav class="navbar d-flex justify-content-between fs-3 mb-5 text-white" style="background-color: #04b31e;">
    <div class="d-flex align-items-center">
      <img src="images/Logo-Stacked-Mascot.png" class="ms-3 me-3" width="125" height="50" alt="Potato Corner Logo">
      Potato Corner Menu List
    </div>
    <a class="navbar-brand" href="index.php">
      <button class="btn btn-outline-light">Manage&nbsp<i class="fa-solid fa-plus fs-5"></i></button>
    </a>
  </nav>

  <?php
  // Get selected menu from URL parameter or default to first menu
  $selected_menu = isset($_GET['menu']) ? $_GET['menu'] : null;
  
  // Get all menus for dropdown
  $menu_sql = "SELECT ID, Name FROM menus WHERE DateDeleted IS NULL ORDER BY Name";
  $menu_result = $conn->query($menu_sql);
  
  // If no menu selected, get the first menu
  if (!$selected_menu && $menu_result->num_rows > 0) {
    $first_menu = $menu_result->fetch_assoc();
    $selected_menu = $first_menu['ID'];
    $menu_result->data_seek(0); // Reset result pointer
  }
  ?>

  <div class="container mt-4">
    <div class="row justify-content-center mb-4">
      <div class="col-md-6">
        <form method="GET" action="display_index.php">
          <div class="input-group">
            <label class="input-group-text" for="menu">Select Menu:</label>
            <select class="form-select" id="menu" name="menu" onchange="this.form.submit()">
              <?php
              if ($menu_result->num_rows > 0) {
                while($menu_row = $menu_result->fetch_assoc()) {
                  $selected = ($menu_row["ID"] == $selected_menu) ? "selected" : "";
                  echo "<option value='" . $menu_row["ID"] . "' $selected>" . $menu_row["Name"] . "</option>";
                }
              }
              ?>
            </select>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php
  // Query to get products for the selected menu
  if ($selected_menu) {
    $sql = "SELECT p.ID, p.Name, p.Price, p.ImagePath 
            FROM products p 
            JOIN menuproducts mp ON p.ID = mp.productID 
            WHERE mp.menuID = $selected_menu 
            ORDER BY p.Name";
  } else {
    // Fallback: show all products if no menu selected
    $sql = "SELECT ID, Name, Price, ImagePath FROM products ORDER BY Name";
  }
  ?>

  <?php
  // Query to get products for the selected menu
  if ($selected_menu) {
    $sql = "SELECT p.ID, p.Name, p.Price, p.ImagePath 
            FROM products p 
            JOIN menuproducts mp ON p.ID = mp.productID 
            WHERE mp.menuID = $selected_menu 
            ORDER BY p.Name";
  } else {
    // Fallback: show all products if no menu selected
    $sql = "SELECT ID, Name, Price, ImagePath FROM products ORDER BY Name";
  }
  
  // Execute the SQL query
  $result = $conn->query($sql);

  // Get menu name for display
  $menu_name_sql = "SELECT Name FROM menus WHERE ID = $selected_menu";
  $menu_name_result = $conn->query($menu_name_sql);
  $menu_name = $menu_name_result->fetch_assoc()['Name'] ?? 'All Products';
  ?>

  <div class="container">
    <div class="text-center mb-4">
      <h2><?php echo htmlspecialchars($menu_name); ?> Menu</h2>
    </div>

    <div class='row row-cols-1 row-cols-md-2 g-4'>

  <?php
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $name = $row["Name"];
          $price = $row["Price"];
          $ImagePath = $row["ImagePath"];

          echo "
          <div class='col'>
              <div class='card h-100'>
                  <img src='$ImagePath' class='card-img-top' style='height: 300px; object-fit: cover;' alt='$name'>
                  <div class='card-body text-center'>
                      <h5 class='card-title'>$name</h5>
                      <p class='card-text text-success fw-bold'>₱$price</p>
                  </div>
              </div>
          </div>";
      }
  } else {
      echo "<div class='col-12'><p class='text-center'>No products found for this menu.</p></div>";
  }

  echo "  </div>"; // End row
  echo "</div>"; // End container
  $conn->close();
  ?>
</html>