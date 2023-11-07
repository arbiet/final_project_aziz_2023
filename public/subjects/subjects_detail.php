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
    <div class="bg-gray-200 w-2/12">
      <h3 class="text-lg font-semibold p-4">Materials</h3>
      <ul class="list-inside">
        <!-- Special list item for "start" with an icon -->
        <li class="py-2 px-4 hover:bg-gray-300">
          <a href="subjects_detail.php?subject_id=<?php echo $subjectID; ?>&material=start" class="hover:text-blue-500">
            <i class="fas fa-play-circle mr-2"></i> Start
          </a>
        </li>
        <!-- Regular list items for other materials with icons and hover effect -->
        <?php foreach ($materials as $material) { ?>
          <li class="py-2 px-4 hover:bg-gray-300">
            <a href="<?php echo $material['Link']; ?>" class="hover:text-blue-500">
              <i class="fas fa-file-alt mr-2"></i> <?php echo $material['TitleMaterial']; ?>
            </a>
          </li>
        <?php } ?>
        <!-- Special list item for "end" with an icon -->
        <li class="py-2 px-4 hover:bg-gray-300">
          <a href="subjects_detail.php?subject_id=<?php echo $subjectID; ?>&material=end" class="hover:text-blue-500">
            <i class="fas fa-stop-circle mr-2"></i> End
          </a>
        </li>
      </ul>
    </div>
    <!-- Main Content -->
    <div class="w-10/12">
      <!-- Main Content -->
      <main class="container mx-auto mt-4 p-4">
        <h2 class="text-3xl font-semibold mb-4">Subject Details: <?php echo $subjectData['SubjectName']; ?></h2>

        <?php if (isset($_GET['material'])) : ?>
          <?php $materialType = $_GET['material']; ?>
          <?php if ($materialType === 'start') : ?>
            <div class="bg-white rounded p-4 shadow relative shadow:md mb-4">
              <i class="fas fa-play-circle text-5xl text-blue-500 mb-4"></i>
              <p class="text-2xl font-semibold">Welcome to the Beginning</p>
              <p class="text-lg">You are about to start your exciting journey in <?= $subjectData['SubjectName']; ?>.</p>
              <p class="text-lg">Subject Name: <?= $subjectData['SubjectName']; ?></p>
              <p class="text-lg">Teacher: <?= $subjectData['TeacherName']; ?></p>
              <p class="text-lg">Difficulty: <?= $subjectData['DifficultyLevel']; ?></p>
              <p class="text-lg">Teaching Method: <?= $subjectData['TeachingMethod']; ?></p>
            </div>
          <?php elseif ($materialType === 'end') : ?>
            <div class="bg-white rounded p-4 shadow relative shadow:md mb-4">
              <i class="fas fa-stop-circle text-5xl text-red-500 mb-4"></i>
              <p class="text-2xl font-semibold">Congratulations, You've Finished!</p>
              <p class="text-lg">You have successfully completed <?= $subjectData['SubjectName']; ?>.</p>
              <p class="text-lg">Subject Name: <?= $subjectData['SubjectName']; ?></p>
              <p class="text-lg">Teacher: <?= $subjectData['TeacherName']; ?></p>
              <p class="text-lg">Difficulty: <?= $subjectData['DifficultyLevel']; ?></p>
              <p class="text-lg">Teaching Method: <?= $subjectData['TeachingMethod']; ?></p>
            </div>
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
    </div>

    <!-- Footer -->
    <footer class="fixed bottom-0 w-full p-4 text-gray-600 ">
      <div class="container mx-auto text-center">
        &copy; <?php echo date('Y'); ?> <?php echo $baseTitle; ?> - All Rights Reserved
      </div>
    </footer>
  </div>
</body>

</html>