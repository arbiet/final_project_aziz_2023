<?php
session_start();
require_once('../../database/connection.php');
include_once('../components/header2.php');

if (!isset($_SESSION['UserID'])) {
    header("Location: ../systems/login.php");
    exit();
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $errors['missing_fields'] = 'All fields are required.';
    }

    if (strlen($new_password) < 8) {
        $errors['password_length'] = 'Password must be at least 8 characters long.';
    }

    if ($new_password !== $confirm_new_password) {
        $errors['password_match'] = 'New password and confirm password must match.';
    }

    if (empty($errors)) {
        $user_id = $_SESSION['UserID'];
        $select_query = "SELECT * FROM Users WHERE UserID = ?";
        $select_stmt = $conn->prepare($select_query);
        $select_stmt->bind_param('i', $user_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashed_current_password = hash('sha256', $current_password);
            
            if ($hashed_current_password === $user['Password']) {
                // Hash the new password
                $hashed_new_password = hash('sha256', $new_password);
                
                // Update the password in the database
                $update_query = "UPDATE Users SET Password = ? WHERE UserID = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param('si', $hashed_new_password, $user_id);
                
                if ($update_stmt->execute()) {
                    // Password updated successfully
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Password updated successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.href = 'change_password_student.php';
                        });
                    </script>";
                    exit();
                } else {
                    $errors['update_failed'] = 'Failed to update password.';
                }
            } else {
                $errors['current_password_incorrect'] = 'Current password is incorrect.';
            }
        } else {
            $errors['user_not_found'] = 'User not found.';
        }
    }
}
?>

<?php include('../components/navbar3.php'); ?>
<div class="p-8 pt-20 bg-teal-100">
<main class="w-full min-h-screen transition-all main">
    <div class="flex items-start justify-start p-6 shadow-lg m-4 bg-white flex-1 flex-col rounded-md">
        <h1 class="text-2xl text-gray-800 font-semibold border-b border-gray-200 w-full">Change Password</h1>
        <!-- Change Password Form -->
        <div class="flex flex-row w-full space-x-2 space-y-2 mt-4 mb-4">
            <form action="change_password_student.php" method="POST" class="flex flex-col w-full space-x-2 mt-4 mb-4" id="change-password-form">
                <!-- Current Password -->
                <label for="current_password" class="block font-semibold text-gray-800 mt-2 mb-2">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none px-2 py-2 border" required>

                <!-- New Password -->
                <label for="new_password" class="block font-semibold text-gray-800 mt-2 mb-2">New Password</label>
                <input type="password" id="new_password" name="new_password" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none px-2 py-2 border" required>

                <!-- Confirm New Password -->
                <label for="confirm_new_password" class="block font-semibold text-gray-800 mt-2 mb-2">Confirm New Password</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none px-2 py-2 border" required>

                <!-- Error Messages -->
                <?php if (!empty($errors)) : ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <?php foreach ($errors as $error) : ?>
                            <span class="block sm:inline"><?php echo $error; ?></span><br>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Submit Button -->
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                    Change Password
                </button>
            </form>
        </div>
        <!-- End Change Password Form -->
    </div>
</main>
</div>
<script>
    function confirmPasswordChange() {
        Swal.fire({
            title: 'Change Password',
            text: 'Are you sure you want to change your password?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, submit the form
                document.getElementById('change-password-form').submit();
            }
        });
    }
</script>
<?php include('../components/footer2.php'); ?>
