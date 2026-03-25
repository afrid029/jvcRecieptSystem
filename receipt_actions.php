<?php
require_once 'session_init.php';
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    // Common fields
    $received_from = $_POST['received_from'];
    $amount = $_POST['amount'];
    $city = $_POST['city'];
    $payment_method = $_POST['payment_method'];
    if ($payment_method === 'Other' && !empty($_POST['payment_method_other'])) {
        $payment_method = $_POST['payment_method_other'];
    }

    $purpose_id = $_POST['purpose_id'];
    $other_purpose = null;
    if (!empty($_POST['other_purpose'])) {
        $other_purpose = $_POST['other_purpose'];
    }

    // Country logic: Super Admin can set, else use Session
    $country_id = $_SESSION['country_id'];
    if (in_array($_SESSION['role'], ['super_admin', 'manager']) && !empty($_POST['country_id'])) {
        $country_id = $_POST['country_id'];
    }

    $address = $_POST['address'];
    $date = $_POST['date'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if ($action === 'create') {
        // Generate Receipt Number
        $year = date('Y');
        $countStmt = $pdo->prepare("SELECT count(*) FROM receipts WHERE YEAR(date) = ?");
        $countStmt->execute([$year]);
        $count = $countStmt->fetchColumn() + 1;
        $receipt_number = "JVCREC-$year-" . str_pad($count, 4, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO receipts (receipt_number, received_from, amount, payment_method, purpose_id, other_purpose, country_id, address, date, email, phone, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$receipt_number, $received_from, $amount, $payment_method, $purpose_id, $other_purpose, $country_id, $address, $date, $email, $phone, $city]);

        header("Location: dashboard.php?msg=Receipt created successfully");
        exit();

    } elseif ($action === 'update') {
        $id = $_POST['id'];

        // Check permission
        if (!in_array($_SESSION['role'], ['super_admin', 'manager'])) {
            $chk = $pdo->prepare("SELECT country_id FROM receipts WHERE id=?");
            $chk->execute([$id]);
            if ($chk->fetchColumn() != $_SESSION['country_id']) {
                die("Access Denied");
            }
        }

        $sql = "UPDATE receipts SET received_from=?, amount=?, payment_method=?, purpose_id=?, other_purpose=?, country_id=?, address=?, date=?, email=?, phone=?, city=?, email_sent=0 WHERE id=?";
        $params = [$received_from, $amount, $payment_method, $purpose_id, $other_purpose, $country_id, $address, $date, $email, $phone, $city, $id];
        $pdo->prepare($sql)->execute($params);

        header("Location: dashboard.php?msg=Receipt updated successfully");
        exit();
    }
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? 0;

    if ($action === 'delete') {
        // Check permission
        if (!in_array($_SESSION['role'], ['super_admin', 'manager'])) {
            $chk = $pdo->prepare("SELECT country_id FROM receipts WHERE id=?");
            $chk->execute([$id]);
            if ($chk->fetchColumn() != $_SESSION['country_id']) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    http_response_code(403);
                    echo json_encode(['error' => 'Access Denied']);
                    exit();
                }
                die("Access Denied");
            }
        }

        $stmt = $pdo->prepare("DELETE FROM receipts WHERE id = ?");
        $stmt->execute([$id]);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        }

        header("Location: dashboard.php?msg=Receipt deleted successfully");
        exit();

    } elseif ($action === 'send_email') {
        $id = $_GET['id'];

        // ... (rest of logic) ...
        // We need to inject JSON response capability inside the existing logic block or wrap it.
        // For simplicity, I will copy the whole block and add JSON checks.

        $stmt = $pdo->prepare("SELECT r.*, p.name as purpose_name, c.name as country_name 
                               FROM receipts r 
                               JOIN purposes p ON r.purpose_id = p.id 
                               LEFT JOIN countries c ON r.country_id = c.id
                               WHERE r.id = ?");
        $stmt->execute([$id]);
        $receipt = $stmt->fetch();

        if (!$receipt) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['error' => 'Receipt not found']);
                exit();
            }
            die("Receipt not found");
        }

        if (!in_array($_SESSION['role'], ['super_admin', 'manager']) && $receipt['country_id'] != $_SESSION['country_id']) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['error' => 'Access Denied']);
                exit();
            }
            die("Access Denied");
        }

        if ($receipt['email_sent']) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Email already sent']);
                exit();
            }
            header("Location: dashboard.php?msg=Email already sent for this receipt");
            exit();
        }

        require 'mail_config.php';
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = constant('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = constant('SMTP_USERNAME');
            $mail->Password = constant('SMTP_PASSWORD');
            $mail->Port = constant('SMTP_PORT');
            $mail->SMTPSecure = 'tls';

            $mail->setFrom(constant('SMTP_FROM_EMAIL'), constant('SMTP_FROM_NAME'));
            $mail->addAddress($receipt['email'], $receipt['received_from']);

            // Logo Logic
            $logoPath = 'assets/images/logo.jpg';
            if ($receipt['country_id']) {
                $obaStmt = $pdo->prepare("SELECT logo FROM oba_infos WHERE country_id = ?");
                $obaStmt->execute([$receipt['country_id']]);
                $obaLogo = $obaStmt->fetchColumn();
                if ($obaLogo && file_exists($obaLogo)) {
                    $logoPath = $obaLogo;
                }
            }
            $mail->addEmbeddedImage($logoPath, 'school_logo');

            // Dynamic Header Title
            $headerTitle = 'J/Victoria College';
            if (!empty($receipt['country_name'])) {
                $headerTitle = 'J/Victoria College ' . htmlspecialchars($receipt['country_name']) . ' OSA';
            }

            $mail->isHTML(true);
            $mail->Subject = 'Donation Receipt - ' . $receipt['receipt_number'];

            $purposeDisplay = $receipt['purpose_name'];
            if ($receipt['other_purpose'])
                $purposeDisplay .= ' (' . $receipt['other_purpose'] . ')';

            $body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px; background-color: #ffffff;">
                <div style="text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                    <img src="cid:school_logo" alt="J/Victoria College" style="width: 80px; height: 80px; border-radius: 50%; display: block; margin: 0 auto;">
                    <h1 style="color: #333; margin: 10px 0; font-size: 24px;">' . $headerTitle . '</h1>
                    <p style="color: #777; margin: 0; font-size: 14px;">Chulipuram, Jaffna</p>
                </div>
                <h2 style="color: #4CAF50; text-align: center; margin-bottom: 25px;">Official Receipt</h2>
                <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #eee;">
                    <p style="margin: 5px 0;"><strong>Receipt #:</strong> ' . htmlspecialchars($receipt['receipt_number']) . '</p>
                    <p style="margin: 5px 0;"><strong>Date:</strong> ' . htmlspecialchars($receipt['date']) . '</p>
                </div>
                <p style="color: #444;">Dear ' . htmlspecialchars($receipt['received_from']) . ',</p>
                <p style="color: #444; line-height: 1.5;">Thank you for your generous payment details below:</p>
                <table style="width: 100%; border-collapse: collapse; margin: 25px 0;">
                    <tr><td style="padding: 12px; border-bottom: 1px solid #eee;">Amount</td><td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee; font-weight: bold;">$' . number_format($receipt['amount'], 2) . '</td></tr>
                    <tr><td style="padding: 12px; border-bottom: 1px solid #eee;">Purpose</td><td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee;">' . htmlspecialchars($purposeDisplay) . '</td></tr>
                    <tr><td style="padding: 12px; border-bottom: 1px solid #eee;">Method</td><td style="padding: 12px; text-align: right; border-bottom: 1px solid #eee;">' . htmlspecialchars($receipt['payment_method']) . '</td></tr>
                </table>
                <div style="text-align: center; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px; font-size: 12px; color: #999;">
                    <p>&copy; ' . date('Y') . ' J/Victoria College. All rights reserved.</p>
                </div>
            </div>';

            $mail->Body = $body;
            $mail->AltBody = "Receipt #{$receipt['receipt_number']} - Amount: \${$receipt['amount']}";
            $mail->send();

            $stmt = $pdo->prepare("UPDATE receipts SET email_sent = 1 WHERE id = ?");
            $stmt->execute([$id]);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit();
            }

            header("Location: dashboard.php?msg=Receipt emailed successfully");
            exit();

        } catch (Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['error' => $mail->ErrorInfo]);
                exit();
            }
            header("Location: dashboard.php?msg=Mailer Error: " . urlencode($mail->ErrorInfo));
            exit();
        }
    }
}
// Add success JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $action === 'send_email') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}
header("Location: dashboard.php");
exit();
?>