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
// Process the result set
if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    $name = $row["Name"];
    $price = $row["Price"];
    $ImagePath = $row["ImagePath"];

    echo "<div class='product'>" . "<img src=$ImagePath width='200px' height='200px'>" . '<br>' . $name . " " . '<br>' . $price . "</div>";
    }
} else {
  echo "0 results";
}
echo "</div>";
$conn->close();
?>

<style>
    .container {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }
</style>