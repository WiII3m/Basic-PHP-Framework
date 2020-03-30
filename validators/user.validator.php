<?php
	namespace UserValidator;

   use Exception;

   class UserValidator
   {
      /**
       * Check params for create new user
       *
       * @param Params array $params - Waiting for an array with email & password keys
       * 
       * @throws Exception
       * @return Boolean True
       */ 
   	static function checkCreateParams($params)
      {  
         if( !isset($params['email']) )
            throw new Exception('Params error : email is missing.');

         if( empty($params['email']) || ! filter_var($params['email'], FILTER_VALIDATE_EMAIL) )
            throw new Exception('Params error : email seems to be invalid.');
         
         if(  !isset($params['password'])  )
            throw new Exception('Params error : password is missing.');

         if( empty(trim($params['password'])) )
            throw new Exception('Params error : password seems to be invalid.');

         return true;
      }

      /**
       * Check auth params
       *
       * @throws Exception
       * @return Boolean True
       */
      static function checkAuthToken()
      {
         if( !isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) )
            throw new Exception('Authorization token is empty / not valid.');
         else
            return true; 
      }

      /**
       * Check params for forgot password
       *
       * @param Params array $params - Waiting for an array with email key
       * 
       * @throws Exception
       * @return Boolean True
       */ 
      static function checkForgotPasswordParams($params)
      {  
         if( !isset($params['email']) )
            throw new Exception('Params error : email is missing.');

         if( empty($params['email']) || ! filter_var($params['email'], FILTER_VALIDATE_EMAIL) )
            throw new Exception('Params error : email seems to be invalid.');
         
         return true;
      }

      /**
       * Check params for change password
       *
       * @param Params array $params - Waiting for an array with reset_password_token and password keys
       * 
       * @throws Exception
       * @return Boolean True
       */ 
      static function checkChangePasswordParams($params)
      {  
         if( !isset($params['reset_password_token']) || !isset($params['password']) || !isset($params['email']) )
            throw new Exception('Params error, waiting for : email, reset_password_token, password.');
         else
            return true;
      }
   }