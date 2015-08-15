<?php

namespace Phpg\Application\Service;

class CliHelper
{
    /**
     * @return array
     */
    public static function extractArgumentsFrom(array $argv)
    {
        $arguments = array();

        foreach ($argv as $k => $arg) {
            if ($k == 1) {
                $arguments['task'] = $arg;
            } elseif ($k == 2) {
                $arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $arguments['params'][] = $arg;
            }
        }

        return $arguments;
    }
}

