<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTask - Task Marketplace</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        nav { display: flex; justify-content: center; gap: 20px; padding: 10px; background: #34495e; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #3498db; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .task-form { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 40px; }
        .task-form h2 { margin-bottom: 20px; }
        .task-form input, .task-form textarea, .task-form select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn:hover { background: #2980b9; }
        .task-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .task-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .task-card h3 { font-size: 1.5em; margin-bottom: 10px; }
        .task-card p { color: #555; }
        @media (max-width: 768px) { .task-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <h1>MicroTask - Task Marketplace</h1>
    </header>
    <nav>
        <a href="javascript:navigate('index.php')">Home</a>
        <a href="javascript:navigate('marketplace.php')">Task Marketplace</a>
        <a href="javascript:navigate('dashboard.php')">Worker Dashboard</a>
    </nav>
    <div class="container">
        <div class="task-form">
            <h2>Post a New Task</h2>
            <form action="marketplace.php" method="POST">
                <input type="text" name="title" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required></textarea>
                <select name="category" required>
                    <option value="Data Entry">Data Entry</option>
                    <option value="Survey">Survey</option>
                    <option value="Transcription">Transcription</option>
                </select>
                <input type="number" step="0.01" name="payment" placeholder="Payment ($)" required>
                <input type="date" name="deadline" required>
                <button type="submit" class="btn">Post Task</button>
            </form>
            <?php
            include 'db.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $title = $_POST['title'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                $payment = $_POST['payment'];
                $deadline = $_POST['deadline'];
                $stmt = $conn->prepare("INSERT INTO tasks (title, description, category, payment, deadline, status) VALUES (?, ?, ?, ?, ?, 'open')");
                $stmt->bind_param("sssds", $title, $description, $category, $payment, $deadline);
                $stmt->execute();
                echo "<p>Task posted successfully!</p>";
            }
            ?>
        </div>
        <h2>Available Tasks</h2>
        <div class="task-grid">
            <?php
            $result = $conn->query("SELECT * FROM tasks WHERE status = 'open'");
            while ($row = $result->fetch_assoc()) {
                echo "<div class='task-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                echo "<p><strong>Payment:</strong> $" . number_format($row['payment'], 2) . "</p>";
                echo "<p><strong>Deadline:</strong> " . $row['deadline'] . "</p>";
                echo "<button class='btn' onclick=\"navigate('apply.php?task_id=" . $row['id'] . "')\">Apply</button>";
                echo "</div>";
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
