<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    echo "Pesan berhasil diterima. Terima kasih $name.";
} else {
    echo "Akses tidak sah.";
}
?>
