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
  </head>

  <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #c9b02573;"><b>Potato Corner Menu List</b></nav>
  <?php
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT ID, Name, Price, ImagePath FROM products";
  // Execute the SQL query
  $result = $conn->query($sql);

  echo "<div class='container'>";
echo "  <div class='row row-cols-1 row-cols-md-4 g-4'>";

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
    echo "<p class='text-center'>0 results</p>";
}

echo "  </div>"; // End row
echo "</div>"; // End container
  $conn->close();
  ?>
</html>