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
  <title>Section List</title>

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
  <h2 style="color:#FF0000" ;>Section List</h2>

  <table style="width:100%">
    <tr>
      <th>Course Title</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Time Slot</th>
      <th>Capacity</th>
      <th>Enrolled Mentee</th>
      <th>Enrolled Mentor</th>
      <th>Enroll as Mentee</th>
      <th>Teach as Mentor</th>
    </tr>
    <?php
    $myconnection = mysqli_connect('localhost', 'root', '', 'db2')
    or die ('Could not connect: ' . mysqli_error($myconnection));
    $meetingsquery = "SELECT meet_id from enroll WHERE mentee_id = '{$_SESSION['user_id']}'";
    $meetingsresult = mysqli_query($myconnection, $meetingsquery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    // while ($meetings = mysqli_fetch_array($meetingsresult, MYSQLI_ASSOC)) {
    //     echo($meetings["meet_id"]."<br>");
    // }
    $meetinginfoquery = "SELECT * from meetings WHERE meet_id IN (".$meetingsquery.")";
    $meetinginforesult = mysqli_query($myconnection, $meetinginfoquery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    // while ($meetinginfo = mysqli_fetch_array($meetinginforesult, MYSQLI_ASSOC)) {
    //     echo($meetinginfo["meet_name"]."<br>");
    // }
    $meetinginfowithtimequery = "SELECT * from meetings, time_slot WHERE ".
    "meetings.time_slot_id = time_slot.time_slot_id";
    $meetinginfowithtimeresult = mysqli_query($myconnection, $meetinginfowithtimequery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    while ($meetinginfowithtime = mysqli_fetch_array($meetinginfowithtimeresult, MYSQLI_ASSOC)) {
        // enrolled mentee query
        $enrolledmenteequery = "SELECT count(mentee_id) as count from enroll WHERE meet_id = '{$meetinginfowithtime['meet_id']}'";
        $enrolledmenteeresult = mysqli_query($myconnection, $enrolledmenteequery)
        or die ('Query failed: ' . mysqli_error($myconnection));
        $enrolledmentee = mysqli_fetch_array($enrolledmenteeresult, MYSQLI_ASSOC);
        // enrolled mentor query
        $enrolledmentorquery = "SELECT count(mentor_id) as count from enroll2 WHERE meet_id = '{$meetinginfowithtime['meet_id']}'";
        $enrolledmentorresult = mysqli_query($myconnection, $enrolledmentorquery)
        or die ('Query failed: ' . mysqli_error($myconnection));
        $enrolledmentor = mysqli_fetch_array($enrolledmentorresult, MYSQLI_ASSOC);
        // enrolled mentee id query
        $enrolledmenteeidquery = "SELECT mentee_id from enroll WHERE meet_id = '{$meetinginfowithtime['meet_id']}'";
        $enrolledmenteeidresult = mysqli_query($myconnection, $enrolledmenteeidquery)
        or die ('Query failed: ' . mysqli_error($myconnection));
        $menteearray = array();
        while ($row = mysqli_fetch_array($enrolledmenteeidresult, MYSQLI_ASSOC)) {
            $menteearray[] =  $row['mentee_id'];
        }

        // var_dump($menteearray);
        // echo "<br>";

        // enrolled mentor id query
        $enrolledmentoridquery = "SELECT mentor_id from enroll2 WHERE meet_id = '{$meetinginfowithtime['meet_id']}'";
        $enrolledmentoridresult = mysqli_query($myconnection, $enrolledmentoridquery)
        or die ('Query failed: ' . mysqli_error($myconnection));
        $mentorarray = array();
        while ($row = mysqli_fetch_array($enrolledmentoridresult, MYSQLI_ASSOC)) {
            $mentorarray[] =  $row['mentor_id'];
        }
        echo("<tr>");
        echo("<td>".$meetinginfowithtime['meet_name']."</td>");
        echo("<td>".$meetinginfowithtime['start_date']."</td>");
        echo("<td>".$meetinginfowithtime['end_date']."</td>");
        echo("<td>".$meetinginfowithtime['day_of_the_week']." ".
        date("g:i a", strtotime($meetinginfowithtime['start_time']))." - ".
        date("g:i a", strtotime($meetinginfowithtime['end_time']))."</td>");
        echo("<td>".($meetinginfowithtime['capacity'] - $enrolledmentee['count'] - $enrolledmentor['count'])."</td>");
        echo("<td>".$enrolledmentee['count']."</td>");
        echo("<td>".$enrolledmentor['count']."</td>");
        if ($enrolledmentee['count'] < 6 && !(in_array($_SESSION['user_id'], $menteearray)) && !(in_array($_SESSION['user_id'], $mentorarray))) {
            echo("<td>"."<a href='Enroll.php?key=".$meetinginfowithtime['meet_id']."'>Enroll</a>"."</td>");
        }
        else {
            echo("<td>"."N/A"."</td>");
        }
        if ($enrolledmentor['count'] < 3 && !(in_array($_SESSION['user_id'], $mentorarray)) && !(in_array($_SESSION['user_id'], $menteearray))) {
            echo("<td>"."<a href='Teach.php?key=".$meetinginfowithtime['meet_id']."'>Teach</a>"."</td>");
        }
        else {
            echo("<td>"."N/A"."</td>");
        }
        echo("</tr>");
    }
    ?>


  </table>

</body>

</html>
