<?php
// Get the video file name from the URL
$video_file = isset($_GET['video']) ? urldecode($_GET['video']) : null;

// Check if the file exists and is a video
if (!$video_file || !file_exists($video_file)) {
    die("Video file not found.");
}

// Define the expected subtitle file (same base name, with .vtt extension)
$subtitle_file = preg_replace('/\.[^.]+$/', '.srt', $video_file);
$subtitle_exists = file_exists($subtitle_file);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video: <?php echo htmlspecialchars($video_file); ?></title>
    <link rel="icon" type="image/x-icon" href="play.ico">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="video-player-container">
    <video id="videoPlayer" controls preload="metadata">
        <source src="<?php echo htmlspecialchars($video_file); ?>" type="video/mp4">
        <?php if ($subtitle_exists): ?>
            <track src="<?php echo htmlspecialchars($subtitle_file); ?>" kind="subtitles" srclang="en" label="English" default>
        <?php endif; ?>
        Your browser does not support the video tag.
    </video>

    <div class="controls">
        <button id="playPauseBtn" class="btn">Play</button>
        <input type="range" id="progressBar" value="0" class="slider">
        <span id="currentTime">0:00</span> / <span id="totalTime">0:00</span>
        <button id="muteBtn" class="btn">Mute</button>
        <input type="range" id="volumeBar" class="slider" value="1" max="1" step="0.05">
        <button id="fullscreenBtn" class="btn">Fullscreen</button>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>
