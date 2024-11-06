<?php

// Database connection details
$host = "pg-b3e567-vendor-portal.h.aivencloud.com";
$port = "16588";
$dbname = "portaldb"; 
$user = "avnadmin";
$password = "AVNS_nbjEuChQbhT3jY1CH8A";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to the database '$dbname'!<br>";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["Email_Register"];
        $password = $_POST["Password_Register"];
        $aadhar = $_POST["Aadhar_no"];
        
        $sql = "INSERT INTO registration (Email, Password, Aadhar_number) VALUES (:email, :password, :aadhar)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':aadhar', $aadhar);

        if ($stmt->execute()) {
            echo "Record saved successfully!";
        } else {
            echo "Error saving record.";
        }
    }
}
catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
