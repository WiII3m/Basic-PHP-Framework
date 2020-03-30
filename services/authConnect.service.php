<?php
   namespace AuthConnectService;

   use Exception;
   use RequestService\RequestService as Request;
   use UserEntity\UserEntity as User;
   use UserValidator\UserValidator as Validator;

   class AuthConnectService
   {
      public static function getUser() 
      {
         Validator::checkAuthToken();

         $user = new User;

         $user->setEmail($_SERVER['PHP_AUTH_USER']);
         $user->setPassword($_SERVER['PHP_AUTH_PW']);

         $request = new Request();

         $request->prepare("SELECT * FROM users WHERE email=:email AND password=:password");

         $results = $request->execute([
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword()
         ]);

         if( count($results) == 0 )
            throw new Exception('unauthorized', 401);

         $user->setId($results[0]['id']);

         return $user;
      }
   }
