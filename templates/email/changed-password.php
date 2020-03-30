<?php
   namespace ChangedPasswordEmail;

   class ChangedPasswordEmail 
   {
      public static function subject()
      {
         return "😎 C'est tout bon !";
      }

      public static function body($password)
      {
         return "Et voilà, vous avez changé votre mot de passe.<br><br>Vous pouvez maintenant vous connecter à play avec le mot de passe ' ".$password." ' .<br><br>Let the music play!&nbsp;🔊";
      }
   }