<?php
/**
 * DokuWiki Plugin github (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Tiago Ferreira <tiagommferreira55@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_github extends DokuWiki_Action_Plugin {

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller) {

       $controller->register_hook('COMMON_WIKIPAGE_SAVE', 'BEFORE', $this, 'handle_common_wikipage_save');
       $controller->register_hook('IO_WIKIPAGE_READ', 'BEFORE', $this, 'handle_wikipage_read');

    }

    /**
     * [Custom event handler which performs action]
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */

    public function handle_common_wikipage_save(Doku_Event &$event, $param) {
      //die(var_dump($event->data["newContent"]));
    }

    public function handle_wikipage_read(Doku_Event &$event, $param) {
      die(var_dump($event));
    }




}

// vim:ts=4:sw=4:et:
