<?php
include 'db.php';

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $sport_id = intval($_POST['sport_id']);

    if ($role == 'student') {
        $student_id = $_POST['studentID'];
        $nic = $_POST['studentNIC'];
        $coach_id = NULL;
    } else {
        $coach_id = $_POST['coachID'];
        $nic = $_POST['coachNIC'];
        $student_id = NULL;
    }

    //  Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, nic, sport_id, student_id, coach_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiis", $name, $email, $password, $role, $nic, $sport_id, $student_id, $coach_id);

    if ($stmt->execute()) {

        $user_id = $conn->insert_id; //  newly created user id

        if ($role == 'student') {

            //  Insert Student
            $stmt2 = $conn->prepare("INSERT INTO student (user_id, name, nic, sport_id, student_id) 
                                     VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("issis", $user_id, $name, $nic, $sport_id, $student_id);
            $stmt2->execute();
            $stmt2->close();

        } else {

            //  Insert Coach
            $stmt3 = $conn->prepare("INSERT INTO coach (user_id, name, nic, sport_id, coach_id) 
                                     VALUES (?, ?, ?, ?, ?)");
            $stmt3->bind_param("issis", $user_id, $name, $nic, $sport_id, $coach_id);
            $stmt3->execute();
            $stmt3->close();

            //  Assign Coach to Sport (using user_id as coach reference)
            $assign = $conn->prepare("INSERT INTO sport_coach (sport_id, coach_id) VALUES (?, ?)");
            $assign->bind_param("ii", $sport_id, $user_id);
            $assign->execute();
            $assign->close();
        }

        echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Registration Failed: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration</title>
    <link rel="icon" type="image/x-icon" href="../images/Favicon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        body {
        background-image: url("../images/register.avif"); 
        background-size: cover;background-position: center;background-attachment: fixed;height: 100vh;overflow: hidden;margin: 0;padding: 0;
        }

        .role-btn {padding: 10px 20px;font-weight: 600;border-radius: 8px;transition: all 0.25s ease;background-color: #ffffffcc;backdrop-filter: blur(6px);}

        /* Hover effect */
        .role-btn:hover {background-color: #e6e9ff;border-color: #2937a5;color: #2937a5;}

        /* Active (Selected) Button */
        .role-btn.active {background-color: #2937a5 !important;border-color: #2937a5 !important;color: white !important;box-shadow: 0 0 12px rgba(41, 55, 165, 0.4);}

        .home-btn {position: absolute; top: 20px; left: 20px; background-color: rgba(255, 255, 255, 0.95); color: #333; border: 2px solid rgba(255, 255, 255, 1);backdrop-filter: blur(10px);font-weight: 500;padding: 10px 20px;border-radius: 10px;box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.15);transition: 0.3s;}
        .home-btn:hover {background-color: white; color: rgb(70, 140, 252);border-color: rgb(70, 140, 252);box-shadow: 0px 4px 15px rgba(70, 140, 252, 0.3);transform: translateY(-2px);}
        
        .login-link {color: #1a1a1a; text-decoration: none; font-size: 14px; font-weight: 500;}
        .login-link:hover {color: rgb(70, 140, 252); text-decoration: underline;}
        
        .btn {transition: 0.7s;}
        .btn:hover {box-shadow: 1px 2px 6px rgb(78, 78, 78);}

    </style>
</head>

<body class="bg-light">
    <!-- Back to Homepage Button -->
    <a href="../Homepage.php" class="btn home-btn">
        ← Back to Home
    </a>
 
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh; padding: 20px; overflow-y: auto;">
        <div class="card shadow-sm p-4 w-100 rounded-4" style="max-width: 650px; background: rgba(255, 255, 255, 0.81);box-shadow: 0px 2px 8px rgb(70, 140, 252);backdrop-filter: blur(10px); ">
            <h2 class="text-center mb-4 fw-bold" style="color:#1a1a1a;" id="formTitle"> Registration Form</h2>

            <!-- Role Selection -->
            <div class="mb-4">
                <label class="form-label fw-semibold" style="color: #333;">Select Role:</label>
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-outline-primary role-btn active" id="studentBtn">Student</button>
                    <button type="button" class="btn btn-outline-primary role-btn" id="coachBtn" >Coach</button>
                </div>
            </div>

            <form action="" method="post">
                <input type="hidden" name="role" id="selectedRole" value="student">

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: #333">Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Your Name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: #333">Email:</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: #333">Password:</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter Your Password" required>
                        </div>

                        <div class="mb-3">
                            <label for="sport_id" class="form-label">Select Sport</label>
                            <select name="sport_id" id="sport_id" class="form-select" required>
                            <option value="">-- Choose a Sport --</option>
                            <?php
                            include 'db.php';
                            $sports = $conn->query("SELECT sport_id, name FROM sports");
                            while ($row = $sports->fetch_assoc()) {
                                echo "<option value='{$row['sport_id']}'>{$row['name']}</option>";
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Student Fields -->
                <div id="studentFields">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333;">Student ID:</label>
                                <input type="text" name="studentID" id="studentID" class="form-control" placeholder="Enter Student ID" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333;">NIC:</label>
                                <input type="text" name="studentNIC" id="studentNIC" class="form-control" placeholder="Enter NIC" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coach Fields -->
                <div id="coachFields" style="display:none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333">Coach ID:</label>
                                <input type="text" name="coachID" id="coachID" class="form-control" placeholder="Enter Coach ID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" style="color: #333">NIC:</label>
                                <input type="text" name="coachNIC" id="coachNIC" class="form-control" placeholder="Enter NIC">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" name="register" class="btn btn-primary px-4">Register</button>
                </div>
            </form>
            <!-- Login Link -->
            <div class="mt-3 text-center">
                <a href="Login.php" class="login-link">Already have an account? Login here</a>
            </div>
        </div>
    </div>

    <script>
        const studentBtn = document.getElementById('studentBtn');
        const coachBtn = document.getElementById('coachBtn');
        const studentFields = document.getElementById('studentFields');
        const coachFields = document.getElementById('coachFields');
        const selectedRole = document.getElementById('selectedRole');
        const formTitle = document.getElementById('formTitle');

        studentBtn.onclick = () => {
            selectedRole.value = "student";
            studentBtn.classList.add('active');
            coachBtn.classList.remove('active');

            studentFields.style.display = 'block';
            coachFields.style.display = 'none';

            document.getElementById('studentID').required = true;
            document.getElementById('studentNIC').required = true;
            document.getElementById('coachID').required = false;
            document.getElementById('coachNIC').required = false;
        };

        coachBtn.onclick = () => {
            selectedRole.value = "coach";
            coachBtn.classList.add('active');
            studentBtn.classList.remove('active');

            coachFields.style.display = 'block';
            studentFields.style.display = 'none';

            document.getElementById('coachID').required = true;
            document.getElementById('coachNIC').required = true;
            document.getElementById('studentID').required = false;
            document.getElementById('studentNIC').required = false;
        };
    </script>


</body>

</html>