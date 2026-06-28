const apiSchedules = [{"id":4084,"start_date":"2026-08-17","venue":"London","venue_id":4,"flag_image":"United Kingdom","formatted_start_date":"17 Aug 2026","formatted_end_date":"21 Aug 2026","date_range":"17 - 21 Aug 2026"}, {"id":4082,"start_date":"2026-11-02","venue":"Dubai","venue_id":2,"flag_image":"United Arab Emirates","formatted_start_date":"02 Nov 2026","formatted_end_date":"06 Nov 2026","date_range":"02 - 06 Nov 2026"}, {"id":4085,"start_date":"2026-12-07","venue":"London","venue_id":4,"flag_image":"United Kingdom","formatted_start_date":"07 Dec 2026","formatted_end_date":"11 Dec 2026","date_range":"07 - 11 Dec 2026"}];

const sessions = apiSchedules.length > 0 ? apiSchedules.map((s, idx) => ({
    id: `1-${idx}`,
    title: "Course",
    date: s.date_range,
    duration: 5,
    venue: s.venue,
    price: 100,
    popular: true,
    link: `/course/cat/seo`
  })) : [];

  const availableMonthsMap = new Set();
  const availableLocationsMap = new Map();

  sessions.forEach((s) => {
    const match = s.date.match(/[A-Za-z]{3}/);
    if (match) {
      const months = {
        'Jan': 'January', 'Feb': 'February', 'Mar': 'March', 'Apr': 'April',
        'May': 'May', 'Jun': 'June', 'Jul': 'July', 'Aug': 'August',
        'Sep': 'September', 'Oct': 'October', 'Nov': 'November', 'Dec': 'December'
      };
      availableMonthsMap.add(months[match[0]] || match[0]);
    }
  });

  if (apiSchedules && apiSchedules.length > 0) {
    apiSchedules.forEach((s) => {
      if (!availableLocationsMap.has(s.venue)) {
        availableLocationsMap.set(s.venue, s.flag_image);
      }
    });
  }

  const availableMonths = Array.from(availableMonthsMap);
  const availableLocations = Array.from(availableLocationsMap.entries());

console.log("Months:", availableMonths);
console.log("Locations:", availableLocations);
