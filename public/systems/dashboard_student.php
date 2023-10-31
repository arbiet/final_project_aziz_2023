<?php
// get file connection.php
require_once('../../database/connection.php');
include('../components/header.php');
// Initialize the session
session_start();
// Mengambil data profil siswa dan kelas siswa dari database berdasarkan UserID sesi
if (isset($_SESSION['UserID'])) {
  $userID = $_SESSION['UserID'];
  $query = "SELECT u.*, s.*, c.* 
              FROM Users u
              JOIN Students s ON u.UserID = s.UserID
              JOIN Classes c ON s.ClassID = c.ClassID
              WHERE u.UserID = $userID";

  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $studentData = mysqli_fetch_assoc($result);
  }
}

// Mengambil data mata pelajaran dari database
$subjectQuery = "SELECT * FROM Subjects";
$subjectResult = mysqli_query($conn, $subjectQuery);

$subjects = array();
if ($subjectResult && mysqli_num_rows($subjectResult) > 0) {
  while ($row = mysqli_fetch_assoc($subjectResult)) {
    $subjects[] = $row;
  }
}

?>

<body class="overflow-hidden">
  <!-- Main Content Height Menyesuaikan Hasil Kurang dari Header dan Footer -->
  <div class="h-screen flex flex-col overflow-y-scroll flex-shrink-0 sc-hide">
    <!-- Navbar -->
    <header class="bg-blue-600 p-4 text-white">
      <nav class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Dashboard Siswa</h1>
        <a href="#" class="text-gray-200 hover:underline">Keluar</a>
      </nav>
    </header>
    <div class="bg-white p-6 shadow-lg mb-4 relative container mx-auto mt-8 rounded-lg border border-gray-200">
      <a href="#" class="absolute top-0 right-0 mt-4 mr-4 text-blue-500 hover:text-blue-700">
        <i class="fas fa-edit text-xl"></i>
      </a>
      <!-- Badge Identifikasi -->
      <span class="bg-green-400 text-white text-xs px-2 py-1 rounded-full absolute -top-2 -left-2">Active</span>
      <div class="flex items-center">
        <div class="w-20 h-20 bg-gray-300 rounded-full overflow-hidden">
          <img src="../static/image/profile/<?php echo $studentData['ProfilePictureURL']; ?>" alt="Profil Siswa" class="w-full h-full object-cover" />
        </div>
        <div class="ml-4">
          <h3 class="text-2xl font-semibold"><?php echo $studentData['FullName']; ?></h3>
          <p class="text-gray-500">Class : <?php echo $studentData['ClassName']; ?></p>
          <p class="text-gray-500">NISN: <?php echo $studentData['StudentNumber']; ?></p>
          <p class="text-gray-500">Email: <?php echo $studentData['Email']; ?></p>
        </div>
      </div>
    </div>
    <!-- Main Content -->
    <main class="container mx-auto mt-4 p-4">
      <h2 class="text-2xl font-semibold mb-4">Daftar Mata Pelajaran</h2>
      <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Loop through each subject and display it in a card -->
        <?php foreach ($subjects as $subject) { ?>
          <div class="bg-white rounded p-4 shadow relative">
            <!-- Tombol titik tiga (ellipsis) di pojok kanan atas -->
            <div class="absolute top-0 right-0 mt-2 mr-2">
              <button class="text-gray-600 hover:text-gray-700 rounded-full p-2">
                <i class="fas fa-ellipsis-v"></i>
              </button>
            </div>
            <h3 class="text-lg font-semibold"><?php echo $subject['SubjectName']; ?></h3>
            <p class="text-gray-500">Guru: John Doe</p>
            <!-- Tulisan "Materi" disamping "Lihat Materi" -->
            <div class="mt-2 flex justify-between items-center">
              <a href="#" class="text-blue-500 hover:underline">Lihat Materi</a>
              <span class="text-blue-500 font-semibold">Materi: 5/10</span>
            </div>
            <div class="mt-4 mb-4">
              <label for="progress-<?php echo $subject['SubjectID']; ?>" class="text-sm font-semibold block">Progress:</label>
              <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                  <div>
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full bg-green-200 text-green-600">
                      50%
                    </span>
                  </div>
                  <div class="text-right">
                    <span class="text-xs font-semibold inline-block text-green-600">
                      100%
                    </span>
                  </div>
                </div>
                <div class="relative w-full bg-gray-200 rounded-full">
                  <div class="absolute left-0 bg-green-600 py-1 rounded-full" style="width: 50%"></div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </main>
    <!-- Footer -->
    <footer class="p-4 text-gray-600">
      <div class="container mx-auto text-center">
        &copy; <?php echo date('Y'); ?> <?php echo $baseTitle; ?> - All Rights Reserved
      </div>
    </footer>
  </div>
</body>

</html>