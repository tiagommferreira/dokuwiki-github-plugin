<?php

/**
 * Created by PhpStorm.
 * User: josemiguelmelo
 * Date: 30/06/16
 * Time: 12:00
 */
class GitHubPullCommand extends GitHubCommand
{

    function execute()
    {
        return $this->github->pull($this->file);
    }
}