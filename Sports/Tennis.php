<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SUSL Tennis Team</title>
  <link rel="icon" type="image/x-icon" href="images/Favicon.png">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800 font-sans scroll-smooth">

<!-- Navbar -->
<header class="text-white py-3 shadow-lg fixed w-full top-0 z-50" style="background-color: rgba(62, 105, 145, 0.95); backdrop-filter: blur(8px);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
    <!-- Logo Section -->
    <div class="flex items-center space-x-3">
      <img src="../images/Favicon.png" alt="SUSL Logo" class="w-12 h-12 rounded-full bg-white p-1.5 border border-white/20 shadow-sm" />
      <div>
        <h1 class="text-lg font-semibold tracking-tight text-white">University Sports Club</h1>
        <p class="text-xs text-gray-100 font-medium opacity-90">Sabaragamuwa University of Sri Lanka</p>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex items-center space-x-2 sm:space-x-4">
      <a href="#introduction" 
         class="px-4 py-2 text-sm font-medium text-gray-100 hover:text-white 
                hover:bg-white/10 rounded-md transition-all duration-200
                focus:outline-none focus:ring-2 focus:ring-white/30">
        Introduction
      </a>
      
      <div class="h-5 w-px bg-white/30 hidden sm:block"></div>
      
      <a href="#details" 
         class="px-4 py-2 text-sm font-medium text-gray-100 hover:text-white 
                hover:bg-white/10 rounded-md transition-all duration-200
                focus:outline-none focus:ring-2 focus:ring-white/30">
        Team Highlights
      </a>
      
      <div class="h-5 w-px bg-white/30 hidden sm:block"></div>
      
      <a href="../Homepage.php#categories"
         class="px-4 py-2 text-sm font-medium bg-white/15 hover:bg-white/25 
                border border-white/30 rounded-md transition-all duration-200 
                flex items-center space-x-2 group focus:outline-none focus:ring-2 focus:ring-white/30">
        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" 
                d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="hidden sm:inline">Back to Home</span>
        <span class="sm:hidden">Back</span>
      </a>
    </nav>
  </div>
</header>

  <!-- Hero Section -->
  <section
    id="introduction"
    class="relative text-center py-24 h-screen mt-16 bg-cover bg-center bg-no-repeat text-white"
    style="background-image: url('images/tennis.jpg');"
  >
    <div class="relative z-10 max-w-3xl mx-auto px-4 bg-black/40 rounded-xl p-6">
      <h2 class="text-4xl font-bold mb-6 text-white">🎾 SUSL Tennis Team</h2>
      <p class="text-lg leading-relaxed text-white/90 mb-4">
        The SUSL Tennis Team competes in singles and doubles events across university circuits, focusing on technique, court movement, and match strategy.
      </p>
      <p class="text-lg leading-relaxed text-white/90">
        Players practice serves, volleys, baseline consistency, and tactical point construction to perform strongly in tournaments.
      </p>

      <div class="mt-6 relative inline-block group">
        <button class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-md hover:shadow-lg hover:scale-105 transition transform duration-300">
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
          SUSL tennis players have achieved strong results in university tournaments, with notable singles and doubles successes and representation at provincial meets.
        </p>
        <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
          <li>Inter-University Doubles Finalists – 2023</li>
          <li>Provincial Team Selections – multiple years</li>
          <li>Consistent top-8 finishes in university ladder events</li>
        </ul>
      </div>

      <!-- Coaches & Captains Card -->
      <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500 w-full">
        <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">👨‍🏫 Coaches & Captains</h4>
        <p class="text-gray-800 text-lg mb-6">
          Our coaching team emphasizes footwork, shot selection, mental toughness, and match tactics. Captains lead practice drills and coordinate match schedules.
        </p>
        <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
          <li>Head Coach: Mr. Chaminda Rajapaksa</li>
          <li>Assistant Coach: Ms. Dilrukshi Senanayake</li>
          <li>Team Captain: Ms. Piumi Fernando</li>
          <li>Vice-Captain: Mr. Sachin Jayawardena</li>
        </ul>
      </div>

      <!-- Registration Card -->
      <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500 w-full">
        <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">📝 Registration</h4>
        <p class="text-gray-800 text-lg mb-8">
          Interested in joining? Register to train with experienced coaches, develop your game, and represent SUSL in singles & doubles competitions.
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
