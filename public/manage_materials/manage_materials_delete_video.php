<?php
session_start();
require_once('../../database/connection.php');

if (!isset($_SESSION['UserID'])) {
    header("Location: ../systems/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $materialID = $_GET['id'];

    // Retrieve video link from the database
    $query = "SELECT Video FROM Materials WHERE MaterialID = $materialID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $videoLink = "../" . $row['Video']; // Path to video file

        // Delete video file from server
        if (unlink($videoLink)) {
            // Update database record to set video link to NULL
            $updateQuery = "UPDATE Materials SET Video = NULL WHERE MaterialID = $materialID";
            if ($conn->query($updateQuery) === TRUE) {
                header("Location: manage_materials_list.php");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Error deleting video file";
        }
    } else {
        echo "Material not found";
    }
} else {
    echo "Material ID not provided";
}
?>
