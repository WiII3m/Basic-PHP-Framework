<?php
   
   header("Content-type: application/json");
   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Headers: Content-Type, origin, Authorization, accept, host, date");

   require "config.php";
   require "router.php";

   require "vendor/PHPMailer/src/Exception.php";
   require "vendor/PHPMailer/src/PHPMailer.php";
   require "vendor/PHPMailer/src/SMTP.php";

   require "entities/user.entity.php";

   require "validators/user.validator.php";

   require "repositories/user.repository.php";

   require "services/email.service.php";
   require "services/request.service.php";
   require "services/response.service.php";
   require "services/error.service.php";
   require "services/authConnect.service.php";

   require "templates/email/account-created.email.php";
   require "templates/email/forgot-password.php";
   require "templates/email/changed-password.php";

   require "controllers/user.controller.php";


   use Router\Router as Router;
   $router = new Router();
