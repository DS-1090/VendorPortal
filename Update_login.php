<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

 
    $conn = new mysqli("localhost", "root", "", "dept_fisheries");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM registration WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE registration SET Password = ? WHERE Email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $password, $email);

        if ($update_stmt->execute()) {
            echo "<script>alert('Password Updated Successfully'); window.location.href = 'user_home.html';</script>";
        } 
        else {
            echo "<script>alert('Error updating password'); window.location.href = 'user_forgotpw.html';</script>";
        }
    } 
    else {
        echo "<script>alert('Email not found'); window.location.href = 'user_forgotpw.html';</script>";
    }

    $stmt->close();
    $update_stmt->close();
    $conn->close();
}  
?>
