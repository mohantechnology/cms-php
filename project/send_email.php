<?php
/**
 * Shared email sending + test page.
 * Include this file and call send_feedback_email() from update_feedback.php.
 * Open in browser to test: /send_email.php or /send_email.php?test=1
 */

require_once __DIR__ . '/config_db.php';

/**
 * Send feedback approval/rejection email. Returns ['sent' => bool, 'log' => array].
 */
function send_feedback_email($email, $message, $status)
{
    $subject = "Feedback " . ($status === 'approved' ? "Approved" : "Rejected");
    $body = "Your feedback status: " . $status . "\n\nFeedback:\n" . $message;
    return send_mail($email, $subject, $body);
}

/**
 * Send email via Gmail SMTP (no sendmail required). Returns ['sent' => bool, 'log' => array].
 * Uses $__email_username and $__email_password from config_db.php.
 */
function send_mail($to, $subject, $body, $from = null)
{

    include __DIR__ . '/config_db.php';
    $from = $from ?? ($__email_username ?? 'noreply@localhost');
    $user = $__email_username ?? '';
    $pass = $__email_password ?? '';

    $log = [
        'to' => $to,
        'subject' => $subject,
        'from' => $from,
        'body_length' => strlen($body),
        'method' => 'smtp_gmail',
    ];

    if ($user === '' || $pass === '') {
        $log['error'] = 'Missing $__email_username or $__email_password in config_db.php';
        return ['sent' => false, 'log' => $log];
    }

    $sent = send_mail_smtp_gmail($user, $pass, $from, $to, $subject, $body, $log);
    $log['mail_returned'] = $sent;
    return ['sent' => $sent, 'log' => $log];
}

/**
 * Gmail SMTP: smtp.gmail.com:587, STARTTLS, AUTH LOGIN. No sendmail needed.
 */
function send_mail_smtp_gmail($user, $pass, $from, $to, $subject, $body, &$log)
{
    $host = 'smtp.gmail.com';
    $port = 587;

    $errno = 0;
    $errstr = '';
    $sock = @stream_socket_client(
        "tcp://{$host}:{$port}",
        $errno,
        $errstr,
        15,
        STREAM_CLIENT_CONNECT
    );
    if (!$sock) {
        $log['smtp_error'] = "Connection failed: $errstr ($errno)";
        return false;
    }

    $read = function () use ($sock) {
        $line = @fgets($sock, 515);
        return $line !== false ? trim($line) : '';
    };
    $send = function ($cmd) use ($sock) {
        @fwrite($sock, $cmd . "\r\n");
    };

    if (strpos($read(), '220') !== 0) {
        $log['smtp_error'] = 'Bad greeting';
        fclose($sock);
        return false;
    }

    $send("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    while ($line = $read()) {
        if (isset($line[3]) && $line[3] === ' ') break;
    }

    $send("STARTTLS");
    if (strpos($read(), '220') !== 0) {
        $log['smtp_error'] = 'STARTTLS failed';
        fclose($sock);
        return false;
    }
    if (!@stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        $log['smtp_error'] = 'TLS handshake failed';
        fclose($sock);
        return false;
    }

    $send("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    while ($line = $read()) {
        if (isset($line[3]) && $line[3] === ' ') break;
    }

    $send("AUTH LOGIN");
    if (strpos($read(), '334') !== 0) {
        $log['smtp_error'] = 'AUTH LOGIN not accepted';
        fclose($sock);
        return false;
    }
    $send(base64_encode($user));
    if (strpos($read(), '334') !== 0) {
        $log['smtp_error'] = 'Username rejected';
        fclose($sock);
        return false;
    }
    $send(base64_encode($pass));
    if (strpos($read(), '235') !== 0) {
        $log['smtp_error'] = 'Password rejected (check app password)';
        fclose($sock);
        return false;
    }

    $send("MAIL FROM:<" . $from . ">");
    if (strpos($read(), '250') !== 0) {
        $log['smtp_error'] = 'MAIL FROM rejected';
        fclose($sock);
        return false;
    }
    $send("RCPT TO:<" . $to . ">");
    if (strpos($read(), '250') !== 0) {
        $log['smtp_error'] = 'RCPT TO rejected';
        fclose($sock);
        return false;
    }
    $send("DATA");
    if (strpos($read(), '354') !== 0) {
        $log['smtp_error'] = 'DATA rejected';
        fclose($sock);
        return false;
    }

    $data = "From: $from\r\nTo: $to\r\nSubject: $subject\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n$body";
    $send($data);
    $send(".");
    if (strpos($read(), '250') !== 0) {
        $log['smtp_error'] = 'Message rejected';
        fclose($sock);
        return false;
    }
    $send("QUIT");
    fclose($sock);
    return true;
}

// Run as test page only when this file is opened directly in the browser (not when included)
$isDirectRequest = (php_sapi_name() !== 'cli' && (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'send_email.php'));
if ($isDirectRequest) {
    header('Content-Type: text/html; charset=utf-8');
    $result = null;
    $testTo = '';
    $testSubject = 'Test email from CMS';
    $testBody = 'If you receive this, PHP mail() is working.';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_test'])) {
        $testTo = trim($_POST['to'] ?? '');
        $testSubject = trim($_POST['subject'] ?? $testSubject);
        $testBody = trim($_POST['body'] ?? $testBody);
        if ($testTo !== '') {
            $result = send_mail($testTo, $testSubject, $testBody);
        } else {
            $result = ['sent' => false, 'log' => ['error' => 'Please enter recipient email.']];
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 20px auto; padding: 0 15px; }
        h1 { font-size: 1.3em; }
        label { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 6px; box-sizing: border-box; }
        textarea { min-height: 80px; }
        button { margin-top: 12px; padding: 8px 16px; }
        pre { background: #f4f4f4; padding: 12px; overflow: auto; font-size: 12px; }
        .ok { color: green; }
        .fail { color: #c00; }
    </style>
</head>
<body>
    <h1>Test email (Gmail SMTP)</h1>
    <p>Uses <code>config_db.php</code> (<code>$__email_username</code>, <code>$__email_password</code>). Sends via Gmail SMTP — no sendmail needed.</p>
    <form method="post" action="">
        <input type="hidden" name="test" value="1">
        <label>To: <input type="email" name="to" value="<?php echo htmlspecialchars($testTo); ?>" placeholder="your@email.com" required></label>
        <label>Subject: <input type="text" name="subject" value="<?php echo htmlspecialchars($testSubject); ?>"></label>
        <label>Body: <textarea name="body"><?php echo htmlspecialchars($testBody); ?></textarea></label>
        <button type="submit" name="send_test" value="1">Send test email</button>
    </form>
    <?php if ($result !== null): ?>
        <h2>Result</h2>
        <p class="<?php echo $result['sent'] ? 'ok' : 'fail'; ?>">
            <?php echo $result['sent'] ? 'Email sent successfully.' : 'Email failed. Check mail_log below.'; ?>
        </p>
        <pre><?php echo htmlspecialchars(json_encode($result['log'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
    <?php endif; ?>
</body>
</html>
    <?php
    exit;
}
