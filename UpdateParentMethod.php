<?php
// Put this code at the top of any "protected" page that we create

// Start first
session_start();

if (isset($_SESSION['user_id'])) {
    // Grab user data from the database using the user_id
    // sql connection
    $myconnection = mysqli_connect('localhost', 'root', '', 'db2') 
    or die ('Could not connect: ' . mysqli_error($myconnection));

    // variables
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    // update statement
    $insert = "UPDATE users SET email=?, password=?, name=?, phone=? WHERE id=?";

    $insertstmt = $myconnection->prepare($insert);
    $insertstmt->bind_param("ssssi", $email, $password, $name, $phone, $_SESSION['user_id']);

    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['name'])) {
        echo("Email, Password, and Name cannot be empty.<br>");
    }
    else if ($insertstmt->execute()) {
        echo("Updated<br>");
    }
    else {
        echo("Not updated<br>");
    }
    // close connection
    mysqli_close($myconnection);
    header('Location: ParentDashboard.php');
    // Let them access the "logged in only" pages
} else {
    // Redirect them to the login page
    header("Location: Homepage.html");
}
?>