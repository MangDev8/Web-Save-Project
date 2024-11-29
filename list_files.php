<?php
require 'config.php'; // Panggil token dari config.php

$url = 'https://api.dropboxapi.com/2/files/list_folder';
$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json',
];
$data = json_encode([
    'path' => '', // Root folder
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo 'Error: ' . $error;
} else {
    $files = json_decode($response, true)['entries'];

    if (empty($files)) {
        echo 'Tidak ada file yang diunggah.';
    } else {
        foreach ($files as $file) {
            echo '<div class="file-item">';
            echo '<a href="https://www.dropbox.com/home' . $file['path_display'] . '" target="_blank">';
            echo htmlspecialchars($file['name']);
            echo '</a>';
            echo '</div>';
        }
    }
}
?>
