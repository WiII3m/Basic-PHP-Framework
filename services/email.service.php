<?php
   namespace EmailService;

   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;

   class EmailService
   {
      private $recipient;
      private $subject;
      private $body;

      public function setRecipient($recipient)
      {
         $this->recipient = $recipient;
      }

      public function setSubject($subject)
      {
         $this->subject = $subject;
      }

      public function setBody($body)
      {
         $this->body = $body;
      }

      public function getRecipient()
      {
         return $this->recipient;
      }

      public function getSubject()
      {
         return $this->subject;
      }

      public function getBody()
      {
         return $this->body;
      }

      public function send()
      {
         try 
         {
            global $smtp;

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host       = $smtp['host'];
            $mail->SMTPAuth   = true; 
            $mail->Username   = $smtp['username'];
            $mail->Password   = $smtp['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $smtp['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($smtp['from']['email'], $smtp['from']['name']);
            $mail->addAddress($this->recipient);

            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;

            $mail->send();

            return true;
         } 
         catch (Exception $e) 
         {  

            print_r($e);
            exit;
            return false;
         }
      }
   }