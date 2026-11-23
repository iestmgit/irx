<?php
// install.php - فقط یک بار اجرا شود
$host = 'localhost';
$user = 'root';
$pass = ''; // رمز پیش‌فرض XAMPP

// اتصال بدون انتخاب دیتابیس
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("خطا در اتصال به MySQL: " . $conn->connect_error);
}

// 1. ساخت دیتابیس
$sql = "CREATE DATABASE IF NOT EXISTS mini_twitter 
        CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "✅ دیتابیس ساخته شد یا از قبل وجود داشت.<br>";
} else {
    die("خطا در ساخت دیتابیس: " . $conn->error);
}

// 2. انتخاب دیتابیس
$conn->select_db('mini_twitter');

// 3. ساخت جدول‌ها
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS tweets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS likes (
        user_id INT NOT NULL,
        tweet_id INT NOT NULL,
        PRIMARY KEY (user_id, tweet_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (tweet_id) REFERENCES tweets(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS follows (
        follower_id INT NOT NULL,
        following_id INT NOT NULL,
        PRIMARY KEY (follower_id, following_id),
        FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "✅ جدول ساخته شد.<br>";
    } else {
        echo "❌ خطا: " . $conn->error . "<br>";
    }
}

// 4. ساخت پوشه tweets
$tweets_dir = __DIR__ . '/tweets';
if (!is_dir($tweets_dir)) {
    mkdir($tweets_dir, 0755, true);
    echo "✅ پوشه tweets ساخته شد.<br>";
} else {
    echo "ℹ️ پوشه tweets از قبل وجود داشت.<br>";
}

echo "<br><strong>نصب کامل شد! حالا install.php را حذف یا تغییر نام بده.</strong>";
$conn->close();
?>
