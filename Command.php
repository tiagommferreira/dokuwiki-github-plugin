<?php

/**
 * Created by PhpStorm.
 * User: josemiguelmelo
 * Date: 30/06/16
 * Time: 12:04
 */
class Command
{
    public static function callCommand($command){
        return $command->execute();
    }
}