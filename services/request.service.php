<?php
   namespace RequestService;

   use PDO;

   class RequestService
   {
      private $db;
      private $request;

      function __construct()
      {
         global $database;

         $this->db = new PDO("mysql:dbname=$database[name];host=$database[host]", $database['user'], $database['password']);
         $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }

      public function prepare($sql)
      {
         $this->request = $this->db->prepare($sql);
      }

      public function execute($array)
      {
         $this->request->execute($array);

         if ( $this->request->columnCount () != 0 )
            return $this->request->fetchAll(PDO::FETCH_ASSOC);            
      }
   }