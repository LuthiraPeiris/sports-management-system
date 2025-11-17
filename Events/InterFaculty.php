<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inter Faculty Sports Meet</title>
    <link rel="icon" type="image/x-icon" href="../images/Favicon.png">
    <style>
        body {font-family: Arial, sans-serif;background: #f8f9fc;margin: 0;padding: 0;}

        /*navigation bar*/
        .top-bar {padding: 10px;}

        .back-btn {display: flex;align-items: center;gap: 8px;background-color: #e4572e;color: #fff;padding: 10px 20px;border: none;border-radius: 30px;cursor: pointer;font-size: 16px;font-weight: bold;transition: background 0.3s ease, transform 0.2s ease;}

        .back-btn:hover {background-color: #cf431e;transform: translateX(-3px);}

        .back-btn:active {transform: scale(0.98);}

        .arrow-icon {margin-right: 5px;}

        .section-header {position: relative;border-radius: 12px;overflow: hidden;height: 400px;background: url('images/Sports_wallpaper.jpg') center/cover no-repeat;}

        .overlay {position: absolute;width: 100%;height: 100%;background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(255, 255, 255, 0.9));display: flex;flex-direction: column;justify-content: flex-end;padding: 20px;}

        .title {font-size: 45px;font-weight: bold;color: #000;}

        /* Three Tabs */
        .tabs {display: flex;justify-content: center;background-color: #f3f7fd;border-radius: 10px;padding: 10px;max-width: 700px;margin: 20px auto;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);}
        .tab {flex: 1;border: none;background: none;font-size: 16px;font-weight: 500;color: #5a6b7b;padding: 10px 20px;border-radius: 8px;cursor: pointer;transition: all 0.2s ease;}
        .tab:hover {background-color: #e8efff;}
        .tab.active {background-color: white;color: #007bff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);}

        /* Home Tab*/
        .section {display: none;margin: 20px;}
        .section.active {display: block;}

        .card {background: #fff;margin-bottom: 20px;padding: 20px;border-radius: 8px;box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);}
        .card h2 {margin-bottom: 10px;font-size: 20px;}

        .info-grid {display: flex;justify-content: space-between;flex-wrap: wrap;gap: 1rem;margin-top: 10px;}
        .info-item {display: flex;align-items: center;font-size: 16px;color: #333;}

        .info-item .icon {margin-right: 8px;color: #007bff;font-size: 18px;}

        /* Achievements Tab */
        .section {padding: 40px;background: #f9fafb;}
        .achievements-grid {display: grid;grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));gap: 25px;justify-content: center;}
        .achievement-card {background: #fff;border-radius: 16px;overflow: hidden;box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);transition: transform 0.3s ease, box-shadow 0.3s ease;font-family: "Poppins", sans-serif;}
        .achievement-card:hover {transform: translateY(-6px);box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);}
        .achievement-card .icon {height: 180px;overflow: hidden;}
        .achievement-card .icon img {width: 100%;height: 100%;object-fit: cover;}
        .achievement-card .content {padding: 20px;text-align: left;}
        .achievement-card h3 {font-size: 18px;margin-bottom: 8px;color: #222;}
        .achievement-card p {font-size: 14px;color: #555;margin: 0;}

        /* Schedule Tab */
        .schedule-list {list-style: none;padding: 0;}
        .schedule-list li {padding: 10px 0;border-bottom: 1px solid #eee;font-size: 16px;}
        .schedule-list li span {font-weight: bold;color: #007bff;margin-right: 10px;}

        /* Responsive */
        @media (max-width: 768px) {
            .title {
                font-size: 28px;
            }

            .info-grid {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .back-btn {
                font-size: 12px;
                padding: 8px 12px;
            }

            .title {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <!-- Back button -->
<div class="top-bar">
    <button class="back-btn" onclick="gotoHomepage()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" class="arrow-icon">
            <path d="M15 18l-6-6 6-6" />
        </svg>
        Back to Home
    </button>
</div>


    <!--header section-->
    <div class="section-header">
        <div class="overlay">
            <h1 class="title">Inter Faculty Sports Meet</h1>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab active" onclick="showSection('home', this)">Home</div>
        <div class="tab" onclick="showSection('achievements', this)">Achievements</div>
        <div class="tab" onclick="showSection('schedules', this)">Schedules</div>
    </div>

    <!-- Home Content -->
    <div id="home" class="section active">
        <div class="card">
            <h2>Event Overview</h2>
            <p>An annual celebration of athletic talent and faculty pride, where different departments compete in
                friendly yet competitive sporting events.</p>
            <div class="info-grid">
                <div class="info-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="currentColor" class="icon">
                        <path
                            d="M6 2a1 1 0 0 1 1 1v1h6V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H3V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1zM3 9h14v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9zm4 3v3h2v-3H7z" />
                    </svg>Date: November 16, 2025</div>
                <div class="info-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="currentColor" class="icon">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z" />
                    </svg>Location: University Sports Complex</div>
                <div class="info-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="currentColor" class="icon">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>Teams: 20 registered</div>
            </div>
        </div>
        <div class="card">
            <h2>History</h2>
            <p>Since 2015, the Inter Faculty Sports Meet has strengthened bonds between departments while promoting
                healthy competition and athletic excellence.</p>
        </div>
    </div>



    <!-- Achievements Section -->
    <div id="achievements" class="section">
        <div class="achievements-grid">


            <div class="achievement-card">
                <div class="icon">
                    <img src="images/Champion.jpeg" alt="Inter University Champion">
                </div>
                <div class="content">
                    <h3>Champions</h3>
                    <p>2024 Champions: Faculty of Computing </p>
                </div>
            </div>


            <div class="achievement-card">
                <div class="icon">
                    <img src="images/Mostwins.jpeg" alt="Speed Record">
                </div>
                <div class="content">
                    <h3>Most Wins</h3>
                    <p>Faculty of Sciences (5 events)</p>
                </div>
            </div>


            <div class="achievement-card">
                <div class="icon">
                    <img src="images/Team_spirit.webp" alt="Popularity Award">
                </div>
                <div class="content">
                    <h3>Team Spirit</h3>
                    <p>Best team spirit: Faculty of Managemnet </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Schedules Section -->
    <div id="schedules" class="section">
        <div class="card">
            <h2>Event Schedule</h2>
            <ul class="schedule-list">
                <li><span>08:00 AM</span>Faculty Registration</li>
                <li><span>09:00 AM</span>Opening Ceremonys</li>
                <li><span>10:00 AM</span>Various Sport Competitions</li>
                <li><span>11:00 AM</span>Faculty Relay Championships</li>
                <li><span>11:30 AM</span>Finals & Closing Ceremony</li>
            </ul>
        </div>
        <div class="card">
            <h2>Upcoming Inter Faculty Sports Meet Dates</h2>
            <p><span style="background:#e9f0ff;padding:5px 10px;border-radius:6px;color:#007bff;font-weight:bold;">November
                    15, 2025</span> Inter Faculty Sports Meet 2026</p>
            <p><span style="background:#e9f0ff;padding:5px 10px;border-radius:6px;color:#007bff;font-weight:bold;">December
                    15, 2025</span> Annual Competition 2025</p>
            <p><span style="background:#e9f0ff;padding:5px 10px;border-radius:6px;color:#007bff;font-weight:bold;">December
                    15, 2025</span> Mid-Year Faculty Challenge</p>
        </div>
    </div>

    <script>
        function showSection(sectionId, tabElement) {
            document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');
            tabElement.classList.add('active');
        }

        function gotoHomepage() {
            window.location.href = "../Homepage.php#events";
        }
    </script>
</body>

</html>