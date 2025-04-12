<?php
// Scan the current directory for video files with specific extensions
$video_files = array_filter(glob("vid-files/*.{mp4}", GLOB_BRACE));

// Function to generate a thumbnail for each video
function getThumbnail($video) {
    // Get the video file's name without the extension
    $thumbnail = preg_replace('/\.[^.]+$/', '', $video) . '.png';
    
    // Check if the thumbnail exists in the directory
    if (file_exists($thumbnail)) {
        return $thumbnail;
    }

    // Fallback thumbnail image if no custom thumbnail is found
    return 'default_thumbnail.png';
}

// Search functionality
$search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$filtered_videos = [];

if ($search_query !== '') {
    foreach ($video_files as $video) {
        if (strpos(strtolower(basename($video)), $search_query) !== false) {
            $filtered_videos[] = $video;
        }
    }
    $video_files = $filtered_videos;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PnacarVideo</title>
	<link rel="icon" type="image/x-icon" href="play.ico">
    <link rel="stylesheet" href="style.css">
</head>
<body>


<div class="container">
    <h1>Video Gallery</h1>
    <p>    <form method="GET">
        <input type="text" name="search" placeholder="Search videos" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form></p>
    <div class="video-list">
        <?php if (count($video_files) > 0): ?>
            <div class="grid">
                <?php foreach ($video_files as $file): ?>
                    <div class="video-item">
                        <a href="video.php?video=<?php echo urlencode($file); ?>" class="video-link">
                            <img src="<?php echo getThumbnail($file); ?>" alt="Thumbnail for <?php echo htmlspecialchars($file); ?>" class="thumbnail">
                            <div class="video-title"><?php echo str_replace(".mp4", "",str_replace("vid-files/", "", htmlspecialchars($file))); ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No video files found in the directory.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
