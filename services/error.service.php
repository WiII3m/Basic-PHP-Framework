<?php 
   namespace ErrorService;

   use ResponseService\ResponseService as Response;

   class ErrorService
   {
      public function __construct($message, $code) 
      {
         $this->message = $message;

         switch ($code) {
            case 401:
               Response::unauthorized();
               break;
            case 404:
               Response::notFound();
               break;
            case 405:
               Response::methodNotAllowed();
               break;
            case 409:
               Response::conflict($message);
               break;
            default:
               Response::internalServerError($message);
               break;
         }
      }
   }