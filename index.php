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
    <title>Days Together</title>
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff4141;
            --secondary-color: #ff6b6b;
        }

        body {
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 40px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .title {
            font-family: 'Aclonica', sans-serif;
            font-size: 4rem;
            margin: 0;
        }

        .image-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .image-item {
            width: 200px;
            height: 200px;
            position: relative;
            overflow: hidden;
            border-radius: 25px;
            cursor: pointer;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .image-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .image-item:hover img {
            transform: scale(1.1);
        }

        .info-layer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-item:hover .info-layer {
            opacity: 1;
        }

         @media (max-width: 768px) {
            .container {
                padding: 20px;
                border-radius: 25px;
                margin: 10px;
            }

            .title {
                font-size: 2.5rem;
                line-height: 1.2;
            }

            .image-item {
                width: 45%;
                height: auto;
                aspect-ratio: 1;
                max-width: 180px;
            }

            .info-layer {
                font-size: 14px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .title {
                font-size: 2rem;
            }

            .container {
                padding: 15px;
                border-radius: 20px;
            }

            .image-item {
                width: 90%;
                max-width: 250px;
            }

            p {
                font-size: 14px;
            }

            .info-layer {
                font-size: 12px;
            }
        }

        @media (hover: none) {
            .info-layer {
                opacity: 1;
                background: rgba(0,0,0,0.5);
            }
        }
    </style>
</head>
<body>
<!-- In your index.php -->
<audio id="backgroundMusic" loop autoplay>
    <source src="Always.mp3" type="audio/mpeg">
</audio>
    <div class="container">
        <h1 class="title">Days Together: <?= $daysTogether ?></h1>
        <p>Date Started: February 22, 2025</p>
        <div class="image-container" id="imageContainer">
            <!-- Images will be loaded here -->
        </div>
        <a href="admin.php" style="display:block; margin-top:20px; color:#333; text-decoration:none;">❤️</a>
    </div>

    <script>
        // Load images and handle scroll animations
        fetch('images.json')
            .then(response => response.json())
            .then(images => {
                const container = document.getElementById('imageContainer');
                images.forEach(image => {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'image-item';
                    imageDiv.innerHTML = `
                        <img src="uploads/${image.filename}" alt="Memory">
                        <div class="info-layer">
                            <div class="date">${image.date}</div>
                            <div class="place">${image.place}</div>
                        </div>
                    `;
                    container.appendChild(imageDiv);

                    // Set up Intersection Observer for scroll animations
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            entry.target.classList.toggle('visible', entry.isIntersecting);
                        });
                    }, { threshold: 0.1 });

                    observer.observe(imageDiv);
                });
            });
    </script>
</body>
</html>