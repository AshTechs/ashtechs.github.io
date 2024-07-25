function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const currentTime = `${hours}:${minutes}:${seconds}`;
    
    document.getElementById('current-time').textContent = currentTime;
}

// Update the time every second
setInterval(updateTime, 1000);

// Initial call to display the time immediately on page load
updateTime();
