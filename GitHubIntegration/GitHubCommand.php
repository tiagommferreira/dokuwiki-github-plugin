<?php

/**
 * Created by PhpStorm.
 * User: josemiguelmelo
 * Date: 30/06/16
 * Time: 11:33
 */
abstract class GitHubCommand
{

    protected $github;

    protected $file;
    protected $commitMessage = null;
    protected $commitContent = null;
    protected $contentChanged = false;

    function __construct(GitHubIntegration $github, $file, $commitMessage = null, $commitContent = null, $contentChanged = false) {
        $this->github = $github;
        $this->file = $file;
        $this->commitMessage = $commitMessage;
        $this->commitContent = $commitContent;
        $this->contentChanged = $contentChanged;
    }

    abstract function execute();
}