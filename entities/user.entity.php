<?php
   namespace UserEntity;

   use Exception;
   use UserValidator\UserValidator as Validator;

   class UserEntity
   {
      public $id;
      public $email;
      private $password;
      private $reset_password_token;

      public function setId($id)
      {
         $this->id = $id;
      }

      public function setEmail($email)
      {
         $this->email = $email;
      }

      public function setPassword($password)
      {
         $this->password = md5($password);
      }

      public function setResetPasswordToken($reset_password_token)
      {
         $this->reset_password_token = $reset_password_token;
      }

      public function getId() 
      {
         return $this->id;
      }

      public function getEmail() 
      {
         return $this->email;
      }

      public function getPassword() 
      {
         return $this->password;
      }

      public function getResetPasswordToken() 
      {
         return $this->reset_password_token;
      }
   }
   