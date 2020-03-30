<?php
	namespace UserValidator;

   use Exception;

   class UserValidator
   {
      /**
       * Check params for create new user
       *
       * @param array $params - [email, password]
       * 
       * @throws Exception
       */ 
   	static function checkCreateParams($params)
      {  
         if( ! isset($params['email']) )
            throw new Exception('email is missing.', 400);

         if( empty($params['email']) || ! filter_var($params['email'], FILTER_VALIDATE_EMAIL) )
            throw new Exception('email seems to be invalid.', 400);
         
         if(  ! isset($params['password'])  )
            throw new Exception('password is missing.', 400);

         if( empty(trim($params['password'])) )
            throw new Exception('password seems to be invalid.', 400);
      }

      /**
       * Check auth params
       *
       * @throws Exception
       */
      static function checkAuthToken()
      {
         if( ! isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) )
            throw new Exception('Basic Authorization token not valid.', 400);
      }

      /**
       * Check params for forgot password
       *
       * @param array $params - [email]
       * 
       * @throws Exception
       */ 
      static function checkForgotPasswordParams($params)
      {  
         if( ! isset($params['email']) )
            throw new Exception('Email is missing.', 400);

         if( empty($params['email']) || ! filter_var($params['email'], FILTER_VALIDATE_EMAIL) )
            throw new Exception('Email seems to be invalid.', 400);
      }

      /**
       * Check params for change password
       *
       * @param array $params - [reset_password_token, password, email]
       * 
       * @throws Exception
       */ 
      static function checkChangePasswordParams($params)
      {  
         if( ! isset($params['reset_password_token']) )
            throw new Exception('reset_password_token is missing.', 400);

         if( ! isset($params['password'])  )
            throw new Exception('password is missing', 400);
         
         if( empty($params['email']) || ! filter_var($params['email'], FILTER_VALIDATE_EMAIL) )
            throw new Exception('email seems to be invalid.', 400);
      }
   }