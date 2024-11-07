<?php

use function PHPSTORM_META\type;

try {
    $t=time();
        echo(date("m/Y",$t));
    $host = "pg-b3e567-vendor-portal.h.aivencloud.com";
    $port = "16588";
    $dbname = "portaldb"; 
    $user = "avnadmin";
    $password = "AVNS_nbjEuChQbhT3jY1CH8A";

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully to the database '$dbname'!";

    // //Fetch and display the table names
    $query = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "Tables in the database '$dbname':<br>";
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo $row['table_name'] . "<br>";
    }

//     // //Display table schema
//     $query = $db->query("
//         SELECT column_name, data_type, is_nullable, column_default
//         FROM information_schema.columns
//         WHERE table_name = 'project_details'
//     ");
//     echo "Schema of the 'project' table:<br>";
//     while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
//         echo "Column: " . $row['column_name'] . "<br>";
//         echo "Type: " . $row['data_type'] . "<br>";
//         echo "Nullable: " . $row['is_nullable'] . "<br>";
//         echo "Default: " . ($row['column_default'] ?? 'None') . "<br><br>";
//     }


    //create table
//   $sql = "
//     CREATE TABLE supporting_documents_uploads (
//         applicant_id SERIAL PRIMARY KEY,
//         gst_tan_available BOOLEAN,
//         name_of_applicant VARCHAR,
//         applicant_state_ut VARCHAR,
//         gender VARCHAR,
//         contact_number VARCHAR,
//         email_id VARCHAR,
//         full_address TEXT,
//         address_proof_id VARCHAR,
//         aadhaar_number VARCHAR,
//         pan_number VARCHAR,
//         years_of_experience_fisheries VARCHAR,
//         gst_tan_number VARCHAR,
//         bank_name_branch VARCHAR,
//         bank_account_number VARCHAR,
//         ifsc_code VARCHAR,
//         type_of_applicant VARCHAR,
//         category_applied VARCHAR,
//         applicant_type VARCHAR,
//         education_qualification VARCHAR,
//         field_of_experience VARCHAR,
//         level_of_experience VARCHAR,
//         applicant_nationality VARCHAR
//     );";

//     // Execute the SQL statement
//     $db->exec($sql);
//     echo "Table 'applicant_details' created successfully.";


// $sql=" CREATE TABLE IF NOT EXISTS uploads_1 (
//         upload_id SERIAL PRIMARY KEY,
//         applicant_id INT REFERENCES applicant_details(applicant_id) ON DELETE CASCADE,
//         applicant_photo BYTEA,
//         address_proof BYTEA,
//         aadhaar_card BYTEA,
//         pan_card BYTEA,
//         gst_tan_document BYTEA
//     );";


//     // Execute the SQL statement to create the 'uploads' table
//     $db->exec($sql);
//     echo "Table 'uploads' created successfully!<br>";

// $sql = "CREATE TABLE IF NOT EXISTS supporting_documents_uploads (
//         document_id SERIAL PRIMARY KEY,
//         technical_feasibility_report BYTEA,
//         cost_benefit_analysis BYTEA,
//         capital_operational_cost_breakup BYTEA,
//         civil_construction_cost_estimate BYTEA,
//         layout_design_details BYTEA,
//         quotations_for_equipment BYTEA,
//         geotagged_photos_vacant_land BYTEA,
//         financial_statement BYTEA,
//         company_registration_document BYTEA,
//         income_sources_document BYTEA,
//         cibil_score_document BYTEA,
//         crz_clearance_document BYTEA,
//         land_availability_document BYTEA,
//         local_authority_permission BYTEA,
//         consultancy_details_document BYTEA,
//         additional_credentials_document BYTEA,
//         prescribed_format_declaration BYTEA,
//         similar_assistance_declaration BYTEA,
//         activity_resolution_declaration BYTEA,
//         group_partnership_deed_resolution BYTEA);";
//         $db->exec($sql);


// $sql = "
// CREATE TABLE IF NOT EXISTS ProjectDetails (
//     project_id SERIAL PRIMARY KEY,
//     applicant_id INT REFERENCES applicant_details(applicant_id),
//     projectTitle VARCHAR(255) ,
//     projectCost DECIMAL(15, 2) ,
//     businessActivity VARCHAR(100) ,
//     typeProject VARCHAR(100),
//     locationProject VARCHAR(255) ,
//     geoCoordinates VARCHAR(255) ,
//     typeLand VARCHAR(50) ,
    
//     ownLandDetails TEXT,
//     ownLandArea DECIMAL(10, 2),
//     ownLandAddress TEXT,

//     leasedLandDetails TEXT,
//     leasedLandArea DECIMAL(10, 2),
//     leasedLandAddress TEXT,
//     leasePeriod VARCHAR(100),

//     bankConsentDetails TEXT,
//     bankName VARCHAR(255),
//     bankBranch VARCHAR(255),
//     bankContact VARCHAR(255)
// );
// ";
// $db->exec($sql);



// $sql= "DELETE FROM uploads_1;";
// $db->exec($sql);

// $sql = "ALTER TABLE uploads_1
//     ADD COLUMN IF NOT EXISTS community_certificate BYTEA;";
// $db->exec($sql);

//    // Display table  data
     $query = $db->query("SELECT * FROM project_proposal_uploads ");
     echo "Data in the  table:<br>";
     while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         foreach ($row as $column => $value) {
             echo "$column: $value<br>";
         }
         echo "<br>";
     }


//    $sql = "CREATE TABLE IF NOT EXISTS Registration (
//     Email VARCHAR(255),
//     Password VARCHAR(255),
//     Aadhar_number VARCHAR(12)
// );";
// $db->exec($sql);

}
 catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
