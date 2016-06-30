<?php

use Milo\Github;


class GitHubIntegration
{
  private $token;
  private $api;
  private $fileSHA;
  private $repos;


  public function __construct($repos, $tokenString)
  {
    $this->token = new Milo\Github\OAuth\Token($tokenString);
    $this->api = new Github\Api;
    $this->api->setToken($this->token);
    $this->repos = $repos;
  }

  public function hasFileSHA(){
    return isset($this->fileSHA);
  }

  public function getFileSHA(){
    return $this->fileSHA;
  }

  public function setFileSHA($null)
  {
    $this->fileSHA = null;
  }


  
  /* GITHUB METHODS */

  public function delete($file, $data){
      $path = $this->getReposPathToFile($file);
      return $this->api->delete($path, $data);
  }

  public function push($file, $data){
      $path = $this->getReposPathToFile($file);
      return $this->api->put($path, $data);
  }
  
  public function pull($file){
    $repoPath = $this->getReposPathToFile($file);
    try {
      $response = $this->api->get($repoPath);
      $file = $this->api->decode($response);
      $content = base64_decode($file->content);

      $this->fileSHA = $file->sha;

      return $content;
    }
    catch(Exception $e) {
      return "";
    }
  }


  /* AUXILIARY METHODS */

  public function getPathToFile($file){
      return substr($file, strrpos($file, '/data/pages/') + 12);
  }

  public function getReposPathToFile($file){
      $path = $this->getPathToFile($file);
      return $this->repos . $path;
  }


}

