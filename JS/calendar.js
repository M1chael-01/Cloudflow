// Function for generating the calendar of the current month
function generateCurrentMonthCalendar() {
    const daysOfWeek = ['Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne'];
    const calendarContainer = document.getElementById('calendar');
    calendarContainer.innerHTML = ''; // Vyčištění kontejneru před generováním nového kalendáře

 // Getting the current data
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();  // current month (0-11)
    const currentYear = currentDate.getFullYear(); // current year
    const currentDay = currentDate.getDate(); // current day in a month

    const monthNames = [
        'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen',
        'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'
    ];

    // Getting the name of the current month
    const monthName = monthNames[currentMonth];

    // Creating the headline of the month
    const monthTitle = document.createElement('div');
    monthTitle.classList.add('month');
    monthTitle.textContent = `${monthName} ${currentYear}`;
    calendarContainer.appendChild(monthTitle);

    // Creating a header of days of the week (Mon, Tue, Wed, Thu, Fri, Sat, Sun)
    const daysHeader = document.createElement('div');
    daysHeader.classList.add('days');
    daysOfWeek.forEach(day => {
        const dayHeader = document.createElement('span');
        dayHeader.textContent = day;
        daysHeader.appendChild(dayHeader);
    });
    calendarContainer.appendChild(daysHeader);

    // Create a container for days of the month
    const daysContainer = document.createElement('div');
    daysContainer.classList.add('days');

    // Získání prvního dne v měsíci a počtu dní v měsíci
    const firstDay = new Date(currentYear, currentMonth, 1).getDay(); // First day of the month (0-6)
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate(); // Number of days per month

    // Customizing the week to start on Monday (in JavaScript Sunday is 0, so  move it by 1)
    const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;

    // Adding blank fields before the first day of the month
    for (let i = 0; i < adjustedFirstDay; i++) {
        const emptyDay = document.createElement('span'); //blank day
        daysContainer.appendChild(emptyDay); 
    }

    // Adding days of the month to the calendar
    for (let day = 1; day <= daysInMonth; day++) {
        const dateElement = document.createElement('div');
        dateElement.classList.add('date');
        
        if (day === currentDay) {
            dateElement.classList.add('current-day');
        }
        
        dateElement.textContent = day; 
        daysContainer.appendChild(dateElement); 
    }

    calendarContainer.appendChild(daysContainer); 
}

onload = generateCurrentMonthCalendar();
