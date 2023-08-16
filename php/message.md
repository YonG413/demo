针对您的产品需求，设计站内信系统并实现相关功能需要进行以下步骤：设计数据表结构，编写PHP代码，以实现消息的发送、标记为已读和未读等功能。

**数据表设计思路与方案：**

1. **用户表 (`users`)**：存储用户的基本信息。
   - `user_id` (Primary Key)
   - `username`
   - `email`
   - ...

2. **消息表 (`messages`)**：存储消息的详细信息。
   - `message_id` (Primary Key)
   - `sender_id` (Foreign Key referencing `users`)
   - `receiver_id` (Foreign Key referencing `users`)
   - `subject`
   - `content`
   - `timestamp`
   - `is_read` (Boolean, 0 for unread, 1 for read)

3. **用户消息关联表 (`user_message`)**：用于跟踪用户和他们收到的消息之间的关系。
   - `user_message_id` (Primary Key)
   - `user_id` (Foreign Key referencing `users`)
   - `message_id` (Foreign Key referencing `messages`)
   - `is_read` (Boolean, 0 for unread, 1 for read)

**PHP代码实现：**

下面是一个简单的PHP代码示例，展示如何实现消息发送、标记为已读和未读的功能。请注意，这只是一个基本的示例，实际项目中您可能需要更多的错误处理、安全性考虑等。

1. **发送消息：**
```php
<?php
// Assuming you have established a database connection
$sender_id = 1; // ID of the sender
$receiver_id = 2; // ID of the receiver
$subject = "Hello";
$content = "This is a test message";

$sql = "INSERT INTO messages (sender_id, receiver_id, subject, content, timestamp, is_read)
        VALUES ($sender_id, $receiver_id, '$subject', '$content', NOW(), 0)";

if (mysqli_query($conn, $sql)) {
    echo "Message sent successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
```

2. **标记消息为已读：**
```php
<?php
// Assuming you have established a database connection
$message_id = 1; // ID of the message
$user_id = 2; // ID of the user

$sql = "UPDATE user_message
        SET is_read = 1
        WHERE user_id = $user_id AND message_id = $message_id";

if (mysqli_query($conn, $sql)) {
    echo "Message marked as read!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
```

3. **获取未读消息数：**
```php
<?php
// Assuming you have established a database connection
$user_id = 2; // ID of the user

$sql = "SELECT COUNT(*) AS unread_count
        FROM user_message
        WHERE user_id = $user_id AND is_read = 0";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$unread_count = $row['unread_count'];

echo "Unread messages: " . $unread_count;

mysqli_close($conn);
?>
```

请注意，上述代码示例是基于传统的MySQL数据库和简单的PHP操作。在实际项目中，您可能还需要考虑更多的安全性措施，如使用参数化查询来防止SQL注入等。另外，前端界面与后端的交互也需要考虑，例如用户界面上的点击事件，需要使用AJAX或其他前端技术与后端交互。