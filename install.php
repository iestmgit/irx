<?php
$host='localhost'; $user='root'; $pass='';
$conn=new mysqli($host,$user,$pass);
if($conn->connect_error) die("خطا: ".$conn->connect_error);

$conn->query("CREATE DATABASE IF NOT EXISTS mini_twitter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db('mini_twitter');

$sqls = [
"CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE, email VARCHAR(100) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, bio VARCHAR(160) DEFAULT '', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS tweets (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS likes (user_id INT NOT NULL, tweet_id INT NOT NULL, PRIMARY KEY(user_id,tweet_id), FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY(tweet_id) REFERENCES tweets(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS follows (follower_id INT NOT NULL, following_id INT NOT NULL, PRIMARY KEY(follower_id,following_id), FOREIGN KEY(follower_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY(following_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];
foreach($sqls as $s){ $conn->query($s); }
if(!is_dir(__DIR__.'/tweets')) mkdir(__DIR__.'/tweets',0755,true);
echo "✅ نصب کامل شد. install.php را حذف کن.";
$conn->close();
