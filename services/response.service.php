<?php

   namespace ResponseService;

   class ResponseService
   {
      static function getErrorMessage($message)
      {
         return $message;
      }

      /**
       * Return HTTP 200 response with json encoded response.
       *
       * @param Response Array $response Array to json return
       */ 
      public static function ok($response)
      {
         http_response_code(200);
         echo json_encode($response);
         die;
      }

      /**
       * Return HTTP 400 response with json encoded error message.
       *
       * @param Message String $message Error message to return
       */ 
      public static function badRequest($message)
      {
         http_response_code(400);
         echo json_encode(self::getErrorMessage($message));
         die;
      }

      /**
       * Return HTTP 401 response
       */ 
      public static function unauthorized()
      {
         http_response_code(401);
         echo json_encode(self::getErrorMessage('Unauthorized'));
         die;
      }

      /**
      * Return HTTP 404 response.
      */ 
      public static function notFound()
      {
         http_response_code(404);
         echo json_encode(self::getErrorMessage('not found'));
         die;
      }

      /**
      * Return HTTP 405 Method Not Allowed
      */ 
      public static function methodNotAllowed()
      {
         http_response_code(405);
         echo json_encode(self::getErrorMessage('Method Not Allowed'));
         die;
      }

      /**
      * Return HTTP 409,
      */ 
      public static function conflict($message)
      {
         http_response_code(409);
         echo json_encode(self::getErrorMessage($message));
         die;
      }

      /**
       * Return HTTP 500 response with json encoded error message.
       *
       * @param Message String $message Error message to return
       */ 
      public static function internalServerError($message)
      {
         http_response_code(500);
         echo json_encode(self::getErrorMessage($message));
         die;
      }

   }