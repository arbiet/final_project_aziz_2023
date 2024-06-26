<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');

// Initialize variables
$materialID = '';
$errors = array();
$materialData = array();

// Retrieve material data
if (isset($_GET['id'])) {
    $materialID = $_GET['id'];
    $query = "SELECT m.MaterialID, m.SubjectID, m.TitleMaterial, m.Type, m.Content, m.Link, m.Sequence
              FROM Materials m
              WHERE m.MaterialID = $materialID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $materialData = $result->fetch_assoc();
    } else {
        $errors[] = "Material not found.";
    }
}
?>

<?php include_once('../components/header2.php'); ?>
<!-- Main Content Height Adjusts to Fit Between Header and Footer -->
<div class="h-screen flex flex-col">
    <!-- Top Navbar -->
    <?php include('../components/navbar.php'); ?>
    <!-- End Top Navbar -->
    <!-- Main Content -->
    <div class="flex-grow bg-gray-50 flex flex-row shadow-lg">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php'); ?>
        <!-- End Sidebar -->
        <main class="bg-gray-50 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <div class="flex items-start justify-start p-6 shadow-lg m-4 flex-1 flex-col">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Material Details</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="../manage_materials/manage_materials_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
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
                            <p class="text-gray-600 text-sm">Material information.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <!-- Material Details -->
                    <?php if (!empty($materialData)) : ?>
                        <div class="bg-white shadow-lg p-4 rounded-md">
                            <h3 class="text-lg font-semibold text-gray-800">Material Information</h3>
                            <p><strong>Material Title:</strong> <?php echo $materialData['TitleMaterial']; ?></p>
                            <p><strong>Type:</strong> <?php echo $materialData['Type']; ?></p>
                            <p><strong>Content:</strong></p>
                            <p><strong>Link:</strong> <?php echo $materialData['Link']; ?></p>
                            <p><strong>Sequence:</strong> <?php echo $materialData['Sequence']; ?></p>
                        </div>
                    <?php else : ?>
                        <div class="bg-white shadow-lg p-4 rounded-md">
                            <p>No material data available.</p>
                        </div>
                    <?php endif; ?>
                    <!-- End Material Details -->
                </div>
                <!-- End Content -->
            </div>
        </main>
    </div>

    <!-- End Main Content -->
    <!-- Footer -->
    <?php include('../components/footer.php'); ?>
    <!-- End Footer -->
</div>
</body>
</html>
