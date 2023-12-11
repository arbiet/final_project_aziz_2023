<?php
require_once('../../database/connection.php');

if (isset($_GET['subject_id'])) {
    $subject_id = mysqli_real_escape_string($conn, $_GET['subject_id']);

    $material_query = "SELECT MaterialID, TitleMaterial FROM Materials WHERE SubjectID = ?";
    $stmt_material = $conn->prepare($material_query);
    $stmt_material->bind_param("i", $subject_id);
    $stmt_material->execute();
    $result_material = $stmt_material->get_result();

    $materials = array();
    while ($row_material = $result_material->fetch_assoc()) {
        $materials[] = $row_material;
    }

    echo json_encode(['materials' => $materials]);

    $stmt_material->close();
} else {
    echo json_encode(['error' => 'Subject ID not provided']);
}
?>
