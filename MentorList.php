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
    <tr>
      <th>Course Title</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Time Slot</th>
      <th>Capacity</th>
      <th>Mentor Req</th>
      <th>Mentee Req</th>
      <th>Enrolled Mentor</th>
      <th>Enrolled Mentee</th>
      <th>Teach as Mentor</th>
      <th>Enroll as Mentee</th>
      <th>Participants</th>
      <th>Materials</th>
    </tr>
    <?php
    $myconnection = mysqli_connect('localhost', 'root', '', 'db2')
    or die ('Could not connect: ' . mysqli_error($myconnection));
    $meetingsquery = "SELECT meet_id from enroll2 WHERE mentor_id = '{$_SESSION['user_id']}'";
    $meetinginfowithtimequery = "SELECT * from meetings, time_slot WHERE ".
    "meetings.time_slot_id = time_slot.time_slot_id AND meet_id IN (".$meetingsquery.")";
    $meetinginfowithtimeresult = mysqli_query($myconnection, $meetinginfowithtimequery)
    or die ('Query failed: ' . mysqli_error($myconnection));
    while ($meetinginfowithtime = mysqli_fetch_array($meetinginfowithtimeresult, MYSQLI_ASSOC)) {
        echo("<tr>");
        echo("<td>".$meetinginfowithtime['meet_name']."</td>");
        echo("<td>"."1/1/2020"."</td>");
        echo("<td>"."12/31/2020"."</td>");
        echo("<td>".$meetinginfowithtime['day_of_the_week']." ".
        date("g:i a", strtotime($meetinginfowithtime['start_time']))." - ".
        date("g:i a", strtotime($meetinginfowithtime['end_time']))."</td>");
        echo("<td>".$meetinginfowithtime['capacity']."</td>");
        echo("<td>"."?"."</td>");
        echo("<td>"."?"."</td>");
        echo("<td>"."?"."</td>");
        echo("<td>"."?"."</td>");
        echo("<td>"."?"."</td>");
        echo("<td>"."?"."</td>");
        echo("<td>"."<a href='ViewParticipants.php?key=".$meetinginfowithtime['meet_id']."'>View</a>"."</td>");
        echo("<td>"."<a href='Materials.php?key=".$meetinginfowithtime['meet_id']."'>View</a>"."</td>");
        echo("</tr>");
    }
    ?>
    <tr>
      <td>Database II</td>
      <td>201</td>
      <td>2020-01-21</td>
      <td>2020-05-09</td>
      <td>T 11:00 AM - 12:15 PM</td>
      <td>45</td>
      <td>8</td>
      <td>7</td>
      <td>2</td>
      <td>2</td>
      <td><input type="button" value="Teach"></td>
      <td><input type="button" value="Enroll"></td>
    </tr>

    <tr>
      <td>Algorithm</td>
      <td>203</td>
      <td>2020-01-21</td>
      <td>2020-05-09</td>
      <td>Th 12:30 PM - 13:45 PM</td>
      <td>45</td>
      <td>8</td>
      <td>7</td>
      <td>2</td>
      <td>2</td>
      <td><input type="button" value="Teach"></td>
      <td><input type="button" value="Enroll"></td>
    </tr>

    <tr>
      <td>Artificial Intelligence</td>
      <td>420</td>
      <td>2020-01-21</td>
      <td>2020-05-09</td>
      <td>Th 15:30 PM - 16:45 PM</td>
      <td>38</td>
      <td>8</td>
      <td>7</td>
      <td>2</td>
      <td>2</td>
      <td><input type="button" value="Teach"></td>
      <td><input type="button" value="Enroll"></td>
    </tr>


  </table>

</body>

</html>
