<?php
require_once('../../database/connection.php');
session_start();
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID']) || $_SESSION['RoleID'] !== 3) {
  // Jika tidak, arahkan ke halaman login
  header("Location: ../systems/login.php");
  exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
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
  } else {
    // Handle jika data siswa tidak ditemukan
    // Redirect atau tampilkan pesan kesalahan
  }
} else {
  // Handle jika UserID tidak tersedia di sesi
  // Redirect atau tampilkan pesan kesalahan
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Siswa - <?php echo $baseTitle; ?></title>
  <!-- Tailwind CSS -->
  <link rel="icon" href="<?php echo $baseUrl; ?>/static/logo.ico" type="image/png">
  <link rel="shortcut icon" href="<?php echo $baseUrl; ?>/static/logo.ico" type="image/png">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>dist/output.css">
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>node_modules/@fortawesome/fontawesome-free/css/all.min.css" />
  <!-- SweetAlert2 CSS and JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
</head>

<body class="overflow-hidden">
  <!-- Main Content Height Menyesuaikan Hasil Kurang dari Header dan Footer -->
  <div class="h-screen flex flex-col overflow-y-scroll flex-shrink-0 sc-hide">
    <!-- Navbar -->
    <header class="bg-blue-600 p-4 text-white">
      <nav class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Profil Siswa</h1>
        <a href="dashboard_student.php" class="text-gray-200 hover:underline">Kembali ke Dashboard</a>
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
          <p class="text-gray-500">Tanggal Lahir: <?php echo $studentData['DateOfBirth']; ?></p>
          <p class="text-gray-500">Jenis Kelamin: <?php echo $studentData['Gender']; ?></p>
          <p class="text-gray-500">Alamat: <?php echo $studentData['Address']; ?></p>
          <p class="text-gray-500">Nomor Telepon: <?php echo $studentData['PhoneNumber']; ?></p>
          <p class="text-gray-500">Agama: <?php echo $studentData['Religion']; ?></p>
          <p class="text-gray-500">Orang Tua / Wali: <?php echo $studentData['ParentGuardianFullName']; ?></p>
          <p class="text-gray-500">Alamat Orang Tua / Wali: <?php echo $studentData['ParentGuardianAddress']; ?></p>
          <p class="text-gray-500">Nomor Telepon Orang Tua / Wali: <?php echo $studentData['ParentGuardianPhoneNumber']; ?></p>
          <p class="text-gray-500">Email Orang Tua / Wali: <?php echo $studentData['ParentGuardianEmail']; ?></p>
        </div>
      </div>
    </div>
    <!-- Main Content -->
    <main class="container mx-auto mt-4 p-4">
      <!-- Tambahkan konten tambahan sesuai kebutuhan -->
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