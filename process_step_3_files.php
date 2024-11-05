<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Database connection parameters
$host = "pg-b3e567-vendor-portal.h.aivencloud.com";
$port = "16588";
$dbname = "portaldb"; 
$user = "avnadmin";
$password = "AVNS_nbjEuChQbhT3jY1CH8A";

try {
    // Create a new PDO instance
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve proposal_id from the request
        $proposal_id = $_POST['proposal_id'] ?? null;

        // Check if proposal_id is provided
        if ($proposal_id === null) {
            echo json_encode(["status" => "error", "message" => "Proposal ID is required."]);
            exit();
        }

        // Array to hold file data for database insertion
        $fileData = [];

        // Loop through each file input
        $fileInputs = [
            "backgroundIntroduction" => 'background_and_introduction',
            "projectDescription" => 'project_description',
            "objectives" => 'objectives',
            "benefitsJustification" => 'benefits_and_justification',
            "implementationDuration" => 'project_implementation_duration',
            "implementationStrategy" => 'project_strategy_plan'
        ];

        foreach ($fileInputs as $fileInputId => $dbColumn) {
            if (isset($_FILES[$fileInputId])) {
                // Loop through all files for the current input
                foreach ($_FILES[$fileInputId]['tmp_name'] as $key => $tmpName) {
                    // Read the file content
                    $fileContent = file_get_contents($tmpName);
                    if ($fileContent === false) {
                        echo json_encode(["status" => "error", "message" => "Error reading file: " . $_FILES[$fileInputId]['name'][$key]]);
                        exit();
                    }

                    // Store the file content for insertion into the database
                    $fileData[$dbColumn][] = $fileContent;
                }
            }
        }

        // Prepare SQL statement to insert file data into project_proposal_uploads
        $stmt = $db->prepare("INSERT INTO project_proposal_uploads 
            (proposal_id, background_and_introduction, project_description, objectives, benefits_and_justification, project_implementation_duration, project_strategy_plan) 
            VALUES (:proposal_id, :background_and_introduction, :project_description, :objectives, :benefits_and_justification, :project_implementation_duration, :project_strategy_plan)");

        // Bind parameters
        $stmt->bindParam(':proposal_id', $proposal_id, PDO::PARAM_INT);
        $stmt->bindParam(':background_and_introduction', $fileData['background_and_introduction'][0], PDO::PARAM_LOB);
        $stmt->bindParam(':project_description', $fileData['project_description'][0], PDO::PARAM_LOB);
        $stmt->bindParam(':objectives', $fileData['objectives'][0], PDO::PARAM_LOB);
        $stmt->bindParam(':benefits_and_justification', $fileData['benefits_and_justification'][0], PDO::PARAM_LOB);
        $stmt->bindParam(':project_implementation_duration', $fileData['project_implementation_duration'][0], PDO::PARAM_LOB);
        $stmt->bindParam(':project_strategy_plan', $fileData['project_strategy_plan'][0], PDO::PARAM_LOB);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error saving file data to database."]);
        }

                $uploadDir = 'uploads/step-3/';

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
        // Handle non-POST requests
        echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    }
} catch (PDOException $e) {
    // Handle connection errors
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
