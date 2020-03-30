<?php
   namespace UserRepository;

   use Exception;
   use RequestService\RequestService as Request;

   class UserRepository 
   {  
      /**
       * Check if user email already exist in database
       *
       * @param string $email - user email
       * 
       * @return boolean
       */ 
      private static function exist($email)
      {
         $request = new Request();
   
         $request->prepare("SELECT id from users WHERE email=:email");
            
         $results = $request->execute([
            ':email' => $email, 
         ]);
         
         return count($results) > 0;
      }

      /**
       * Save user into DB
       *
       * @param User $user - Waiting for user entity
       * 
       * @throws Exception
       * @return Array with success key (boolean)
       */ 
      public static function save($user)
      {
         if( self::exist($user->getEmail()) )
            throw new Exception("email_not_available", 409);
         
         $request = new Request();
   
         $request->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            
         $request = $request->execute([
            ':email' => $user->getEmail(), 
            ':password' => $user->getPassword()
         ]);
      }

      /**
       * Set rst password token for user
       *
       * @param User
       */
      static function putResetPasswordToken($user)
      {
         $request = new Request();
   
         $request->prepare("UPDATE users SET reset_password_token=:reset_password_token WHERE email =:email");
            
         $request = $request->execute([
            ':reset_password_token' => $user->getResetPasswordToken(),
            ':email' => $user->getEmail()
         ]);
      }

      /**
       * Set new user password with rst password token 
       *
       * @param user
       */
      static function putNewPassword($user)
      {
         $request = new Request();
   
         $request->prepare("UPDATE users SET password=:password WHERE reset_password_token=:reset_password_token AND email=:email");
            
         $request = $request->execute([
            ':email' => $user->getEmail(),
            ':reset_password_token' => $user->getResetPasswordToken(),
            ':password' => $user->getPassword()
         ]);

         $request = new Request();
   
         $request->prepare("UPDATE users SET reset_password_token = null WHERE email=:email");
            
         $request = $request->execute([
            ':email' => $user->getEmail(),
         ]);
      }
   }