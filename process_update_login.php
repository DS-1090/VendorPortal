<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Database connection details
    $host = "pg-b3e567-vendor-portal.h.aivencloud.com";
    $port = "16588";
    $dbname = "portaldb"; 
    $user = "avnadmin";
    $dbPassword = "AVNS_nbjEuChQbhT3jY1CH8A";

    try {
        // Set up a PDO connection
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
        $db = new PDO($dsn, $user, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the email exists
        $sql = "SELECT * FROM registration WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Update the password
            $update_sql = "UPDATE registration SET password = :password WHERE email = :email";
            $update_stmt = $db->prepare($update_sql);
            $update_stmt->bindParam(':password', $password);
            $update_stmt->bindParam(':email', $email);

            if ($update_stmt->execute()) {
                echo "<script>alert('Password Updated Successfully'); window.location.href = 'user_home.html';</script>";
            } else {
                echo "<script>alert('Error updating password'); window.location.href = 'user_forgotpw.html';</script>";
            }
        } else {
            echo "<script>alert('Email not found'); window.location.href = 'user_forgotpw.html';</script>";
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
