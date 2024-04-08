// Function to fetch data and append to table
async function fetchDataAndAppend(startDate = null, endDate = null) {
    try {
        const baseUrl = 'http://127.0.0.1:8000';
        let trackerApi = `${baseUrl}/tracker`;
        if (startDate && endDate) {
            trackerApi = `${trackerApi}?startDate=${startDate}&endDate=${endDate}`
        }
        const response = await fetch(trackerApi);
        const { data } = await response.json();
        const tableBody = document.getElementById('table-body');

        // Clear existing table data
        tableBody.innerHTML = '';

        // Append fetched data to the table
        if (data?.length > 0) {
            data.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `<td>${index + 1}</td><td>${item.page_url}</td><td>${item.unique_visits}</td>`;
                tableBody.appendChild(row);
            });
        } else {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="3">No data to display</td>`;
            tableBody.appendChild(row);
        }
    
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

function submitDateFilter(event) {
    event.preventDefault(); // Prevent form submission
  
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
  
    // Validate that endDate is greater than startDate
    if (startDate && endDate && endDate < startDate) {
        alert('End date must be greater than start date.');
        return; // Exit early if validation fails
    }
  
    // Send request to backend with selected dates
    fetchDataAndAppend(startDate, endDate);
}

(function() {
    // Call the function to fetch data and append to table immediately
    fetchDataAndAppend();
})();