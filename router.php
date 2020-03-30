<?php
   namespace Router;

   use ResponseService\ResponseService as Response;
   use ErrorService\ErrorService as Error;
   use UserController\UserController;
   use AuthConnectService\AuthConnectService as AuthConnect;
   use Exception;

   class Router
   { 
      
      private $routes;

      public function __construct() 
      {
         $this->routes = [ 
            '/user/create' => [
               'public' => true,
               'allowed_method' => 'POST',
               'callback' => function () { UserController::create($_POST); }
            ],
            '/user/forgot-password' => [
               'public' => true,
               'allowed_method' => 'POST',
               'callback' => function () { UserController::forgotPassword($_POST); }
            ],
            '/user/change-password' => [
               'public' => true,
               'allowed_method' => 'POST',
               'callback' => function () { UserController::changePassword($_POST); }
            ],
            '/user/me' => [
               'public' => false,
               'allowed_method' => 'GET',
               'callback' => function () { UserController::me(); } 
            ]
         ];

         try{
            $this->redirect();
         }
         catch(Exception $error){
            new Error($error->getMessage(), $error->getCode());
         }
      }

      private function checkRouteValidity()
      {
         $route_founded = array_key_exists($_SERVER['REQUEST_URI'], $this->routes);

         if( ! $route_founded )
            throw new Exception("not_found", 404);
            
         if( $_SERVER['REQUEST_METHOD'] == "OPTIONS" )
            Response::ok();

         $method_allowed = $this->routes[$_SERVER['REQUEST_URI']]['allowed_method'] == $_SERVER['REQUEST_METHOD'];

         if( ! $method_allowed )
            throw new Exception("not_allowed", 405);
      }

      private function redirect()
      {
         $this->checkRouteValidity();

         if( ! $this->routes[$_SERVER['REQUEST_URI']]['public'] )
            $GLOBALS['user'] = AuthConnect::getUser();

         $this->routes[$_SERVER['REQUEST_URI']]['callback']();            
      }

   }