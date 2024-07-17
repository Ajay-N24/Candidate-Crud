<?php
require_once ('./config/db.php');
// $query = "select users.Name, users.Email, users.Mobile from candidate.users";
$query = "SELECT
    candidate.users.u_id,
    Name,
    Email,
    Mobile,
    COUNT(DISTINCT exp_id) AS Companies_Served,
    FLOOR(SUM(years)+(SUM(months)/12)) AS year,
    (SUM(months) % 12) As month
FROM
    candidate.users 
LEFT JOIN
    candidate.experience
    ON candidate.users.u_id = candidate.experience.u_id
GROUP BY
    candidate.users.u_id";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Data Application</title>
</head>

<body>
    <a href="./CreateCandidate.php"><button type="button" href="./CreateCandidate.php">Create</button></a>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Total Company Served</th>
                <th>Total Experience</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo
                    '<tr>
                        <td>' . $row['Name'] . '</td>
                        <td>' . $row['Email'] . '</td>
                        <td>' . $row['Mobile'] . '</td>
                        <td>' . $row['Companies_Served'] . '</td>
                        <td>' . $row['year'] . ' Years , ' . $row['month'] . ' Months' . '</td>
                        <td>
                            <a href="EditCandidate.php?id=' . $row['u_id'] . '"><button type="button">Edit</button></a>
                            <button type="button" onclick="deleteCandidate(' . $row['u_id'] . ')">Delete</button>
                        </td>
                    </tr>
                    ';
            }
            ?>
        </tbody>
        <script>
            function deleteCandidate(id) {
                if (confirm("Are you sure you want to delete this candidate?")) {
                    window.location.href = "DeleteCandidate.php?id=" + id;
                }
            }
        </script>
    </table>
</body>

</html>