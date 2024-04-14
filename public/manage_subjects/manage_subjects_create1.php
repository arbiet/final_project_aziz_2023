<?php
session_start();

// Include the database connection
require_once('../../database/connection.php');
include_once('../components/header2.php');

// Initialize variables
$subject_name = $difficulty_level = $teaching_method = $learning_objective = $duration_hours = $curriculum_framework = $assessment_method = $student_engagement = '';
$errors = array();

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input data
  $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);
  $difficulty_level = mysqli_real_escape_string($conn, $_POST['difficulty_level']);
  $teaching_method = mysqli_real_escape_string($conn, $_POST['teaching_method']);
  $learning_objective = mysqli_real_escape_string($conn, $_POST['learning_objective']);
  $duration_hours = mysqli_real_escape_string($conn, $_POST['duration_hours']);
  $curriculum_framework = mysqli_real_escape_string($conn, $_POST['curriculum_framework']);
  $assessment_method = mysqli_real_escape_string($conn, $_POST['assessment_method']);
  $student_engagement = mysqli_real_escape_string($conn, $_POST['student_engagement']);
  $teacher_mapel = mysqli_real_escape_string($conn, $_POST['teacher_mapel']);

  // Check for errors
  if (empty($subject_name)) {
    $errors['subject_name'] = "Subject Name is required.";
  }
  // Add validation rules for other fields as needed...

  // If there are no errors, insert the data into the database
  if (empty($errors)) {
    $query = "INSERT INTO Subjects (SubjectName, DifficultyLevel, TeachingMethod, LearningObjective, DurationHours, CurriculumFramework, AssessmentMethod, StudentEngagement, TeacherID)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssi", $subject_name, $difficulty_level, $teaching_method, $learning_objective, $duration_hours, $curriculum_framework, $assessment_method, $student_engagement, $teacher_mapel);

    if ($stmt->execute()) {
      // Subject creation successful
      // Log the activity for subject creation
      $activityDescription = "Subject created: $subject_name";
      $currentUserID = $_SESSION['UserID'];
      insertLogActivity($conn, $currentUserID, $activityDescription);
      // Display a success notification
      echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Subject created successfully.",
                }).then(function() {
                    window.location.href = "manage_subjects_list.php";
                });
            </script>';
      exit();
    } else {
      // Subject creation failed
      $errors['db_error'] = "Subject creation failed.";

      // Display an error notification
      echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Subject creation failed.",
                });
            </script>';
    }
  }
}

// Close the database connection
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
          <h1 class="text-3xl text-gray-800 font-semibold w-full">Create Subject</h1>
          <div class="flex flex-row justify-end items-center">
            <a href="manage_subjects_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
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
              <p class="text-gray-600 text-sm">Subject creation form.</p>
            </div>
          </div>
          <!-- End Navigation -->
          <!-- Subject Creation Form -->
          <form action="" method="POST" class="flex flex-col w-full space-x-2">
            <!-- Subject Name -->
            <label for="subject_name" class="block font-semibold text-gray-800 mt-2 mb-2">Subject Name <span class="text-red-500">*</span></label>
            <input type="text" id="subject_name" name="subject_name" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Subject Name" value="<?php echo $subject_name; ?>">
            <?php if (isset($errors['subject_name'])) : ?>
              <p class="text-red-500 text-sm">
                <?php echo $errors['subject_name']; ?>
              </p>
            <?php endif; ?>
            <?php
              // Fetch the list of teachers
              $query = "SELECT Teachers.TeacherID, Users.FullName, Teachers.AcademicDegree
              FROM Teachers
              INNER JOIN Users ON Teachers.UserID = Users.UserID";

              $result = $conn->query($query);

              // Check for errors in the database query
              if (!$result) {
                  die("Database query failed: " . $conn->error);
              }

              // Close the database connection
              $conn->close();

            ?>

            <!-- Homeroom Teacher -->
            <label for="teacher_mapel" class="block font-semibold text-gray-800 mt-2 mb-2">Homeroom Teacher</label>
            <select id="teacher_mapel" name="teacher_mapel" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                <?php
                // Iterate through the retrieved teachers and populate the select field
                while ($row = $result->fetch_assoc()) {
                    $teacherID = $row['TeacherID'];
                    $teacherName = $row['FullName'];
                    $AcademicDegree = $row['AcademicDegree'];
                    echo "<option value='$teacherID'>$teacherName, $AcademicDegree</option>";
                }
                ?>
            </select>

            <!-- Difficulty Level -->
            <label for="difficulty_level" class="block font-semibold text-gray-800 mt-2 mb-2">Difficulty Level</label>
            <input type="text" id="difficulty_level" name="difficulty_level" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Difficulty Level" value="<?php echo $difficulty_level; ?>">

            <!-- Teaching Method -->
            <label for="teaching_method" class="block font-semibold text-gray-800 mt-2 mb-2">Teaching Method</label>
            <input type="text" id="teaching_method" name="teaching_method" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Teaching Method" value="<?php echo $teaching_method; ?>">

            <!-- Learning Objective -->
            <label for="learning_objective" class="block font-semibold text-gray-800 mt-2 mb-2">Learning Objective</label>
            <textarea id="learning_objective" name="learning_objective" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Learning Objective"><?php echo $learning_objective; ?></textarea>

            <!-- Duration (Hours) -->
            <label for="duration_hours" class="block font-semibold text-gray-800 mt-2 mb-2">Duration (Hours)</label>
            <input type="text" id="duration_hours" name="duration_hours" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Duration (Hours)" value="<?php echo $duration_hours; ?>">

            <!-- Curriculum Framework -->
            <label for="curriculum_framework" class="block font-semibold text-gray-800 mt-2 mb-2">Curriculum Framework</label>
            <input type="text" id="curriculum_framework" name="curriculum_framework" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Curriculum Framework" value="<?php echo $curriculum_framework; ?>">

            <!-- Assessment Method -->
            <label for="assessment_method" class="block font-semibold text-gray-800 mt-2 mb-2">Assessment Method</label>
            <input type="text" id="assessment_method" name="assessment_method" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Assessment Method" value="<?php echo $assessment_method; ?>">

            <!-- Student Engagement -->
            <label for="student_engagement" class="block font-semibold text-gray-800 mt-2 mb-2">Student Engagement</label>
            <input type="text" id="student_engagement" name="student_engagement" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Student Engagement" value="<?php echo $student_engagement; ?>">

            <!-- Submit Button -->
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
              <i class="fas fa-check mr-2"></i>
              <span>Create Subject</span>
            </button>
          </form>
          <!-- End Subject Creation Form -->
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