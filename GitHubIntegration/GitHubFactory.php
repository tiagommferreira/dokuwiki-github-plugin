<?php
class GitHubFactory
{
   public static function create($repos, $tokenString)
   {
       return new GitHubIntegration($repos, $tokenString);
   }
}
