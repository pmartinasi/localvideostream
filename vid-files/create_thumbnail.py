import cv2
from PIL import Image
import os
import sys
import multiprocessing

def create_thumbnail(video_path):
    try:
        # Open the video file
        cap = cv2.VideoCapture(video_path)
        if not cap.isOpened():
            print(f"Error: Unable to open video {video_path}")
            return
        
        # Get the total number of frames
        total_frames = int(cap.get(cv2.CAP_PROP_FRAME_COUNT))
        if total_frames <= 0:
            print(f"Error: Video {video_path} has no frames.")
            return
        
        # Calculate the middle frame
        middle_frame = total_frames // 2
        
        # Set the video position to the middle frame
        cap.set(cv2.CAP_PROP_POS_FRAMES, middle_frame)
        
        # Read the frame
        success, frame = cap.read()
        if not success:
            print(f"Error: Unable to read frame from video {video_path}")
            return
        
        # Convert the frame to an RGB format
        frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        
        # Convert the frame to a Pillow image
        image = Image.fromarray(frame_rgb)
        
        # Crop the image to 16:9 aspect ratio
        width, height = image.size
        new_width = width
        new_height = int(width * 9 / 16)
        if new_height > height:
            new_height = height
            new_width = int(height * 16 / 9)
        left = (width - new_width) // 2
        top = (height - new_height) // 2
        right = left + new_width
        bottom = top + new_height
        image = image.crop((left, top, right, bottom))
        
        # Create the output file path (same name as video, but with .png extension)
        output_file = os.path.splitext(video_path)[0] + ".png"
        
        # Save the image as a PNG file
        image.save(output_file)
        print(f"[✓] Thumbnail saved as {output_file}")

    except Exception as e:
        print(f"[!] Error processing {video_path}: {e}")
    finally:
        cap.release()


def main():
    if len(sys.argv) != 2:
        print("Usage: python create_thumbnail.py <directory_with_videos>")
        sys.exit(1)

    # Get the directory from command-line arguments
    directory = sys.argv[1]

    # Check if the provided argument is a directory
    if not os.path.isdir(directory):
        print(f"Error: {directory} is not a valid directory.")
        sys.exit(1)

    # List all video files in the directory
    video_extensions = ('.mp4', '.avi', '.mkv', '.mov', '.flv', '.wmv', '.webm')
    video_files = [
        os.path.join(directory, f)
        for f in os.listdir(directory)
        if f.lower().endswith(video_extensions)
    ]

    if not video_files:
        print(f"No video files found in the directory: {directory}")
        sys.exit(1)

    print(f"Found {len(video_files)} videos. Starting thumbnail generation...")

    # Use multiprocessing to process multiple videos at once
    cpu_count = multiprocessing.cpu_count()
    print(f"Using up to {cpu_count} CPU cores.")
    
    with multiprocessing.Pool(processes=cpu_count) as pool:
        pool.map(create_thumbnail, video_files)

    print("✅ All thumbnails processed.")


if __name__ == "__main__":
    main()
