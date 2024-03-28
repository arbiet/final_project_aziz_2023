    <!--sidenav -->
    <aside class="fixed left-0 top-0 w-64 bg-emerald-600 z-50 sidebar-menu transition-transform overflow-y-scroll h-screen flex-shrink-0 sc-hide">
        <!-- Icon Section -->
        <div class="fixed flex items-end border-b border-b-gray-800 pb-4 bg-emerald-600 p-4 w-64">
            <img src="../static/image/icon.png" alt="Icon" class="w-8 h-8 mr-2">
            <h2 class="font-bold text-2xl">E<span class="bg-[#f84525] text-white px-2 rounded-md">SAY</span></h2>
            <span class="text-xs text-white ml-1">.beta</span>
        </div>
        <div class="mt-10 p-4">
        <a href="../systems/dashboard_student.php" class="mt-4 flex font-semibold items-center py-2 px-4 text-white hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200">
          <i class="fas fa-arrow-left mr-2"></i>
          Back
        </a>
        <?php
         $currentMaterial = isset($_GET['material']) ? $_GET['material'] : '';
        ?>
        <ul class="list-inside">
          <li class="py-2 px-4 text-white font-bold border-b mb-2">
            <i class="fa-solid fa-play mr-2"></i>
            <span class="text-white">Start</span>
          </li>
          <a href="subjects_detail.php?subject_id=<?php echo $subjectID; ?>&material=start" class="">
            <li class="py-2 px-4 <?php echo $currentMaterial === 'start' ? 'bg-gray-950 text-white' : 'hover:bg-gray-950 hover:text-gray-200'; ?> text-white hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200">
              <i class="fas fa-play-circle mr-2"></i> Pendahuluan
            </li>
          </a>
          <?php
            $prevMaterialType = null;
            // Ambil nilai material dari parameter URL
            // Array untuk menandai item sidebar yang aktif
            $activeItems = array();

            foreach ($materials as $material) {
                $isActive = ($currentMaterial == $material['MaterialID'] || in_array($currentMaterial, $activeItems)) ? 'active' : '';
                // Check if the current material has the same type as the previous one
                if ($material['Type'] !== $prevMaterialType) {
                    // Display the material type
                    echo '<li class="py-2 px-4 text-white font-bold border-b">';
                    echo '<i class="fa-solid fa-book mr-2"></i>';
                    echo '<span class="text-white">' . $material['Type'] . '</span>';
                    echo '</li>';
                }

                // Check if the material has an associated exam (pretest or post-test)
                $hasPretest = false;
                $hasPosttest = false;

                // Check if it has a pretest
                $pretestQuery = "SELECT * FROM Tests WHERE MaterialID = {$material['MaterialID']} AND TestType = 'Pretest'";
                $pretestResult = mysqli_query($conn, $pretestQuery);
                if ($pretestResult && mysqli_num_rows($pretestResult) > 0) {
                    $pretestData = mysqli_fetch_assoc($pretestResult);
                    $hasPretest = true;

                    // Check if the student has completed the pretest
                    $pretestResultQuery = "SELECT * FROM TestResults WHERE StudentID = {$_SESSION['StudentID']} AND TestID = {$pretestData['TestID']}";
                    $pretestResultResult = mysqli_query($conn, $pretestResultQuery);
                    $pretestResultData = mysqli_fetch_assoc($pretestResultResult);
                }

                // Check if it has a post-test
                $postTestQuery = "SELECT * FROM Tests WHERE MaterialID = {$material['MaterialID']} AND TestType = 'Post-test'";
                $postTestResult = mysqli_query($conn, $postTestQuery);
                if ($postTestResult && mysqli_num_rows($postTestResult) > 0) {
                    $postTestData = mysqli_fetch_assoc($postTestResult);
                    $hasPosttest = true;

                    // Check if the student has completed the post-test
                    $postTestResultQuery = "SELECT * FROM TestResults WHERE StudentID = {$_SESSION['StudentID']} AND TestID = {$postTestData['TestID']}";
                    $postTestResultResult = mysqli_query($conn, $postTestResultQuery);
                    $postTestResultData = mysqli_fetch_assoc($postTestResultResult);
                }
                $clsss = ($isActive == 'active') ? 'text-white bg-gray-950' : 'text-white';
                // Display the material without exam links
                echo '<li class="py-2 pl-8 pr-4 mt-2 '. $clsss . ' hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200 ' . $isActive . '">';
                echo '<a href="subjects_detail.php?subject_id=' . $subjectID . '&material=' . $material['MaterialID'] . '" class="hover:text-gray-200">';
                echo '<i class="fas fa-file-alt mr-2"></i>' . $material['TitleMaterial']; 
                echo '</a>';
                echo '</li>';

                // Display pretest link if material has associated pretest
                if ($hasPretest) {
                    echo '<li class="py-2 pl-12 pr-4 hover:bg-gray-300 flex justify-between items-center">';
                    echo '<a href="subjects_test.php?subject_id=' . $subjectID . '&material=' . $material['MaterialID'] . '&test_id=' . $pretestData['TestID'] . '&test_type=Pretest" class="hover:text-gray-200 flex items-center">';
                    echo '<i class="fas fa-pencil-alt mr-2"></i>' . $pretestData['TestName'];

                    // Display pretest results
                    if ($pretestResultData) {
                        echo '<span class="flex items-center ml-2">';
                        echo '<i class="fas fa-check-circle text-green-500 mr-2"></i>Score ' . $pretestResultData['Score'];
                        echo '</span>';
                    } else {
                        echo '<span class="flex items-center ml-2">';
                        echo '<i class="fas fa-times-circle text-red-500 mr-2"></i>';
                        echo '</span>';
                    }

                    echo '</a>';
                    echo '</li>';
                }

                // Display post-test link if material has associated post-test
                if ($hasPosttest) {
                    echo '<li class="py-2 pl-12 pr-4 hover:bg-gray-300 flex justify-between items-center">';
                    echo '<a href="subjects_test.php?subject_id=' . $subjectID . '&material=' . $material['MaterialID'] . '&test_id=' . $postTestData['TestID'] . '&test_type=Post-test" class="hover:text-gray-200 flex items-center">';
                    echo '<i class="fas fa-pencil-alt mr-2"></i>' . $postTestData['TestName'];

                    // Display post-test results
                    if ($postTestResultData) {
                        echo '<span class="flex items-center ml-2">';
                        echo '<i class="fas fa-check-circle text-green-500 mr-2"></i>Score ' . $postTestResultData['Score'];
                        echo '</span>';
                    } else {
                        echo '<span class="flex items-center ml-2">';
                        echo '<i class="fas fa-times-circle text-red-500 mr-2"></i>';
                        echo '</span>';
                    }

                    echo '</a>';
                    echo '</li>';
                  }
                  // Fetch and display assignments for the current material
                  $assignmentQuery = "SELECT * FROM Assignments WHERE MaterialID = {$material['MaterialID']}";
                  $assignmentResult = mysqli_query($conn, $assignmentQuery);

                  while ($assignment = mysqli_fetch_assoc($assignmentResult)) {
                      // Check if the user has submitted the assignment
                      $submissionQuery = "SELECT * FROM AssignmentSubmissions WHERE AssignmentID = {$assignment['AssignmentID']} AND StudentID = {$_SESSION['StudentID']}";
                      $submissionResult = mysqli_query($conn, $submissionQuery);
                      $hasSubmitted = mysqli_num_rows($submissionResult) > 0;

                      echo '<li class="py-2 pl-8 pr-4 text-white hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200">';
                      echo '<a href="subjects_assignment.php?subject_id=' . $subjectID . '&assignment_id=' . $assignment['AssignmentID'] . '" class="hover:text-gray-200">';
                      echo '<i class="fas fa-tasks mr-2"></i>' . $assignment['Title'];

                      // Display an indicator if the user has submitted the assignment
                      if ($hasSubmitted) {
                          echo '<span class="bg-white text-black rounded text-sm p-1 ml-2"><i class="fas fa-check-circle text-green-500 mr-1"></i>Done</span>';
                      } else {
                          echo '<span class="bg-white text-black rounded text-sm p-1 ml-2"><i class="fas fa-times-circle text-red-500 mr-1"></i>Undone</span>';
                      }

                      echo '</a>';
                      echo '</li>';
                  }


                // Update the previous material type
                $prevMaterialType = $material['Type'];
            }
            ?>

          <!-- Displaying a manual type for "End" -->
          <li class="py-2 px-4 text-white font-bold border-b mb-2">
            <i class="fa-solid fa-flag-checkered mr-2"></i>
            <span class="text-white">Finish</span>
          </li>
          <!-- Special list item for "end" with an icon -->
          <a href="subjects_detail.php?subject_id=<?php echo $subjectID; ?>&material=end" class="hover:text-gray-200">
            <li class="py-2 px-4 <?php echo $currentMaterial === 'end' ? 'bg-gray-950 text-white' : 'hover:bg-gray-950 hover:text-gray-200'; ?> text-white hover:bg-gray-950 hover:text-gray-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-200">
              <i class="fas fa-stop-circle mr-2"></i> Penutup
            </li>
          </a>
        </ul>
        </div>
    </aside>
    <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
    <!-- end sidenav -->
    