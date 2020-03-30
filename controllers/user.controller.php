<?php
   namespace UserController;
   
   use Exception;
   use UserEntity\UserEntity as User;
   use UserValidator\UserValidator as Validator;
   use UserRepository\UserRepository;
   use ResponseService\ResponseService as Response;
   use EmailService\EmailService as Email;
   use AccountCreatedEmail\AccountCreatedEmail;
   use ForgotPasswordEmail\ForgotPasswordEmail;
   use ChangedPasswordEmail\ChangedPasswordEmail;

   class UserController
   {
      /**
       * Create and save new user into DB.
       *
       * @param array $params - Waiting for an array with email & password keys.
       */ 
      static function create($params)
      {
         try{
            Validator::checkCreateParams($params);
         }
         catch(Exception $error){
            Response::badRequest($error->getMessage());
         }

         $user = new User();
         $user->setEmail($params['email']);
         $user->setPassword($params['password']);

         try
         {
            UserRepository::save($user);

            $email = new Email();
            $email->setRecipient($user->getEmail());
            $email->setSubject(AccountCreatedEmail::subject());
            $email->setBody(AccountCreatedEmail::body($params['email'], $params['password']));

            if( $email->send() )
               Response::ok('account_created');
            else 
               Response::ok('account_created_but_mail_not_send');
         }
         catch(Exception $error)
         {
            switch (get_class($error)) {
               case 'UserRepository\UserAlredyExist':
                  Response::conflict($error->getMessage());
                  break;
               default:
                  Response::internalServerError($error->getMessage());
                  break;
            }
         }
      }

      /**
       * Get user informations with Authorization token
       */ 
      static function me()
      {
         Response::ok($GLOBALS['user']);
      }

      /**
       * Send email for set new password 
       *
       * @param array $params - Waiting for an array with email key.
       */ 
      static function forgotPassword($params)
      {
         try{
            Validator::checkForgotPasswordParams($params);
         }
         catch(Exception $error) {
            Response::badRequest($error->getMessage());
         }

         $user = new User();

         $user->setEmail($params['email']);
         $user->setResetPasswordToken( md5(time()*random_int(0, 1000000)) );

         try{
            UserRepository::putResetPasswordToken($user);

            $email = new Email();
            $email->setRecipient($user->getEmail());
            $email->setSubject(ForgotPasswordEmail::subject());
            $email->setBody(ForgotPasswordEmail::body($user->getResetPasswordToken(), $user->getEmail() ));

            if( ! $email->send() )
               Response::internalServerError('cannot_send_email');

            Response::ok('email_sended');  
         }
         catch(Exception $error){
            Response::internalServerError($error->getMessage());
         }         
      }

      /**
       * Change password for reset_password_token + send mail for confirm success.
       *
       * @param array $params - Waiting for an array with reset_password_token & password keys.
       */ 
      static function changePassword($params)
      {
         try
         {
            Validator::checkChangePasswordParams($params);
         }
         catch(Exception $error) 
         {
            Response::badRequest($error->getMessage());
         }

         $user = new User();

         $user->setResetPasswordToken($params['reset_password_token']);
         $user->setPassword($params['password']);
         $user->setEmail($params['email']);
            
         try{
            UserRepository::putNewPassword($user);

            $email = new Email();
            $email->setRecipient($user->getEmail());
            $email->setSubject(ChangedPasswordEmail::subject());
            $email->setBody(ChangedPasswordEmail::body($params['password']));

            if( ! $email->send() )
               Response::ok('password_changed_email_not_send');

            Response::ok('password_changed');  
         }
         catch(Exception $error){
            Response::internalServerError($error->getMessage());
         }  
      }

   }
