<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mike & Althea's Memory Admin</title>
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
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(247, 202, 201, 0.2) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(146, 168, 209, 0.2) 0%, transparent 20%);
        }

        .admin-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 20px;
            max-width: 600px;
            width: 90%;
            margin: 30px 0;
            box-shadow: 0 15px 35px rgba(214, 165, 165, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
        }

        .admin-container::before {
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

        h2 {
            font-family: 'Playfair Display', serif;
            color: var(--deep-mauve);
            text-align: center;
            margin-bottom: 25px;
            position: relative;
        }

        h2::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 25%;
            width: 50%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--rose-quartz), transparent);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--deep-mauve);
            font-weight: 500;
        }

        input[type="file"], 
        input[type="date"], 
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--rose-quartz);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
        }

        input[type="file"] {
            padding: 8px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--serenity);
            box-shadow: 0 0 0 3px rgba(146, 168, 209, 0.2);
        }

        button {
            background: var(--dusty-rose);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background: var(--deep-mauve);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(214, 165, 165, 0.3);
        }

        .image-list {
            margin-top: 30px;
        }

        .image-item {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid var(--rose-quartz);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .image-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(214, 165, 165, 0.2);
        }

        .image-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .image-info {
            margin-bottom: 15px;
        }

        .image-info p {
            margin: 5px 0;
            color: var(--deep-mauve);
        }

        .delete-button {
            background: var(--rose-quartz);
            width: auto;
            padding: 8px 15px;
            margin-top: 10px;
        }

        .delete-button:hover {
            background: #e6a0a0;
        }

        #statusMessage {
            text-align: center;
            margin: 15px 0;
            font-weight: 500;
            min-height: 20px;
        }

        .success {
            color: #5a8f5a;
        }

        .error {
            color: #d35d5d;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: var(--deep-mauve);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: var(--dusty-rose);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 30px;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .admin-container {
                padding: 25px 20px;
                margin: 20px 10px;
            }
            
            .image-item {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Mike & Althea's Memory Keeper</h2>
        
        <div class="form-group">
            <h2>Add New Memory</h2>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="imageUpload">Choose Photo</label>
                    <input type="file" id="imageUpload" name="image" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="dateInput">Date</label>
                    <input type="date" id="dateInput" name="date" required>
                </div>
                
                <div class="form-group">
                    <label for="placeInput">Place</label>
                    <input type="text" id="placeInput" name="place" placeholder="Where was this taken?" required>
                </div>
                
                <button type="submit">Save Memory</button>
            </form>
            <p id="statusMessage"></p>
        </div>

        <div class="image-list">
            <h2>Your Memories</h2>
            <div id="imageList">
                <?php
                $images = json_decode(file_get_contents('images.json'), true);
                foreach ($images as $image) {
                    echo '<div class="image-item">';
                    echo '<img src="uploads/' . $image['filename'] . '" alt="Memory">';
                    echo '<div class="image-info">';
                    echo '<p><i class="far fa-calendar-alt"></i> ' . $image['date'] . '</p>';
                    echo '<p><i class="fas fa-map-marker-alt"></i> ' . $image['place'] . '</p>';
                    echo '</div>';
                    echo '<button class="delete-button" onclick="deleteImage(\'' . $image['filename'] . '\')"><i class="fas fa-trash-alt"></i> Delete</button>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const statusMessage = document.getElementById('statusMessage');
            statusMessage.textContent = "Uploading your memory...";
            statusMessage.className = "";
            
            fetch('save_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                statusMessage.textContent = data.message;
                if(data.success) {
                    statusMessage.className = "success";
                    this.reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    statusMessage.className = "error";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusMessage.textContent = 'Error uploading memory';
                statusMessage.className = "error";
            });
        });

        function deleteImage(filename) {
            if (confirm('Are you sure you want to delete this precious memory?')) {
                const statusMessage = document.getElementById('statusMessage');
                statusMessage.textContent = "Deleting memory...";
                statusMessage.className = "";
                
                fetch('delete_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'filename=' + encodeURIComponent(filename)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        statusMessage.textContent = data.message;
                        statusMessage.className = "success";
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        statusMessage.textContent = 'Error: ' + data.message;
                        statusMessage.className = "error";
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusMessage.textContent = 'Error deleting memory';
                    statusMessage.className = "error";
                });
            }
        }
    </script>
</body>
</html>