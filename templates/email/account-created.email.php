<?php
   namespace AccountCreatedEmail;

   class AccountCreatedEmail 
   {
      public static function subject()
      {
         return '🤩 Bienvenue sur Play';
      }

      public static function body($userEmail, $userPassword)
      {
         return "Bienvenue sur le service <a href='https://play.willemverrier.fr'>Play</a> <br><br> Vous pouvez maintenant vous connecter à l'aide des identifiants suivants :<br><br><u><b>Email:</u></b>&nbsp;&nbsp;".$userEmail."<br> <u><b>Mot de passe:</u></b>&nbsp;&nbsp;".$userPassword."<br><br>Let the music play!&nbsp;🔊";
      }
   }