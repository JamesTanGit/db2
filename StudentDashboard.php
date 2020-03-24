<?php
// Put this code at the top of any "protected" page that we create

// Start first
session_start();

if (isset($_SESSION['user_id'])) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
} else {
    // Redirect them to the login page
    header("Location: Homepage.html");
}
?>


  <!DOCTYPE html>
  <html lang="en" dir="ltr">

  <head>
    <meta charset="utf-8">
    <title>Student Dashboard</title>

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
    <p><a href="StudentSetting.html">Student Setting</a>
      &emsp;
      <a href="Logout.php">Logout</a>
    </p>


    <h1>Student Information</h1>

    <table style="width:80%">
      <tr>
        <th colspan="2">User Type</th>
        <th>Action</th>
      </tr>

      <tr>
        <td>User</td>
        <td>Profile</td>
        <td><a href="StudentUpdateStudentInfo.php">Change Your Profile</td>
      </tr>
      <tr>
        <td>Student</td>
        <td>Section</td>
        <td><a href="StudentSection.php">View Sections</td>
      </tr>
      <tr>
        <td>Mentor</td>
        <td>Mentor</td>
        <td><a href="MentorList.php">View Mentor</td>
      </tr>
      <tr>
        <td>Mentee</td>
        <td>Mentee</td>
        <td><a href="MenteeList.php">View Mentee</td>
      </tr>
    </table>
    <br>


    <h2 style="color:#FF0000" ;>Mentee Notification For Next Week</h2>
      <table style="width:80%">
        <tr>
          <th>Role</th>
          <th>Course Name</th>
          <th>Session Number</th>
          <th>Session Date</th>
          <th>Participate Mentee Count</th>
          <th>Participate/Cancel</th>
        </tr>

        <?php
        $myconnection = mysqli_connect('localhost', 'root', '', 'db2')
        or die ('Could not connect: ' . mysqli_error($myconnection));

        $menteequery = "SELECT * from enroll WHERE mentee_id = '{$_SESSION['user_id']}'";
        $menteeresult = mysqli_query($myconnection, $menteequery)
        or die ('Query failed: ' . mysqli_error($myconnection));
        $mentee = mysqli_fetch_array($menteeresult, MYSQLI_ASSOC);

        $menteemeetingquery = "SELECT * from meetings JOIN enroll ON (".
        "meetings.meet_id = enroll.meet_id) WHERE mentee_id = '{$_SESSION['user_id']}'";
        $menteemeetingresult = mysqli_query($myconnection, $menteemeetingquery)
        or die ('Query failed: ' . mysqli_error($myconnection));


        while ($menteemeeting = mysqli_fetch_array($menteemeetingresult, MYSQLI_ASSOC)) {
          // enrolled mentee query
          $enrolledmenteequery = "SELECT count(mentee_id) as count from enroll WHERE meet_id = '{$menteemeeting['meet_id']}'";
          $enrolledmenteeresult = mysqli_query($myconnection, $enrolledmenteequery)
          or die ('Query failed: ' . mysqli_error($myconnection));
          $enrolledmentee = mysqli_fetch_array($enrolledmenteeresult, MYSQLI_ASSOC);

          echo("<tr>");
          echo ("<td>Mentee</td>");
          echo("<td>".$menteemeeting['meet_name']."</td>");
          echo("<td>".$menteemeeting['meet_id']."</td>");
          echo("<td>".$menteemeeting['start_date']."</td>");
          echo("<td>".$enrolledmentee['count']."</td>");
          if ($enrolledmentee['count'] > 1 ) {
            echo("<td><form method=\"POST\" action=''>");
            echo("<input type=\"submit\" name=\"MenteeQuit\" value=\"Quit\"></form></td>");
            if (isset($_POST['MenteeQuit'])){
              $deletementee = "DELETE FROM enroll WHERE mentee_id = '{$_SESSION['user_id']}'
                                              AND meet_id = '{$menteemeeting['meet_id']}'";
              mysqli_query($myconnection, $deletementee);
              mysqli_close($myconnection);
              header('Location: StudentDashboard.php');
            }

          }
          else {
              echo("<td>"."N/A"."</td>");
          }
          echo("</tr>");

        }
        ?>
      </table>
    </form>
    <br>

    <h2 style="color:#FF0000" ;>Mentor Notification For Next Week</h2>

      <table style="width:80%">
        <tr>
          <th>Role</th>
          <th>Course Name</th>
          <th>Session Number</th>
          <th>Session Date</th>
          <th>Participate Mentor Count</th>
          <th>Participate/Cancel</th>
        </tr>

        <?php
        $myconnection = mysqli_connect('localhost', 'root', '', 'db2')
        or die ('Could not connect: ' . mysqli_error($myconnection));

        $mentorquery = "SELECT * from enroll2 WHERE mentor_id = '{$_SESSION['user_id']}'";
        $mentorresult = mysqli_query($myconnection, $mentorquery)
        or die ('Query failed: ' . mysqli_error($myconnection));
        $mentor = mysqli_fetch_array($mentorresult, MYSQLI_ASSOC);

        $mentormeetingquery = "SELECT * from meetings JOIN enroll2 ON (".
        "meetings.meet_id = enroll2.meet_id) WHERE mentor_id = '{$_SESSION['user_id']}'";
        $mentormeetingresult = mysqli_query($myconnection, $mentormeetingquery)
        or die ('Query failed: ' . mysqli_error($myconnection));


        while ($mentormeeting = mysqli_fetch_array($mentormeetingresult, MYSQLI_ASSOC)) {
          // enrolled mentor query
          $enrolledmentorquery = "SELECT count(mentor_id) as count from enroll2 WHERE meet_id = '{$mentormeeting['meet_id']}'";
          $enrolledmentorresult = mysqli_query($myconnection, $enrolledmentorquery)
          or die ('Query failed: ' . mysqli_error($myconnection));
          $enrolledmentor = mysqli_fetch_array($enrolledmentorresult, MYSQLI_ASSOC);

          echo("<tr>");
          echo ("<td>Mentor</td>");
          echo("<td>".$mentormeeting['meet_name']."</td>");
          echo("<td>".$mentormeeting['meet_id']."</td>");
          echo("<td>".$mentormeeting['start_date']."</td>");
          echo("<td>".$enrolledmentor['count']."</td>");
          if ($enrolledmentor['count'] > 1 ) {
            echo("<td><form method=\"POST\" action=''>");
            echo("<input type=\"submit\" name=\"MentorQuit\" value=\"Quit\"></form></td>");
            if (isset($_POST['MentorQuit'])){
              $deletementor = "DELETE FROM enroll2 WHERE mentor_id = '{$_SESSION['user_id']}'
                                              AND meet_id = '{$mentormeeting['meet_id']}'";
              mysqli_query($myconnection, $deletementor);
              mysqli_close($myconnection);
              header('Location: StudentDashboard.php');
            }

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
