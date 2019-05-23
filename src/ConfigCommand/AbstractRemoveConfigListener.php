<?php
/**
 * @see       https://github.com/phly/keep-a-changelog for the canonical source repository
 * @copyright Copyright (c) 2019 Matthew Weier O'Phinney
 * @license   https://github.com/phly/keep-a-changelog/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\KeepAChangelog\ConfigCommand;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;

abstract class AbstractRemoveConfigListener
{
    abstract public function configRemovalRequested(RemoveConfigEvent $event) : bool;

    abstract public function getConfigFile() : string;

    public function __invoke(RemoveConfigEvent $event) : void
    {
        if (! $this->configRemovalRequested($event)) {
            return;
        }

        $configFile = $this->getConfigFile();

        if (! file_exists($configFile)) {
            $event->configFileNotFound($configFile);
            return;
        }

        $output = $event->output();

        $output->writeln(sprintf(
            '<info>Found the following configuration file: %s</info>',
            $configFile
        ));

        $helper   = new QuestionHelper();
        $question = new ConfirmationQuestion('Do you really want to delete this file?', false);

        if (! $helper->ask($input, $output, $question)) {
            $event->abort($configFile);
            return;
        }

        if (false === unlink($configFile)) {
            $event->errorRemovingConfig($configFile);
            return;
        }

        $event->deletedConfigFile($configFile);
    }
}