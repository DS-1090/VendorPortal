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

        $sql = "INSERT INTO supporting_documents_uploads (
            technical_feasibility_report,
            cost_benefit_analysis,
            capital_operational_cost_breakup,
            civil_construction_cost_estimate,
            layout_design_details,
            quotations_for_equipment,
            geotagged_photos_vacant_land,
            financial_statement,
            company_registration_document,
            income_sources_document,
            cibil_score_document,
            crz_clearance_document,
            land_availability_document,
            local_authority_permission,
            consultancy_details_document,
            additional_credentials_document,
            prescribed_format_declaration,
            similar_assistance_declaration,
            activity_resolution_declaration,
            group_partnership_deed_resolution
        ) VALUES (
            :technical_feasibility_report,
            :cost_benefit_analysis,
            :capital_operational_cost_breakup,
            :civil_construction_cost_estimate,
            :layout_design_details,
            :quotations_for_equipment,
            :geotagged_photos_vacant_land,
            :financial_statement,
            :company_registration_document,
            :income_sources_document,
            :cibil_score_document,
            :crz_clearance_document,
            :land_availability_document,
            :local_authority_permission,
            :consultancy_details_document,
            :additional_credentials_document,
            :prescribed_format_declaration,
            :similar_assistance_declaration,
            :activity_resolution_declaration,
            :group_partnership_deed_resolution
        )";
        
        $stmt = $pdo->prepare($sql);

        $files = [
            'technicalFeasibility' => null,
            'costBenefitAnalysis' => null,
            'capitalOperationalCost' => null,
            'costEstimates' => null,
            'layoutDesign' => null,
            'quotations' => null,
            'geotaggedPhotos' => null,
            'financialStatements' => null,
            'registrationDocument' => null,
            'currentIncomeSources' => null,
            'cibilScore' => null,
            'crzClearances' => null,
            'landAvailabilityEvidence' => null,
            'localAuthorityPermission' => null,
            'consultancyDetails' => null,
            'additionalCredentials' => null,
            'declarationFormat' => null,
            'similarAssistanceDeclaration' => null,
            'activityResolution' => null,
            'groupResolution' => null,
        ];

        $uploadDir = 'uploads/step-4/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $t = time();
        $text = date("m-Y", $t); 

        foreach ($files as $key => &$value) {
            if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES[$key]['name']);
                $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '_' . $text . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $fileContent = file_get_contents($_FILES[$key]['tmp_name']);

                if (move_uploaded_file($_FILES[$key]['tmp_name'], $filePath)) {
                    $value = $fileContent; 
                } else {
                    $value = null; 
                }
            } else {
                $value = null;
            }
        }

        $stmt->bindParam(':technical_feasibility_report', $files['technicalFeasibility'], PDO::PARAM_LOB);
        $stmt->bindParam(':cost_benefit_analysis', $files['costBenefitAnalysis'], PDO::PARAM_LOB);
        $stmt->bindParam(':capital_operational_cost_breakup', $files['capitalOperationalCost'], PDO::PARAM_LOB);
        $stmt->bindParam(':civil_construction_cost_estimate', $files['costEstimates'], PDO::PARAM_LOB);
        $stmt->bindParam(':layout_design_details', $files['layoutDesign'], PDO::PARAM_LOB);
        $stmt->bindParam(':quotations_for_equipment', $files['quotations'], PDO::PARAM_LOB);
        $stmt->bindParam(':geotagged_photos_vacant_land', $files['geotaggedPhotos'], PDO::PARAM_LOB);
        $stmt->bindParam(':financial_statement', $files['financialStatements'], PDO::PARAM_LOB);
        $stmt->bindParam(':company_registration_document', $files['registrationDocument'], PDO::PARAM_LOB);
        $stmt->bindParam(':income_sources_document', $files['currentIncomeSources'], PDO::PARAM_LOB);
        $stmt->bindParam(':cibil_score_document', $files['cibilScore'], PDO::PARAM_LOB);
        $stmt->bindParam(':crz_clearance_document', $files['crzClearances'], PDO::PARAM_LOB);
        $stmt->bindParam(':land_availability_document', $files['landAvailabilityEvidence'], PDO::PARAM_LOB);
        $stmt->bindParam(':local_authority_permission', $files['localAuthorityPermission'], PDO::PARAM_LOB);
        $stmt->bindParam(':consultancy_details_document', $files['consultancyDetails'], PDO::PARAM_LOB);
        $stmt->bindParam(':additional_credentials_document', $files['additionalCredentials'], PDO::PARAM_LOB);
        $stmt->bindParam(':prescribed_format_declaration', $files['declarationFormat'], PDO::PARAM_LOB);
        $stmt->bindParam(':similar_assistance_declaration', $files['similarAssistanceDeclaration'], PDO::PARAM_LOB);
        $stmt->bindParam(':activity_resolution_declaration', $files['activityResolution'], PDO::PARAM_LOB);
        $stmt->bindParam(':group_partnership_deed_resolution', $files['groupResolution'], PDO::PARAM_LOB);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Files uploaded successfully."]);
        } else {
            echo json_encode(["message" => "Failed to upload files."]);
        }

    } else {
        echo json_encode(["message" => "Invalid request method."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
