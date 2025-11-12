<?php
session_start();
include 'db.php';


if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'student') {
                header("Location: ../Student_Interface/Student_Dashboard.php");
                exit();
            } else {
                header("Location: ../Coach_Interface/Coach_Dashboard.php");
                exit();
            }
        } else {
            echo "<script>alert('Invalid Password');</script>";
        }
    } else {
        echo "<script>alert('Email Not Found');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-image: url("../images/login_4.jpeg"); background-size: cover; background-repeat: no-repeat; background-position: center; min-height: 100vh;}

        .login-card input { width: 100%; margin-bottom: 15px;}
        .login-card {color: white;max-width: 450px;padding: 35px 40px;border-radius: 15px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 2px 8px rgb(70, 140, 252);backdrop-filter: blur(10px);text-align: center;height: 50vh;     display: flex;flex-direction: column;justify-content: center; text-decoration-color: blue;}
        .btn{transition: 0.7s;}
        .btn:hover{box-shadow: 1px 2px 6px rgb(78, 78, 78);}
        .register-link {color: #333; text-decoration: none; font-size: 14px; font-weight: 500;}
        .register-link:hover {color: rgb(70, 140, 252); text-decoration: underline;}
        .home-btn {position: absolute; top: 20px; left: 20px;}
        .login-card h2 {color: #1a1a1a;}
    </style>
</head>

<body class="bg-light">
    <!-- Back to Homepage Button -->
    <a href="../Homepage.php" class="btn home-btn">
        <i class="bi bi-house-door"></i>  ← Back to Home
    </a>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card login-card w-100">
            <h2 class="text-center mb-4 fw-bold">Login</h2>
            <form method="POST">
                <input class="form-control mb-3" placeholder="Email" name="email" required>
                <input class="form-control mb-3" type="password" placeholder="Password" name="password" required>
                <div class="text-center">
                    <button class="btn btn-primary px-5" name="login">Login</button>
                </div>
            </form>
            <!-- Register Link -->
            <div class="mt-3">
                <a href="Register.php" class="register-link">Don't have an account? Register here</a>
            </div>
        </div>
    </div>
</body>
</html>
