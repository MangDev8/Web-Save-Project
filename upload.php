<?php
// Masukkan Access Token Dropbox Anda
$accessToken = 'sl.CBR3tH6s__jAgLkASSkU_Ufy5Yr5Zp5b3ybHkQr976Ut9ZiCBWeSGZzbT_8TkY4luFJkeYhFNPbXEOXIAXAi_o5IAHrY6v2kUsh5_4gwmST8Qr11g2BBEFFn2oUJpkMLlMya6HCFiRhl'; // Ganti dengan Access Token kamu dari Dropbox

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileTempPath = $file['tmp_name'];
    $fileName = $file['name'];
    $fileSize = $file['size'];

    // Validasi file (hanya file ZIP dengan ukuran maksimum 10MB)
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    if (strtolower($fileType) !== 'zip') {
        die("Error: Hanya file ZIP yang diperbolehkan!");
    }

    if ($fileSize > 10 * 1024 * 1024) {
        die("Error: Ukuran file terlalu besar. Maksimum 10MB!");
    }

    // Nama file yang akan disimpan di Dropbox
    $dropboxPath = '/' . $fileName;

    // URL API Dropbox
    $url = 'https://content.dropboxapi.com/2/files/upload';

    // Header permintaan
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/octet-stream',
        'Dropbox-API-Arg: ' . json_encode([
            'path' => $dropboxPath,
            'mode' => 'add',
            'autorename' => true,
            'mute' => false
        ])
    ];

    // Inisialisasi CURL untuk unggahan
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($fileTempPath));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Eksekusi permintaan CURL
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Debugging: Tampilkan hasil atau error
    if ($error) {
        die("Error CURL: " . $error);
    }

    if ($httpCode === 200) {
        echo "File berhasil diunggah ke Dropbox: " . $dropboxPath;
    } else {
        echo "Error Dropbox: HTTP Code " . $httpCode . " - Response: " . $response;
    }
} else {
    echo "Tidak ada file yang diunggah.";
}
?>