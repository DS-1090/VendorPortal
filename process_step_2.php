<?php
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json");

$host = "pg-b3e567-vendor-portal.h.aivencloud.com";
$port = "16588";
$dbname = "portaldb"; 
$username = "avnadmin";
$password = "AVNS_nbjEuChQbhT3jY1CH8A";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $applicant_id = filter_input(INPUT_POST, 'applicant_id', FILTER_SANITIZE_NUMBER_INT);
        $projectTitle = filter_input(INPUT_POST, 'projectTitle', FILTER_SANITIZE_STRING);
        $projectCost = filter_input(INPUT_POST, 'projectCost', FILTER_SANITIZE_STRING);    
        $businessActivity = filter_input(INPUT_POST, 'businessActivity', FILTER_SANITIZE_STRING);
        $typeProject = filter_input(INPUT_POST, 'typeProject', FILTER_SANITIZE_STRING);
        $locationProject = filter_input(INPUT_POST, 'locationProject', FILTER_SANITIZE_STRING);
        $geoCoordinates = filter_input(INPUT_POST, 'geoCoordinates', FILTER_SANITIZE_STRING);
        $typeLand = filter_input(INPUT_POST, 'typeLand', FILTER_SANITIZE_STRING);
        $ownLandDetails = filter_input(INPUT_POST, 'ownLandDetails', FILTER_SANITIZE_STRING);
        $ownLandArea = filter_input(INPUT_POST, 'ownLandArea', FILTER_SANITIZE_STRING);
        $ownLandAddress = filter_input(INPUT_POST, 'ownLandAddress', FILTER_SANITIZE_STRING);
        $leasedLandDetails = filter_input(INPUT_POST, 'leasedLandDetails', FILTER_SANITIZE_STRING);
        $leasedLandArea = filter_input(INPUT_POST, 'leasedLandArea', FILTER_SANITIZE_STRING);
        $leasedLandAddress = filter_input(INPUT_POST, 'leasedLandAddress', FILTER_SANITIZE_STRING);
        $leasePeriod = filter_input(INPUT_POST, 'leasePeriod', FILTER_SANITIZE_STRING);
        $bankConsentDetails = filter_input(INPUT_POST, 'bankConsentDetails', FILTER_SANITIZE_STRING);

        $stmt = $db->prepare("
            INSERT INTO ProjectDetails (
                applicant_id,
                projectTitle, 
                projectCost, 
                businessActivity, 
                typeProject, 
                locationProject, 
                geoCoordinates, 
                typeLand, 
                ownLandDetails, 
                ownLandArea, 
                ownLandAddress, 
                leasedLandDetails, 
                leasedLandArea, 
                leasedLandAddress, 
                leasePeriod,
                bankConsentDetails
            ) VALUES (
                :applicant_id,
                :projectTitle, 
                :projectCost, 
                :businessActivity, 
                :typeProject, 
                :locationProject, 
                :geoCoordinates, 
                :typeLand, 
                :ownLandDetails, 
                :ownLandArea, 
                :ownLandAddress, 
                :leasedLandDetails, 
                :leasedLandArea, 
                :leasedLandAddress, 
                :leasePeriod,
                :bankConsentDetails
            )
        ");

        $stmt->bindParam(':applicant_id', $applicant_id);
        $stmt->bindParam(':projectTitle', $projectTitle);
        $stmt->bindParam(':projectCost', $projectCost);
        $stmt->bindParam(':businessActivity', $businessActivity);
        $stmt->bindParam(':typeProject', $typeProject);
        $stmt->bindParam(':locationProject', $locationProject);
        $stmt->bindParam(':geoCoordinates', $geoCoordinates);
        $stmt->bindParam(':typeLand', $typeLand);
        $stmt->bindParam(':ownLandDetails', $ownLandDetails);
        $stmt->bindParam(':ownLandArea', $ownLandArea);
        $stmt->bindParam(':ownLandAddress', $ownLandAddress);
        $stmt->bindParam(':leasedLandDetails', $leasedLandDetails);
        $stmt->bindParam(':leasedLandArea', $leasedLandArea);
        $stmt->bindParam(':leasedLandAddress', $leasedLandAddress);
        $stmt->bindParam(':leasePeriod', $leasePeriod);
        $stmt->bindParam(':bankConsentDetails', $bankConsentDetails);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Project details saved successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error saving project details."]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
