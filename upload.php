<?php
require 'config.php'; // Panggil token dari config.php

if (isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];

    $dropboxPath = '/' . $fileName;

    $url = 'https://content.dropboxapi.com/2/files/upload';
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/octet-stream',
        'Dropbox-API-Arg: ' . json_encode([
            'path' => $dropboxPath,
            'mode' => 'add',
            'autorename' => true,
            'mute' => false,
        ]),
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        die('Error: ' . $error);
    } else {
        echo "File berhasil diunggah!";
        header('Location: index.html'); // Kembali ke halaman utama
    }
}
?>
