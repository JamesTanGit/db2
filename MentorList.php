<?php
// Put this code at the top of any "protected" page that we create

// Start first
session_start();
if (isset($_SESSION['user_id'])) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
}
else {
    // Redirect them to the login page
    header("Location: Homepage.html");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Mentor's Section List</title>

  <style>
    table {
      border: 1px solid black;
      border-collapse: collapse;
    }

    td,
    th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }
  </style>
</head>



<body>
  <a href="StudentSetting.html">Student Setting</a>
  <h2 style="color:#FF0000" ;>Mentor's Section List</h2>
  <table style="width:100%">


    <?php
    $myconnection = mysqli_connect('localhost', 'root', '', 'db2')
    or die ('Could not connect: ' . mysqli_error($myconnection));



    $mentorquery = "SELECT * from enroll2 WHERE mentor_id = '{$_SESSION['user_id']}'";
    $mentorresult = mysqli_query($myconnection, $mentorquery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    $mentor = mysqli_fetch_array($mentorresult, MYSQLI_ASSOC);


    $enrolledmenteeidquery = "SELECT mentee_id from enroll WHERE meet_id = '{$mentor['meet_id']}'";
    $enrolledmenteeidresult = mysqli_query($myconnection, $enrolledmenteeidquery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    $menteearray = array();
    while ($row = mysqli_fetch_array($enrolledmenteeidresult, MYSQLI_ASSOC)) {
        $menteearray[] =  $row['mentee_id'];
    }

    $enrolledmentoridquery = "SELECT mentor_id from enroll2 WHERE meet_id = '{$mentor['meet_id']}'";
    $enrolledmentoridresult = mysqli_query($myconnection, $enrolledmentoridquery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    $mentorarray = array();
    while ($row = mysqli_fetch_array($enrolledmentoridresult, MYSQLI_ASSOC)) {
        $mentorarray[] =  $row['mentor_id'];
    }


    $studentmenteequery = "SELECT * from enroll, students WHERE ".
    "students.student_id = enroll.mentee_id AND student_id IN (".$enrolledmenteeidquery.")";
    $studentmenteeresult = mysqli_query($myconnection, $studentmenteequery)
    or die ('Query failed: ' . mysqli_error($myconnection));

    $studentmentorquery = "SELECT * from enroll2, students WHERE ".
    "students.student_id = enroll2.mentor_id AND student_id IN (".$enrolledmentoridquery.")";
    $studentmentorresult = mysqli_query($myconnection, $studentmentorquery)
    or die ('Query failed: ' . mysqli_error($myconnection));


    if ( !(in_array($_SESSION['user_id'], $mentorarray))) {
      echo "<p>Sorry did not find any section</p>";
    }
    else {
      echo("<h2>".$mentor['meet_id']."</h2>");

      echo("<tr>");
      echo("<th>Student Name</th>");
      echo("<th>Student Name</th>");
      echo("<th>Student Name</th>");
      echo("</tr>");

      echo("<tr>");
      echo("<th colspan=\"3\" style=\"text-align:center;\">Mentees</th>");
      echo("</tr>");
      while ($studentmentee = mysqli_fetch_array($studentmenteeresult, MYSQLI_ASSOC)) {
        echo("<tr>");
        echo("<td>".$studentmentee['student_id']."</td>");
        echo("<td>".$studentmentee['grade']."</td>");
        echo("<td>Mentee</td>");
        echo("</tr>");
      }

      echo("<tr>");
      echo("<th colspan=\"3\" style=\"text-align:center;\">Mentors</th>");
      echo("</tr>");
      while ($studentmentor = mysqli_fetch_array($studentmentorresult, MYSQLI_ASSOC)) {
        echo("<tr>");
        echo("<td>".$studentmentor['student_id']."</td>");
        echo("<td>".$studentmentor['grade']."</td>");
        echo("<td>Mentor</td>");
        echo("</tr>");
      }

    }

    ?>





</body>

</html>
