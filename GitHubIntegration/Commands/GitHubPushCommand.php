<?php


class GitHubPushCommand extends GitHubCommand
{

    function execute()
    {
        $data = [
            'message' => $this->commitMessage,
            'content' => base64_encode($this->commitContent)
        ];

        //if the content of the file is empty, the file has been deleted on the wiki, so delete it on the repo.
        if($this->commitContent == "") {
            $data = [
                'message' => $this->commitMessage,
                'sha' => $this->github->getFileSHA()
            ];
            $response = $this->github->delete($this->file, $data);
            return;
        }
        //if the file exists, update it, else create a new file
        else if($this->contentChanged && $this->github->hasFileSHA() ) {
            $data["sha"] = $this->github->getFileSHA();
        }

        $response = $this->github->push($this->file, $data);

        $this->github->setFileSHA(null);
    }
}