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


  public function push($file, $commitMessage, $commitContent, $contentChanged = false){
    $path = substr($file, strrpos($file, '/data/pages/') + 12);
    $repoPath = $this->repos . $path;

    $data = [
      'message' => $commitMessage,
      'content' => base64_encode($commitContent),
    ];

    //if the content of the file is empty, the file has been deleted on the wiki, so delete it on the repo.
    if($commitContent == "") {
      $data = [
        'message' => $commitMessage,
        'sha' => $this->fileSHA
      ];

      $response = $this->api->delete($repoPath, $data);
      $this->fileSHA = null;

      return;

    }
    //if the file exists, update it, else create a new file
    else if($contentChanged && isset($this->fileSHA)) {
      $data["sha"] = $this->fileSHA;
    }

    $response = $this->api->put($repoPath, $data);

    $this->fileSHA = null;
  }


  public function pull($file){
    $path = substr($file, strrpos($file, '/data/pages/') + 12);
    $repoPath = $this->repos . $path;
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
}

