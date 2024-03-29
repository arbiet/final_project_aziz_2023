  <?php
  require_once('../../database/connection.php');
  include('../components/header2.php');
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

<?php include('../components/sidebar_students.php'); ?>
<main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-teal-100 min-h-screen transition-all main">
    <?php include('../components/navbar4.php'); ?>
    <!-- Content -->
    <div class="p-6">
        <div class="flex items-start justify-start shadow-lg m-4 bg-white flex-1 flex-col rounded-md">
          <div class="flex flex-row overflow-hidden sc-hide">
            <!-- Sidebar for Materials -->
            <?php include_once('../components/sidebar_students.php') ?>
            <!-- Main Content -->
            <div class="w-9/12 flex flex-col flex-1">
              <!-- Main Content -->
              <main class="container mx-auto mt-4 p-6">
                <div>
                  <h2 class="text-3xl font-semibold mb-4"><?php echo $subjectData['SubjectName']; ?></h2>
                   <!-- Video -->
                   
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
                      ?>
                        <div class="relative w-full md:w-md mb-4 md:mt-2">
                            <video id="floating-video" class="w-full md:w-md rounded max-w-3xl" controls>
                                <source src="<?= '../'.$materialData['Video']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>

                      <?php
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
                </div>
                <div class="flex justify-between">
                  <?php if ($_GET['material'] !== 'start') { ?>
                    <?php if ($previousMaterialLink) { ?>
                      <a href="<?php echo $previousMaterialLink; ?>" class="py-2 px-4 text-gray-950 hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Previous Material
                      </a>
                    <?php } ?>
                  <?php } ?>

                  <?php if ($_GET['material'] !== 'end') { ?>
                    <?php if ($nextMaterialLink) { ?>
                      <a href="<?php echo $nextMaterialLink; ?>" class="py-2 px-4 text-gray-950 hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200">
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
                    <a href="../systems/dashboard_student.php" class="py-2 px-4 text-gray-950 hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200">
                      <i class="fas fa-arrow-right mr-2"></i>
                      Finish
                    </a>
                  <?php } ?>
                </div>
              </main>
              <!-- Footer -->
            </div>
          </div>
        </div>
    </div>
</main>
<script>
    window.addEventListener('scroll', function () {
        var video = document.getElementById('floating-video');
        if (window.scrollY > 100) {
            video.classList.add('fixed', 'top-20', 'right-20', 'w-96', 'z-40');
            video.classList.remove('static', 'w-full');
        } else {
            video.classList.remove('fixed', 'top-20', 'right-20', 'w-96', 'z-40');
            video.classList.add('static', 'w-full');
        }
    });

    var isDragging = false;
    var initialX;
    var initialY;

    document.getElementById('floating-video').addEventListener('mousedown', function (e) {
        isDragging = true;
        initialX = e.clientX;
        initialY = e.clientY;
        video.style.cursor = 'grabbing';
    });

    document.addEventListener('mousemove', function (e) {
        if (isDragging) {
            var dx = e.clientX - initialX;
            var dy = e.clientY - initialY;
            var rect = video.getBoundingClientRect();
            var newTop = rect.top + dy;
            var newRight = rect.right - dx;
            video.style.top = newTop + 'px';
            video.style.right = newRight + 'px';
            initialX = e.clientX;
            initialY = e.clientY;
        }
    });

    document.addEventListener('mouseup', function () {
        isDragging = false;
        video.style.cursor = 'grab';
    });
</script>
<?php include('../components/footer2.php'); ?>