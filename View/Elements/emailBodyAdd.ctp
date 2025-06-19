<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Status Update Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .email-container {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            max-width: 600px;
            margin: auto;
        }
        h2 {
            color: #2c3e50;
        }
        .info {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Status Update Notification</h2>
        <p>Dear <?php echo h($recipientName); ?>,</p>
        <p>You have successfully added a new record to the system. Here are your details:</p>

        <div class="info"><span class="label">Name:</span> <?php echo h($recipientName); ?></div>
        <div class="info"><span class="label">Email:</span> <?php echo h($recipientEmail); ?></div>
        <div class="info"><span class="label">Age:</span> <?php echo h($recipientAge); ?></div>
        <div class="info"><span class="label">Birthday:</span> <?php echo h($recipientBirthday); ?></div>
        <div class="info"><span class="label">Role:</span> <?php echo h($recipientRole); ?></div>
        <div class="info"><span class="label">Character Info:</span> <?php echo h($recipientCharacter); ?></div>
        <div class="info"><span class="label">Beneficiaries:</span> <?php echo h($Beneficiaries); ?></div>

        <p>If you have any questions, feel free to reach out to us.</p>
        <p>Thank you,<br>
        The Team</p>
    </div>
</body>
</html>