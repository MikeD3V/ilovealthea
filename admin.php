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
    <title>Admin Panel</title>
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
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .admin-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 20px;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        input[type="file"], input[type="date"], input[type="text"] {
            display: block;
            margin: 10px 0;
            width: 100%;
        }

        button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: var(--secondary-color);
        }

        .image-item {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .image-item img {
            max-width: 100%;
            height: auto;
            margin-bottom: 5px;
        }

        .delete-button {
            background: #ff6b6b;
            width: auto;
            padding: 5px 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Add New Memory</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" id="imageUpload" name="image" accept="image/*" required>
            <input type="date" id="dateInput" name="date" required>
            <input type="text" id="placeInput" name="place" placeholder="Place" required>
            <button type="submit">Save Memory</button>
        </form>
        <p id="statusMessage"></p>

        <h2>Uploaded Images</h2>
        <div id="imageList">
            <?php
            $images = json_decode(file_get_contents('images.json'), true);
            foreach ($images as $image) {
                echo '<div class="image-item">';
                echo '<img src="uploads/' . $image['filename'] . '" alt="Uploaded">';
                echo '<p>Date: ' . $image['date'] . '</p>';
                echo '<p>Place: ' . $image['place'] . '</p>';
                echo '<button class="delete-button" onclick="deleteImage(\'' . $image['filename'] . '\')">Delete</button>';
                echo '</div>';
            }
            ?>
        </div>

        <a href="logout.php" style="display:block; margin-top:20px; color:#333;">Logout</a>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const statusMessage = document.getElementById('statusMessage');
            
            fetch('save_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                statusMessage.textContent = data.message;
                if(data.success) {
                    statusMessage.style.color = 'green';
                    this.reset();
                    location.reload();
                } else {
                    statusMessage.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusMessage.textContent = 'Error uploading image';
                statusMessage.style.color = 'red';
            });
        });

        function deleteImage(filename) {
            if (confirm('Are you sure you want to delete this image?')) {
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
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting image');
                });
            }
        }
    </script>
</body>
</html>