<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$host = "pg-b3e567-vendor-portal.h.aivencloud.com";
$port = "16588";
$dbname = "portaldb"; 
$user = "avnadmin";
$password = "AVNS_nbjEuChQbhT3jY1CH8A";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $fileData = [];

        $fileInputs = [
            "backgroundIntroduction" => 'background_and_introduction',
            "projectDescription" => 'project_description',
            "objectives" => 'objectives',
            "benefitsJustification" => 'benefits_and_justification',
            "implementationDuration" => 'project_implementation_duration',
            "implementationStrategy" => 'project_strategy_plan'
        ];

        $uploadDir = 'uploads/step-3/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($fileInputs as $fileInputId => $dbColumn) {
            if (isset($_FILES[$fileInputId]) && $_FILES[$fileInputId]['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES[$fileInputId]['name']);
                                $text=$fileInputId.'-'. date("m-Y"); 

                $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '_' .$text . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $fileContent = file_get_contents($_FILES[$fileInputId]['tmp_name']);

                if (move_uploaded_file($_FILES[$fileInputId]['tmp_name'], $filePath)) {
                    $fileData[$dbColumn] = $fileContent;
                } else {
                    echo json_encode(["status" => "error", "message" => "Error uploading file: " . $fileName]);
                    exit();
                }
            }
        }

        $stmt = $pdo->prepare("INSERT INTO project_proposal_uploads 
            ( background_and_introduction, project_description, objectives, benefits_and_justification, project_implementation_duration, project_strategy_plan) 
            VALUES ( :background_and_introduction, :project_description, :objectives, :benefits_and_justification, :project_implementation_duration, :project_strategy_plan)");

        $stmt->bindParam(':background_and_introduction', $fileData['background_and_introduction'], PDO::PARAM_LOB);
        $stmt->bindParam(':project_description', $fileData['project_description'], PDO::PARAM_LOB);
        $stmt->bindParam(':objectives', $fileData['objectives'], PDO::PARAM_LOB);
        $stmt->bindParam(':benefits_and_justification', $fileData['benefits_and_justification'], PDO::PARAM_LOB);
        $stmt->bindParam(':project_implementation_duration', $fileData['project_implementation_duration'], PDO::PARAM_LOB);
        $stmt->bindParam(':project_strategy_plan', $fileData['project_strategy_plan'], PDO::PARAM_LOB);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error saving file data to database."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
