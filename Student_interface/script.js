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
                attendence: '80%',
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
            
        ]
    }
})