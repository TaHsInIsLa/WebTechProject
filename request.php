<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db = 'cities_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cities from the database
$sql = "SELECT id, city_name FROM cities LIMIT 20";
$result = $conn->query($sql);

// Handle form submission and save selected cities in session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if cities are selected
    if (isset($_POST['cities']) && !empty($_POST['cities'])) {
        $_SESSION['selected_cities'] = $_POST['cities'];  // Save selected city IDs to session
        header("Location: Showaqi.php"); // Redirect to Showaqi.php to display AQI data
        exit();
    } else {
        echo "No cities selected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Select Cities</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f4f7;
      margin: 0;
      padding: 0;
      color: #333;
    }

    h2 {
      color: #4aa3ff;
      text-align: center;
      margin-top: 50px;
    }

    .form-container {
      width: 90%;
      max-width: 600px;
      margin: 0 auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 50px;
    }

    form {
      margin-top: 20px;
    }

    label {
      font-size: 16px;
      display: block;
      margin-bottom: 10px;
      cursor: pointer;
    }

    input[type="checkbox"] {
      margin-right: 10px;
      transform: scale(1.3);
      vertical-align: middle;
    }

    button {
      display: block;
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      background-color: #4aa3ff;
      color: white;
      font-size: 18px;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #1e7fe3;
    }

    .checkbox-container {
      margin-bottom: 20px;
    }

    .checkbox-container label {
      margin-bottom: 5px;
    }

    /* Add styles for alert when more than 10 checkboxes are selected */
    .alert {
      color: red;
      font-size: 14px;
      margin-top: 10px;
    }

    .info {
      text-align: center;
      margin-top: 50px;
      color: #999;
    }
  </style>

  <script>
    // JavaScript to limit the selection to 10 cities
    function limitCheckboxes(max) {
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(chk => {
        chk.addEventListener('change', () => {
          const checkedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
          if (checkedCount > max) {
            alert('You can select up to ' + max + ' cities only.');
            chk.checked = false;
          }
        });
      });
    }

    window.onload = function() {
      limitCheckboxes(10);
    };
  </script>

</head>
<body>

<div class="form-container">
  <h2>Select up to 10 cities</h2>

  <form action="request.php" method="POST">
    <div class="checkbox-container">
      <?php
      // Display cities fetched from the database
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo '<label>';
              echo '<input type="checkbox" name="cities[]" value="'.htmlspecialchars($row['id']).'"> ';
              echo htmlspecialchars($row['city_name']);
              echo '</label>';
          }
      } else {
          echo "<p>No cities found.</p>";
      }
      ?>
    </div>
    
    <button type="submit">Submit</button>
  </form>

  <div class="info">
    <p>Feel free to select cities from the list above to see their AQI values.</p>
  </div>
</div>

</body>
</html>
