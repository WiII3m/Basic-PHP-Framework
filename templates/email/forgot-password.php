<?php
   namespace ForgotPasswordEmail;

   class ForgotPasswordEmail 
   {
      public static function subject()
      {
         return '🧐 Alors comme ça on oublie son mot de passe ?';
      }

      public static function body($resetToken, $email)
      {
         return "Mot de passe oublié ? Pas de panique, ça arrive, et c'est très facile d'en changer ! ( ouf ! ) <br><br>Vous pouvez maintenant changer votre mot de passe en cliquant <a href='https://play.willemverrier.fr/user/change-password/".$resetToken."/".$email."'>ici</a>.<br><br>Let the music play!&nbsp;🔊";
      }
   }