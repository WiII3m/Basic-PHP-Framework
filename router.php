<?php
   namespace Router;

   use ResponseService\ResponseService as Response;
   use UserController\UserController as User;
   use UserRepository\UserRepository;

   class Router
   { 
      private $routes;

      public function __construct() 
      {
         $this->routes = [ 
            '/user/create' => [
               'public' => true,
               'allowed_method' => 'POST',
               'callback' => function () { User::create($_POST); }
            ],
            '/user/forgot-password' => [
               'public' => true,
               'allowed_method' => 'POST',
               'callback' => function () { User::forgotPassword($_POST); }
            ],
            '/user/change-password' => [
               'public' => true,
               'allowed_method' => 'POST',
               'callback' => function () { User::changePassword($_POST); }
            ],
            '/user/me' => [
               'public' => false,
               'allowed_method' => 'GET',
               'callback' => function () { User::me(); } 
            ]
         ];
      }

      public function listen()
      {         
         $route_founded = array_key_exists($_SERVER['REQUEST_URI'], $this->routes);
         $method_allowed = false;

         if( $route_founded )
            $method_allowed = $this->routes[$_SERVER['REQUEST_URI']]['allowed_method'] == $_SERVER['REQUEST_METHOD'];

         if( $route_founded && $method_allowed )
         {
            if( ! $this->routes[$_SERVER['REQUEST_URI']]['public'] )
            {
               $GLOBALS['user'] = UserRepository::authConnect();

               if( ! $GLOBALS['user'] ) 
                  Response::unauthorized();
            }
            
            $this->routes[$_SERVER['REQUEST_URI']]['callback']();
         }
         else if( ! $route_founded ){
            Response::notFound();
         } 
         // else if( $route_founded && ! $method_allowed ){
         //    Response::methodNotAllowed();
         // }
      }

   }