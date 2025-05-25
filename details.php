<?php
session_start();

// Redirect if no registration data
if (!isset($_SESSION['registration_data'])) {
    header("Location: project.html"); // registration form page
    exit();
}

// Handle Cancel or Confirm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        unset($_SESSION['registration_data']);
        header("Location: project.html");
        exit();
    }
    if (isset($_POST['confirm'])) {
        // Insert into database now
        $host = 'localhost';
        $db   = 'registration_db';
        $user = 'root';
        $pass = '';

        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $data = $_SESSION['registration_data'];

        $fullname = $conn->real_escape_string($data['fullname']);
        $gender = $conn->real_escape_string($data['gender']);
        $email = $conn->real_escape_string($data['email']);
        $password = $data['password']; // raw password
        $dob = $conn->real_escape_string($data['dob']);
        $country = $conn->real_escape_string($data['country']);
        $opinion = $conn->real_escape_string($data['opinion']);
        $terms = (int)$data['terms'];

        // Hash password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute insert statement
        $stmt = $conn->prepare("INSERT INTO users (fullname, gender, email, password, dob, country, opinion, terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $fullname, $gender, $email, $hashed_password, $dob, $country, $opinion, $terms);

        if ($stmt->execute()) {
            unset($_SESSION['registration_data']); // clear session after successful insert
            header("Location: project.html"); // redirect to initial page
            exit();
        } else {
            if ($conn->errno === 1062) {
                $error_msg = "This email is already registered.";
            } else {
                $error_msg = "Database error: " . $conn->error;
            }
        }

        $stmt->close();
        $conn->close();
    }
}

$data = $_SESSION['registration_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Confirm Your Registration</title>
<style>
  body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
  .details-container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
  h2 { color: #4aa3ff; }
  p { margin: 10px 0; }
  strong { color: #07387f; }
  .buttons {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
  }
  button {
    padding: 10px 25px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  .cancel-btn {
    background-color: #d9534f;
    color: white;
  }
  .cancel-btn:hover {
    background-color: #c9302c;
  }
  .confirm-btn {
    background-color: #5cb85c;
    color: white;
  }
  .confirm-btn:hover {
    background-color: #449d44;
  }
</style>
</head>
<body>

<div class="details-container">
  <h2>Confirm Your Details</h2>

  <?php if (!empty($error_msg)) echo "<p style='color:red;'>$error_msg</p>"; ?>

  <p><strong>Full Name:</strong> <?php echo htmlspecialchars($data['fullname']); ?></p>
  <p><strong>Gender:</strong> <?php echo htmlspecialchars($data['gender']); ?></p>
  <p><strong>Email:</strong> <?php echo htmlspecialchars($data['email']); ?></p>
  <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($data['dob']); ?></p>
  <p><strong>Country:</strong> <?php echo htmlspecialchars($data['country']); ?></p>
  <p><strong>Opinion:</strong> <?php echo nl2br(htmlspecialchars($data['opinion'])); ?></p>
  <p><strong>Accepted Terms:</strong> <?php echo $data['terms'] ? 'Yes' : 'No'; ?></p>

  <form method="POST" class="buttons">
    <button type="submit" name="cancel" class="cancel-btn">Cancel</button>
    <button type="submit" name="confirm" class="confirm-btn">Confirm</button>
  </form>
</div>

</body>
</html>
