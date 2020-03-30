<?php
   namespace RequestService;

   use PDO;
   use PDOException;
   use ResponseService\ResponseService as Response;

   class RequestService
   {
      private $db;
      private $request;

      function __construct()
      {
         global $database;

         try 
         {
            $this->db = new PDO("mysql:dbname=$database[name];host=$database[host]", $database['user'], $database['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         } 
         catch (PDOException $e) 
         {
            Response::internalServerError('Database connection error');
         }
      }

      public function prepare($sql)
      {
         $this->request = $this->db->prepare($sql);
      }

      public function execute($array)
      {
         try
         {
            $this->request->execute($array);

            if ($this->request->columnCount () == 0)
               return ["success" => true];
            else
               return ["success" => true, "results" => $this->request->fetchAll(PDO::FETCH_ASSOC)];
         }
         catch (PDOException $error) 
         {
            return ["success" => false, "error" => $error->getmessage()];
         }
      }

      public function exec()
      {
         try
         {
            return $this->db->exec($this->request->queryString);
         }
         catch (PDOException $error) 
         {
            return ["success" => false, "error" => $error->getmessage()];
         }
      }
   }