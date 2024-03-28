<?php
session_start();

// Include the database connection
require_once('../../database/connection.php');
include_once('../components/header.php');

// Initialize variables
$material_id = $type = $test_name = '';
$errors = array();
$form_processed = false;

// Fetch materials that don't have tests for the selected type
$material_query = "SELECT Materials.MaterialID, CONCAT(Subjects.SubjectName, ' - ', Materials.TitleMaterial) AS MaterialName
                   FROM Materials
                   LEFT JOIN Tests ON Materials.MaterialID = Tests.MaterialID
                   INNER JOIN Subjects ON Materials.SubjectID = Subjects.SubjectID
                   WHERE Tests.MaterialID IS NULL OR Tests.TestType != ?";
$stmt_material = $conn->prepare($material_query);
$stmt_material->bind_param("s", $type);
$stmt_material->execute();
$result_material = $stmt_material->get_result();

$materials = array();
while ($row_material = $result_material->fetch_assoc()) {
    $materials[] = $row_material;
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input data
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $test_name = mysqli_real_escape_string($conn, $_POST['test_name']);

    // Check for errors
    if (empty($material_id)) {
        $errors['material_id'] = "Material ID is required.";
    }
    if (empty($type)) {
        $errors['type'] = "Exam Type is required.";
    }
    if (empty($test_name)) {
        $errors['test_name'] = "Test Name is required.";
    }

    // If there are no errors, insert the data into the database
    if (empty($errors)) {
        $query = "INSERT INTO Tests (MaterialID, TestType, TestName)
                  VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $material_id, $type, $test_name);

        if ($stmt->execute()) {
            // Exam creation successful
            // Log the activity for exam creation
            $activityDescription = "Exam created: $material_id, Type: $type, Name: $test_name";
            $currentUserID = $_SESSION['UserID'];
            insertLogActivity($conn, $currentUserID, $activityDescription);
            $form_processed = true;

            // Display success notification and redirect
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Exam created successfully!",
                    }).then(function() {
                        window.location.href = "manage_exams_list.php";
                    });
                </script>';
        } else {
            // Exam creation failed
            $errors['db_error'] = "Exam creation failed.";

            // Display an error notification
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Exam creation failed.",
                    });
                </script>';
        }
    }
}

// Close the database connection
$stmt_material->close();
?>
<!-- Main Content Height Adjusted to Fit Between Header and Footer -->
<div class="h-screen flex flex-col">
    <!-- Top Navbar -->
    <?php include('../components/navbar.php'); ?>
    <!-- End Top Navbar -->
    <!-- Main Content -->
    <div class="flex-grow bg-gray-50 flex flex-row shadow-lg">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php'); ?>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <main class="bg-gray-50 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <div class="flex items-start justify-start p-6 shadow-lg m-4 flex-1 flex-col">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Create Exam</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="manage_exams_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!-- End Header Content -->
                <!-- Content -->
                <div class="flex flex-col w-full">
                    <!-- Navigation -->
                    <div class="flex flex-row justify-between items-center w-full pb-2">
                        <div>
                            <h2 class="text-lg text-gray-800 font-semibold">Welcome back, <?php echo $_SESSION['FullName']; ?>!</h2>
                            <p class="text-gray-600 text-sm">Exam creation form.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <?php if (!empty($errors)) : ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops! There were some errors:</strong>
                            <ul>
                                <?php foreach ($errors as $error) : ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <!-- Exam Creation Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2">
                        <!-- Material ID -->
                        <label for="material_id" class="block font-semibold text-gray-800 mt-2 mb-2">Material ID <span class="text-red-500">*</span></label>
                        <select id="material_id" name="material_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="">Select Material</option>
                            <?php foreach ($materials as $material) : ?>
                                <option value="<?php echo $material['MaterialID']; ?>"><?php echo $material['MaterialName']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['material_id'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['material_id']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Exam Type -->
                        <label for="type" class="block font-semibold text-gray-800 mt-2 mb-2">Exam Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="">Select Exam Type</option>
                            <option value="Pretest">Pretest</option>
                            <option value="Post-test">Post-test</option>
                        </select>
                        <?php if (isset($errors['type'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['type']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Test Name -->
                        <label for="test_name" class="block font-semibold text-gray-800 mt-2 mb-2">Test Name <span class="text-red-500">*</span></label>
                        <input type="text" id="test_name" name="test_name" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Test Name" value="<?php echo $test_name; ?>">
                        <?php if (isset($errors['test_name'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['test_name']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Submit Button -->
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                            <i class="fas fa-check mr-2"></i>
                            <span>Create Exam</span>
                        </button>
                    </form>
                    <!-- End Exam Creation Form -->
                </div>
                <!-- End Content -->
            </div>
        </main>
        <!-- End Main Content -->
    </div>
    <!-- End Main Content -->
    <!-- Footer -->
    <?php include('../components/footer.php'); ?>
    <!-- End Footer -->
</div>
<!-- End Main Content -->
</body>

</html>