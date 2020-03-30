<?php
   namespace UserController;
   
   use Exception;

   use UserEntity\UserEntity as User;
   use EmailService\EmailService as Email;
   use UserValidator\UserValidator as Validator;
   use UserRepository\UserRepository as Repository;
   use ResponseService\ResponseService as Response;

   use AccountCreatedEmail\AccountCreatedEmail;
   use ForgotPasswordEmail\ForgotPasswordEmail;
   use ChangedPasswordEmail\ChangedPasswordEmail;

   class UserController
   {
      /**
       * Create and save new user.
       *
       * @param array $params - [email, password]
       */ 
      static function create($params)
      {
         Validator::checkCreateParams($params);

         $user = new User();
         $user->setEmail($params['email']);
         $user->setPassword($params['password']);
         
         Repository::save($user);

         $email = new Email();
         $email->setRecipient($user->getEmail());
         $email->setSubject(AccountCreatedEmail::subject());
         $email->setBody(AccountCreatedEmail::body($params['email'], $params['password']));

         if( ! $email->send() )
            Response::ok('account_created_but_mail_not_send');
            
         Response::ok('account_created');
      }

      /**
       * Get user informations with Authorization token
       */ 
      static function me()
      {
         Response::ok($GLOBALS['user']);
      }

      /**
       * Set reset password token for an user with given email & send email with reset password token
       *
       * @param array $params - [email]
       */ 
      static function forgotPassword($params)
      {
         Validator::checkForgotPasswordParams($params);
         
         $user = new User();

         $user->setEmail($params['email']);
         $user->setResetPasswordToken( md5(time()*random_int(0, 1000000)) );

         Repository::putResetPasswordToken($user);

         $email = new Email();
         $email->setRecipient($user->getEmail());
         $email->setSubject(ForgotPasswordEmail::subject());
         $email->setBody(ForgotPasswordEmail::body($user->getResetPasswordToken(), $user->getEmail() ));

         if( ! $email->send() )
            throw new Exception("email_cannot_be_send");
         
         Response::ok('email_sended');       
      }

      /**
       * Change password for reset_password_token & send mail for confirm success.
       *
       * @param array $params - [reset_password_token,password, email].
       */ 
      static function changePassword($params)
      {
         Validator::checkChangePasswordParams($params);

         $user = new User();

         $user->setResetPasswordToken($params['reset_password_token']);
         $user->setPassword($params['password']);
         $user->setEmail($params['email']);
         
         Repository::putNewPassword($user);

         $email = new Email();
         $email->setRecipient($user->getEmail());
         $email->setSubject(ChangedPasswordEmail::subject());
         $email->setBody(ChangedPasswordEmail::body($params['password']));

         if( ! $email->send() )
            Response::ok('password_changed_but_mail_not_send');
            
         Response::ok('password_changed');
      }

   }
