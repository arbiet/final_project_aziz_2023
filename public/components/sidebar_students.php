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
                  echo '<i class="fa-solid fa-book mr-2"></i>';
                  echo '<span class="text-gray-500">' . $material['Type'] . '</span>';
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
              
              // Display the material without exam links
              echo '<li class="py-2 pl-8 pr-4 hover:bg-gray-300">';
              echo '<a href="subjects_detail.php?subject_id=' . $subjectID . '&material=' . $material['MaterialID'] . '" class="hover:text-blue-500">';
              echo '<i class="fas fa-file-alt mr-2"></i>' . $material['TitleMaterial'];
              echo '</a>';
              echo '</li>';

              // Display pretest link if material has associated pretest
              if ($hasPretest) {
                  echo '<li class="py-2 pl-12 pr-4 hover:bg-gray-300 flex justify-between items-center">';
                  echo '<a href="subjects_test.php?subject_id=' . $subjectID . '&material=' . $material['MaterialID'] . '&test_id=' . $pretestData['TestID'] . '&test_type=Pretest" class="hover:text-blue-500 flex items-center">';
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
                  echo '<a href="subjects_test.php?subject_id=' . $subjectID . '&material=' . $material['MaterialID'] . '&test_id=' . $postTestData['TestID'] . '&test_type=Post-test" class="hover:text-blue-500 flex items-center">';
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