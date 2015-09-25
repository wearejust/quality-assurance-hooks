<?php

namespace Just;

use Composer\Script\Event;

class Hooks
{
    public static function checkHooks(Event $event)
    {
        $io = $event->getIO();
        $gitHook = @file_get_contents(__DIR__.'/../.git/hooks/pre-commit');
        $docHook = @file_get_contents(__DIR__.'/../bin/pre-commit');

        if ($gitHook !== $docHook) {
            $io->write('<error>GIT hooks ontbreken</error>');
                exec(
                    'cd .git/hooks && '.
                    'ln -s ../bin/pre-commit ./hooks/pre-commit'
                );
        }
    }
}