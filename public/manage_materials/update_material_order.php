<?php
// Include the connection file
require_once('../../database/connection.php');

// Retrieve materials data based on the subject or your specific query
$subjectID = 1; // Replace with the appropriate subject ID
$materialQuery = "SELECT MaterialID, Sequence FROM Materials WHERE SubjectID = $subjectID ORDER BY Sequence";
$materialResult = $conn->query($materialQuery);

$materialsData = array();
if ($materialResult->num_rows > 0) {
  while ($materialRow = $materialResult->fetch_assoc()) {
    $materialsData[] = $materialRow;
  }
}

if (isset($_POST['materialID']) && isset($_POST['direction'])) {
  $materialID = $_POST['materialID'];
  $direction = $_POST['direction'];

  // Get the current material sequence
  $query = "SELECT Sequence FROM Materials WHERE MaterialID = $materialID";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentSequence = $row['Sequence'];

    // Calculate the new sequence
    if ($direction === 'up') {
      // If it's not the first material, swap with the previous one
      if ($currentSequence > 1) {
        $previousMaterialID = $materialsData[$currentSequence - 2]['MaterialID'];
        $newSequence = $currentSequence - 1;
        $updateQuery = "UPDATE Materials SET Sequence = $newSequence WHERE MaterialID = $materialID";
        $conn->query($updateQuery);
        $updateQuery = "UPDATE Materials SET Sequence = $currentSequence WHERE MaterialID = $previousMaterialID";
        $conn->query($updateQuery);
        echo "success";
      } else {
        echo "Material is already at the top.";
      }
    } else {
      // If it's not the last material, swap with the next one
      if ($currentSequence < count($materialsData)) {
        $nextMaterialID = $materialsData[$currentSequence]['MaterialID'];
        $newSequence = $currentSequence + 1;
        $updateQuery = "UPDATE Materials SET Sequence = $newSequence WHERE MaterialID = $materialID";
        $conn->query($updateQuery);
        $updateQuery = "UPDATE Materials SET Sequence = $currentSequence WHERE MaterialID = $nextMaterialID";
        $conn->query($updateQuery);
        echo "success";
      } else {
        echo "Material is already at the bottom.";
      }
    }
  }
}
