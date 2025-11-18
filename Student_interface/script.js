document.addEventListener('DOMContentLoaded', () => {
    const studentData = {
        name: "Amal",
        fullName: "Amal Kumar",

        status: "21CSE102",

        profilePicUrl: "https://placehold.co/50x50/c4c4c4/333333?text=Profile%0Aimage%0Ahere&font=arial",

        stats: {
            enrolled: 3,
            registered: 5,
            achievements: 3
        },

        schedule: [
            {date: '28', month: 'OCT', event: 'Football Practice'},
            {date: '29', month: 'OCT', event: 'Basketball Match'},
            {date: '30', month: 'OCT', event: 'Swimming Training'},
            {date: '01', month: 'NOV', event: 'Inter-University Cricket'}
        ],

        sports: [
            {
                name: 'Football',
                coach: 'Mr. Perera',
                nextPractice: '3 Nov, 4:00 PM',
                location: 'University Ground',
                attendance: '80%',
                link: '#',
                buttonText: 'Leave Sport'
            },
            {
                name: 'Cricket',
                coach: 'Mr. Jayasinghe', 
                nextPractice: '10 Nov, 10:00 AM',
                location: 'University Ground',
                attendance: '85%',
                link: 'sport_details_cricket.html',
                buttonText: 'Leave Sport'
            },
            {
                name: 'Swimming',
                coach: 'Mr. Fernando', 
                nextPractice: '5 Nov, 6:00 PM',
                location: 'Sports Complex Pool',
                attendance: '70%',
                link: 'sport_details_swimming.html',
                buttonText: 'Leave Sport'
            }

        ],

        achievements: [
            { title: 'Best Player - Football', date: 'August 2024', event: 'Inter-University Sports Meet'},
            { title: 'Gold Medal - Swimming', date: 'July 2024', event: 'Inter-University Sports Meet'},
            { title: 'Best Player - Football', date: 'August 2024', event: 'Inter-Univeesirt Sports Meer'},
            { title: 'Gold Medal - Swimming', date: 'July 2024', event: 'Inter-University Sports Meet'}
        ]
    };

    loadHeaderProfile(studentData.fullName, studentData.status, studentData.profilePicUrl);
    loadWelcomeMassage(studentData.name);
    loadStats(studentData.stats);
    loadSchedule(studentData.schedule);
    loadMySports(studentData.sports);
    loadAchievements(studentData.achievements);

    function loadHeaderProfile(fullName, status, imageUrl) {
        const profileImg = document.getElementById('profile-pic-preview');
        const profileName = document.getElementById('profile-name');
        const profileStatus = document.getElementById('profile-status');
        
        if (profileImg) {
            profileImg.src = imageUrl;
        }

        if (profileName) {
            profileName.textContent = fullName;
        }

        if (profileStatus) {
            profileStatus.textContent = status;
        }

    }

    function loadWelcomeMassage(name) {
        const welcomeHeader = document.getElementById('welcome-header');
        if (welcomeHeader) {
            welcomeHeader.textContent = `welcome back ${name} !`;
        }
    }

    function loadStats(stats) {
        document.getElementById('stats-enrolled').textContent = stats.enrolled;
        document.getElementById('stats-registered').textContent = stats.registered;
        document.getElementById('stats-achievements').textContent = stats.achievements;
    }

    function loadSchedule(scheduleItems) {
        const scheduleList = document.getElementById('schedule-list');
        if (!scheduleList) return;

        scheduleList.innerHTML = '';

        scheduleItems.forEach(item => {
            const div = document.createElement('div');
            div.className = 'schedule-item';

            div.innerHTML = `
                <div class="date">
                    <span>${item.date}</span>
                    <small>${item.month}</small>
                </div>
                <div class="event-name">${item.event}</div>
            `;

            scheduleList.appendChild(div);
        });
    }

    function loadMySports(sports) {
        const sportsContainer = document.getElementById('sports-container');
        if (!sportsContainer) return;

        sportsContainer.innerHTML = '';
        
        sports.forEach(sport => {
            const card = document.createElement('div');
            card.className = 'sport-card';
            card.innerHTML = `
                <h3>${sport.name}</h3>
                <p>Coach: ${sport.coach}</p>
                <p>Next Practice: ${sport.nextPractice}</p>
                <p>Location: ${sport.location}</p>
                <p>Attendance: ${sport.attendance}</p>
                <a href="${sport.link}" class="action-button">${sport.buttonText}</a>
            `;
            sportsContainer.appendChild(card);
      });
    }

    function loadAchievements(achievements) {
        const achievementsContainer = document.getElementById('achievements-container');
        if(!achievementsContainer) return;

        achievementsContainer.innerHTML = '';

        achievements.forEach(achievement => {
            const card = document.createElement('div');
            card.className = 'achievement-card';
            card.innerHTML = `
                <h4>${achievement.title}</h4>
                <p>Date: ${achievement.date}</p>
                <p>Event: ${achievement.event}</p>
            `;
            achievementsContainer.appendChild(card);
        });
    }

    const themeToggleBtn = document.getElementById('theme-toggle');
    const body = document.body;

    if(themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');

            if (body.classList.contains('dark-mode')) {
                themeToggleBtn.textContent = '☀️';
            } else {
                themeToggleBtn.textContent = '🌙';
            }
        });
    }

    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('nav-links');
    const overlay = document.getElementById('overlay');

    if(mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');

            if(overlay) overlay.classList.toggle('active');

            if(navLinks.classList.contains('active')) {
                mobileMenuBtn.innerHTML = '&times;';
            } else {
                mobileMenuBtn.innerHTML = '&#9776;';
            }
        });

        if(overlay) {
            overlay.addEventListener('click', () => {
                navLinks.classList.remove('active');
                overlay.classList.remove('active');
                mobileMenuBtn.innerHTML = '&#9776;';
            });
        }
    }
});
