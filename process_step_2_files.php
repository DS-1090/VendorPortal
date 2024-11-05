<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Database connection details
$host = "pg-b3e567-vendor-portal.h.aivencloud.com";
$port = "16588";
$dbname = "portaldb"; 
$user = "avnadmin";
$password = "AVNS_nbjEuChQbhT3jY1CH8A";

try {
    // Establishing a database connection
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if files are uploaded
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve project_id from the request (you should ensure that this is sent along with the files)
        $project_id = $_POST['project_id'] ?? null;

        // Prepare the SQL statement for inserting data
        $sql = "INSERT INTO project_uploads (project_id, own_land_documents, lease_land_documents, bank_consent_document, signature) 
                VALUES (:project_id, :own_land_documents, :lease_land_documents, :bank_consent_document, :signature)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

        // Initialize file variables
        $own_land_documents = null;
        $lease_land_documents = null;
        $bank_consent_document = null;
        $signature = null;

        // Check and bind each file input
        if (isset($_FILES['ownLandDocuments']) && $_FILES['ownLandDocuments']['error'] === UPLOAD_ERR_OK) {
            $own_land_documents = file_get_contents($_FILES['ownLandDocuments']['tmp_name']);
        }

        if (isset($_FILES['leaseLandDocuments']) && $_FILES['leaseLandDocuments']['error'] === UPLOAD_ERR_OK) {
            $lease_land_documents = file_get_contents($_FILES['leaseLandDocuments']['tmp_name']);
        }

        if (isset($_FILES['bankConsentUpload']) && $_FILES['bankConsentUpload']['error'] === UPLOAD_ERR_OK) {
            $bank_consent_document = file_get_contents($_FILES['bankConsentUpload']['tmp_name']);
        }

        if (isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {
            $signature = file_get_contents($_FILES['signature']['tmp_name']);
        }

        // Bind file data
        $stmt->bindParam(':own_land_documents', $own_land_documents, PDO::PARAM_LOB);
        $stmt->bindParam(':lease_land_documents', $lease_land_documents, PDO::PARAM_LOB);
        $stmt->bindParam(':bank_consent_document', $bank_consent_document, PDO::PARAM_LOB);
        $stmt->bindParam(':signature', $signature, PDO::PARAM_LOB);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["message" => "Failed to upload files."]);
        }
                 $uploadDir = 'uploads/step-2/';

// Create uploads directory if it does not exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$t = time();
$text = date("m-Y", $t); // Use "-" instead of "/" for file naming

// Check and read each file input
foreach ($files as $key => &$value) {
    if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES[$key]['name']);
        // Create a new filename with the date suffix
        $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '_' . $text . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $fileContent = file_get_contents($_FILES[$key]['tmp_name']);

        if (move_uploaded_file($_FILES[$key]['tmp_name'], $filePath)) {
            $value = $fileContent; // Store the binary content for database insertion
        } else {
            $value = null; 
        }
    } else {
        $value = null;
    }
}
    } else {
        echo json_encode(["message" => "Invalid request method."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>