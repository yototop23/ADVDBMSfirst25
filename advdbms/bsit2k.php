<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style type="text/css">
        .bebas-neue-regular {
            font-family: "Bebas Neue", sans-serif;
            font-weight: 200;
            font-style: normal;
        }
        .poppins-thin {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: normal;
        }
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Student Midterm Grades</h2>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbplmun";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $sql = "SELECT stud_num, last_name, first_name, course, year_level, 
                   mtcp, mt_attendance, mt_q1, mt_q2, mt_q3, mt_q4, mt_lec, mt_lab 
            FROM BSIT2K ORDER BY last_name";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered table-striped table-hover fs-6 poppins-regular'>
                <thead class='thead-dark'>
                    <tr>
                        <th>StudNum</th>
                        <th>Lastname</th>
                        <th>Firstname</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>MTCP</th>
                        <th>Attendance</th>
                        <th>Q1</th>
                        <th>Q2</th>
                        <th>Q3</th>
                        <th>Q4</th>
                        <th>Lecture</th>
                        <th>Lab</th>
                        <th>Midterm Grade</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = $result->fetch_assoc()) {
            // Calculate quiz average
            $quiz_avg = (((($row['mt_q1']/15)*60+40) + (($row['mt_q2']/10)*60+40) + (($row['mt_q3']/25)*60+40) + (($row['mt_q4']/40)*60+40)) / 4);

            // Calculate lecture component (60% weight)
            $lecture_part = ($row['mtcp'] * 0.20) + ($row['mt_attendance'] * 0.10) + ($quiz_avg * 0.30) + ((($row['mt_lec']/50)*60+40) * 0.40);

            // Calculate final midterm grade (60% lecture + 40% lab)
            $midterm_grade = ($lecture_part * 0.60) + ($row['mt_lab'] * 0.40);

            // Format midterm grade to two decimal places and remove extra zeros
            $midterm_grade_display = number_format($midterm_grade, 2, '.', '');
            $midterm_grade_display = rtrim(rtrim($midterm_grade_display, '0'), '.');

            // Output midterm grade with conditional formatting
            if ($midterm_grade < 75) {
                $midterm_grade_display = "<span class='text-danger'>{$midterm_grade_display}</span>";
            }

            echo "<tr>
                    <td>{$row['stud_num']}</td>
                    <td>{$row['last_name']}</td>
                    <td>{$row['first_name']}</td>
                    <td>{$row['course']}</td>
                    <td>{$row['year_level']}</td>
                    <td>{$row['mtcp']}</td>
                    <td>{$row['mt_attendance']}</td>
                    <td>{$row['mt_q1']}</td>
                    <td>{$row['mt_q2']}</td>
                    <td>{$row['mt_q3']}</td>
                    <td>{$row['mt_q4']}</td>
                    <td>{$row['mt_lec']}</td>
                    <td>{$row['mt_lab']}</td>
                    <td><strong>{$midterm_grade_display}</strong></td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-warning'>No records found</div>";
    }
    $conn->close();
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>
