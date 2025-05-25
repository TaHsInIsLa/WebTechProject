<?php
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if cities are selected
    if (isset($_POST['cities']) && !empty($_POST['cities'])) {
        // Store the selected cities in session
        $_SESSION['selected_cities'] = $_POST['cities'];

        // Redirect to another page or just confirm the selection
        header("Location: Showaqi.php");
        exit();
    } else {
        echo "No cities selected.";
    }
}