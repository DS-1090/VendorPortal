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
        // Retrieve form data from the request
        $title_of_project = $_POST['title_of_project'] ?? null;
        $total_project_cost = $_POST['total_project_cost'] ?? null;
        $government_assistance = $_POST['government_assistance'] ?? null;
        $beneficiary_contribution = $_POST['beneficiary_contribution'] ?? null;
        $bank_loan = $_POST['bank_loan'] ?? null;
        $project_duration = $_POST['project_duration'] ?? null;
        $expected_income_generation = $_POST['expected_income_generation'] ?? null;
        $expected_employment_generation = $_POST['expected_employment_generation'] ?? null;
        $expected_assets_infrastructure = $_POST['expected_assets_infrastructure'] ?? null;

        // Prepare SQL statement to insert project proposal details
        $stmt = $db->prepare("INSERT INTO project_proposal_details 
            (title_of_project, total_project_cost, government_assistance, beneficiary_contribution, bank_loan, project_duration, expected_income_generation, expected_employment_generation, expected_assets_infrastructure) 
            VALUES (:title_of_project, :total_project_cost, :government_assistance, :beneficiary_contribution, :bank_loan, :project_duration, :expected_income_generation, :expected_employment_generation, :expected_assets_infrastructure)");

        // Bind parameters
        $stmt->bindParam(':title_of_project', $title_of_project);
        $stmt->bindParam(':total_project_cost', $total_project_cost);
        $stmt->bindParam(':government_assistance', $government_assistance);
        $stmt->bindParam(':beneficiary_contribution', $beneficiary_contribution);
        $stmt->bindParam(':bank_loan', $bank_loan);
        $stmt->bindParam(':project_duration', $project_duration);
        $stmt->bindParam(':expected_income_generation', $expected_income_generation);
        $stmt->bindParam(':expected_employment_generation', $expected_employment_generation);
        $stmt->bindParam(':expected_assets_infrastructure', $expected_assets_infrastructure);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Project proposal details saved successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error saving project proposal details."]);
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
