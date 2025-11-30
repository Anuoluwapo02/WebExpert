<?php
// project.php: Display Projects with Updated Image Path for admin/uploads
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'admin/config.php'; // Correct path from iPortfolio directory

try {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>" . count($projects) . " .</p>";
} catch (PDOException $e) {
    echo "<p>Database error: " . $e->getMessage() . "</p>";
    $projects = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Projects</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-text {
            background: linear-gradient(90deg, #4b6cb7, #182848);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <section id="projects" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">My <span class="gradient-text">Projects</span></h2>
            
            <?php if (empty($projects)): ?>
                <p class="text-center text-gray-600 col-span-full">No projects available yet or database error occurred.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card bg-white rounded-lg overflow-hidden shadow-md transition duration-300 hover:shadow-lg">
                            <div class="h-48 overflow-hidden bg-gray-200 flex items-center justify-center">
                                <?php
                                $image_filename = htmlspecialchars($project['image_path'] ?? ''); // e.g., 2025-10-08_18-08-00_image.png
                                $full_path = $_SERVER['DOCUMENT_ROOT'] . '/iPortfolio/admin/uploads/' . $image_filename; // e.g., C:\xampp\htdocs\iPortfolio\admin\uploads\2025-10-08_18-08-00_image.png
                                $web_path = '/iPortfolio/admin/uploads/' . $image_filename; // Web-accessible path

                                if (!file_exists($full_path)) {
                                    $web_path = 'https://via.placeholder.com/300x200?text=Image+Not+Found+at+' . urlencode($image_filename);
                                } elseif (!@getimagesize($full_path)) {
                                    $web_path = 'https://via.placeholder.com/300x200?text=Invalid+Image';
                                }
                                ?>
                                <img src="<?php echo $web_path; ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/300x200?text=Error+Loading+Image';">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2 text-gray-800"><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($project['description']); ?></p>
                                <?php if (!empty($project['technologies'])): ?>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <?php 
                                        $techs = explode(',', $project['technologies']);
                                        foreach ($techs as $tech): 
                                            $tech = trim($tech);
                                            $color = 'bg-blue-100 text-blue-800';
                                            if (strtolower($tech) === 'node.js') $color = 'bg-green-100 text-green-800';
                                            if (strtolower($tech) === 'mongodb') $color = 'bg-purple-100 text-purple-800';
                                            if (strtolower($tech) === 'python') $color = 'bg-yellow-100 text-yellow-800';
                                            if (strtolower($tech) === 'php') $color = 'bg-red-100 text-red-800';
                                            if (strtolower($tech) === 'javascript') $color = 'bg-orange-100 text-orange-800';
                                        ?>
                                            <span class="px-3 py-1 <?php echo $color; ?> text-sm rounded-full"><?php echo htmlspecialchars($tech); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="flex space-x-3">
                                    <?php if (!empty($project['demo_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($project['demo_link']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                            <i class="fas fa-external-link-alt mr-1"></i> Live Demo
                                        </a>
                                    <?php endif; ?>
                                    <?php 
                                        $github_link = !empty($project['github_link']) ? $project['github_link'] : '#';
                                    ?>
                                    <a href="<?php echo htmlspecialchars($github_link); ?>" target="_blank" class="text-gray-600 hover:text-gray-800 flex items-center">
                                        <i class="fab fa-github mr-1"></i> Code
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
```