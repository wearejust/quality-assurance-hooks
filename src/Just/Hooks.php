<?php

namespace Just;

use Composer\Script\Event;

class Hooks
{
    public static function checkHooks(Event $event)
    {
        $io = $event->getIO();
        $newPath = __DIR__.'/../../../../../.git/hooks/pre-commit';

        $gitHook = @file_get_contents($newPath);
        $docHook = @file_get_contents(__DIR__.'/../../../../../bin/pre-commit');

        if ($gitHook !== $docHook) {
            $io->write('<error>GIT hooks ontbreken</error>');
                exec(
                    'cd .git/hooks && '.
                    'ln -s ../../bin/pre-commit ./pre-commit'
                );
                copy(__DIR__ . '/../../.php_cs', __DIR__.'/../../../../../.php_cs');
                chmod($newPath, 0755);
        }
    }
}