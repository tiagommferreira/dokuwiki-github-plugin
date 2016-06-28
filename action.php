<?php
/**
 * DokuWiki Plugin github (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Tiago Ferreira <tiagommferreira55@gmail.com>
 */

require 'github-api-1.4.3/src/github-api.php';
use Milo\Github;

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();


class action_plugin_github extends DokuWiki_Action_Plugin {

    private $token;
    private $api;
    private $fileSHA;

    public function __construct() {
      //CHANGE TOKEN - https://github.com/settings/tokens
      $this->token = new Milo\Github\OAuth\Token('de4ffd52b5ebfc4675f46c8d49c5bbb2e3b05c5a');
      $this->api = new Github\Api;
      $this->api->setToken($this->token);
    }

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller) {

       $controller->register_hook('COMMON_WIKIPAGE_SAVE', 'BEFORE', $this, 'handle_common_wikipage_save');
       $controller->register_hook('IO_WIKIPAGE_READ', 'AFTER', $this, 'handle_wikipage_read');

    }

    /**
     * [Custom event handler which performs action]
     *s
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */

    public function handle_common_wikipage_save(Doku_Event &$event, $param) {
      $commitMessage = $event->data["summary"];
      $commitContent = $event->data["newContent"];

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

        $response = $this->api->delete('/repos/tiagommferreira/asso-test-2/contents/cenas.txt', $data);
        $this->fileSHA = null;

        return;

      }
      //if the file exists, update it, else create a new file
      else if($event->data["contentChanged"] && isset($this->fileSHA)) {

        $data["sha"] = $this->fileSHA;

      }

      $response = $this->api->put('/repos/tiagommferreira/asso-test-2/contents/cenas.txt', $data);

      $this->fileSHA = null;

    }

    public function handle_wikipage_read(Doku_Event &$event, $param) {

      try {
        $response = $this->api->get('/repos/tiagommferreira/asso-test-2/contents/cenas.txt');
        $file = $this->api->decode($response);
        $content = base64_decode($file->content);

        $this->fileSHA = $file->sha;

        $event->result = $content;
      }
      catch(Exception $e) {

      }


    }


}

// vim:ts=4:sw=4:et:
