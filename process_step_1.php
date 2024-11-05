<?php

// Database connection details
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
    
    echo "Connected successfully to the database '$dbname'!<br>";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Collect input data and sanitize
        // $applicant_id = filter_input(INPUT_POST, 'applicant_id', FILTER_SANITIZE_NUMBER_INT);

        $name_of_applicant = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        $applicant_state_ut = filter_input(INPUT_POST, 'states', FILTER_SANITIZE_STRING);    
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
        $contact_number = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
        $email_id = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $full_address = filter_input(INPUT_POST, 'faddress', FILTER_SANITIZE_STRING);
        $address_proof_id = filter_input(INPUT_POST, 'addressProofId', FILTER_SANITIZE_STRING);
        $aadhaar_number = filter_input(INPUT_POST, 'aadhaarNumber', FILTER_SANITIZE_STRING);
        $pan_number = filter_input(INPUT_POST, 'panNumber', FILTER_SANITIZE_STRING);
        $years_of_experience_fisheries = filter_input(INPUT_POST, 'yearsExperience', FILTER_SANITIZE_STRING);
        $gst_tan_available = isset($_POST['gstTanAvailable']) ? true : false;
        $gst_tan_number = filter_input(INPUT_POST, 'gstTanNumber', FILTER_SANITIZE_STRING);
        $bank_name_branch = filter_input(INPUT_POST, 'bankNameBranch', FILTER_SANITIZE_STRING);
        $bank_account_number = filter_input(INPUT_POST, 'bankAccountNo', FILTER_SANITIZE_STRING);
        $ifsc_code = filter_input(INPUT_POST, 'ifscCode', FILTER_SANITIZE_STRING);
        $type_of_applicant = filter_input(INPUT_POST, 'typeApplicant', FILTER_SANITIZE_STRING);
        $category_applied = filter_input(INPUT_POST, 'categoryApplied', FILTER_SANITIZE_STRING);
        $applicant_type = filter_input(INPUT_POST, 'applicantType', FILTER_SANITIZE_STRING);
        $education_qualification = filter_input(INPUT_POST, 'educationQualification', FILTER_SANITIZE_STRING);
        $field_of_experience = filter_input(INPUT_POST, 'fieldExperience', FILTER_SANITIZE_STRING);
        $level_of_experience = filter_input(INPUT_POST, 'levelOfExperience', FILTER_SANITIZE_STRING);
        $applicant_nationality = "Indian"; 

        var_dump($name_of_applicant, $applicant_state_ut, $gender, $contact_number, $email_id, $full_address, $address_proof_id, $aadhaar_number, $pan_number, $years_of_experience_fisheries, $gst_tan_available, $gst_tan_number, $bank_name_branch, $bank_account_number, $ifsc_code, $type_of_applicant, $category_applied, $applicant_type, $education_qualification, $field_of_experience, $level_of_experience, $applicant_nationality);

$stmt = $db->prepare("
 INSERT INTO applicant_details (
                name_of_applicant,
                applicant_state_ut,
                gender,
                contact_number,
                email_id,
                full_address,
                address_proof_id,
                aadhaar_number,
                pan_number,
                years_of_experience_fisheries,
                gst_tan_available,
                gst_tan_number,
                bank_name_branch,
                bank_account_number,
                ifsc_code,
                type_of_applicant,
                category_applied,
                applicant_type,
                education_qualification,
                field_of_experience,
                level_of_experience,
                applicant_nationality
            ) VALUES (
                :name_of_applicant,
                :applicant_state_ut,
                :gender,
                :contact_number,
                :email_id,
                :full_address,
                :address_proof_id,
                :aadhaar_number,
                :pan_number,
                :years_of_experience_fisheries,
                :gst_tan_available,
                :gst_tan_number,
                :bank_name_branch,
                :bank_account_number,
                :ifsc_code,
                :type_of_applicant,
                :category_applied,
                :applicant_type,
                :education_qualification,
                :field_of_experience,
                :level_of_experience,
                :applicant_nationality
            )
");

 $stmt->bindParam(':name_of_applicant', $name_of_applicant);
        $stmt->bindParam(':applicant_state_ut', $applicant_state_ut);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->bindParam(':email_id', $email_id);
        $stmt->bindParam(':full_address', $full_address);
        $stmt->bindParam(':address_proof_id', $address_proof_id);
        $stmt->bindParam(':aadhaar_number', $aadhaar_number);
        $stmt->bindParam(':pan_number', $pan_number);
        $stmt->bindParam(':years_of_experience_fisheries', $years_of_experience_fisheries);
        $stmt->bindParam(':gst_tan_available', $gst_tan_available, PDO::PARAM_BOOL); // Bind as boolean
        $stmt->bindParam(':gst_tan_number', $gst_tan_number);
        $stmt->bindParam(':bank_name_branch', $bank_name_branch);
        $stmt->bindParam(':bank_account_number', $bank_account_number);
        $stmt->bindParam(':ifsc_code', $ifsc_code);
        $stmt->bindParam(':type_of_applicant', $type_of_applicant);
        $stmt->bindParam(':category_applied', $category_applied);
        $stmt->bindParam(':applicant_type', $applicant_type);
        $stmt->bindParam(':education_qualification', $education_qualification);
        $stmt->bindParam(':field_of_experience', $field_of_experience);
        $stmt->bindParam(':level_of_experience', $level_of_experience);
        $stmt->bindParam(':applicant_nationality', $applicant_nationality);

        $stmt->execute();


        // Return success message
        echo json_encode(['success' => true, 'message' => 'Data submitted successfully.']);
    } 
    else {
        // Handle invalid request method
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    }
} catch (PDOException $e) {
    // Handle connection errors
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
