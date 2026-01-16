<?php
require_once 'session_init.php';
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        // Auto-generate Receipt #
// Format: JVC-{YYYYMMDD}-{RAND}
        $receipt_number = 'JVC-' . date('Ymd') . '-' . mt_rand(1000, 9999);

        // Handle "Other" fields
        $payment_method = $_POST['payment_method'];
        if ($payment_method === 'Other' && !empty($_POST['payment_method_other'])) {
            $payment_method = $_POST['payment_method_other'];
        }

        $payment_purpose = $_POST['payment_purpose'];
        if ($payment_purpose === 'Other' && !empty($_POST['payment_purpose_other'])) {
            $payment_purpose = $_POST['payment_purpose_other'];
        }

        $stmt = $pdo->prepare("INSERT INTO receipts (receipt_number, date, received_from, address, phone, email, amount,
payment_method, payment_purpose) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $receipt_number,
            $_POST['date'],
            $_POST['received_from'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['amount'],
            $payment_method,
            $payment_purpose
        ]);
        header("Location: dashboard.php?msg=Receipt created successfully");
        exit();

    } elseif ($action === 'update') {
        // Handle "Other" fields
        $payment_method = $_POST['payment_method'];
        if ($payment_method === 'Other' && !empty($_POST['payment_method_other'])) {
            $payment_method = $_POST['payment_method_other'];
        }

        $payment_purpose = $_POST['payment_purpose'];
        if ($payment_purpose === 'Other' && !empty($_POST['payment_purpose_other'])) {
            $payment_purpose = $_POST['payment_purpose_other'];
        }

        // When updating, reset email_sent to 0 (false) so it can be sent again
// Note: receipt_number is NOT updated
        $stmt = $pdo->prepare("UPDATE receipts SET date=?, received_from=?, address=?, phone=?, email=?, amount=?,
payment_method=?, payment_purpose=?, email_sent=0 WHERE id=?");
        $stmt->execute([
            $_POST['date'],
            $_POST['received_from'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['amount'],
            $payment_method,
            $payment_purpose,
            $_POST['id']
        ]);
        header("Location: dashboard.php?msg=Receipt updated successfully");
        exit();
    }
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? 0;

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM receipts WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: dashboard.php?msg=Receipt deleted successfully");
        exit();

    } elseif ($action === 'send_email') {
        $id = $_GET['id'];

        // Fetch receipt details
        $stmt = $pdo->prepare("SELECT * FROM receipts WHERE id = ?");
        $stmt->execute([$id]);
        $receipt = $stmt->fetch();

        if (!$receipt) {
            die("Receipt not found");
        }

        // Check if email already sent - but User asked to "email if not sent" logic
        if ($receipt['email_sent']) {
            header("Location: dashboard.php?msg=Email already sent for this receipt");
            exit();
        }

        require 'mail_config.php';
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = constant('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = constant('SMTP_USERNAME');
            $mail->Password = constant('SMTP_PASSWORD');
            $mail->Port = constant('SMTP_PORT');
            $mail->SMTPSecure = 'tls'; // Often required for port 587

            // Recipient
            $mail->setFrom(constant('SMTP_FROM_EMAIL'), constant('SMTP_FROM_NAME'));
            $mail->addAddress($receipt['email'], $receipt['received_from']);

            // Embed Logo
            $mail->addEmbeddedImage('assets/images/logo.jpg', 'school_logo');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Donation Receipt - ' . $receipt['receipt_number'];

            // HTML Template
            $body = '
<div
    style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px; background-color: #ffffff;">
    <div style="text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
        <img src="cid:school_logo" alt="J/Victoria College"
            style="width: 80px; height: 80px; border-radius: 50%; display: block; margin: 0 auto;">
        <h1 style="color: #333; margin: 10px 0; font-size: 24px;">J/Victoria College</h1>
        <p style="color: #777; margin: 0; font-size: 14px;">Chulipuram, Jaffna</p>
    </div>

    <h2 style="color: #4CAF50; text-align: center; margin-bottom: 25px;">Official Receipt</h2>

    <div
        style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #eee;">
        <p style="margin: 5px 0;"><strong>Receipt #:</strong> ' . htmlspecialchars($receipt['receipt_number']) . '</p>
        <p style="margin: 5px 0;"><strong>Date:</strong> ' . htmlspecialchars($receipt['date']) . '</p>
    </div>

    <p style="color: #444;">Dear ' . htmlspecialchars($receipt['received_from']) . ',</p>

    <p style="color: #444; line-height: 1.5;">Thank you for your generous payment. We have successfully received your
        contribution as detailed below:</p>

    <table style="width: 100%; border-collapse: collapse; margin: 25px 0;">
        <tr style="background-color: #eee;">
            <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd; color: #555;">Description</th>
            <th style="padding: 12px; text-align: right; border-bottom: 1px solid #ddd; color: #555;">Details</th>
        </tr>
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #eee; color: #666;">Amount</td>
            <td
                style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; color: #333; font-weight: bold; font-size: 16px;">
                $' . number_format($receipt['amount'], 2) . '</td>
        </tr>
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #eee; color: #666;">Purpose</td>
            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; color: #333;">' .
                htmlspecialchars($receipt['payment_purpose']) . '</td>
        </tr>
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #eee; color: #666;">Payment Method</td>
            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; color: #333;">' .
                htmlspecialchars($receipt['payment_method']) . '</td>
        </tr>
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #eee; color: #666;">Phone #</td>
            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; color: #333;">' .
                htmlspecialchars($receipt['phone']) . '</td>
        </tr>
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #eee; color: #666;">Address</td>
            <td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; color: #333;">' .
                htmlspecialchars($receipt['address']) . '</td>
        </tr>
    </table>

    <div
        style="text-align: center; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; font-size: 12px; color: #999;">
        <p>This is an electronically generated receipt.</p>
        <p>&copy; ' . date('Y') . ' J/Victoria College. All rights reserved.</p>
    </div>
</div>';

            $mail->Body = $body;
            $mail->AltBody = "Receipt #{$receipt['receipt_number']}\n" .
                "Amount: \${$receipt['amount']}\n" .
                "Phone: {$receipt['phone']}\n" .
                "Address: {$receipt['address']}\n\n" .
                "Thank you for your payment.";

            $mail->send();

            // Update database
            $stmt = $pdo->prepare("UPDATE receipts SET email_sent = 1 WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: dashboard.php?msg=Receipt emailed successfully");
            exit();

        } catch (Exception $e) {
            // Note: In production you might not want to show the raw error to start
            header("Location: dashboard.php?msg=Message could not be sent. Mailer Error: " . urlencode($mail->ErrorInfo));
            exit();
        }
    }
}

header("Location: dashboard.php");
exit();