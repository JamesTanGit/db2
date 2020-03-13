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
    $oldemail = $_POST['oldemail'];
    $newemail = $_POST['newemail'];
    $oldpassword = $_POST['oldpassword'];
    $newpassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
    $newname = $_POST['newstudentname'];
    $newphone = $_POST['newphone'];
    $newgrade = $_POST['newgrade'];
    $newrole = $_POST['newrole'];

    $studentquery = "SELECT * from users WHERE id = '{$_SESSION['user_id']}'";
    $studentresult = mysqli_query($myconnection, $studentquery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    $student = mysqli_fetch_array ($studentresult, MYSQLI_ASSOC);
    if ($oldemail == $student["email"] && password_verify($_POST['oldpassword'], $student['password'])) {
        // update statement for users
        $insert = "UPDATE users SET email=?, password=?, name=?, phone=? WHERE id=?";

        $insertstmt = $myconnection->prepare($insert);
        $insertstmt->bind_param("ssssi", $newemail, $newpassword, $newname, $newphone, $_SESSION['user_id']);

        if (empty($newemail) || empty($newpassword) || empty($newname)) {
            echo("Email, Password, and Name cannot be empty.<br>");
        }
        else if (!$insertstmt->execute()) {
            // echo("Error description: " . $insertstmt -> error) . "<br>";
            echo("Email may be duplicate");
        }
        else {
            echo("Updated<br>");
        }

        // update statement for students
        $insertgrade = "UPDATE students SET grade=? WHERE student_id=?";
        $insertgradestmt = $myconnection->prepare($insertgrade);
        $insertgradestmt->bind_param("ii", $newgrade, $student["id"]);
        $insertgradestmt->execute();

        // update statement for mentors/mentees
        $insertintomentees = "INSERT INTO mentees (mentee_id) VALUES ({$_SESSION['user_id']})";
        $insertintomentors = "INSERT INTO mentors (mentor_id) VALUES ({$_SESSION['user_id']})";
        $deletefrommentees = "DELETE FROM mentees WHERE mentee_id = ({$_SESSION['user_id']})";
        $deletefrommentors = "DELETE FROM mentors WHERE mentor_id = ({$_SESSION['user_id']})";
        if ($newrole == "both") {
            mysqli_query($myconnection, $insertintomentors);
            mysqli_query($myconnection, $insertintomentees);
            echo("Insert both<br>");
        }
        else if ($newrole == "mentor") {
            mysqli_query($myconnection, $insertintomentors);
            mysqli_query($myconnection, $deletefrommentees);
            echo("Insert mentor<br>");
        }
        else if ($newrole == "mentee") {
            mysqli_query($myconnection, $insertintomentees);
            mysqli_query($myconnection, $deletefrommentors);
            echo("Insert mentee<br>");
        }
        else {
            mysqli_query($myconnection, $deletefrommentees);
            mysqli_query($myconnection, $deletefrommentors);
            echo("No role<br>");
        }
    }
    // close connection
    mysqli_close($myconnection);
    header('Location: StudentDashboard.php');
    // Let them access the "logged in only" pages
}
else {
    // Redirect them to the login page
    header("Location: Homepage.html");
}
?>
