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
        $io = $event->getIO();
        if (!$event->isDevMode()) {
            return null;
        }

        $projectRoot = realpath(__DIR__ . '/../../../../../');
        $packageHook = $projectRoot . 'bin/pre-commit';
        if (strpos($projectRoot, 'laravel') !== false) {
            $projectRoot = realpath($projectRoot . '/../');
            $packageHook = $projectRoot . '/laravel/bin/pre-commit';
        }

        $hooksDir = realpath($projectRoot . '/.git/hooks/');
        $newHook  = $hooksDir . '/pre-commit';

        if (@file_get_contents($newHook) !== @file_get_contents($packageHook)) {
            $io->write('<comment>GIT hooks are missing</comment>');
            $symlink = symlink($packageHook, $newHook);

            if (false === $symlink) {
                $io->write('<error>Failed to symlink the GIT hooks</error>');

                return null;
            }

            chmod($newHook, 0755);

            $defaultPhpCSFile = realpath(__DIR__ . '/../../.php_cs.example');
            $newPhpCSFile     = $projectRoot . '/.php_cs';

            copy($defaultPhpCSFile, $newPhpCSFile);
        }

        $io->write('<info>GIT hooks are succesfully installed</info>');
    }
}