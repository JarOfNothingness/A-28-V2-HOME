<?php
include("../LoginRegisterAuthentication/connection.php");

// Get folder ID from GET parameters
$folder_id = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;

// Query to get files in the folder
$query = "SELECT file_id, file_name, file_path FROM fileserver_files WHERE folder_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$result = $stmt->get_result();

// Display files with download and delete options
echo "<h2>Files in this folder</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<a href='" . htmlspecialchars($row['file_path']) . "' download>" . htmlspecialchars($row['file_name']) . "</a>";

    // Form to delete file
    echo "<form action='deletefile.php' method='POST' style='display:inline;'>
            <input type='hidden' name='file_id' value='" . htmlspecialchars($row['file_id']) . "'>
            <input type='submit' value='Delete' onclick='return confirm(\"Are you sure you want to delete this file?\");'>
          </form><br>";
}

// Display folder navigation form
echo "<h3>Open another folder</h3>";
echo "<form action='viewfolder.php' method='GET'>
        <label for='folder_id_input'>Folder ID:</label>
        <input type='number' id='folder_id_input' name='folder_id' required>
        <input type='submit' value='Open Folder'>
      </form>";

// Close the statement and connection
$stmt->close();
$connection->close();
?>
