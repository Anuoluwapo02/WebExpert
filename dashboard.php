<?php
// dashboard.php: Enhanced Admin Dashboard with Project Count Card
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Query to get the total number of projects
$stmt = $pdo->query("SELECT COUNT(*) AS total_projects FROM projects");
$total_projects = $stmt->fetch(PDO::FETCH_ASSOC)['total_projects'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Inline CSS for Professional Dashboard */
        body /* Inline CSS for Professional Dashboard */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f4f8;
    margin: 0;
    padding: 0;
    color: #333;
}

.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
    flex-wrap: wrap; /* allow stacking on small screens */
}

.sidebar {
    width: 250px;
    background: #157c57;
    color: white;
    padding: 20px;
    position: fixed;
    height: 100%;
    overflow-y: auto;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    z-index: 1000;
}

.sidebar:hover {
    width: 260px; /* Slight expand on hover for animation */
}

.sidebar h3 {
    margin-bottom: 30px;
    text-align: center;
    font-size: 1.5em;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar li {
    margin-bottom: 15px;
}

.sidebar a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
    border-radius: 4px;
    transition: background 0.3s, transform 0.2s;
}

.sidebar a:hover {
    background: #0f5c3e;
    transform: translateX(5px);
}

.main-content {
    margin-left: 250px;
    padding: 40px;
    flex: 1;
    background: #f0f4f8;
    width: 100%;
}

.dashboard-container {
    max-width: 800px;
    margin: 0 auto;
}

.project-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    margin-bottom: 30px;
    animation: fadeIn 1s ease-in-out;
}

.project-card h2 {
    color: #157c57;
    margin: 0;
    font-size: 2.5em;
}

.project-card p {
    margin: 5px 0 0;
    font-size: 1.2em;
    color: #666;
}

.add-project-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    animation: fadeIn 1s ease-in-out 0.5s;
    opacity: 0;
}

.add-project-form h2 {
    color: #157c57;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-top: 10px;
    font-weight: bold;
}

input, textarea {
    padding: 12px;
    margin-top: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
}

input:focus, textarea:focus {
    border-color: #157c57;
    outline: none;
}

button {
    margin-top: 20px;
    padding: 12px;
    background: #157c57;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s, transform 0.2s;
}

button:hover {
    background: #0f5c3e;
    transform: scale(1.05);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ✅ Responsive Design */
@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }

    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        box-shadow: none;
    }

    .sidebar:hover {
        width: 100%;
    }

    .sidebar h3 {
        font-size: 1.3em;
    }

    .project-card h2 {
        font-size: 2em;
    }

    .project-card p {
        font-size: 1em;
    }

    .add-project-form {
        padding: 20px;
    }

    input, textarea {
        font-size: 15px;
    }

    button {
        font-size: 15px;
    }
}

/* ✅ Extra small screens (phones) */
@media (max-width: 480px) {
    .dashboard-container {
        width: 100%;
    }

    .project-card h2 {
        font-size: 1.8em;
    }

    .project-card p {
        font-size: 0.9em;
    }

    .sidebar h3 {
        font-size: 1.2em;
    }

    .sidebar a {
        font-size: 0.9em;
        padding: 8px;
    }

    button {
        font-size: 14px;
        padding: 10px;
    }
}
    </style>
    <script>
        // Inline JS for Animation Trigger
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.add-project-form');
            if (form) {
                form.style.opacity = '1'; // Trigger fade-in
            }

            // Client-side validation
            const uploadForm = document.querySelector('form');
            if (uploadForm) {
                uploadForm.addEventListener('submit', function(e) {
                    const title = document.getElementById('title').value.trim();
                    const description = document.getElementById('description').value.trim();
                    const demoLink = document.getElementById('demo_link').value.trim();
                    const image = document.getElementById('image').files[0];
                    
                    if (!title || !description || !demoLink || !image) {
                        alert('Please fill all fields and select an image.');
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</head>
<body>
    <div class="dashboard-wrapper">
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="dashboard.php">Add Project</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="dashboard-container">
                <div class="project-card">
                    <h2><?php echo $total_projects; ?></h2>
                    <p>Total Projects Uploaded</p>
                </div>
                <div class="add-project-form">
                    <h2>Add New Project</h2>
                    <form action="upload.php" method="POST" enctype="multipart/form-data">
    <label for="title">Project Title:</label>
    <input type="text" id="title" name="title" required>
    
    <label for="description">Short Description:</label>
    <textarea id="description" name="description" required rows="5"></textarea>
    
    <label for="demo_link">Demo Link:</label>
    <input type="url" id="demo_link" name="demo_link" required>
    
    <label for="technologies">Technologies (comma-separated):</label>
    <input type="text" id="technologies" name="technologies">
    
    <label for="github_link">GitHub Link:</label>
    <input type="url" id="github_link" name="github_link">
    
    <label for="image">Project Image:</label>
    <input type="file" id="image" name="image" accept="image/*" required>
    
    <button type="submit">Add Project</button>
</form>
                        
                </div>
            </div>
        </div>
    </div>
</body>
</html>