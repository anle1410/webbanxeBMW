<?php
// Simple mailer wrapper. Prefer installing PHPMailer via Composer for reliable SMTP.
$mailConfig = require __DIR__ . '/../config/mail.php';

class Mailer {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function sendVerification($toEmail, $toName, $verificationLink) {
        // Try PHPMailer if available
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $smtp = $this->config['smtp'];
                $mail->isSMTP();
                $mail->Host = $smtp['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $smtp['username'];
                $mail->Password = $smtp['password'];
                $mail->SMTPSecure = $smtp['secure'];
                $mail->Port = $smtp['port'];

                $mail->setFrom($this->config['from_email'], $this->config['from_name']);
                $mail->addAddress($toEmail, $toName);

                $mail->isHTML(true);
                $mail->Subject = 'Xác minh email - BMW Showroom';
                $mail->Body = "Xin chào {$toName},<br><br>Vui lòng nhấp vào liên kết sau để xác minh email của bạn:<br><a href='" . htmlspecialchars($verificationLink) . "'>Xác minh email</a><br><br>Nếu bạn không tạo tài khoản, hãy bỏ qua email này.";

                $mail->send();
                return true;
            } catch (Exception $e) {
                error_log('PHPMailer error: ' . $e->getMessage());
                return false;
            }
        }

        // Fallback to PHP mail() with simple headers
        $subject = 'Xác minh email - BMW Showroom';
        $message = "Xin chao {$toName},\n\nVui long truy cap link de xac minh email: {$verificationLink}\n\nNeu ban khong tao tai khoan, bo qua email nay.";
        $headers = 'From: ' . $this->config['from_name'] . ' <' . $this->config['from_email'] . "\r\n" .
                   'MIME-Version: 1.0' . "\r\n" .
                   'Content-type: text/plain; charset=utf-8' . "\r\n";

        return mail($toEmail, $subject, $message, $headers);
    }
}

$mailer = new Mailer($mailConfig);
