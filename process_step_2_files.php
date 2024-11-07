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
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $project_id = $_POST['project_id'] ?? null;

        $sql = "INSERT INTO project_uploads (project_id, own_land_documents, lease_land_documents, bank_consent_document, signature) 
                VALUES (:project_id, :own_land_documents, :lease_land_documents, :bank_consent_document, :signature)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

        $own_land_documents = null;
        $lease_land_documents = null;
        $bank_consent_document = null;
        $signature = null;

        $applicantId = $_POST['applicantId'] ?? 'uploads';     
error_log("Applicant ID: " . $applicantId);
$uploadDir = $applicantId.'/step-2/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileInputs = [
            'ownLandDocuments' => 'own_land_documents',
            'leaseLandDocuments' => 'lease_land_documents',
            'bankConsentUpload' => 'bank_consent_document',
            'signature' => 'signature',
            'declaration'=> 'declaration'
        ];

        foreach ($fileInputs as $fileInputId => $dbColumn) {
            if (isset($_FILES[$fileInputId]) && $_FILES[$fileInputId]['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES[$fileInputId]['name']);
                $text=$fileInputId.'-'. date("m-Y"); 

                $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '_'.$text. '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $fileContent = file_get_contents($_FILES[$fileInputId]['tmp_name']);

                if (move_uploaded_file($_FILES[$fileInputId]['tmp_name'], $filePath)) {
                    $$dbColumn = $fileContent;
                } else {
                    echo json_encode(["status" => "error", "message" => "Error uploading file: " . $fileName]);
                    exit();
                }
            }
        }

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

        $stmt->bindParam(':own_land_documents', $own_land_documents, PDO::PARAM_LOB);
        $stmt->bindParam(':lease_land_documents', $lease_land_documents, PDO::PARAM_LOB);
        $stmt->bindParam(':bank_consent_document', $bank_consent_document, PDO::PARAM_LOB);
        $stmt->bindParam(':signature', $signature, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["message" => "Failed to upload files."]);
        }
                 $uploadDir = 'uploads/step-2/';

       
    } else {
        echo json_encode(["message" => "Invalid request method."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>