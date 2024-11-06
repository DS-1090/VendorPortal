<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['Email_login']);  

    $host = "pg-b3e567-vendor-portal.h.aivencloud.com";
    $port = "16588";
    $dbname = "portaldb"; 
    $user = "avnadmin";
    $dbPassword = "AVNS_nbjEuChQbhT3jY1CH8A";

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
        $db = new PDO($dsn, $user, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM registration WHERE email ILIKE :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // echo "<pre>";
            // print_r($result); 
            // echo "</pre>";
            // ob_flush();
            // flush();

            // echo "Email found: " . $result['email'] . "<br>";
            // echo "Password from DB: " . $result['password'] . "<br>";
            // echo "Entered Password: " . $_POST['Password_Login'] . "<br>";

            // Check for password match
            if (strcmp($result['password'], $_POST['Password_Login']) === 0) {
                $_SESSION['email'] = $email;
                header("Location: user_application_new.html");
                exit();
            } else {
                echo "Invalid password.";
                header("Location: user_login.html?error=invalid_login");
            }
        } else {
            echo "Email not found.";
            header("Location: user_login.html?error=invalid_login");
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo "Invalid request method.";
}
?>
