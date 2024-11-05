<?php
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json");

$host = "pg-b3e567-vendor-portal.h.aivencloud.com";
$port = "16588";
$dbname = "portaldb"; 
$user = "avnadmin";
$password = "AVNS_nbjEuChQbhT3jY1CH8A";
try {
    // Create a new PDO instance
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare SQL statement
       $projectTitle = filter_input(INPUT_POST, 'projectTitle', FILTER_SANITIZE_STRING);
        $applicant_state_ut = filter_input(INPUT_POST, 'projectCost', FILTER_SANITIZE_STRING);    
        $businessActivity = filter_input(INPUT_POST, 'businessActivity', FILTER_SANITIZE_STRING);
        $typeProject = filter_input(INPUT_POST, 'typeProject', FILTER_SANITIZE_STRING);
        $locationProject = filter_input(INPUT_POST, 'locationProject', FILTER_SANITIZE_EMAIL);
        $geoCoordinates = filter_input(INPUT_POST, 'geoCoordinates', FILTER_SANITIZE_STRING);
        $typeLand = filter_input(INPUT_POST, 'typeLand', FILTER_SANITIZE_STRING);
        $landInTheNameOf = filter_input(INPUT_POST, 'landInTheNameOf', FILTER_SANITIZE_STRING);
        $ownLandDetails = filter_input(INPUT_POST, 'ownLandDetails', FILTER_SANITIZE_STRING);
        $ownLandArea = filter_input(INPUT_POST, 'ownLandArea', FILTER_SANITIZE_STRING);
        $ownLandAddress = isset($_POST['ownLandAddress']) ? true : false;
        $leasedLandDetails = filter_input(INPUT_POST, 'leasedLandDetails', FILTER_SANITIZE_STRING);
        $leasedLandArea = filter_input(INPUT_POST, 'leasedLandArea', FILTER_SANITIZE_STRING);
        $leasedLandAddress = filter_input(INPUT_POST, 'leasedLandAddress', FILTER_SANITIZE_STRING);
        $leasePeriod = filter_input(INPUT_POST, 'leasePeriod', FILTER_SANITIZE_STRING);
       
    $stmt = $db->prepare("
        INSERT INTO project_details (
            project_title, 
            project_cost, 
            proposed_business_activity, 
            type_of_project, 
            location_of_project, 
            geo_coordinates, 
            type_of_land_available, 
            land_in_the_name_of, 
            own_land_details, 
            own_land_area, 
            own_land_address, 
            leased_land_details, 
            leased_land_area, 
            leased_land_address, 
            lease_period
        ) VALUES (
            :project_title, 
            :project_cost, 
            :proposed_business_activity, 
            :type_of_project, 
            :location_of_project, 
            :geo_coordinates, 
            :type_of_land_available, 
            :land_in_the_name_of, 
            :own_land_details, 
            :own_land_area, 
            :own_land_address, 
            :leased_land_details, 
            :leased_land_area, 
            :leased_land_address, 
            :lease_period
        )
    ");
  
    // Bind parameters
    echo  $_POST['projectTitle'];
    $stmt->bindParam(':project_title', $_POST['projectTitle']);
    $stmt->bindParam(':project_cost', $_POST['projectCost']);
    $stmt->bindParam(':proposed_business_activity', $_POST['businessActivity']);
    $stmt->bindParam(':type_of_project', $_POST['typeProject']);
    $stmt->bindParam(':location_of_project', $_POST['locationProject']);
    $stmt->bindParam(':geo_coordinates', $_POST['geoCoordinates']);
    $stmt->bindParam(':type_of_land_available', $_POST['typeLand']);
    $stmt->bindParam(':land_in_the_name_of', $_POST['landInTheNameOf']); // Assuming you have this field in your form
    $stmt->bindParam(':own_land_details', $_POST['ownLandDetails']);
    $stmt->bindParam(':own_land_area', $_POST['ownLandArea']);
    $stmt->bindParam(':own_land_address', $_POST['ownLandAddress']);
    $stmt->bindParam(':leased_land_details', $_POST['leasedLandDetails']);
    $stmt->bindParam(':leased_land_area', $_POST['leasedLandArea']);
    $stmt->bindParam(':leased_land_address', $_POST['leasedLandAddress']);
    $stmt->bindParam(':lease_period', $_POST['leasePeriod']);

    $stmt->execute();


        // Check and read each file input
        // foreach ($files as $key => &$value) {
        //     if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
        //         $fileName = basename($_FILES[$key]['name']);
        //         $filePath = $uploadDir . $fileName . $text;
        //         $fileContent = file_get_contents($_FILES[$key]['tmp_name']);

        //         // Move the uploaded file to the uploads directory
        //         if (move_uploaded_file($_FILES[$key]['tmp_name'], $filePath)) {
        //             $value = $fileContent; // Store the binary content for database insertion
        //         } else {
        //             $value = null; 
        //         }
        //     } else {
        //         $value = null;
        //     }
        // }
    
    // Execute the statement
    // if ($stmt->execute()) {
    //     echo json_encode(["status" => "success", "message" => "Project details saved successfully."]);
    // } else {
    //     echo json_encode(["status" => "error", "message" => "Error saving project details."]);
    // }
    }
} catch (PDOException $e) {
    // Handle connection errors
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
