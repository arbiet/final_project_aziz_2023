<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');

// Initialize variables
$errors = array();

?>
<?php include_once('../components/header.php'); ?>
<!-- Main Content Height Menyesuaikan Hasil Kurang dari Header dan Footer -->
<div class="h-screen flex flex-col">
  <!-- Top Navbar -->
  <?php include('../components/navbar.php'); ?>
  <!-- End Top Navbar -->
  <!-- Main Content -->
  <div class="flex-grow bg-gray-50 flex flex-row shadow-md">
    <!-- Sidebar -->
    <?php include('../components/sidebar.php'); ?>
    <!-- End Sidebar -->

    <!-- Main Content -->
    <main class="bg-gray-50 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
      <div class="flex items-start justify-start p-6 shadow-md m-4 flex-1 flex-col">
        <!-- Header Content -->
        <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
          <h1 class="text-3xl text-gray-800 font-semibold w-full">Exams</h1>
          <div class="flex flex-row justify-end items-center">
            <a href="<?php echo $baseUrl; ?>public/manage_exams/manage_exams_create.php" class="bg-green-500 hover-bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
              <i class="fas fa-plus mr-2"></i>
              <span>Create</span>
            </a>
          </div>
        </div>
        <!-- End Header Content -->
        <!-- Content -->
        <div class="flex flex-col w-full">
          <!-- Navigation -->
          <div class="flex flex-row justify-between items-center w-full mb-2 pb-2">
            <!-- ... (Similar structure for welcome message and search form) ... -->
          </div>
          <!-- End Navigation -->
          <!-- Filter -->
          <div class="flex flex-row justify-between items-center w-full mb-2">
            <label class="text-gray-600">Filter:</label>
            <select id="testTypeFilter" class="bg-white border border-gray-300 p-2 rounded">
              <option value="">All</option>
              <option value="Pretest">Pretest</option>
              <option value="Post-test">Post-test</option>
            </select>
          </div>
          <!-- End Filter -->
          <!-- Table -->
          <table class="min-w-full">
            <thead>
              <tr>
                <th class="text-left py-2">No</th>
                <th class="text-left py-2">Test Name</th>
                <th class="text-left py-2">Test Type</th>
                <th class="text-left py-2">Material Title</th>
                <th class="text-left py-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Fetch exam data from the database
              $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
              $testTypeFilter = isset($_GET['testTypeFilter']) ? $_GET['testTypeFilter'] : '';
              $page = isset($_GET['page']) ? $_GET['page'] : 1;

              $condition = "WHERE Tests.TestType LIKE '%$testTypeFilter%'";
              if (!empty($searchTerm)) {
                $condition .= " AND Materials.TitleMaterial LIKE '%$searchTerm%'";
              }

              $query = "SELECT Tests.*, Materials.TitleMaterial FROM Tests
                        INNER JOIN Materials ON Tests.MaterialID = Materials.MaterialID
                        $condition
                        LIMIT 15 OFFSET " . ($page - 1) * 15;
              $result = $conn->query($query);

              // Count total rows in the table
              $queryCount = "SELECT COUNT(*) AS count FROM Tests
                             INNER JOIN Materials ON Tests.MaterialID = Materials.MaterialID
                             $condition";
              $resultCount = $conn->query($queryCount);
              $rowCount = $resultCount->fetch_assoc()['count'];
              $totalPage = ceil($rowCount / 15);
              $no = 1;

              // Loop through the results and display data in rows
              while ($row = $result->fetch_assoc()) {
              ?>
                <tr>
                  <td class="py-2"><?php echo $no++; ?></td>
                  <td class="py-2"><?php echo $row['TestName']; ?></td>
                  <td class="py-2"><?php echo $row['TestType']; ?></td>
                  <td class="py-2"><?php echo $row['TitleMaterial']; ?></td>
                  <td class='py-2'>
                    <a href="<?php echo $baseUrl; ?>public/manage_exams/manage_exams_detail.php?id=<?php echo $row['TestID'] ?>" class='bg-green-500 hover-bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                        <i class='fas fa-eye mr-2'></i>
                        <span>Detail</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>public/manage_exams/manage_exams_update.php?id=<?php echo $row['TestID'] ?>" class='bg-blue-500 hover-bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                        <i class='fas fa-edit mr-2'></i>
                        <span>Edit</span>
                    </a>
                    <a href="#" onclick="confirmDelete(<?php echo $row['TestID']; ?>)" class='bg-red-500 hover-bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                        <i class='fas fa-trash mr-2'></i>
                        <span>Delete</span>
                    </a>
                  </td>
                </tr>
              <?php
              }
              if ($result->num_rows === 0) {
              ?>
                <tr>
                  <td colspan="5" class="py-2 text-center">No data found.</td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
          <!-- End Table -->
          <?php
          // Pagination
          ?>
          <div class="flex flex-row justify-between items-center w-full mt-4">
            <div class="flex flex-row justify-start items-center">
                <span class="text-gray-600">Total <?php echo $rowCount; ?> rows</span>
            </div>
            <div class="flex flex-row justify-end items-center space-x-2">
                <a href="?page=1&search=<?php echo $searchTerm; ?>&testTypeFilter=<?php echo $testTypeFilter; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-angle-double-left"></i>
                </a>
                <a href="?page=<?php if ($page == 1) {
                                echo $page;
                            } else {
                                echo $page - 1;
                            } ?>&search=<?php echo $searchTerm; ?>&testTypeFilter=<?php echo $testTypeFilter; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-angle-left"></i>
                </a>
                <!-- Page number -->
                <?php
                $startPage = $page - 2;
                $endPage = $page + 2;
                if ($startPage < 1) {
                    $endPage += abs($startPage) + 1;
                    $startPage = 1;
                }
                if ($endPage > $totalPage) {
                    $startPage -= $endPage - $totalPage;
                    $endPage = $totalPage;
                }
                if ($startPage < 1) {
                    $startPage = 1;
                }
                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $page) {
                        echo "<a href='?page=$i&search=$searchTerm&testTypeFilter=$testTypeFilter' class='bg-blue-500 hover-bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center'>$i</a>";
                    } else {
                        echo "<a href='?page=$i&search=$searchTerm&testTypeFilter=$testTypeFilter' class='bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center'>$i</a>";
                    }
                }
                ?>
                <a href="?page=<?php if ($page == $totalPage) {
                                echo $page;
                            } else {
                                echo $page + 1;
                            } ?>&search=<?php echo $searchTerm; ?>&testTypeFilter=<?php echo $testTypeFilter; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-angle-right"></i>
                </a>
                <a href="?page=<?php echo $totalPage; ?>&search=<?php echo $searchTerm; ?>&testTypeFilter=<?php echo $testTypeFilter; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </div>
            <div class="flex flex-row justify-end items-center ml-2">
                <span class="text-gray-600">Page <?php echo $page; ?> of <?php echo $totalPage; ?></span>
            </div>
        </div>
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
<script>
  // Function to show a confirmation dialog for deletion
  function confirmDelete(testID) {
    Swal.fire({
      title: 'Are you sure?',
      text: 'You won\'t be able to revert this!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // If the user confirms, redirect to the delete page
        window.location.href = `manage_exams_delete.php?id=${testID}`;
      }
    });
  }

  // Event listener for test type filter
  document.getElementById('testTypeFilter').addEventListener('change', function() {
    const testTypeFilter = this.value;
    window.location.href = `manage_exams_list.php?testTypeFilter=${testTypeFilter}`;
  });
</script>

</body>

</html>
