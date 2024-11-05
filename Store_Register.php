<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pwText = $_POST["text"];
    $mail = $_POST["mail"];
    echo($pwText); 
    echo ($mail);
    $conn = new mysqli("localhost", "root", "", "dept_fisheries");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $mail = $conn->real_escape_string($mail);
    $pwText = $conn->real_escape_string($pwText);

    $sql = "INSERT INTO registration (Email, Password) VALUES ('$mail', '$pwText')";
    if ($conn->query($sql) === TRUE) {
        echo "Record saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method";
}
?>
