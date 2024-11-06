ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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
        $applicant_id = null;

        $sql = "INSERT INTO uploads_1 (applicant_id, applicant_photo, address_proof, aadhaar_card, pan_card, gst_tan_document) 
                VALUES (:applicant_id, :applicant_photo, :address_proof, :aadhaar_card, :pan_card, :gst_tan_document)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':applicant_id', $applicant_id, PDO::PARAM_INT);

        $files = [
            'photo-file-upload' => null,
            'uploadAadhar' => null,
            'uploadPan' => null,
            'uploadGstTan' => null,
            'addressProof' => null, 
            'categoryApplied' => null
        ];

        foreach ($files as $key => &$value) {
            if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                $value = file_get_contents($_FILES[$key]['tmp_name']);
            } else {
                $value = null; 
            }
        }

        $stmt->bindParam(':applicant_photo', $files['photo-file-upload'], PDO::PARAM_LOB);
        $stmt->bindParam(':address_proof', $files['addressProof'], PDO::PARAM_LOB);
        $stmt->bindParam(':aadhaar_card', $files['uploadAadhar'], PDO::PARAM_LOB);
        $stmt->bindParam(':pan_card', $files['uploadPan'], PDO::PARAM_LOB);
        $stmt->bindParam(':gst_tan_document', $files['uploadGstTan'], PDO::PARAM_LOB);
      


        if ($stmt->execute()) {
            echo json_encode(["message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["message" => "Failed to upload files."]);
        }


$applicantId = $_POST['applicantId'] ?? 'uploads';     
error_log("Applicant ID: " . $applicantId);
$uploadDir = $applicantId.'/step-1/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$t = time();
$text = date("m-Y", $t); 

foreach ($files as $key => &$value) {
    if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES[$key]['name']);
        $text=$key.'-'.$text; 
        $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '_' . $text . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $fileContent = file_get_contents($_FILES[$key]['tmp_name']);

        if (move_uploaded_file($_FILES[$key]['tmp_name'], $filePath)) {
            $value = $fileContent; 
        } else {
            $value = null; 
        }
         echo "File Key: $key\n";
        echo "File Name: $fileName\n";
        echo "File Size: $fileSize bytes\n";
        echo "File Type: $fileType\n";
        echo "File Path: $filePath\n";

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