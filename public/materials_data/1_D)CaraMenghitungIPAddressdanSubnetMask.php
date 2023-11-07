<div class="bg-white rounded p-4 shadow relative shadow:md mb-4">
    <h1 class="text-3xl font-semibold">IP Address</h1>
    <h2 class="text-2xl font-semibold">D. Cara Menghitung IP Address dan Subnet Mask</h2>

    <h3 class="text-xl font-semibold">1. Cara Menghitung IP Address</h3>
    <p>
        Menghitung alamat IP melibatkan beberapa langkah penting, terutama saat Anda bekerja dengan subnetting untuk membagi jaringan menjadi sub-jaringan yang lebih kecil. Berikut adalah cara menghitung alamat IP:
    </p>

    <h4 class="text-lg font-semibold">a. Konversi ke Biner</h4>
    <p>
        Ubah setiap oktet dalam alamat IP ke format biner. Sebagai contoh, mari konversi alamat IP 192.168.0.1 ke biner:
        <br>192. => 11000000
        <br>168. => 10101000
        <br>0. => 00000000
        <br>1. => 00000001
        <br>Sehingga, alamat IP 192.168.0.1 dalam format biner adalah 11000000.10101000.00000000.00000001.
    </p>

    <h4 class="text-lg font-semibold">b. Subnetting (Jika Diperlukan)</h4>
    <p>
        Jika Anda bekerja dengan subnetting, tentukan berapa bit yang digunakan untuk bagian jaringan dan bagian host. Dalam subnetting, Anda membagi alamat IP menjadi dua bagian, yaitu bagian yang menunjukkan jaringan dan bagian yang menunjukkan host.
    </p>

    <table class="w-full border-collapse border border-gray-300 mt-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">CDIR</th>
                <th class="border border-gray-300 px-4 py-2">Subnet Mask (Decimal)</th>
                <th class="border border-gray-300 px-4 py-2">Subnet Mask (Binary)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/9</td>
                <td class="border border-gray-300 px-4 py-2">255.128.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.10000000.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/10</td>
                <td class="border border-gray-300 px-4 py-2">255.192.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11000000.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/11</td>
                <td class="border border-gray-300 px-4 py-2">255.224.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11100000.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/12</td>
                <td class="border border-gray-300 px-4 py-2">255.240.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11110000.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/13</td>
                <td class="border border-gray-300 px-4 py-2">255.248.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111000.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/14</td>
                <td class="border border-gray-300 px-4 py-2">255.252.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111100.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/15</td>
                <td class="border border-gray-300 px-4 py-2">255.254.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111110.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/16</td>
                <td class="border border-gray-300 px-4 py-2">255.255.0.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.00000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/17</td>
                <td class="border border-gray-300 px-4 py-2">255.255.128.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.10000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/18</td>
                <td class="border border-gray-300 px-4 py-2">255.255.192.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11000000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/19</td>
                <td class="border border-gray-300 px-4 py-2">255.255.224.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11100000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/20</td>
                <td class="border border-gray-300 px-4 py-2">255.255.240.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11110000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/21</td>
                <td class="border border-gray-300 px-4 py-2">255.255.248.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111000.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/22</td>
                <td class="border border-gray-300 px-4 py-2">255.255.252.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111100.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/23</td>
                <td class="border border-gray-300 px-4 py-2">255.255.254.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111110.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/24</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.0</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.00000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/25</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.128</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.10000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/26</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.192</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.11000000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/27</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.224</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.11100000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/28</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.240</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.11110000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/29</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.248</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.11111000</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/30</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.252</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.11111100</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/31</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.254</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.11111110</td>
            </tr>
            <tr>
                <td class="border border-gray-300 px-4 py-2">/32</td>
                <td class="border border-gray-300 px-4 py-2">255.255.255.255</td>
                <td class="border border-gray-300 px-4 py-2">11111111.11111111.11111111.11111111</td>
            </tr>
        </tbody>
    </table>


    <p>
        Dengan memahami Subnet Mask, Anda dapat menentukan berapa banyak bit yang digunakan untuk bagian jaringan dan bagian host dalam alamat IP yang Anda kerjakan.
    </p>

    <h4 class="text-lg font-semibold">c. Menggunakan Bagian Jaringan dan Bagian Host</h4>
    <p>
        Setelah Anda menentukan bagian jaringan dan bagian host, Anda dapat mengidentifikasi jaringan di mana alamat IP berada serta host yang terkait dengan jaringan tersebut. Ini berguna terutama ketika Anda melakukan routing atau mengatur aturan keamanan dalam jaringan Anda.
    </p>

    <h3 class="text-xl font-semibold">2. Cara Menghitung Subnet Mask</h3>
    <p>
        Subnet Mask digunakan untuk memisahkan alamat IP menjadi dua bagian, yaitu bagian jaringan dan bagian host. Subnet Mask biasanya dinyatakan dalam bentuk prefix CIDR (Classless Inter-Domain Routing) seperti /24, /16, atau /8. Jika Anda ingin menghitung Subnet Mask, Anda perlu memahami berapa banyak bit yang digunakan untuk bagian jaringan.
    </p>

    <p>
        Cara menghitung Subnet Mask adalah dengan menyediakan jumlah bit yang diatur sebagai bagian jaringan. Jumlah bit ini akan menjadi "1" pada digit biner dalam Subnet Mask.
    </p>

    <h3 class="text-xl font-semibold">3. Menentukan Blok Subnet dan IP Address</h3>
    <p>
        Setelah Anda memahami cara menghitung IP Address dan Subnet Mask, Anda dapat menentukan blok subnet dan alamat IP untuk host. Berikut adalah langkah-langkahnya:
    </p>

    <h4 class="text-lg font-semibold">a. Mengidentifikasi Subnet Mask</h4>
    <p>
        Pertama, identifikasi Subnet Mask yang akan digunakan dalam jaringan Anda. Ini akan menentukan berapa banyak bit yang digunakan untuk bagian jaringan dan bagian host.
    </p>

    <h4 class="text-lg font-semibold">b. Mengidentifikasi Blok Subnet</h4>
    <p>
        Dengan mengetahui Subnet Mask, Anda dapat mengidentifikasi blok subnet. Sebagai contoh, jika Anda menggunakan Subnet Mask /24 (255.255.255.0), Anda memiliki 256 alamat IP yang tersedia dalam blok subnet.
    </p>

    <h4 class="text-lg font-semibold">c. Menentukan IP Network</h4>
    <p>
        IP Network adalah alamat pertama dalam blok subnet yang digunakan untuk menunjukkan jaringan itu sendiri. Biasanya, alamat ini memiliki semua bit host diatur ke 0.
    </p>

    <h4 class="text-lg font-semibold">d. Menentukan IP Start dan End</h4>
    <p>
        IP Start adalah alamat host pertama dalam blok subnet, sementara IP End adalah alamat host terakhir. Untuk menghitung IP Start, tinggal tambahkan 1 pada IP Network. Untuk menghitung IP End, kurangkan 1 dari alamat yang tersedia setelah IP Network.
    </p>

    <h4 class="text-lg font-semibold">e. Menentukan IP Broadcast</h4>
    <p>
        IP Broadcast adalah alamat yang digunakan untuk mengirim data ke seluruh host dalam blok subnet. Biasanya, alamat ini memiliki semua bit host diatur ke 1.
    </p>

    <p>
        Dengan langkah-langkah ini, Anda dapat dengan tepat menghitung alamat IP dan mengorganisasi jaringan Anda sesuai kebutuhan Anda.
    </p>
    <h2 class="text-2xl font-semibold mb-2">Contoh Soal:</h2>
    <p>Anda diberikan alamat IP 192.168.3.50 dengan subnet mask 255.255.255.240. Hitung subnet mask dalam notasi CIDR, tentukan blok subnet, alamat IP jaringan, alamat IP awal, alamat IP akhir, dan alamat IP broadcast.</p>

    <h2 class="text-2xl font-semibold my-2">Rumus:</h2>
    <ul class="list-disc list-inside ml-6">
        <li>Subnet mask dalam notasi CIDR = 32 - jumlah bit yang diatur dalam subnet mask.</li>
        <li>Blok subnet adalah alamat IP AND subnet mask.</li>
        <li>Alamat IP jaringan adalah blok subnet.</li>
        <li>Alamat IP awal adalah alamat jaringan + 1.</li>
        <li>Alamat IP akhir adalah alamat jaringan + 2^n - 2 (di mana n adalah jumlah bit yang digunakan untuk host).</li>
        <li>Alamat IP broadcast adalah alamat jaringan + 2^n - 1.</li>
    </ul>

    <h2 class="text-2xl font-semibold my-2">Jawaban:</h2>
    <p>Subnet mask dalam notasi CIDR = 32 - jumlah bit yang diatur dalam subnet mask</p>
    <p>Subnet mask dalam notasi CIDR = 32 - 28 (karena 255.255.255.240 memiliki 28 bit yang diatur dalam subnet mask)</p>
    <p>Subnet mask dalam notasi CIDR = 4</p>
    <p>Jadi, subnet mask dalam notasi CIDR adalah /4.</p>

    <p>Blok subnet adalah alamat IP AND subnet mask</p>
    <p>192.168.3.50 AND 255.255.255.240 = 192.168.3.48</p>
    <p>Blok subnet adalah 192.168.3.48/4.</p>

    <p>Alamat IP jaringan adalah blok subnet</p>
    <p>Alamat IP jaringan adalah 192.168.3.48.</p>

    <p>Alamat IP awal adalah alamat jaringan + 1</p>
    <p>Alamat IP awal adalah 192.168.3.49.</p>

    <p>Alamat IP akhir adalah alamat jaringan + 2^n - 2</p>
    <p>2^4 - 2 = 16 - 2 = 14</p>
    <p>Alamat IP akhir adalah 192.168.3.62.</p>

    <p>Alamat IP broadcast adalah alamat jaringan + 2^n - 1</p>
    <p>2^4 - 1 = 16 - 1 = 15</p>
    <p>Alamat IP broadcast adalah 192.168.3.63.</p>

    <p>Jadi, dengan alamat IP 192.168.3.50 dan subnet mask 255.255.255.240, subnet mask dalam notasi CIDR adalah /4, blok subnet adalah 192.168.3.48/4, alamat IP jaringan adalah 192.168.3.48, alamat IP awal adalah 192.168.3.49, alamat IP akhir adalah 192.168.3.62, dan alamat IP broadcast adalah 192.168.3.63.</p>

</div>