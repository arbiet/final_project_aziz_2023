<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
  // Jika tidak, arahkan ke halaman login
  header("Location: ../systems/login.php");
  exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
// Initialize variables
$errors = array();

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
            <h1 class="text-3xl text-gray-800 font-semibold w-full">Students</h1>
            <?php
              if(!$isTeacher && !$isHomeroomTeacher){
            ?>
            <div class="flex flex-row justify-end items-center">
              <a href="<?php echo $baseUrl; ?>public/manage_students/manage_students_create.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                <span>Create</span>
              </a>
            </div>
            <?php
              }
            ?>
          </div>
          <!-- End Header Content -->
          <!-- Content -->
          <div class="flex flex-col w-full">
            <!-- Navigation -->
            <div class="flex flex-row justify-between items-center w-full mb-2 pb-2">
              <div>
                <h2 class="text-lg text-gray-800 font-semibold">Welcome back, <?php echo $_SESSION['FullName']; ?>!</h2>
                <p class="text-gray-600 text-sm">Student information. <?php echo $teacherID; ?></p>
              </div>
              <!-- Search -->
              <form class="flex items-center justify-end space-x-2 w-96">
                <input type="text" name="search" class="bg-gray-200 focus-bg-white focus-outline-none border border-gray-300 rounded-lg py-2 px-4 block w-full appearance-none leading-normal" placeholder="Search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded space-x-2 inline-flex items-center">
                  <i class="fas fa-search"></i>
                  <span>Search</span>
                </button>
              </form>
              <!-- End Search -->
            </div>
            <!-- End Navigation -->
            <!-- Table -->
            <table class="min-w-full">
              <thead>
                <tr>
                  <th class="text-left ">No</th>
                  <th class="text-left ">Student Number</th>
                  <th class="text-left ">Full Name</th>
                  <th class="text-left ">Phone Number</th>
                  <th class="text-left ">Email</th>
                  <th class="text-left ">Class</th> <!-- Add this column for Class -->
                  <th class="text-left ">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                // Initialize the query with the common part
                $query = "SELECT Students.StudentID, Students.StudentNumber, Users.FullName, Users.PhoneNumber, Users.Email, Classes.ClassCode 
                          FROM Students 
                          INNER JOIN Users ON Students.UserID = Users.UserID 
                          LEFT JOIN Classes ON Students.ClassID = Classes.ClassID 
                          WHERE (Students.StudentNumber LIKE '%$searchTerm%' 
                          OR Users.FullName LIKE '%$searchTerm%' 
                          OR Users.PhoneNumber LIKE '%$searchTerm%' 
                          OR Users.Email LIKE '%$searchTerm%')";
                
                // Append additional conditions if the user is a teacher and a homeroom teacher
                if ($isTeacher && $isHomeroomTeacher) {
                    $query .= " AND Students.ClassID = $KelaseDewe";
                }
                
                // Add LIMIT and OFFSET to the query
                $query .= " LIMIT 15 OFFSET " . ($page - 1) * 15;
                
                $result = $conn->query($query);
                
                // Count total rows in the table
                $queryCount = "SELECT COUNT(*) AS count 
                              FROM Students 
                              INNER JOIN Users ON Students.UserID = Users.UserID 
                              LEFT JOIN Classes ON Students.ClassID = Classes.ClassID";
                
                // Append additional conditions if the user is a teacher and a homeroom teacher
                if ($isTeacher && $isHomeroomTeacher) {
                    $queryCount .= " WHERE Classes.ClassID = $KelaseDewe";
                }
                
                $resultCount = $conn->query($queryCount);
                $rowCount = $resultCount->fetch_assoc()['count'];
                $totalPage = ceil($rowCount / 15);
                $no = 1;

                // Loop through the results and display data in rows
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td class=""><?php echo $no++; ?></td>
                    <td class=""><?php echo $row['StudentNumber']; ?></td>
                    <td class=""><?php echo $row['FullName']; ?></td>
                    <td class=""><?php echo $row['PhoneNumber']; ?></td>
                    <td class=""><?php echo $row['Email']; ?></td>
                    <td class="">
                      <?php
                      if ($row['ClassCode'] === null) {
                        echo "Belum memiliki kelas";
                      } else {
                        echo $row['ClassCode'];
                      }
                      ?>
                    </td>
                    <!-- Add this column for Class -->
                    <td class='py-2'>
                      <a href="<?php echo $baseUrl; ?>public/manage_students/manage_students_detail.php?id=<?php echo $row['StudentID'] ?>" class='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                        <i class='fas fa-eye mr-2'></i>
                        <span>Detail</span>
                      </a>
                      <a href="<?php echo $baseUrl; ?>public/manage_students/manage_students_update.php?id=<?php echo $row['StudentID'] ?>" class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                        <i class='fas fa-edit mr-2'></i>
                        <span>Edit</span>
                      </a>
                      <?php
                        if(!$isTeacher && !$isHomeroomTeacher){
                      ?>
                      <a href="#" onclick="confirmDelete(<?php echo $row['StudentID']; ?>)" class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                        <i class='fas fa-trash mr-2'></i>
                        <span>Delete</span>
                      </a>
                      <?php
                        }
                      ?>
                    </td>
                  </tr>
                <?php
                }
                if ($result->num_rows === 0) {
                ?>
                  <tr>
                    <td colspan="7" class="py-2 text-center">No data found.</td>
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
                <a href="?page=1&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                  <i class="fas fa-angle-double-left"></i>
                </a>
                <a href="?page=<?php if ($page == 1) {
                                  echo $page;
                                } else {
                                  echo $page - 1;
                                } ?>&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
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
                    echo "<a href='?page=$i&search=$searchTerm' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center'>$i</a>";
                  } else {
                    echo "<a href='?page=$i&search=$searchTerm' class='bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center'>$i</a>";
                  }
                }
                ?>
                <a href="?page=<?php if ($page == $totalPage) {
                                  echo $page;
                                } else {
                                  echo $page + 1;
                                } ?>&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                  <i class="fas fa-angle-right"></i>
                </a>
                <a href="?page=<?php echo $totalPage; ?>&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
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
    </div>
    <!-- End Main Content -->
<!-- End Main Content -->
<script>
  // Function to show a confirmation dialog for deletion
  function confirmDelete(studentID) {
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
        window.location.href = `manage_students_delete.php?id=${studentID}`;
      }
    });
  }
</script>

</main>
<?php include('../components/footer2.php'); ?>