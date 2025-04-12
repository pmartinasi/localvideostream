document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("videoPlayer");
    const playPauseBtn = document.getElementById("playPauseBtn");
    const progressBar = document.getElementById("progressBar");
    const currentTimeDisplay = document.getElementById("currentTime");
    const totalTimeDisplay = document.getElementById("totalTime");
    const muteBtn = document.getElementById("muteBtn");
    const volumeBar = document.getElementById("volumeBar");
    const fullscreenBtn = document.getElementById("fullscreenBtn");

    // Play/Pause functionality
    playPauseBtn.addEventListener("click", () => {
        if (video.paused) {
            video.play();
            playPauseBtn.textContent = "Pause";
        } else {
            video.pause();
            playPauseBtn.textContent = "Play";
        }
    });

    // Update progress bar
    video.addEventListener("timeupdate", () => {
        const progress = (video.currentTime / video.duration) * 100;
        progressBar.value = progress;
        currentTimeDisplay.textContent = formatTime(video.currentTime);
    });

    // Seek video when progress bar is adjusted
    progressBar.addEventListener("input", () => {
        const seekTime = (progressBar.value / 100) * video.duration;
        video.currentTime = seekTime;
    });

    // Display total duration when video metadata is loaded
    video.addEventListener("loadedmetadata", () => {
        totalTimeDisplay.textContent = formatTime(video.duration);
    });

    // Mute/Unmute functionality
    muteBtn.addEventListener("click", () => {
        video.muted = !video.muted;
        muteBtn.textContent = video.muted ? "Unmute" : "Mute";
    });

    // Volume control
    volumeBar.addEventListener("input", () => {
        video.volume = volumeBar.value;
    });

    // Fullscreen functionality
    fullscreenBtn.addEventListener("click", () => {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            video.requestFullscreen();
        }
    });

    // Helper function to format time
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60).toString().padStart(2, "0");
        return `${minutes}:${secs}`;
    }
});
