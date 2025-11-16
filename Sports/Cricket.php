<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUSL Cricket Team</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800 font-sans scroll-smooth">

<!-- Navbar -->
<header class="bg-[#3e6991] text-white py-4 shadow fixed w-full top-0 z-50">
  <div class="max-w-6xl mx-auto px-4 flex justify-between items-center gap-3 sm:gap-0">
    <div class="flex items-center space-x-2 sm:space-x-3">
      <img src="../images/Favicon.png" alt="SUSL Logo" class="w-12 h-12 rounded-full bg-white p-1" />
      <div>
        <h1 class="text-base sm:text-lg font-bold leading-tight">Sports Club</h1>
        <p class="text-sm text-gray-200 -mt-1">Sabaragamuwa University of Sri Lanka</p>
      </div>
    </div>
    <nav class="flex flex-wrap justify-center gap-2 sm:gap-4 text-sm sm:text-base">
      <a href="#introduction" class="hover:underline transition duration-300">Introduction</a>
      <a href="#details" class="hover:underline transition duration-300">Team Highlights</a>
      <a href="../Homepage.php#categories"
      class="px-3 py-1.5 rounded-md bg-white/10 border border-white/20 
          text-white text-sm backdrop-blur-sm
          hover:bg-white/20 hover:border-white/30 hover:shadow-sm
          transition duration-300 ease-out">
          ⬅ Back
      </a>
    </nav>
  </div>
</header>

<!-- Hero Section -->
<section 
  id="introduction"
  class="relative text-center py-24 h-screen mt-16 bg-cover bg-center bg-no-repeat text-white"
  style="background-image: url('images/cricketteam.jpg');"
>
  <div class="relative z-10 max-w-3xl mx-auto px-4 bg-black/40 rounded-xl p-6">
    <h2 class="text-4xl font-bold mb-6 text-white">🏏 SUSL Cricket Team</h2>
    <p class="text-lg leading-relaxed text-white/90 mb-4">
      The Sabaragamuwa University Cricket Team competes in inter-university tournaments, showcasing batting, bowling, fielding, and teamwork.
    </p>
    <p class="text-lg leading-relaxed text-white/90">
      Players develop technical skills, strategy, and sportsmanship to excel at university and national levels.
    </p>
    <div class="mt-6 relative inline-block group">
      <button class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-md hover:shadow-lg hover:scale-105 transition transform duration-300">
        Follow us on Facebook
      </button>
    </div>
  </div>
</section>

<!-- Team Highlights -->
<section id="details" class="py-20 bg-blue-50">
  <div class="max-w-5xl mx-auto px-6 space-y-12">
    
    <!-- Achievements Card -->
    <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500 w-full">
      <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">🏆 Achievements</h4>
      <p class="text-gray-800 text-lg mb-6">
        The Cricket Team has won multiple inter-university tournaments, demonstrating skill, teamwork, and strategy.
      </p>
      <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
        <li>Inter-University Champions 2023</li>
        <li>Regional Semi-Finalists 2022</li>
        <li>Players selected for national cricket squads</li>
      </ul>
    </div>

    <!-- Coaches/Captains Card -->
    <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500 w-full">
      <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">👨‍🏫 Coaches & Captains</h4>
      <p class="text-gray-800 text-lg mb-6">
        Coaches train players in fitness, technique, and tactics. Captains lead the team, ensuring teamwork and discipline.
      </p>
      <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
        <li>Head Coach: Mr. Pradeep Fernando</li>
        <li>Assistant Coach: Ms. Nadeesha Jayasinghe</li>
        <li>Team Captain: Mr. Ishara Perera</li>
        <li>Vice-Captain: Mr. Kasun Silva</li>
      </ul>
    </div>

    <!-- Registration Card -->
    <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500 w-full">
      <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">📝 Registration</h4>
      <p class="text-gray-800 text-lg mb-8">
        Join the SUSL Cricket Team to enhance your skills and represent the university in competitive matches.
      </p>
      <a href="../Dashboard/Login.php">
        <button class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition w-full">
          Register Now
        </button>
      </a>
    </div>

  </div>
</section>

<!-- Footer -->
<footer class="bg-[#3e6991] text-gray-200 py-6 text-center mt-12">
  <p>&copy; 2025 Sabaragamuwa University Sports Management System. All rights reserved.</p>
</footer>

</body>
</html>
