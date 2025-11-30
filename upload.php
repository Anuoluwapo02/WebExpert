<?php
// upload.php: Handle Project Upload with Robust Error Handling
session_start();
include_once 'config.php'; // Correct path

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $demo_link = trim($_POST['demo_link']);
    $technologies = trim($_POST['technologies'] ?? '');
    $github_link = trim($_POST['github_link'] ?? '');

    // Validate inputs
    if (empty($title) || empty($description) || empty($demo_link)) {
        die("All fields (Title, Description, Demo Link) are required.");
    }

    // Handle image upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true) or die("Failed to create uploads directory.");
    }

    $original_filename = basename($_FILES["image"]["name"]);
    $timestamp = date('Y-m-d_H-i-s'); // ISO-like format with hyphens
    $filename = $timestamp . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $original_filename);
    $target_file = $target_dir . $filename;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is valid
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        die("File is not a valid image.");
    }
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        die("Only JPG, PNG, JPEG, and GIF files are allowed.");
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Debug: Confirm file location
        echo "<p>File uploaded to: " . $target_file . "</p>";

        // Insert into database with web-relative path
        $web_path = '' . $filename; // Adjusted for XAMPP subdirectory
        try {
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, demo_link, image_path, technologies, github_link) VALUES (:title, :description, :demo_link, :image_path, :technologies, :github_link)");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'demo_link' => $demo_link,
                'image_path' => $web_path,
                'technologies' => $technologies,
                'github_link' => $github_link
            ]);
            header('Location: dashboard.php'); // Updated redirect
            exit;
        } catch (PDOException $e) {
            die("Database insert failed: " . $e->getMessage());
        }
    } else {
        $error = $_FILES["image"]["error"];
        die("Error uploading file. Error code: " . $error . " (See: https://www.php.net/manual/en/features.file-upload.errors.php)");
    }
}
?>