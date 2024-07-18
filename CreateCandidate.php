<?php
require_once ('./config/db.php');
$inserted = false;
$n = 1;
if (isset($_POST['Name'])) {
    $name = $_POST['Name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $sql = "INSERT INTO `candidate`.`users` (`Name`,`Email`,`Mobile`,`Gender`) VALUES ('$name','$email','$mobile','$gender');";
    if ($conn->query($sql) == true) {
        $user_id = $conn->insert_id; // Get the inserted user ID
// Insert experiences
        $companies = $_POST['company'];
        $years = $_POST['years'];
        $months = $_POST['months'];

        for ($i = 0; $i < count($companies); $i++) {
            $company = $companies[$i];
            $year = $years[$i];
            $month = $months[$i];

            $sql_experience = "INSERT INTO `candidate`.`experience` (`u_id`, `company_name`, `years`, `months`) VALUES ('$user_id', '$company', '$year', '$month');";
            $conn->query($sql_experience);
        }
        $inserted = true;
    } else {
        echo "<center>Error in $sql<br> $conn->error </center>";
    }
    $name = "";
    $email = "";
    $gender = "";
    $mobile = "";
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Candidate Data</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="body-container">
        <form action="CreateCandidate.php" class="container" method="post" onsubmit="return validateForm()" id="forms">
            <a href="./index.php"><button type="button" href="./CreateCandidate.php"
                    class="btn">Back</button></a><br><br>
            <div class="input-box">
                <label for="Name">Name</label><br>
                <input type="text" name="Name" id="Name">
            </div>
            <div class="input-box">
                <label for="">Email</label><br>
                <input type="text" name="email" id="email">
            </div>
            <label>Gender</label>
            <div>
                <input type="radio" name="gender" id="m" value="Male">Male
                <input type="radio" name="gender" id="f" value="Female">Female
            </div>
            <div class="input-box">
                <label for="mobile">Mobile</label><br>
                <input type="number" name="mobile" id="mobile">
            </div>
            <div class="heading">Experience: </div>
            <div class="Experience" id="experience-container">
                <div>
                    <label for="company">Company Name</label>
                    <input type="text" name="company[]" id="company">
                    <div class="row">
                        <div>
                            <label for="years">No of Years</label>
                            <input type="number" name="years[]" id="years">
                        </div>
                        <div>
                            <label for="months">No of Months</label>
                            <input type="number" name="months[]" id="months">
                        </div>
                    </div>
                    <button type="button" class="btn" onclick="addExperience()">Add more Experience</button>
                </div>
            </div>
            <div class="btn-submit">
                <input type="submit" value="Create" class="btn">
                <div id="error-message"></div>
                <?php
                if ($inserted == true) {
                    echo "<br><br><h3>Data Submitted Successfully!!</h3>";
                }
                ?>
            </div>
        </form>
    </div>
    <script>
        let n = 1;
        let err = "";
        function addExperience() {
            if (n <= 4) {
                const container = document.getElementById('experience-container');
                const newExperience = document.createElement("div");
                newExperience.className = 'experience-sub';
                newExperience.innerHTML = `
                    <label for="company">Company Name</label>
                    <input type="text" name="company[]" id="company" >
                    <div class="row">
                    <div>
                        <label for="years">No of Years</label>
                        <input type="number" name="years[]" id="years" >
                    </div>
                    <div>
                        <label for="months">No of Months</label>
                        <input type="number" name="months[]" id="months" >
                    </div>
                </div>
            `;
                container.appendChild(newExperience);
                n++;
            }
        }
        function validateForm() {
            const name = document.getElementById('Name').value;
            const email = document.getElementById('email').value;
            const mobile = document.getElementById('mobile').value;
            const f = document.getElementById('forms');
            const gender = document.querySelector('input[name="gender"]:checked');
            const experienceFields = document.querySelectorAll('.experience-sub');
            if (!name || !email || !mobile || !gender) {
                displayErrorMessage("Please fill out all required fields.");
                return false;
            }
            // Validate email format
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                displayErrorMessage("Please enter a valid email address.");
                return false;
            }
            // Validate mobile number format (10 digits)
            const mobilePattern = /^[0-9]{10}$/;
            if (!mobilePattern.test(mobile)) {
                displayErrorMessage("Please enter a valid 10-digit mobile number.");
                return false;
            }
            for (let field of experienceFields) {
                const company = field.querySelector('input[name="company[]"]').value;
                const years = field.querySelector('input[name="years[]"]').value;
                const months = field.querySelector('input[name="months[]"]').value;

                if (!company || !years || !months) {
                    displayErrorMessage("Please fill out all experience fields.");
                    return false;
                }
            }
            return true;
        }
        function displayErrorMessage(message) {
            const errorContainer = document.getElementById('error-message');
            errorContainer.innerHTML = `<h3>${message}</h3>`;
        }
    </script>
</body>

</html>