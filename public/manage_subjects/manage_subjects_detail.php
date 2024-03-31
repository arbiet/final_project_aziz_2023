<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');

// Initialize variables
$subjectID = '';
$errors = array();
$subjectData = array();
$classesData = array();

// Retrieve subject data
if (isset($_GET['id'])) {
    $subjectID = $_GET['id'];
    $query = "SELECT s.SubjectID, s.SubjectName, s.DifficultyLevel, s.TeachingMethod, s.LearningObjective, s.DurationHours, s.CurriculumFramework, s.AssessmentMethod, s.StudentEngagement
              FROM Subjects s
              WHERE s.SubjectID = $subjectID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $subjectData = $result->fetch_assoc();
    } else {
        $errors[] = "Subject not found.";
    }

    // Retrieve classes associated with the subject
    $query = "SELECT c.ClassName, c.EducationLevel, CONCAT(u.FullName, ', ', t.AcademicDegree) AS HomeroomTeacher, c.Curriculum, c.AcademicYear, c.ClassCode
          FROM ClassSubjects cs
          INNER JOIN Classes c ON cs.ClassID = c.ClassID
          LEFT JOIN Teachers t ON c.HomeroomTeacher = t.TeacherID
          LEFT JOIN Users u ON t.UserID = u.UserID
          WHERE cs.SubjectID = $subjectID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $classesData[] = $row;
        }
    }

    // Retrieve materials associated with the subject
    $materialsData = array();
    $materialQuery = "SELECT m.MaterialID, m.TitleMaterial, m.Type, m.Content, m.Link, m.Sequence
              FROM Materials m
              WHERE m.SubjectID = $subjectID
              ORDER BY m.Sequence";
    $materialResult = $conn->query($materialQuery);

    if ($materialResult->num_rows > 0) {
        while ($materialRow = $materialResult->fetch_assoc()) {
            $materialsData[] = $materialRow;
        }
    }
}
?>

<?php include_once('../components/header2.php'); ?>
<?php include('../components/sidebar2.php'); ?>
<main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-200 min-h-screen transition-all main">
    <?php include('../components/navbar2.php'); ?>
    <!-- Content -->
    <div class="p-4">
        <!-- Main Content -->
        <div class="flex items-start justify-start p-6 shadow-lg m-4 bg-white flex-1 flex-col rounded-md">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Subject Details</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="../manage_subjects/manage_subjects_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!-- End Header Content -->
                <!-- Content -->
                <div class="flex flex-col w-full">
                    <!-- Navigation -->
                    <div class="flex flex-row justify-between items-center w-full mb-2 pb-2">
                        <div>
                            <h2 class="text-lg text-gray-800 font-semibold">Welcome back, <?php echo $_SESSION['FullName']; ?>!</h2>
                            <p class="text-gray-600 text-sm">Subject information.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <!-- Subject Details -->
                    <?php if (!empty($subjectData)) : ?>
                        <div class="bg-white shadow-lg p-4 rounded-md">
                            <h3 class="text-lg font-semibold text-gray-800">Subject Information</h3>
                            <p><strong>Subject Name:</strong> <?php echo $subjectData['SubjectName']; ?></p>
                            <p><strong>Difficulty Level:</strong> <?php echo $subjectData['DifficultyLevel']; ?></p>
                            <p><strong>Teaching Method:</strong> <?php echo $subjectData['TeachingMethod']; ?></p>
                            <p><strong>Learning Objective:</strong> <?php echo $subjectData['LearningObjective']; ?></p>
                            <p><strong>Duration Hours:</strong> <?php echo $subjectData['DurationHours']; ?></p>
                            <p><strong>Curriculum Framework:</strong> <?php echo $subjectData['CurriculumFramework']; ?></p>
                            <p><strong>Assessment Method:</strong> <?php echo $subjectData['AssessmentMethod']; ?></p>
                            <p><strong>Student Engagement:</strong> <?php echo $subjectData['StudentEngagement']; ?></p>
                        </div>
                        <?php if (!empty($materialsData)) : ?>
                            <div class="mt-4 bg-white shadow-lg p-4 rounded-md">
                                <h3 class="text-lg font-semibold text-gray-800">Materials for this Subject</h3>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material Title</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sequence</th>
                                            <!-- <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th> -->
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $uniqueMaterials = array(); ?>
                                        <?php foreach ($materialsData as $index => $material) : ?>
                                            <?php if (!in_array($material['MaterialID'], $uniqueMaterials)) : ?>
                                                <tr>
                                                    <td class="px-2 py-2 whitespace-nowrap">
                                                        <?php echo $material['TitleMaterial']; ?>
                                                    </td>
                                                    <td class="px-2 py-2 whitespace-nowrap">
                                                        <?php echo $material['Type']; ?>
                                                    </td>
                                                    <td class="px-2 py-2 whitespace-nowrap">
                                                        <?php echo $material['Sequence']; ?>
                                                        <?php if ($index > 0) : ?>
                                                            <button class="move-material-up hover:bg-gray-200 py-1 px-2" data-material-id="<?php echo $material['MaterialID']; ?>">
                                                                <i class="fas fa-arrow-up"></i> Up
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($index < count($materialsData) - 1) : ?>
                                                            <button class="move-material-down hover:bg-gray-200 py-1 px-2" data-material-id="<?php echo $material['MaterialID']; ?>">
                                                                <i class="fas fa-arrow-down"></i> Down
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                    <!-- Exam Column -->
                                                    <!-- <td class="px-2 py-2 whitespace-nowrap">
                                                        <?php
                                                        // $pretestExists = false;
                                                        // $posttestExists = false;

                                                        // // Check if pretest and posttest exist for the material
                                                        // $testQuery = "SELECT TestType FROM Tests WHERE MaterialID = {$material['MaterialID']}";
                                                        // $testResult = $conn->query($testQuery);

                                                        // while ($testRow = $testResult->fetch_assoc()) {
                                                        //     if ($testRow['TestType'] === 'Pretest') {
                                                        //         $pretestExists = true;
                                                        //     } elseif ($testRow['TestType'] === 'Post-test') {
                                                        //         $posttestExists = true;
                                                        //     }
                                                        // }

                                                        // // Display buttons or links based on test existence
                                                        // if (!$pretestExists) {
                                                        //     echo '<a href="../manage_exams/manage_exams_create.php?material_id=' . $material['MaterialID'] . '&type=Pretest" class="text-green-500 hover:underline"><i class="fas fa-plus"></i> Add Pretest</a>';
                                                        // } else {
                                                        //     echo '<a href="../manage_exams/manage_exams_detail.php?material_id=' . $material['MaterialID'] . '&type=Pretest" class="text-blue-500 hover:underline"><i class="fas fa-eye"></i> View Pretest</a>';
                                                        // }

                                                        // if (!$posttestExists) {
                                                        //     echo '<a href="../manage_exams/manage_exams_create.php?material_id=' . $material['MaterialID'] . '&type=Post-test" class="text-green-500 hover:underline ml-2"><i class="fas fa-plus"></i> Add Post Test</a>';
                                                        // } else {
                                                        //     echo '<a href="../manage_exams/manage_exams_detail.php?material_id=' . $material['MaterialID'] . '&type=Post-test" class="text-blue-500 hover:underline ml-2"><i class="fas fa-eye"></i> View Post Test</a>';
                                                        // }
                                                        ?>
                                                    </td> -->
                                                </tr>
                                                <?php $uniqueMaterials[] = $material['MaterialID']; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="bg-white shadow-lg p-4 rounded-md mt-4">
                                <p>No materials available for this subject.</p>
                            </div>
                        <?php endif; ?>
                        <!-- Associated Classes -->
                        <?php if (!empty($classesData)) : ?>
                            <div class="mt-4 bg-white shadow-lg p-4 rounded-md">
                                <h3 class="text-lg font-semibold text-gray-800">Classes Offering this Subject</h3>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Name</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Education Level</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Homeroom Teacher</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curriculum</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Code</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($classesData as $class) : ?>
                                            <tr>
                                                <td class="px-2 py-2 whitespace-nowrap">
                                                    <?php echo $class['ClassName']; ?>
                                                </td>
                                                <td class="px-2 py-2 whitespace-nowrap">
                                                    <?php echo $class['EducationLevel']; ?>
                                                </td>
                                                <td class="px-2 py-2 whitespace-nowrap">
                                                    <?php echo $class['HomeroomTeacher']; ?>
                                                </td>
                                                <td class="px-2 py-2 whitespace-nowrap">
                                                    <?php echo $class['Curriculum']; ?>
                                                </td>
                                                <td class="px-2 py-2 whitespace-nowrap">
                                                    <?php echo $class['AcademicYear']; ?>
                                                </td>
                                                <td class="px-2 py-2 whitespace-nowrap">
                                                    <?php echo $class['ClassCode']; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="bg-white shadow-lg p-4 rounded-md mt-4">
                                <p>No classes offer this subject.</p>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="bg-white shadow-lg p-4 rounded-md">
                            <p>No subject data available.</p>
                        </div>
                    <?php endif; ?>
                    <!-- End Subject Details -->
                </div>
                <!-- End Content -->
            </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".move-material-up").click(function() {
            moveMaterial($(this).data('material-id'), 'up');
        });

        $(".move-material-down").click(function() {
            moveMaterial($(this).data('material-id'), 'down');
        });

        function moveMaterial(materialID, direction) {
            $.ajax({
                type: "POST",
                url: "../manage_materials/update_material_order.php",
                data: {
                    materialID: materialID,
                    direction: direction
                },
                success: function(data) {
                    if (data === 'success') {
                        // Reload the page to reflect the updated order
                        location.reload();
                    } else {
                        alert(data); // Display an error message
                    }
                }
            });
        }
    });
</script>
</main>
<?php include('../components/footer2.php'); ?>