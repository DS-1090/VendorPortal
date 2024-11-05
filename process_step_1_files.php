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
        // Retrieve applicant_id from the request (you should ensure that this is sent along with the files)
        $applicant_id = $_POST['applicant_id'] ?? null;

        // Prepare the SQL statement
        $sql = "INSERT INTO uploads_1 (applicant_id, applicant_photo, address_proof, aadhaar_card, pan_card, gst_tan_document) 
                VALUES (:applicant_id, :applicant_photo, :address_proof, :aadhaar_card, :pan_card, :gst_tan_document)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':applicant_id', $applicant_id, PDO::PARAM_INT);

        // Check and bind each file input
        $files = [
            'photo-file-upload' => null,
            'uploadAadhar' => null,
            'uploadPan' => null,
            'uploadGstTan' => null,
            'addressProof' => null // Assuming you want to store address proof as well
        ];

        foreach ($files as $key => &$value) {
            if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                $value = file_get_contents($_FILES[$key]['tmp_name']);
            } else {
                $value = null; // Set to null if no file was uploaded or if there was an error
            }
        }

        // Bind file data
        $stmt->bindParam(':applicant_photo', $files['photo-file-upload'], PDO::PARAM_LOB);
        $stmt->bindParam(':address_proof', $files['addressProof'], PDO::PARAM_LOB);
        $stmt->bindParam(':aadhaar_card', $files['uploadAadhar'], PDO::PARAM_LOB);
        $stmt->bindParam(':pan_card', $files['uploadPan'], PDO::PARAM_LOB);
        $stmt->bindParam(':gst_tan_document', $files['uploadGstTan'], PDO::PARAM_LOB);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["message" => "Failed to upload files."]);
        }

         $uploadDir = 'uploads/step-1/';

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