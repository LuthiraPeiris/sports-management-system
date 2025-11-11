<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Interface</title>
    <link rel="icon" href="..\images\Favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    
</head>

<body>

     <style>
        .top-header {
            background-color: #2b2b2b;
        }

        .info-bar {
            background-color: #a8e6ff;
        }

        .nav-bar {
            background-color: #8b7bd8;
        }

        .nav-bar .nav-link {
            color: white !important;
        }

        .nav-bar .nav-link:hover {
            background-color: #7a6bc7;
        }

        .nav-bar .nav-link.active {
            background-color: #6a5bb7 !important;
        }

        .logo-circle {
            width: 60px;
            height: 60px;
            background-color: white;
        }

        .profile-placeholder {
            background-color: #ff9999;
            font-size: 12px;
            line-height: 1.3;
        }

        .dropdown-menu {
            background-color: #8b7bd8;
            border: none;
            width: 100%;
            margin: 0;
        }

        .dropdown-menu .dropdown-item {
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #7a6bc7;
        }

        .dropdown-menu .dropdown-item.active {
            background-color: #6a5bb7;
        }

        .mobile-menu-btn {
            text-decoration: none !important;
        }

        .mobile-menu-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 5px;
        }
    </style>



    <!-- header section -->
    <div class="info-bar py-3 px-3 px-md-4">
        <div class="d-flex justify-content-between align-items-center">
            
            <div class="d-flex align-items-center gap-3">
                <div class="logo-circle rounded-circle d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0">
                    <img src="../images/Favicon.png" alt="Sports Club Logo" class="w-100 h-100" style="object-fit: cover;">
                </div>
                <div >
                    <div class="fw-bold" style="font-size: 14px; color: #333;">Sports Club</div>
                    <div class="fw-semibold" style="font-size: 14px; color: #333;">Sabaragamuwa University Of Sri Lanka</div>
                </div>
            </div>

            <div class="d-none d-md-flex align-items-center gap-3">
                <div class="profile-placeholder text-white rounded text-center px-3 py-2">
                    <div style="font-size: 40px;">👤</div>
                </div>
                <div class="text-end">
                    <p class="fw-bold mb-0" style="color: #333;">Coach Name</p>
                    <p class="mb-0" style="font-size: 12px; color: #555;">#########</p>
                </div>
            </div>

            <!-- mobile nav button -->
            <div class="d-md-none">
                <button class="btn btn-link text-dark fs-3 p-0 mobile-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    ☰
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="px-3 py-2 border-bottom border-light">
                        <div class="d-flex align-items-center gap-2">
                            <div style="font-size: 40px;">👤</div>
                            <div>
                                <div class="fw-bold text-white" style="font-size: 14px;">Coach Name</div>
                                <div class="text-white" style="font-size: 12px;">#########</div>
                            </div>
                        </div>
                    </li>
                    <li><a class="dropdown-item active" href="#">Home</a></li>
                    <li><a class="dropdown-item" href="#">Dashboard</a></li>
                    <li><a class="dropdown-item" href="#">Matches</a></li>
                    <li><a class="dropdown-item" href="#">Players</a></li>
                    <li><a class="dropdown-item" href="#">Requests</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Desktop Navigation Bar -->
    <nav class="nav-bar d-none d-md-block">
        <ul class="nav nav-fill">
            <li class="nav-item">
                <a class="nav-link active py-3" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-3" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-3" href="#">Matches</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-3" href="#">Players</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-3" href="#">Requests</a>
            </li>
        </ul>
    </nav>

  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>