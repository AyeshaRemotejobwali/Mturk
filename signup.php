<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTask - Sign Up</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        nav { display: flex; justify-content: center; gap: 20px; padding: 10px; background: #34495e; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #3498db; }
        .container { max-width: 600px; margin: 20px auto; padding: 0 20px; }
        .signup-form { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .signup-form h2 { margin-bottom: 20px; }
        .signup-form input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 768px) { .signup-form h2 { font-size: 1.5em; } }
    </style>
</head>
<body>
    <header>
        <h1>MicroTask - Sign Up</h1>
    </header>
    <nav>
        <a href="javascript:navigate('index.php')">Home</a>
        <a href="javascript:navigate('marketplace.php')">Task Marketplace</a>
        <a href="javascript:navigate('dashboard.php')">Worker Dashboard</a>
    </nav>
    <div class="container">
        <div class="signup-form">
            <h2>Sign Up as <?php echo isset($_GET['type']) && $_GET['type'] == 'requester' ? 'Requester' : 'Worker'; ?></h2>
            <form action="signup.php" method="POST">
                <input type="hidden" name="type" value="<?php echo isset($_GET['type']) ? $_GET['type'] : 'worker'; ?>">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Sign Up</button>
            </form>
            <?php
            include 'db.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $type = $_POST['type'];
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, type) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $password, $type);
                $stmt->execute();
                echo "<p>Sign up successful! <a href='javascript:navigate(\"index.php\")'>Go to Home</a></p>";
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
