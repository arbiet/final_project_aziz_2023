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
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Classes</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="<?php echo $baseUrl; ?>public/manage_classes/manage_classes_create.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
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
                        <div>
                            <h2 class="text-lg text-gray-800 font-semibold">Welcome back, <?php echo $_SESSION['FullName']; ?>!</h2>
                            <p class="text-gray-600 text-sm">Class information.</p>
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
                                <th class="text-left py-2">No</th>
                                <th class="text-left py-2">Class Code</th>
                                <th class="text-left py-2">Education Level</th>
                                <th class="text-left py-2">Homeroom Teacher</th>
                                <th class="text-left py-2">Curriculum</th>
                                <th class="text-left py-2">Academic Year</th>
                                <th class="text-left py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch class data from the database
                            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;

                            $query = "SELECT Classes.ClassID, Classes.ClassCode, Classes.EducationLevel, Teachers.AcademicDegree, Users.FullName AS HomeroomTeacher, Classes.Curriculum, Classes.AcademicYear
                            FROM Classes
                            LEFT JOIN Teachers ON Classes.HomeroomTeacher = Teachers.TeacherID
                            LEFT JOIN Users ON Teachers.UserID = Users.UserID
                            WHERE Classes.ClassName LIKE '%$searchTerm%'
                            LIMIT 15 OFFSET " . ($page - 1) * 15;

                            $result = $conn->query($query);

                            // Count total rows in the table
                            $queryCount = "SELECT COUNT(*) AS count FROM Classes WHERE ClassName LIKE '%$searchTerm%'";
                            $resultCount = $conn->query($queryCount);
                            $rowCount = $resultCount->fetch_assoc()['count'];
                            $totalPage = ceil($rowCount / 15);
                            $no = 1;

                            // Loop through the results and display data in rows
                            while ($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td class="py-2"><?php echo $no++; ?></td>
                                    <td class="py-2"><?php echo $row['ClassCode']; ?></td>
                                    <td class="py-2"><?php echo $row['EducationLevel']; ?></td>
                                    <td class="py-2">
                                        <?php
                                        if ($row['HomeroomTeacher']) {
                                            echo $row['HomeroomTeacher'];
                                            echo ' (' . $row['AcademicDegree'] . ')';
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td class="py-2"><?php echo $row['Curriculum']; ?></td>
                                    <td class="py-2"><?php echo $row['AcademicYear']; ?></td>

                                    <td class='py-2'>
                                        <a href="<?php echo $baseUrl; ?>public/manage_classes/manage_classes_detail.php?id=<?php echo $row['ClassID'] ?>" class='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                                            <i class='fas fa-eye mr-2'></i>
                                            <span>Detail</span>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>public/manage_classes/manage_classes_update.php?id=<?php echo $row['ClassID'] ?>" class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                                            <i class='fas fa-edit mr-2'></i>
                                            <span>Edit</span>
                                        </a>
                                        <a href="<?php echo $baseUrl; ?>public/manage_classes/manage_classes_add_subject.php?id=<?php echo $row['ClassID'] ?>" class='bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
                                            <i class='fas fa-plus mr-2'></i>
                                            <span>Add Subject</span>
                                        </a>
                                        <a href="#" onclick="confirmDelete(<?php echo $row['ClassID']; ?>)" class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm'>
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
        </main>
        <!-- End Main Content -->
    </div>
    <!-- End Main Content -->
    <!-- Footer -->
    <!-- End Footer -->
<!-- End Main Content -->
<script>
    // Function to show a confirmation dialog for deletion
    function confirmDelete(classID) {
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
                window.location.href = `manage_classes_delete.php?id=${classID}`;
            }
        });
    }
</script>
</main>
<?php include('../components/footer2.php'); ?>