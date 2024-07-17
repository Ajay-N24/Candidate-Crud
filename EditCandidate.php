<?php
require_once ('./config/db.php');
$updated = false;
$n = 1;
if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $query = "SELECT * FROM candidate.users WHERE u_id=$id;";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    $exp_query = "SELECT * FROM candidate.experience WHERE u_id = $id;";
    $exp_result = mysqli_query($conn, $exp_query);
    $experiences = [];
    while ($row = mysqli_fetch_assoc($exp_result)) {
        $experiences[] = $row;
    }
}
if (isset($_POST["id"])) {
    $id = $_POST['id'];
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $mobile = $_POST['Mobile'];
    $gender = $_POST['gender'];

    // Update user
    $query = "UPDATE candidate.users SET Name='$name', Email='$email', Mobile='$mobile', Gender='$gender' WHERE u_id = $id;";
    mysqli_query($conn, $query);

    // Insert new exp
    $companies = $_POST['company'];
    $years = $_POST['years'];
    $months = $_POST['months'];

    for ($i = 0; $i < count($companies); $i++) {
        $company = $companies[$i];
        $year = $years[$i];
        $month = $months[$i];

        $exp_query = "INSERT INTO candidate.experience (u_id, company_name, years, months) VALUES ('$id', '$company', '$year', '$month');";
        mysqli_query($conn, $exp_query);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Candidate Data</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding-top: 70px;
            padding: 20px;
        }

        .btn {
            padding: 10px 17px;
            border-radius: 10%;
            cursor: pointer;
        }

        .body-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            position: relative;
            max-width: 700px;
            width: 100%;
            padding: 25px 70px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .input-box {
            margin: 24px 0;
        }

        .Experience {
            margin: 24px 0;
        }

        .Experience input {
            width: 100%;
            height: 30px;
            font-size: 16px;
        }

        .heading {
            margin: 10px 0;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .input-box input {
            width: 100%;
            height: 30px;
            font-size: 16px;
        }

        .Experience .row {
            display: flex;
            padding: 10px 0;
            align-content: space-between;
        }

        .Experience .row div:nth-child(1) {
            margin-right: 50px;
        }

        .btn-submit {
            text-align: center;
        }

        .experience-sub {
            margin: 24px 0;
        }

        #error-message {
            margin-top: 18px;
            color: red;
        }
    </style>
</head>

<body>
    <div class="body-container">
        <form action="CreateCandidate.php" class="container" method="post" onsubmit="return validateForm()" id="forms">
            <a href="./index.php"><button type="button" href="./CreateCandidate.php"
                    class="btn">Back</button></a><br><br>
            <div class="input-box">
                <label for="Name">Name</label><br>
                <input type="text" name="Name" id="Name" value="<?php echo $user['Name']; ?>">
            </div>
            <div class="input-box">
                <label for="">Email</label><br>
                <input type="text" name="email" id="email" value="<?php echo $user['Email']; ?>">
            </div>
            <label>Gender</label>
            <div>
                <input type="radio" name="gender" id="m" value="Male" <?php if ($user['Gender'] == 'Male')
                    echo 'checked'; ?>>Male
                <input type="radio" name="gender" id="f" value="Female" <?php if ($user['Gender'] == 'Female')
                    echo 'checked'; ?>>Female
            </div>
            <div class="input-box">
                <label for="mobile">Mobile</label><br>
                <input type="number" name="mobile" id="mobile" value="<?php echo $user['Mobile']; ?>">
            </div>
            <div class="heading">Experience: </div>
            <div class="Experience" id="experience-container">
                <div>
                    <?php
                    foreach ($experiences as $exp) {
                        echo
                            '<label for="company">Company Name</label>
                            <input type="text" name="company[]" id="company" value="' . $exp['company_name'] . '">
                            <div class="row">
                                <div>
                                    <label for="years">No of Years</label>
                                    <input type="number" name="years[]" id="years" value="' . $exp['years'] . '">
                                </div>
                                <div>
                                    <label for="months">No of Months</label>
                                    <input type="number" name="months[]" id="months" value="' . $exp['months'] . '">
                                </div>
                            </div>
                            ';
                    }
                    ?>
                    <button type="button" class="btn" onclick="addExperience()">Add more Experience</button>
                </div>
            </div>
            <div class="btn-submit">
                <input type="submit" value="Create" class="btn">
                <div id="error-message"></div>
                <?php
                if ($updated == true) {
                    echo "<br><br><h3>Data Updated Successfully!!</h3>";
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