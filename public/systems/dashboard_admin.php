<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');

// Initialize variables
$username = $password = '';
$errors = array();


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user inputs
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform basic validation
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        // Hash the password for comparison
        $hashed_password = hash('sha256', $password);

        // Prepare and execute a query to check user credentials
        $query = "SELECT * FROM Users WHERE Username = ? AND Password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $username, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows === 1) {
            // Perform login actions (e.g., set sessions, redirect, etc.)
            $row = $result->fetch_assoc();
            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['RoleID'] = $row['RoleID'];
            $_SESSION['Email'] = $row['Email'];
            $_SESSION['FullName'] = $row['FullName'];

            if (isset($_SESSION['UserID'])) {
                $roleID = $_SESSION['RoleID']; // Dapatkan RoleID dari sesi

                // Pengecekan peran
                if (
                    $roleID == 1
                ) {
                    // Admin login, redirect to admin dashboard
                    header('Location: dashboard_admin.php');
                    exit();
                } elseif ($roleID == 2) {
                    // Teacher login, redirect to teacher dashboard
                    header('Location: dashboard_teacher.php');
                    exit();
                } elseif ($roleID == 3) {
                    // Student login, redirect to student dashboard
                    header('Location: dashboard_student.php');
                    exit();
                }
            }
            exit();
        } else {
            $errors['login_failed'] = 'Invalid username or password.';
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<?php include('../components/header.php'); ?>
<!-- Main Content Height Menyesuaikan Hasil Kurang dari Header dan Footer -->
<div class="h-screen flex flex-col">
    <!-- Top Navbar -->
    <?php include('../components/navbar.php'); ?>
    <!-- End Top Navbar -->
    <!-- Main Content -->
    <div class="bg-gray-50 flex flex-row shadow-md">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php'); ?>
        <!-- End Sidebar -->
        <!-- Main Content -->
        <main class=" bg-gray-50 flex flex-col flex-1">
            <div class="flex items-start justify-start p-6 shadow-md m-4 flex-1 flex-col">
                <h1 class="text-3xl text-gray-800 font-semibold border-b border-gray-200 w-full">Dashboard</h1>
                <h2 class="text-xl text-gray-800 font-semibold">
                    Welcome back, <?php echo $_SESSION['FullName']; ?>!
                    <?php
                    if ($_SESSION['RoleID'] === 1) {
                        echo " (Admin)";
                    } elseif ($_SESSION['RoleID'] === 2) {
                        echo " (Student)";
                    } elseif ($_SESSION['RoleID'] === 3) {
                        echo " (Teacher)";
                    }
                    ?>
                </h2>
                
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