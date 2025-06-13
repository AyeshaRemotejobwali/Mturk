```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTask - Worker Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        nav { display: flex; justify-content: center; gap: 20px; padding: 10px; background: #34495e; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #3498db; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .dashboard { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .dashboard h2 { margin-bottom: 20px; }
        .earnings { margin-bottom: 40px; }
        .earnings p { font-size: 1.2em; }
        .task-list { margin-top: 20px; }
        .task-item { background: #f9f9f9; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn:hover { background: #2980b9; }
        .review-form { margin-top: 10px; }
        .review-form textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .withdrawal-form { margin-top: 20px; }
        .withdrawal-form input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .withdrawal-history { margin-top: 40px; }
        .withdrawal-item { background: #f9f9f9; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .error { color: red; }
        .success { color: green; }
        @media (max-width: 768px) { .dashboard h2 { font-size: 1.5em; } }
    </style>
</head>
<body>
    <header>
        <h1>MicroTask - Worker Dashboard</h1>
    </header>
    <nav>
        <a href="javascript:navigate('index.php')">Home</a>
        <a href="javascript:navigate('marketplace.php')">Task Marketplace</a>
        <a href="javascript:navigate('dashboard.php')">Worker Dashboard</a>
    </nav>
    <div class="container">
        <div class="dashboard">
            <h2>Worker Dashboard</h2>
            <div class="earnings">
                <?php
                include 'db.php';
                $worker_id = 1; // Simulated worker ID
                $result = $conn->query("SELECT SUM(payment) as total_earnings FROM tasks WHERE worker_id = $worker_id AND status = 'completed'");
                $total_earnings = $result->fetch_assoc()['total_earnings'] ?? 0;
                $result = $conn->query("SELECT SUM(amount) as total_withdrawn FROM withdrawals WHERE worker_id = $worker_id AND status = 'processed'");
                $total_withdrawn = $result->fetch_assoc()['total_withdrawn'] ?? 0;
                $available_balance = $total_earnings - $total_withdrawn;
                echo "<p><strong>Total Earnings:</strong> $" . number_format($total_earnings, 2) . "</p>";
                echo "<p><strong>Available Balance:</strong> $" . number_format($available_balance, 2) . "</p>";
                ?>
                <div class="withdrawal-form">
                    <h3>Request Withdrawal</h3>
                    <form action="dashboard.php" method="POST">
                        <input type="number" step="0.01" name="amount" placeholder="Amount to withdraw ($)" required>
                        <button type="submit" class="btn">Request Withdrawal</button>
                    </form>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'])) {
                        $amount = $_POST['amount'];
                        if ($amount <= 0) {
                            echo "<p class='error'>Amount must be greater than zero.</p>";
                        } elseif ($amount > $available_balance) {
                            echo "<p class='error'>Insufficient balance for withdrawal.</p>";
                        } else {
                            $stmt = $conn->prepare("INSERT INTO withdrawals (worker_id, amount, status) VALUES (?, ?, 'pending')");
                            $stmt->bind_param("id", $worker_id, $amount);
                            if ($stmt->execute()) {
                                echo "<p class='success'>Withdrawal request submitted successfully!</p>";
                            } else {
                                echo "<p class='error'>Error processing withdrawal request.</p>";
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <h2>Your Tasks</h2>
            <div class="task-list">
                <?php
                $result = $conn->query("SELECT * FROM tasks WHERE worker_id = $worker_id");
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='task-item'>";
                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p><strong>Status:</strong> " . ucfirst($row['status']) . "</p>";
                    echo "<p><strong>Payment:</strong> $" . number_format($row['payment'], 2) . "</p>";
                    if ($row['status'] == 'accepted') {
                        echo "<button class='btn' onclick=\"navigate('complete.php?task_id=" . $row['id'] . "')\">Complete Task</button>";
                    }
                    if ($row['status'] == 'completed') {
                        echo "<form class='review-form' action='dashboard.php' method='POST'>";
                        echo "<input type='hidden' name='task_id' value='" . $row['id'] . "'>";
                        echo "<textarea name='review' placeholder='Leave a review' required></textarea>";
                        echo "<button type='submit' class='btn'>Submit Review</button>";
                        echo "</form>";
                    }
                    echo "</div>";
                }
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
                    $task_id = $_POST['task_id'];
                    $review = $_POST['review'];
                    $stmt = $conn->prepare("UPDATE tasks SET review = ? WHERE id = ?");
                    $stmt->bind_param("si", $review, $task_id);
                    $stmt->execute();
                    echo "<p class='success'>Review submitted!</p>";
                }
                ?>
            </div>
            <div class="withdrawal-history">
                <h3>Withdrawal History</h3>
                <?php
                $result = $conn->query("SELECT * FROM withdrawals WHERE worker_id = $worker_id ORDER BY requested_at DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='withdrawal-item'>";
                        echo "<p><strong>Amount:</strong> $" . number_format($row['amount'], 2) . "</p>";
                        echo "<p><strong>Status:</strong> " . ucfirst($row['status']) . "</p>";
                        echo "<p><strong>Requested At:</strong> " . $row['requested_at'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No withdrawal requests yet.</p>";
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
```
