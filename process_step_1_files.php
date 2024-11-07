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
        $applicant_id = $_POST['applicantId'] ?? null;

        $sql = "INSERT INTO uploads_1 (applicant_photo, address_proof, aadhaar_card, pan_card, gst_tan_document, community_certificate) 
                VALUES (:applicant_photo, :address_proof, :aadhaar_card, :pan_card, :gst_tan_document, :community_certificate)";
        $stmt = $pdo->prepare($sql);

        $files = [
            'photo-file-upload' => null,
            'uploadAadhar' => null,
            'uploadPan' => null,
            'uploadGstTan' => null,
            'addressProof' => null, 
            'communityCertificate' => null
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
        $stmt->bindParam(':community_certificate', $files['communityCertificate'], PDO::PARAM_LOB);
        

        if ($stmt->execute()) {
            echo json_encode(["message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["message" => "Failed to upload files."]);
        }

        // Directory Creation
        $uploadDir = $applicant_id . '/step-1/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // File Saving Process
        $t = time();
        $text = date("m-Y", $t); 
        $fileInfo = [];

        foreach ($files as $key => &$value) {
            if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES[$key]['name']);
                $text = $key . '-' . $text; 
                $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '_' . $text . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

                // Use move_uploaded_file instead of file_put_contents for moving the file from temp location
                if (move_uploaded_file($_FILES[$key]['tmp_name'], $filePath)) {
                    $fileInfo[] = [
                        "key" => $key,
                        "fileName" => $fileName,
                        "fileSize" => $_FILES[$key]['size'],
                        "fileType" => $_FILES[$key]['type'],
                        "filePath" => $filePath
                    ];
                    error_log("File uploaded successfully: $filePath");
                } else {
                    error_log("File NOT uploaded successfully: $filePath");
                }
            }
        }

        // Send back file info to JavaScript
        echo json_encode(["message" => "Files processed.", "fileInfo" => $fileInfo]);

    } else {
        echo json_encode(["message" => "Invalid request method."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
