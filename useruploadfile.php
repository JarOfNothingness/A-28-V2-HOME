<?php
session_start();
include("../LoginRegisterAuthentication/connection.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid']; // Assuming you store the user's ID in the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folder_id = $_POST['folder_id'];
    $file = $_FILES['file_upload'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_name = $file['name'];
        $file_path = 'uploads/' . basename($file_name);

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insert file info into the database without userid
            $query = "INSERT INTO userfileserverfiles (folder_id, file_name, file_path, uploaded_at) VALUES (?, ?, ?, NOW())";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("iss", $folder_id, $file_name, $file_path);
            if ($stmt->execute()) {
                header("Location: fileserver.php");
                exit();
            } else {
                die("Failed to upload file: " . $stmt->error);
            }
        } else {
            die("Failed to move uploaded file.");
        }
    } else {
        die("File upload error: " . $file['error']);
    }
}
?>
