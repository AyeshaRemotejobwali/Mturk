<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTask - Complete Task</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        nav { display: flex; justify-content: center; gap: 20px; padding: 10px; background: #34495e; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #3498db; }
        .container { max-width: 600px; margin: 20px auto; padding: 0 20px; }
        .complete-form { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .complete-form h2 { margin-bottom: 20px; }
        .complete-form p { margin-bottom: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 768px) { .complete-form h2 { font-size: 1.5em; } }
    </style>
</head>
<body>
    <header>
        <h1>MicroTask - Complete Task</h1>
    </header>
    <nav>
        <a href="javascript:navigate('index.php')">Home</a>
        <a href="javascript:navigate('marketplace.php')">Task Marketplace</a>
        <a href="javascript:navigate('dashboard.php')">Worker Dashboard</a>
    </nav>
    <div class="container">
        <div class="complete-form">
            <h2>Complete Task</h2>
            <?php
            include 'db.php';
            $task_id = $_GET['task_id'];
            $result = $conn->query("SELECT * FROM tasks WHERE id = $task_id");
            $task = $result->fetch_assoc();
            echo "<p><strong>Title:</strong> " . htmlspecialchars($task['title']) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($task['description']) . "</p>";
            echo "<p><strong>Payment:</strong> $" . number_format($task['payment'], 2) . "</p>";
            ?>
            <form action="complete.php?task_id=<?php echo $task_id; ?>" method="POST">
                <button type="submit" class="btn">Mark as Completed</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $stmt = $conn->prepare("UPDATE tasks SET status = 'completed' WHERE id = ?");
                $stmt->bind_param("i", $task_id);
                $stmt->execute();
                echo "<p>Task completed successfully! <a href='javascript:navigate(\"dashboard.php\")'>Go to Dashboard</a></p>";
            }
            ?>
        </div>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
