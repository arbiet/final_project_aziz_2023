<div class="bg-white rounded p-4 shadow relative shadow:md mb-4">
    <h2 class="text-2xl font-semibold">B. Subnetting dalam Jaringan</h2>

    <p>
        Subnetting adalah konsep penting dalam jaringan IP, memungkinkan penggunaan alamat IP yang efisien dan mengoptimalkan kinerja jaringan. Ini melibatkan pembagian jaringan IP yang besar menjadi sub-jaringan atau subnet yang lebih kecil dan mudah dikelola.
    </p>

    <p>
        <strong>Konsep Utama:</strong>
    </p>

    <ul class="list-disc list-inside pl-4">
        <li>
            <strong>Struktur Alamat IP:</strong>
            Alamat IP terdiri dari bagian jaringan dan host. Subnetting berfokus pada pembagian dan alokasi efektif dari kedua bagian ini.
        </li>
        <li>
            <strong>Subnet Mask:</strong>
            Subnet mask digunakan untuk mengidentifikasi bagian jaringan dan host dari alamat IP. Ini sangat penting dalam menentukan ukuran setiap subnet.
        </li>
        <li>
            <strong>Rentang Alamat:</strong>
            Setiap subnet memiliki rentang alamat IP yang spesifik. Memahami cara menghitung dan mengalokasikan rentang ini adalah dasar dalam subnetting.
        </li>
        <li>
            <strong>Manfaat Subnetting:</strong>
            Subnetting membantu mengurangi kemacetan jaringan, meningkatkan keamanan, dan menyederhanakan manajemen jaringan.
        </li>
    </ul>

    <img src="subnetting_example.png" alt="Contoh Subnetting" class="my-4 rounded-md">

    <p>
        <strong>Contoh Subnetting:</strong>
    </p>

    <p>
        Misalkan kita memiliki alamat IP seperti 192.168.1.0 dengan subnet mask 255.255.255.0. Dalam hal ini, oktet terakhir (0-255) digunakan untuk alamat host, memungkinkan hingga 254 perangkat. Jika subnetting diterapkan dengan subnet mask baru seperti 255.255.255.192, setiap subnet dapat mendukung hingga 62 host. Hal ini memberikan kontrol yang lebih terperinci terhadap alokasi alamat IP.
    </p>

    <p>
        Subnetting adalah keterampilan dasar bagi administrator dan insinyur jaringan, memungkinkan mereka merancang dan mengelola jaringan IP secara efisien. Ini memainkan peran penting dalam menciptakan arsitektur jaringan yang terorganisir dan dapat diskalakan.
    </p>
</div>
