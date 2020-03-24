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

    <?php
    $myconnection = mysqli_connect('localhost', 'root', '', 'db2')
    or die ('Could not connect: ' . mysqli_error($myconnection));

    $mentorquery = "SELECT * from enroll2 WHERE mentor_id = '{$_SESSION['user_id']}'";
    $mentorresult = mysqli_query($myconnection, $mentorquery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    $mentor = mysqli_fetch_array($mentorresult, MYSQLI_ASSOC);

    $mentormeetingquery = "SELECT meet_name, mentor_id, enroll2.meet_id from meetings JOIN enroll2 ON (".
    "meetings.meet_id = enroll2.meet_id) WHERE mentor_id = '{$_SESSION['user_id']}'";
    $mentormeetingresult = mysqli_query($myconnection, $mentormeetingquery)
    or die ('Query failed: ' . mysqli_error($myconnection));


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



    if ( !(in_array($_SESSION['user_id'], $mentorarray))) {
      echo "<p>Sorry did not find any section</p>";
    }
    else {
      while($mentormeeting = mysqli_fetch_array($mentormeetingresult, MYSQLI_ASSOC)){
        $menteesquery = "SELECT mentee_id from enroll WHERE meet_id = '{$mentormeeting['meet_id']}'";
        $menteesresult = mysqli_query($myconnection, $menteesquery)
        or die ('Query failed: ' . mysqli_error($myconnection));

        $mentorsquery = "SELECT mentor_id from enroll2 WHERE meet_id = '{$mentormeeting['meet_id']}'";
        $mentorsresult = mysqli_query($myconnection, $mentorsquery)
        or die ('Query failed: ' . mysqli_error($myconnection));


        echo ("<table style=\"width:50%\">");
        echo ("<tr><th colspan=\"3\" height=\"40\" style=\"text-align:center;\">"
        .$mentormeeting['meet_name']."</h2></tr>");
        echo("<th>Student Name</th>");
        echo("<th>Student Grade</th>");
        echo("<th>Student Role</th>");
        echo("</tr>");

        echo("<tr>");
        echo("<th colspan=\"3\" height=\"25\" style=\"text-align:center;\">Mentees</th>");
        echo("</tr>");
        while ($mentees = mysqli_fetch_array($menteesresult, MYSQLI_ASSOC)) {
          $menteeuserquery = "SELECT * from enroll, users WHERE id = '{$mentees['mentee_id']}'";
          $menteeuserresult = mysqli_query($myconnection, $menteeuserquery)
          or die ('Query failed: ' . mysqli_error($myconnection));
          $menteeuser = mysqli_fetch_array($menteeuserresult, MYSQLI_ASSOC);

          $studentmenteequery = "SELECT * from enroll, students WHERE
          student_id = '{$mentees['mentee_id']}' AND meet_id = '{$mentormeeting['meet_id']}'";
          $studentmenteeresult = mysqli_query($myconnection, $studentmenteequery)
          or die ('Query failed: ' . mysqli_error($myconnection));
          $studentmentee = mysqli_fetch_array($studentmenteeresult, MYSQLI_ASSOC);

          echo("<tr>");
          echo("<td>".$menteeuser['name']."</td>");
          echo("<td>".$studentmentee['grade']."</td>");
          echo("<td>Mentee</td>");
          echo("</tr>");
        }

        echo("<tr>");
        echo("<th colspan=\"3\" height=\"25\" style=\"text-align:center;\">Mentors</th>");
        echo("</tr>");
        while ($mentors = mysqli_fetch_array($mentorsresult, MYSQLI_ASSOC)) {
          $mentoruserquery = "SELECT * from enroll2, users WHERE id = '{$mentors['mentor_id']}'";
          $mentoruserresult = mysqli_query($myconnection, $mentoruserquery)
          or die ('Query failed: ' . mysqli_error($myconnection));
          $mentoruser = mysqli_fetch_array($mentoruserresult, MYSQLI_ASSOC);

          $studentmentorquery = "SELECT * from enroll2, students WHERE
          student_id = '{$mentors['mentor_id']}' AND meet_id = '{$mentormeeting['meet_id']}'";
          $studentmentorresult = mysqli_query($myconnection, $studentmentorquery)
          or die ('Query failed: ' . mysqli_error($myconnection));
          $studentmentor = mysqli_fetch_array($studentmentorresult, MYSQLI_ASSOC);

          echo("<tr>");
          echo("<td>".$mentoruser['name']."</td>");
          echo("<td>".$studentmentor['grade']."</td>");
          echo("<td>Mentor</td>");
          echo("</tr>");
        }
        echo ("</table>");
        echo ("<br><br><br>");

      }
    }

    ?>


</body>
</html>
