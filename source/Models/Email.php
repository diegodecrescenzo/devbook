<?php

namespace Source\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    /** @var array */
    private $data;

    /** @var PHPMailer */
    private $mail;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new \stdClass();

        //setup
        $this->mail->isSMTP();
        $this->mail->setLanguage("br");
        $this->mail->isHTML(true);
        $this->mail->SMTPAuth = "true";
        $this->mail->SMTPSecure = "tls";
        $this->mail->CharSet = "utf-8";

        //auth
        $this->mail->Host = "smtp.sendgrid.net";
        $this->mail->Port = 587;
        $this->mail->Username = "apikey";
        $this->mail->Password = "";
    }

    /**
     * @param string $subject
     * @param string $message
     * @param string $toEmail
     * @param string $toName
     * @return Email
     */
    public function bootstrap(string $subject, string $message, string $toEmail, string $toName): Email
    {
        $this->data->subject = $subject;
        $this->data->message = $message;
        $this->data->toEmail = $toEmail;
        $this->data->toName = $toName;
        return $this;
    }

    /**
     * @param $fromEmail
     * @param $fromName
     * @return bool
     */
    public function send($fromEmail = "socialmediadevbook@gmail.com", $fromName = "DevBook Social Media"): bool
    {
        if (empty($this->data)) {
            echo "Erro ao enviar, favor verifique os dados";
            return false;
        }

        if (!filter_var($this->data->toEmail, FILTER_VALIDATE_EMAIL)) {
            echo "O e-mail de destinatário não é válido";
            return false;
        }

        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            echo "O e-mail de remetente não é válido";
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->message);
            $this->mail->addAddress($this->data->toEmail, $this->data->toName);
            $this->mail->setFrom($fromEmail, $fromName);

            $this->mail->send();
            return true;

        } catch (Exception $exception) {
           return $exception->getMessage();
        }
    }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }
}
