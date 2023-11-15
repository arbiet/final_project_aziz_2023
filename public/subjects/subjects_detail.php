<?php
require_once('../../database/connection.php');
include('../components/header.php');
session_start();

if (isset($_SESSION['UserID'])) {
  // Check if 'subject_id' is set in the URL
  if (isset($_GET['subject_id'])) {
    $subjectID = $_GET['subject_id'];

    // Fetch subject information from the database
    $query = "SELECT s.*, t.*, u.FullName AS TeacherName
                  FROM Subjects s
                  LEFT JOIN Teachers t ON s.TeacherID = t.TeacherID
                  LEFT JOIN Users u ON t.UserID = u.UserID
                  WHERE s.SubjectID = $subjectID";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
      $subjectData = mysqli_fetch_assoc($result);
    }

    // Fetch materials related to the subject
    $materialQuery = "SELECT * FROM Materials WHERE SubjectID = $subjectID ORDER BY Sequence";
    $materialResult = mysqli_query($conn, $materialQuery);
    $materials = [];

    if ($materialResult && mysqli_num_rows($materialResult) > 0) {
      while ($row = mysqli_fetch_assoc($materialResult)) {
        $materials[] = $row;
      }
    }
  }
}

// Find the index of the currently displayed material
$currentMaterialIndex = -1;
if (isset($_GET['material'])) {
  $currentMaterialID = $_GET['material'];
  foreach ($materials as $index => $material) {
    if ($material['MaterialID'] == $currentMaterialID) {
      $currentMaterialIndex = $index;
      break;
    }
  }
}

// Determine the previous and next material links
$previousMaterialLink = null;
$nextMaterialLink = null;
if ($currentMaterialIndex > 0) {
  $previousMaterialLink = "subjects_detail.php?subject_id=$subjectID&material=" . $materials[$currentMaterialIndex - 1]['MaterialID'];
}
if ($currentMaterialIndex < count($materials) - 1) {
  $nextMaterialLink = "subjects_detail.php?subject_id=$subjectID&material=" . $materials[$currentMaterialIndex + 1]['MaterialID'];
}
?>

<body class="overflow-hidden">
  <!-- Navbar -->
  <header class="bg-blue-600 p-4 text-white">
    <nav class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">Dashboard Siswa</h1>
      <a href="javascript:void(0);" onclick="confirmLogout()" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray-500 hover:bg-white mt-4 lg:mt-0">Logout</a>
    </nav>
  </header>
  <!-- Your existing header code can go here -->
  <div class="h-screen flex flex-row overflow-hidden sc-hide">
    <!-- Sidebar for Materials -->
    <div class="bg-gray-200 w-3/12 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
      <div class="p-4 hover:bg-gray-300">
        <a href="../systems/dashboard_student.php" class=" hover:text-blue-500">
          <i class="fas fa-arrow-left mr-2"></i>
          Back
        </a>
      </div>
      <h3 class="text-lg font-semibold p-4">Materials</h3>
      <ul class="list-inside">
        <!-- Displaying a manual type for "Start" -->
        <li class="py-2 px-4 hover:bg-gray-300">
          <span class="text-gray-500">Starter</span>
        </li>
        <!-- Special list item for "start" with an icon -->
        <a href="subjects_detail.php?subject_id=<?php echo $subjectID; ?>&material=start" class="hover:text-blue-500">
          <li class="py-2 px-4 hover:bg-gray-300">
            <i class="fas fa-play-circle mr-2"></i> Start
          </li>
        </a>
        <?php
        $prevMaterialType = null;
        foreach ($materials as $material) {
          // Check if the current material has the same type as the previous one
          if ($material['Type'] !== $prevMaterialType) {
            // Display the material type
            echo '<li class="py-2 px-4 hover:bg-gray-300">';
            echo '<span class="text-gray-500">' . $material['Type'] . '</span>';
            echo '</li>';
          }
          echo '<a href="subjects_detail.php?subject_id=' . $subjectID . '&material=' . $material['MaterialID'] . '" class="hover:text-blue-500">';
          echo '<li class="py-2 px-4 hover:bg-gray-300">';
          echo '<i class="fas fa-file-alt mr-2"></i>' . $material['TitleMaterial'];
          echo '</li>';
          echo '</a>';

          // Update the previous material type
          $prevMaterialType = $material['Type'];
        }
        ?>

        <!-- Displaying a manual type for "End" -->
        <li class="py-2 px-4 hover-bg-gray-300">
          <span class="text-gray-500">Finish</span>
        </li>

        <!-- Special list item for "end" with an icon -->
        <a href="subjects_detail.php?subject_id=<?php echo $subjectID; ?>&material=end" class="hover:text-blue-500">
          <li class="py-2 px-4 hover-bg-gray-300">
            <i class="fas fa-stop-circle mr-2"></i> End
          </li>
        </a>
      </ul>
    </div>
    <!-- Main Content -->
    <div class="w-9/12 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
      <!-- Main Content -->
      <main class="container mx-auto mt-4 p-4">
        <!-- navigation previous and next -->
        <div class="flex justify-between">
          <?php if ($_GET['material'] !== 'start') { ?>
            <?php if ($previousMaterialLink) { ?>
              <a href="<?php echo $previousMaterialLink; ?>" class="p-4 hover:bg-gray-300 hover:text-blue-500 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Previous Material
              </a>
            <?php } ?>
          <?php } ?>

          <?php if ($_GET['material'] !== 'end') { ?>
            <?php if ($nextMaterialLink) { ?>
              <a href="<?php echo $nextMaterialLink; ?>" class="p-4 hover:bg-gray-300 hover:text-blue-500 flex items-center">
                <?php if ($_GET['material'] !== 'finish') { ?>
                  Next Material
                  <i class="fas fa-arrow-right ml-2"></i>
                <?php } else { ?>
                  <i class="fas fa-arrow-right mr-2"></i>
                  Finish
                <?php } ?>
              </a>
            <?php } ?>
          <?php } else { ?>
            <a href="../systems/dashboard_student.php" class="p-4 hover:bg-gray-300 hover:text-blue-500 flex items-center">
              <i class="fas fa-arrow-right mr-2"></i>
              Finish
            </a>
          <?php } ?>
        </div>


        <h2 class="text-3xl font-semibold mb-4"><?php echo $subjectData['SubjectName']; ?></h2>

        <?php if (isset($_GET['material'])) : ?>
          <?php $material_id = $_GET['material']; ?>
          <?php if ($material_id === 'start') : ?>
            <div class="bg-white rounded p-4 shadow relative shadow:md mb-4">
              <i class="fas fa-play-circle text-5xl text-blue-500 mb-4"></i>
              <p class="text-2xl font-semibold">Welcome to the Beginning</p>
              <p class="text-lg">You are about to start your exciting journey in <?= $subjectData['SubjectName']; ?>.</p>
              <p class="text-lg">Subject Name: <?= $subjectData['SubjectName']; ?></p>
              <p class="text-lg">Teacher: <?= $subjectData['TeacherName']; ?></p>
              <p class="text-lg">Difficulty: <?= $subjectData['DifficultyLevel']; ?></p>
              <p class="text-lg">Teaching Method: <?= $subjectData['TeachingMethod']; ?></p>
            </div>
          <?php elseif ($material_id === 'end') : ?>
            <div class="bg-white rounded p-4 shadow relative shadow:md mb-4">
              <i class="fas fa-stop-circle text-5xl text-red-500 mb-4"></i>
              <p class="text-2xl font-semibold">Congratulations, You've Finished!</p>
              <p class="text-lg">You have successfully completed <?= $subjectData['SubjectName']; ?>.</p>
              <p class="text-lg">Subject Name: <?= $subjectData['SubjectName']; ?></p>
              <p class="text-lg">Teacher: <?= $subjectData['TeacherName']; ?></p>
              <p class="text-lg">Difficulty: <?= $subjectData['DifficultyLevel']; ?></p>
              <p class="text-lg">Teaching Method: <?= $subjectData['TeachingMethod']; ?></p>
            </div>
          <?php else : ?>
            <?php
            // Check if the material_id is a valid material ID
            $materialQuery = "SELECT * FROM Materials WHERE MaterialID = $material_id AND SubjectID = $subjectID";
            $materialResult = mysqli_query($conn, $materialQuery);

            if ($materialResult && mysqli_num_rows($materialResult) > 0) {
              $materialData = mysqli_fetch_assoc($materialResult);
              include($materialData['Link']); // Include the material file
            } else {
              echo "Material not found or invalid ID.";
            }
            ?>
          <?php endif; ?>
        <?php else : ?>
          <!-- Display subject information if no material type is specified -->
          <div class="bg-white rounded p-4 shadow relative shadow:md mb-4">
            <h3 class="text-2xl font-semibold"><?= $subjectData['SubjectName']; ?></h3>
            <?php if (isset($subjectData['TeacherID'])) : ?>
              <p class="text-lg">Teacher: <?= $subjectData['TeacherName']; ?></p>
            <?php else : ?>
              <p class="text-lg">Teacher: Not assigned</p>
            <?php endif; ?>
            <p class="text-lg">Difficulty: <?= $subjectData['DifficultyLevel']; ?></p>
            <p class="text-lg">Teaching Method: <?= $subjectData['TeachingMethod']; ?></p>
          </div>
        <?php endif; ?>
      </main>
      <!-- Footer -->
    </div>
  </div>
</body>

</html>