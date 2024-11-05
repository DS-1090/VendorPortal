<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email_login'];
    $password = $_POST['Password_Login'];

    $conn = new mysqli("localhost", "root", "", "dept_fisheries");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM registration WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        
        header("Location: user_application_new.html");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: user_login.html");
        header("Location: user_login.html?error=invalid_login");

       // exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
