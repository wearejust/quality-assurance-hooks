<?php

namespace Just;

use Composer\Script\Event;

class Hooks
{
    /**
     * @param Event $event
     * @return null
     */
    public static function checkHooks(Event $event)
    {
        if (!$event->isDevMode()) {
            return null;
        }

        $io = $event->getIO();
        $newPath = __DIR__.'/../../../../../.git/hooks/pre-commit';

        $gitHook = @file_get_contents($newPath);
        
        $docHookPath = __DIR__.'/../../../../../';
        $docHookFile = $docHookPath . 'bin/pre-commit';
        
        if(!file_exists($docHookPath . 'bin/pre-commit')){
            $docHookFile = $docHookPath . 'laravel/bin/pre-commit';
        }
        
        $docHook = @file_get_contents($docHookFile);

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