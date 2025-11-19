<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SUSL Football Team</title>
  <link rel="icon" type="image/x-icon" href="images/Favicon.png">
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
  style="background-image: url('images/footballteam.jpg');"
>
  <div class="relative z-10 max-w-3xl mx-auto px-4 bg-black/40 rounded-xl p-6">
    <h2 class="text-4xl font-bold mb-6 text-white">⚽ SUSL Football Team</h2>
    <p class="text-lg leading-relaxed text-white/90 mb-4">
      The Sabaragamuwa University Football Team is a highly competitive team participating in inter-university and regional football tournaments. 
    </p>
    <p class="text-lg leading-relaxed text-white/90">
      Players focus on passing accuracy, tactical strategies, stamina, and teamwork. The team emphasizes strong offense and defense, coordination, and sportsmanship. Regular training sessions improve agility, shooting skills, and overall fitness.
    </p>
    <div class="mt-6 relative inline-block group">
      <button class="bg-white text-blue-700 px-8 py-3 rounded-full font-bold shadow-md hover:shadow-lg hover:scale-105 transition transform duration-300">
        Follow us on Facebook
      </button>
      <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 hidden group-hover:block bg-gradient-to-r from-blue-400 to-blue-600 text-white text-sm px-4 py-2 rounded-xl shadow-lg whitespace-nowrap">
        📣 Visit our official Facebook page!
        <div class="absolute left-1/2 -bottom-2 w-3 h-3 bg-blue-700 rotate-45 -translate-x-1/2"></div>
      </div>
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
        The Football Team has achieved top rankings in inter-university leagues and regional tournaments, producing talented players who excel nationally.
      </p>
      <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
        <li>Inter-University Champions 2022 & 2023</li>
        <li>Regional finalists 2023</li>
        <li>National team call-ups for outstanding players</li>
      </ul>
    </div>

    <!-- Coaches/Captains Card -->
    <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500 w-full">
      <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">👨‍🏫 Coaches & Captains</h4>
      <p class="text-gray-800 text-lg mb-6">
        Coaches train players in tactics, ball control, and endurance. Captains lead matches, promote teamwork, and motivate players on and off the pitch.
      </p>
      <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
        <li>Head Coach: Mr. Dilan Perera</li>
        <li>Assistant Coach: Ms. Rashmi Fernando</li>
        <li>Team Captain: Mr. Chathura Silva</li>
        <li>Vice-Captain: Ms. Anushka Jayawardena</li>
      </ul>
    </div>

    <!-- Registration Card -->
    <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500 w-full">
      <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">📝 Registration</h4>
      <p class="text-gray-800 text-lg mb-8">
        Join the SUSL Football Team to develop your skills, teamwork, and fitness while representing the university in competitive football matches.
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
  <p>&copy; 2025 Sabaragamuwa University Of Sri Lanka. All rights reserved.</p>
</footer>

</body>
</html>
