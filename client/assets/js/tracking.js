function getIpAddress() {
    return fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            return data.ip;
        })
        .catch(error => {
            console.error('Error fetching IP address:', error);
            throw error; // Rethrow the error to propagate it to the caller
        });
}

(async function() {
    try {
        const pageUrl = window.location.href;
        const ipAddress = await getIpAddress();
        const platform = window.navigator.userAgentData.platform;

        // Send visit data to the server
        const response = await fetch('http://127.0.0.1:8000/tracker', {
            method: 'POST',
            body: JSON.stringify({
                url: pageUrl,
                ip_address: ipAddress,
                platform,
            })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const responseData = await response.json();
        console.log('Visit data sent successfully:', responseData);
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error.message);
    }
})();
