<?php
// Calculate days together with proper handling
$startDate = new DateTime('2025-02-22 00:00:00', new DateTimeZone('UTC'));
$today = new DateTime('now', new DateTimeZone('UTC'));
$interval = $startDate->diff($today);

// Show 0 days if start date is in the future
$daysTogether = $interval->invert ? 0 : $interval->days;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mike & Althea's Love Story</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --rose-quartz: #f7cac9;
            --serenity: #92a8d1;
            --dusty-rose: #d4a5a5;
            --soft-white: #fff9f5;
            --deep-mauve: #c6a4a4;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--soft-white);
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(247, 202, 201, 0.2) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(146, 168, 209, 0.2) 0%, transparent 20%);
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(214, 165, 165, 0.2);
            max-width: 800px;
            margin: 20px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .container::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 0%,
                rgba(247, 202, 201, 0.1) 50%,
                transparent 100%
            );
            animation: shimmer 8s infinite linear;
            z-index: -1;
        }

        @keyframes shimmer {
            0% { transform: translateX(-50%) translateY(-50%) rotate(0deg); }
            100% { transform: translateX(-50%) translateY(-50%) rotate(360deg); }
        }

        .couple-names {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--deep-mauve);
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            margin: 0 0 15px;
            color: var(--deep-mauve);
            font-weight: 700;
            letter-spacing: 1px;
        }

        .days-counter {
            font-size: 4.5rem;
            font-weight: 400;
            color: var(--dusty-rose);
            margin: 25px 0;
            font-family: 'Playfair Display', serif;
            position: relative;
            display: inline-block;
        }

        .days-counter::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--rose-quartz), transparent);
        }

        .date-started {
            font-size: 1.1rem;
            color: var(--deep-mauve);
            margin-bottom: 30px;
            letter-spacing: 0.5px;
        }

        .image-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .image-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            aspect-ratio: 1;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            cursor: pointer;
        }

        .image-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .image-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(214, 165, 165, 0.3);
        }

        .image-item:hover img {
            transform: scale(1.05);
        }

        .info-layer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
            padding: 15px 10px 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-item:hover .info-layer {
            opacity: 1;
        }

        .info-date {
            font-size: 0.9rem;
            margin-bottom: 3px;
        }

        .info-place {
            font-size: 1rem;
            font-weight: 500;
        }

        .heart-link {
            display: inline-block;
            margin-top: 30px;
            color: var(--dusty-rose);
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .heart-link:hover {
            transform: scale(1.2);
            color: var(--deep-mauve);
        }

        /* Lightbox Styles */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lightbox.active {
            display: flex;
            opacity: 1;
        }

        .lightbox-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }

        .lightbox-img {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0,0,0,0.5);
        }

        .lightbox-caption {
            position: absolute;
            bottom: -40px;
            left: 0;
            right: 0;
            color: white;
            text-align: center;
            padding: 10px;
            font-family: 'Montserrat', sans-serif;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .lightbox-close:hover {
            transform: rotate(90deg);
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }
            
            .couple-names {
                font-size: 1.8rem;
            }
            
            .title {
                font-size: 2.2rem;
            }
            
            .days-counter {
                font-size: 3.5rem;
            }
            
            .image-container {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 25px 20px;
                margin: 15px;
            }
            
            .couple-names {
                font-size: 1.5rem;
            }
            
            .title {
                font-size: 1.8rem;
            }
            
            .days-counter {
                font-size: 2.8rem;
            }
            
            .date-started {
                font-size: 1rem;
            }
            
            .image-container {
                grid-template-columns: 1fr 1fr;
            }
            
            .lightbox-img {
                max-height: 60vh;
            }
        }
    </style>
</head>
<body>
    <audio id="backgroundMusic" loop autoplay>
        <source src="Always.mp3" type="audio/mpeg">
    </audio>
    
    <div class="container">
        <div class="couple-names">Mike & Althea</div>
        <h1 class="title">Our Journey</h1>
        <div class="days-counter"><?= $daysTogether ?> Days</div>
        <p class="date-started">Since February 22, 2025</p>
        
        <div class="image-container" id="imageContainer">
            <!-- Images will be loaded here -->
        </div>
        
        <a href="admin.php" class="heart-link">
            <i class="fas fa-heart"></i>
        </a>
    </div>

    <!-- Lightbox Element -->
    <div class="lightbox" id="lightbox">
        <span class="lightbox-close">&times;</span>
        <div class="lightbox-content">
            <img class="lightbox-img" id="lightbox-img" src="">
            <div class="lightbox-caption" id="lightbox-caption"></div>
        </div>
    </div>

    <script>
        // Load images and handle scroll animations
        fetch('images.json')
            .then(response => response.json())
            .then(images => {
                const container = document.getElementById('imageContainer');
                images.forEach((image, index) => {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'image-item';
                    imageDiv.style.transitionDelay = `${index * 0.1}s`;
                    imageDiv.innerHTML = `
                        <img src="uploads/${image.filename}" alt="Our Memory">
                        <div class="info-layer">
                            <div class="info-date">${image.date}</div>
                            <div class="info-place">${image.place}</div>
                        </div>
                    `;
                    container.appendChild(imageDiv);

                    // Add click event for lightbox
                    imageDiv.addEventListener('click', () => {
                        const lightbox = document.getElementById('lightbox');
                        const lightboxImg = document.getElementById('lightbox-img');
                        const lightboxCaption = document.getElementById('lightbox-caption');
                        
                        lightboxImg.src = `uploads/${image.filename}`;
                        lightboxCaption.innerHTML = `
                            <div>${image.date}</div>
                            <div><strong>${image.place}</strong></div>
                        `;
                        lightbox.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    });

                    // Set up Intersection Observer for scroll animations
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                            }
                        });
                    }, { threshold: 0.1 });

                    observer.observe(imageDiv);
                });
            });

        // Lightbox functionality
        const lightbox = document.getElementById('lightbox');
        const closeBtn = document.querySelector('.lightbox-close');
        
        closeBtn.addEventListener('click', () => {
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
        
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Close with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                lightbox.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html>