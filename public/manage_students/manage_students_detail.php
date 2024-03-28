<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');

// Initialize variables
$studentID = '';
$errors = array();
$studentData = array();

// Retrieve student data
if (isset($_GET['id'])) {
    $studentID = $_GET['id'];
    $query = "SELECT s.StudentID, s.StudentNumber, s.Religion, s.ParentGuardianFullName, s.ParentGuardianAddress, s.ParentGuardianPhoneNumber, s.ParentGuardianEmail, u.Username, u.Email, u.FullName, u.DateOfBirth, u.Gender, u.Address, u.PhoneNumber, r.RoleName
              FROM Students s
              LEFT JOIN Users u ON s.UserID = u.UserID
              LEFT JOIN Role r ON u.RoleID = r.RoleID
              WHERE s.StudentID = $studentID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $studentData = $result->fetch_assoc();
    } else {
        $errors[] = "Student not found.";
    }
}

?>
<?php include_once('../components/header.php'); ?>
<?php include('../components/sidebar2.php'); ?>
<main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-200 min-h-screen transition-all main">
    <?php include('../components/navbar2.php'); ?>
    <!-- Content -->
    <div class="p-4">
        <!-- Main Content -->
        <div class="flex items-start justify-start p-6 shadow-lg m-4 bg-white flex-1 flex-col rounded-md">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Student Details</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="../manage_students/manage_students_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
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
                            <p class="text-gray-600 text-sm">Student information.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <!-- Student Details -->
                    <?php if (!empty($studentData)) : ?>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-200 shadow-lg p-4 rounded-md">
                                <h3 class="text-lg font-semibold text-gray-800">Student Information</h3>
                                <p><strong>Student Number:</strong> <?php echo $studentData['StudentNumber']; ?></p>
                                <p><strong>Religion:</strong> <?php echo $studentData['Religion']; ?></p>
                                <p><strong>Parent/Guardian Full Name:</strong> <?php echo $studentData['ParentGuardianFullName']; ?></p>
                                <p><strong>Parent/Guardian Address:</strong> <?php echo $studentData['ParentGuardianAddress']; ?></p>
                                <p><strong>Parent/Guardian Phone Number:</strong> <?php echo $studentData['ParentGuardianPhoneNumber']; ?></p>
                                <p><strong>Parent/Guardian Email:</strong> <?php echo $studentData['ParentGuardianEmail']; ?></p>
                                <p><strong>Username:</strong> <?php echo $studentData['Username']; ?></p>
                                <p><strong>Email:</strong> <?php echo $studentData['Email']; ?></p>
                            </div>
                            <div class="bg-blue-200 shadow-lg p-4 rounded-md">
                                <h3 class="text-lg font-semibold text-gray-800">Personal Information</h3>
                                <p><strong>Full Name:</strong> <?php echo $studentData['FullName']; ?></p>
                                <p><strong>Date of Birth:</strong> <?php echo $studentData['DateOfBirth']; ?></p>
                                <p><strong>Gender:</strong> <?php echo $studentData['Gender']; ?></p>
                                <p><strong>Address:</strong> <?php echo $studentData['Address']; ?></p>
                                <p><strong>Phone Number:</strong> <?php echo $studentData['PhoneNumber']; ?></p>
                                <p><strong>Role:</strong> <?php echo $studentData['RoleName']; ?></p>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="bg-white shadow-lg p-4 rounded-md">
                            <p>No student data available.</p>
                        </div>
                    <?php endif; ?>
                    <!-- End Student Details -->
                </div>
                <!-- End Content -->
        </div>
    </div>
</main>
<?php include('../components/footer2.php'); ?>