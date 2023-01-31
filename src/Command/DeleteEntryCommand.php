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
 * Command class to delete an entry in a keychain
 *
 * @since  2.0.0
 */
class DeleteEntryCommand extends AbstractKeychainCommand
{
    /**
     * The default command name
     *
     * @var    string|null
     * @since  2.0.0
     */
    protected static $defaultName = 'keychain:delete-entry';

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
        $symfonyStyle->title('Delete Keychain Entry');

        $entryName = $input->getArgument('entry-name');

        if (!$this->keychain->exists($entryName)) {
            $symfonyStyle->note(
                sprintf(
                    'There is no entry in the keychain with the key `%s`.',
                    $entryName
                )
            );

            return 0;
        }

        $this->keychain->remove($entryName);

        if (!$this->saveKeychain()) {
            $symfonyStyle->error('The entry was not removed from the keychain.');

            return 1;
        }

        $symfonyStyle->success('The entry was removed from the keychain.');

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

        $this->setDescription('Deletes an entry in the keychain');

        $this->addArgument(
            'entry-name',
            InputArgument::REQUIRED,
            'The key to remove from the keychain'
        );
    }
}
