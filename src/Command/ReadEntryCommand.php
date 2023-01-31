<?php

/**
 * Part of the Joomla Framework Keychain Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/lgpl-2.1.txt GNU Lesser General Public License Version 2.1 or Later
 */

namespace Joomla\Keychain\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command class to read a single entry from a keychain
 *
 * @since  2.0.0
 */
class ReadEntryCommand extends AbstractKeychainCommand
{
    /**
     * The default command name
     *
     * @var    string|null
     * @since  2.0.0
     */
    protected static $defaultName = 'keychain:read-entry';

    /**
     * Internal function to execute the command.
     *
     * @param   InputInterface   $input   The input to inject into the command.
     * @param   OutputInterface  $output  The output to inject into the command.
     *
     * @return  integer  The command exit code
     *
     * @since   2.0.0
     */
    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Read Keychain Entry');

        $entryName = $input->getArgument('entry-name');

        if (!$this->keychain->exists($entryName)) {
            $symfonyStyle->warning(
                sprintf(
                    'There is no entry in the keychain with the key `%s`.',
                    $entryName
                )
            );

            return 1;
        }

        $symfonyStyle->table(['Key', 'Value'], [[$entryName, $this->keychain->get($entryName)]]);

        return 0;
    }

    /**
     * Configure the command.
     *
     * @return  void
     *
     * @since   2.0.0
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Reads a single entry in the keychain');

        $this->addArgument(
            'entry-name',
            InputArgument::REQUIRED,
            'The key to read from the keychain'
        );
    }
}
