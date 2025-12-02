<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>SUSL Wrestling Team</title>
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
    style="background-image: url('images/wrestlingteam.jpg');"
  >
    <div class="relative z-10 max-w-3xl mx-auto px-4 bg-black/40 rounded-xl p-6">
      <h2 class="text-4xl font-bold mb-6 text-white">🤼‍♂️ SUSL Wrestling Team</h2>
      <p class="text-lg leading-relaxed text-white/90 mb-4">
        The SUSL Wrestling Team trains in both freestyle and Greco-Roman disciplines, competing at inter-university and national levels with focus on technique, balance, and tactical control.
      </p>
      <p class="text-lg leading-relaxed text-white/90">
        Wrestlers develop strength, agility, takedown techniques, mat strategy, and conditioning to excel in weight-class competitions.
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
      <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500">
        <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">🏆 Achievements</h4>
        <p class="text-gray-800 text-lg mb-6">
          SUSL wrestlers have achieved podium finishes at inter-university tournaments and contributed athletes to provincial and national squads through disciplined training and competition.
        </p>
        <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
          <li>Inter-University Wrestling Medalists – 2023</li>
          <li>Provincial Championship Representatives – multiple years</li>
          <li>Wrestlers selected for national junior camps</li>
        </ul>
      </div>

      <!-- Coaches & Leadership Card -->
      <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500">
        <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">👨‍🏫 Coaches & Captains</h4>
        <p class="text-gray-800 text-lg mb-6">
          Coaches emphasize takedown defense, clinch work, escapes, pinning combinations, and match tactics. Team leaders ensure consistent practice intensity and sportsmanship.
        </p>
        <ul class="list-disc list-inside text-gray-700 font-medium space-y-2">
          <li>Head Coach: Mr. Ruwan Jayawardena</li>
          <li>Assistant Coach: Ms. Chamari De Silva</li>
          <li>Team Captain: Ms. Inoshi Perera</li>
          <li>Vice-Captain: Mr. Sandun Wickramasinghe</li>
        </ul>
      </div>

      <!-- Training & Registration Card -->
      <div class="relative bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl shadow-2xl border border-blue-300 p-10 overflow-hidden hover:scale-105 transform transition duration-500">
        <h4 class="text-3xl font-bold text-blue-800 mb-6 flex items-center gap-2">🤼 Training & Registration</h4>
        <p class="text-gray-800 text-lg mb-6">
          Training sessions include technique drills, live rolling, strength & conditioning, weight-class management, and recovery protocols. Beginners receive fundamentals before competitive progression.
        </p>
        <p class="text-gray-800 text-lg mb-6">
          Prospective team members should complete a short physical screening and attend trial sessions. We welcome athletes of all levels who are committed to improvement.
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
