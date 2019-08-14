<?php
namespace DIW\CmsCommand\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractDumpCommand extends Command
{
    abstract protected function getCmsDriver();

    protected function configure()
    {
        $this->setName('cms:'.$this->getCmsDriver()->getEntryType().':dump')
            ->setDescription('Output a CMS '.$this->getCmsDriver()->getEntryTypeName().' in a machine-readable format')
            ->setDefinition([
                new InputArgument(
                    'entry_id',
                    InputArgument::OPTIONAL,
                    'ID of '.$this->getCmsDriver()->getEntryTypeName().' to output (omit for "all")'
                )
            ]);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entryId = $input->getArgument('entry_id');
        if ($entryId !== NULL){
            if( ctype_digit($entryId.'') ){
                $entry = $this->getCmsDriver()->loadId(intval($entryId));
            } else {
                $entry = $this->getCmsDriver()->loadIdentifier($entryId);
            }

            $output->writeln(json_encode($entry->getData()));
        } else {
            $output->writeln('[');
            $previous = NULL;
            foreach( $this->getCmsDriver()->list() as $entry ){
                if( $previous !== NULL ) $output->writeln($previous.',');
                $previous = json_encode($entry->getData());
            }
            if( $previous !== NULL ) $output->writeln($previous);
            $output->writeln(']');
        }
    }
}
