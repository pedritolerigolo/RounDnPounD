import { MUKBANG_VIDEOS, VIDEO_PATH_BASE } from './videos.js';


function getRandomIndex(max) {
    return Math.floor(Math.random() * max);
}

function updatePlayPauseButton(videoElement, button) {
    if (videoElement.paused) {
        button.textContent = "▶ Lecture";
    } else {
        button.textContent = "⏸ Pause";
    }
}

function showNextVideo() {
    const videoElement = document.querySelector("#mukbang-video");
    const playPauseButton = document.querySelector("#toggle-play-pause");

    if (!videoElement) {
        console.error("mukbang video introuvable.");
        return;
    }

    videoElement.loop = false;

    const randomIndex = getRandomIndex(MUKBANG_VIDEOS.length);
    const videoFileName = MUKBANG_VIDEOS[randomIndex];
    const videoSource = VIDEO_PATH_BASE + videoFileName;
    videoElement.src = videoSource;

    videoElement.load(); 
    videoElement.play()
        .then(() => {
            if (playPauseButton) {
                updatePlayPauseButton(videoElement, playPauseButton);
            }
        });
    console.log(`lecture de la vidéo : ${videoFileName}`);
}

function toggleSidebar(isOpen) {
    const sidebar = document.querySelector(".tiktok-sidebar");
    const openBtn = document.querySelector("#open-sidebar-btn");
    const videoElement = document.querySelector("#mukbang-video");

    if (isOpen) {
        sidebar.classList.remove('closed');
        openBtn.style.display = 'none';
        videoElement.play();
    } else {
        sidebar.classList.add('closed');
        openBtn.style.display = 'block';
        videoElement.pause();
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const videoElement = document.querySelector("#mukbang-video");
    const playPauseButton = document.querySelector("#toggle-play-pause");
    const nextButton = document.querySelector("#next-video");

    const closeBtn = document.querySelector("#close-sidebar-btn");
    const openBtn = document.querySelector("#open-sidebar-btn");

    if (!videoElement) return;

    videoElement.addEventListener('ended', () => {
        console.log("passage automatique");
        showNextVideo();
    });
    
    if (playPauseButton) {
        playPauseButton.addEventListener('click', () => {
            if (videoElement.paused) {
                videoElement.play();
            } else {
                videoElement.pause();
            }
            updatePlayPauseButton(videoElement, playPauseButton);
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            console.log("passage manuel.");
            showNextVideo();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            toggleSidebar(false);
        });
    }

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            toggleSidebar(true);
        });
    }

    showNextVideo();
});