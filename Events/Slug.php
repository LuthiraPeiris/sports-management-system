<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLUG COMPETITION</title>
    <link rel="icon" type="image/x-icon" href="../images/Favicon.png">
    <style>
        body {font-family: Arial, sans-serif;background: #f8f9fc;margin: 0;padding: 0;}

        /*navigation bar*/
        .top-bar {padding: 15px;display: flex;justify-content: flex-start;}
        .back-btn {background-color: #ffffff;color: #0b0a0a;border: none;padding: 10px 15px;border-radius: 10px;cursor: pointer;font-size: 12px;transition: background 0.3s ease;font-weight: 600;}
        .back-btn:hover {background-color: #e85a2a;color: #ffffff;}

        .section-header {position: relative;border-radius: 12px;overflow: hidden;height: 400px;background: url('images/sports.png') center/cover no-repeat;}

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
    <!--Back button-->
    <div class="top-bar">
        <button class="back-btn" onclick="gotoHomepage()">← Back to Home</button>
    </div>

    <!--header section-->
    <div class="section-header">
        <div class="overlay">
            <h1 class="title">Slug Competition</h1>
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
            <p>The annual slug race competition brings together the best teams from across the university in an exciting
                display of speed, strategy, and team coordination.</p>
            <div class="info-grid">
                <div class="info-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="currentColor" class="icon">
                        <path
                            d="M6 2a1 1 0 0 1 1 1v1h6V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H3V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1zM3 9h14v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9zm4 3v3h2v-3H7z" />
                    </svg>Date: November 15, 2025</div>
                <div class="info-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="currentColor" class="icon">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z" />
                    </svg>Location: University Main Track & Field</div>
                <div class="info-item"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="currentColor" class="icon">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>Teams: 12 registered</div>
            </div>
        </div>
        <div class="card">
            <h2>History</h2>
            <p>Started in 2018, the Slug Competition has become one of the most anticipated events on campus, showcasing
                incredible athletic prowess and team spirit.</p>
        </div>
    </div>



    <!-- Achievements Section -->
    <div id="achievements" class="section">
        <div class="achievements-grid">


            <div class="achievement-card">
                <div class="icon">
                    <img src="images/inter-uni-champion.jpg" alt="Inter University Champion">
                </div>
                <div class="content">
                    <h3>Inter-University Champion</h3>
                    <p>Dominated 3 consecutive years with Alpha Team.</p>
                </div>
            </div>


            <div class="achievement-card">
                <div class="icon">
                    <img src="images/speed_race.jpg" alt="Speed Record">
                </div>
                <div class="content">
                    <h3>Speed Record</h3>
                    <p>Fastest completion time: 2.45 seconds.</p>
                </div>
            </div>


            <div class="achievement-card">
                <div class="icon">
                    <img src="images/fairplay.jpg" alt="Popularity Award">
                </div>
                <div class="content">
                    <h3>Fair Play Award</h3>
                    <p>Recognized for outstanding sportsmanship and discipline</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Schedules Section -->
    <div id="schedules" class="section">
        <div class="card">
            <h2>Event Schedule</h2>
            <ul class="schedule-list">
                <li><span>09:00 AM</span>Registration & Team Check-in</li>
                <li><span>10:30 AM</span>Opening Ceremony</li>
                <li><span>11:00 AM</span>Qualifying Rounds</li>
                <li><span>02:00 PM</span>Semi-Finals</li>
                <li><span>04:00 PM</span>Finals & Awards</li>
            </ul>
        </div>
        <div class="card">
            <h2>Upcoming Slug Competition Dates</h2>
            <p><span style="background:#e9f0ff;padding:5px 10px;border-radius:6px;color:#007bff;font-weight:bold;">November
                    15, 2025</span> Annual Slug Competition 2025</p>
            <p><span style="background:#e9f0ff;padding:5px 10px;border-radius:6px;color:#007bff;font-weight:bold;">November
                    15, 2025</span> Annual Slug Competition 2025</p>
            <p><span style="background:#e9f0ff;padding:5px 10px;border-radius:6px;color:#007bff;font-weight:bold;">November
                    15, 2025</span> Annual Slug Competition 2025</p>
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