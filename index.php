<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTask - Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        header h1 { font-size: 2.5em; }
        nav { display: flex; justify-content: center; gap: 20px; padding: 10px; background: #34495e; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #3498db; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .hero { text-align: center; padding: 50px 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .hero h2 { font-size: 2em; margin-bottom: 20px; }
        .hero p { font-size: 1.2em; color: #555; margin-bottom: 30px; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn:hover { background: #2980b9; }
        .featured-tasks { margin-top: 40px; }
        .featured-tasks h2 { text-align: center; margin-bottom: 20px; }
        .task-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .task-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .task-card h3 { font-size: 1.5em; margin-bottom: 10px; }
        .task-card p { color: #555; }
        @media (max-width: 768px) { .hero h2 { font-size: 1.5em; } .hero p { font-size: 1em; } .task-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <h1>MicroTask</h1>
    </header>
    <nav>
        <a href="javascript:navigate('index.php')">Home</a>
        <a href="javascript:navigate('marketplace.php')">Task Marketplace</a>
        <a href="javascript:navigate('dashboard.php')">Worker Dashboard</a>
    </nav>
    <div class="container">
        <div class="hero">
            <h2>Welcome to MicroTask</h2>
            <p>Earn money by completing small tasks or post tasks to get work done quickly!</p>
            <a href="javascript:navigate('signup.php?type=worker')" class="btn">Sign Up as Worker</a>
            <a href="javascript:navigate('signup.php?type=requester')" class="btn">Sign Up as Requester</a>
        </div>
        <div class="featured-tasks">
            <h2>Featured Tasks</h2>
            <div class="task-grid">
                <?php
                include 'db.php';
                $result = $conn->query("SELECT * FROM tasks WHERE status = 'open' LIMIT 3");
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='task-card'>";
                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p><strong>Payment:</strong> $" . number_format($row['payment'], 2) . "</p>";
                    echo "<p><strong>Deadline:</strong> " . $row['deadline'] . "</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
