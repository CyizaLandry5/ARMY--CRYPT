<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "army_crypt";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle encryption
if (isset($_POST['encrypt'])) {
    if (isset($_POST['soldier_name']) && isset($_POST['rank']) && isset($_POST['mission_report'])) {
        $soldier_name = $_POST['soldier_name'];
        $rank = $_POST['rank'];
        $mission = $_POST['mission_report'];

        $publicKey = file_get_contents('keys/public.pem');

        if ($publicKey && openssl_public_encrypt($mission, $encrypted, $publicKey)) {
            $cipher_mission = base64_encode($encrypted);

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO encrypted_data (soldier_name, rank, mission_report, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $soldier_name, $rank, $cipher_mission);
            $stmt->execute();
            $stmt->close();

            $success = "Mission report encrypted and saved!";
        } else {
            $error = "Encryption failed! (Check public key)";
        }
    } else {
        $error = "Please fill all fields.";
    }
}

// Handle decryption
if (isset($_POST['decrypt'])) {
    if (isset($_POST['message_id'])) {
        $id = $_POST['message_id'];

        // Fetch the encrypted data by ID
        $sql = "SELECT soldier_name, rank, mission_report FROM encrypted_data WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($soldier_name, $rank, $ciphertext);
        $stmt->fetch();
        $stmt->close();

        if ($ciphertext) {
            $ciphertext = base64_decode($ciphertext);

            $privateKey = file_get_contents('keys/private.pem');

            if ($privateKey && openssl_private_decrypt($ciphertext, $decrypted_mission, $privateKey)) {
                // Successful decryption
            } else {
                $error = "Decryption failed! (Check private key)";
            }
        } else {
            $error = "No message found with that ID.";
        }
    } else {
        $error = "Please enter a message ID.";
    }
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

    <button onclick="showForm('encryptForm')">Send Encrypted Report</button>
    <button onclick="showForm('decryptForm')">View Mission Report</button>

    <?php if (isset($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

    <!-- Encrypt Form -->
    <div id="encryptForm" style="display:none;">
        <h2>Encrypt Mission Report</h2>
        <form method="post">
            <input type="text" name="soldier_name" placeholder="Soldier Name" required><br><br>
            <input type="text" name="rank" placeholder="Rank" required><br><br>
            <textarea name="mission_report" placeholder="Write Mission Report..." required></textarea><br><br>
            <button type="submit" name="encrypt">Encrypt & Save</button>
        </form>
    </div>

    <!-- Decrypt Form -->
    <div id="decryptForm" style="display:none;">
        <h2>Decrypt Mission Report</h2>
        <form method="post">
            <input type="number" name="message_id" placeholder="Enter Message ID" required><br><br>
            <button type="submit" name="decrypt">Decrypt</button>
        </form>

        <?php if (isset($decrypted_mission)): ?>
            <h3>Decrypted Mission Details</h3>
            <p><strong>Soldier Name:</strong> <?php echo htmlspecialchars($soldier_name); ?></p>
            <p><strong>Rank:</strong> <?php echo htmlspecialchars($rank); ?></p>
            <textarea readonly><?php echo htmlspecialchars($decrypted_mission); ?></textarea>
        <?php endif; ?>
    </div>

</body>
</html>
