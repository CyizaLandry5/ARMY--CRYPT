<?php
// Handle encryption
if (isset($_POST['encrypt'])) {
    $message = $_POST['message'];
    $publicKey = file_get_contents('keys/public.pem');
    openssl_public_encrypt($message, $encrypted, $publicKey);
    $ciphertext = base64_encode($encrypted);
}

// Handle decryption
if (isset($_POST['decrypt'])) {
    $ciphertext = base64_decode($_POST['ciphertext']);
    $privateKey = file_get_contents('keys/private.pem');
    openssl_private_decrypt($ciphertext, $decrypted, $privateKey);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Army Crypt</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function showForm(formId) {
            document.getElementById('encryptForm').style.display = 'none';
            document.getElementById('decryptForm').style.display = 'none';
            document.getElementById(formId).style.display = 'block';
        }
    </script>
</head>
<body>

    <h1>Army Crypt System</h1>

    <button onclick="showForm('encryptForm')">Send Encrypted Message</button>
    <button onclick="showForm('decryptForm')">View Message</button>

    <!-- Encrypt Form -->
    <div id="encryptForm" style="display:none;">
        <h2>Encrypt Message</h2>
        <form method="post">
            <textarea name="message" placeholder="Type your message..." required></textarea><br>
            <button type="submit" name="encrypt">Encrypt & Save</button>
        </form>

        <?php if (isset($ciphertext) && isset($_POST['encrypt'])): ?>
            <h3>Encrypted Message (Save it somewhere safe)</h3>
            <textarea readonly><?php echo $ciphertext; ?></textarea>
        <?php endif; ?>
    </div>

    <!-- Decrypt Form -->
    <div id="decryptForm" style="display:none;">
        <h2>Decrypt Message</h2>
        <form method="post">
            <textarea name="ciphertext" placeholder="Paste encrypted message..." required></textarea><br>
            <button type="submit" name="decrypt">Decrypt</button>
        </form>

        <?php if (isset($decrypted) && isset($_POST['decrypt'])): ?>
            <h3>Decrypted Message</h3>
            <textarea readonly><?php echo $decrypted; ?></textarea>
        <?php endif; ?>
    </div>

</body>
</html>
