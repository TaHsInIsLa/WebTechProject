<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: project.html");
    exit();
}

if (!isset($_SESSION['selected_cities']) || empty($_SESSION['selected_cities'])) {
    echo "No cities selected.";
    exit();
}

$selectedCities = $_SESSION['selected_cities'];

$host = 'localhost';
$db = 'cities_db';  
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$placeholders = implode(',', array_fill(0, count($selectedCities), '?'));

$sql = "SELECT city_name, country_name, aqi_value FROM cities WHERE id IN ($placeholders)";

$stmt = $conn->prepare($sql);

$types = str_repeat('i', count($selectedCities));
$stmt->bind_param($types, ...$selectedCities);

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Selected Cities and AQI</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f7f9fc;
    margin: 40px;
    color: #333;
  }
  h2 {
    color: #4aa3ff;
    text-align: center;
    margin-bottom: 25px;
  }
  table {
    width: 80%;
    margin: 0 auto;
    border-collapse: collapse;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background-color: white;
  }
  th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }
  thead tr {
    background-color: #4aa3ff;
    color: white;
  }
  tbody tr:nth-child(even) {
    background-color: #f4f6f8;
  }
  tbody tr:hover {
    background-color: #cce4ff;
  }
</style>
</head>
<body>

<?php
if ($result->num_rows > 0) {
    echo "<h2>Selected Cities and AQI</h2>";
    echo "<table>";
    echo "<thead><tr><th>City</th><th>Country</th><th>AQI Value</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['city_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['country_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['aqi_value']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p style='text-align:center; color: #777;'>No data found for the selected cities.</p>";
}

$stmt->close();
$conn->close();
?>

</body>
</html>
