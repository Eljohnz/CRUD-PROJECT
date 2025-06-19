<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Status Update Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; background-color: #f2f2f2; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 10px;">
        <h2 style="color: #2c3e50;">Status Update Notification</h2>
        
        <p>Dear <?php echo h($recipientName); ?>,</p>

        <p>Your status has been updated in our system. Below are the details:</p>

        <p><strong>Previous Status:</strong> <?php echo h($oldStatus); ?></p>
        
        <?php
            $statusColor = '#f39c12'; // default orange
            if ($newStatus === 'Approved') {
                $statusColor = '#27ae60'; // green
            } elseif ($newStatus === 'Disapproved') {
                $statusColor = '#c0392b'; // red
            }
        ?>
        <p><strong>New Status:</strong> <span style="color: <?php echo $statusColor; ?>;"><?php echo h($newStatus); ?></span></p>

        <hr style="border: none; border-top: 1px solid #ccc; margin: 20px 0;">

        <p><strong>Name:</strong> <?php echo h($recipientName); ?></p>
        <p><strong>Age:</strong> <?php echo h($recipientAge); ?></p>
        <p><strong>Birthday:</strong> <?php echo h($recipientBirthday); ?></p>
        <p><strong>Role:</strong> <?php echo h($recipientRole); ?></p>
        <p><strong>Character Info:</strong> <?php echo h($recipientCharacter); ?></p>
        <p><strong>Beneficiary:</strong> <?php echo h($Beneficiaries); ?></p>

        <p>If you have any questions, feel free to reach out to us.</p>

        <p>Thank you,<br>
        <strong>The Team</strong></p>
    </div>
</body>
</html>
