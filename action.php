<?php
/**
 * DokuWiki Plugin github (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Tiago Ferreira <tiagommferreira55@gmail.com>
 */

require 'github-api-1.4.3/src/github-api.php';
require 'GitHubIntegration/GitHubFactory.php';
require 'GitHubIntegration/GitHubIntegration.php';
require 'GitHubIntegration/GitHubCommand.php';
require 'GitHubIntegration/Commands/GitHubPullCommand.php';
require 'GitHubIntegration/Commands/GitHubPushCommand.php';
require 'Command.php';

use Milo\Github;

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();


class action_plugin_github extends DokuWiki_Action_Plugin {

    private $github;

    public function __construct() {
      //CHANGE TOKEN - https://github.com/settings/tokens
      $this->github = GitHubFactory::create("/repos/tiagommferreira/asso-test-2/contents/", 'e83ee95dbfcf8ce6923e7be78a3bbfbca0deeb9b');
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
        $pushCommand = new GitHubPushCommand($this->github, $event->data["file"],
                                             $event->data["summary"], $event->data["newContent"],
                                             $event->data["contentChanged"]);
        Command::callCommand($pushCommand);
    }

    public function handle_wikipage_read(Doku_Event &$event, $param) {
        $pullCommand = new GitHubPullCommand($this->github, $event->data[0][0]);
        $event->result = Command::callCommand($pullCommand);
    }


}

// vim:ts=4:sw=4:et:
