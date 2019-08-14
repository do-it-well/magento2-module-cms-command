<?php
namespace DIW\CmsCommand\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractListCommand extends Command
{
    abstract protected function getCmsDriver();

    protected function configure()
    {
        $this->setName('cms:'.$this->getCmsDriver()->getEntryType().':list')
            ->setDescription('List CMS '.$this->getCmsDriver()->getEntryTypeName().'s')
            ->setDefinition([
                new InputOption(
                    'format',
                    'text',
                    InputOption::VALUE_REQUIRED,
                    'Output format, one of "text" (the default) or "json"'
                )
            ]);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $format = $input->getOption('format');
        if ($format === NULL) $format = 'text';
        if (!in_array($format, array('text', 'json'))) {
            throw new \InvalidArgumentException(sprintf('Error unknown output format: "%s"', $format));
        }

        switch ($format) {
            case 'json':
                $output->write("[");
                break;

            default:
                $output->writeln(implode("\t", array(
                    strtoupper($this->getCmsDriver()->getIdAttributeName()),
                    'IDENTIFIER',
                    'TITLE'
                )));
                break;
        }

        $id_attribute_name = $this->getCmsDriver()->getIdAttributeName();
        $count = 0;

        foreach ($this->getCmsDriver()->list() as $entry) {
            switch ($format) {
                case 'json':
                    $output->write(($count++ ? ',' : '').json_encode(array(
                        $id_attribute_name => $entry->getData($id_attribute_name),
                        'identifier' => $entry->getData('identifier'),
                        'title' => $entry->getData('title')
                    )));
                    break;

                default:
                    $output->writeln(implode("\t", array(
                        $entry->getData($id_attribute_name),
                        $entry->getData('identifier'),
                        $entry->getData('title')
                    )));
                    break;
            }
        }

        if ($format === 'json') {
            $output->writeln("]");
        }
    }
}
