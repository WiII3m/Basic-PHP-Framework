<?php
   namespace UserRepository;

   use Exception;
   use RequestService\RequestService as Request;
   use UserEntity\UserEntity as User;
   use UserValidator\UserValidator as Validator;

   class UserAlredyExist extends Exception{}

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
            
         $request = $request->execute([
            ':email' => $email, 
         ]);

         if( ! $request['success'] )
            throw new Exception( $request['error'] );
         
         return count($request['results']) > 0;
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
            throw new UserAlredyExist("email_not_available");
         
         $request = new Request();
   
         $request->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            
         $request = $request->execute([
            ':email' => $user->getEmail(), 
            ':password' => $user->getPassword()
         ]);

         if( ! $request['success'] )
            throw new Exception( $request['error'] );

         return $user;
      }

      /**
       * Get User from Basic Authorization token from request Headers
       *
       * @return User
       */ 
      static function authConnect()
      {
         try 
         {
            Validator::checkAuthToken();
         }
         catch (Exception $error) 
         {
            throw new Exception($error->getMessage());
         }

         $user = new User;

         $user->setEmail($_SERVER['PHP_AUTH_USER']);
         $user->setPassword($_SERVER['PHP_AUTH_PW']);

         $request = new Request();

         $request->prepare("SELECT * FROM users WHERE email = ? AND password = ? ");

         $request = $request->execute([
            $user->getEmail(),
            $user->getPassword()
         ]);

         if( ! $request['success'] ) throw new Exception($request['error']);
         if( count($request['results']) == 0 ) return false;

         $user->setId($request['results'][0]['id']);

         return $user;
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

         if( ! $request['success'] )
            throw new Exception( $request['error'] );
         
         return true;
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

         if( ! $request['success'] )
            throw new Exception( $request['error'] );
         
         return true;
      }
   }