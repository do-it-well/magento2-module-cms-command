<?php
namespace DIW\CmsCommand\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractLoadCommand extends Command
{
    abstract protected function getCmsDriver();

    protected function configure()
    {
        $this->setName('cms:'.$this->getCmsDriver()->getEntryType().':load')
            ->setDescription('Import a CMS '.ucfirst($this->getCmsDriver()->getEntryTypeName()).' from a JSON object, or JSON array of objects')
            ->setDefinition([
                new InputArgument(
                    'file',
                    InputArgument::OPTIONAL,
                    'Input file to read (defaults to STDIN)'
                )
            ]);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('file');
        if( $filename === null ){
            $encoded = file_get_contents('php://stdin');
        } else {
            $encoded = file_get_contents($filename);
        }

        $decoded = json_decode($encoded);
        if( is_array($decoded) ){
            $decoded = json_decode($encoded, TRUE);
            foreach( $decoded as $index => $data ){
                if( !is_array($data) ){
                    throw new \InvalidArgumentException("Error: input file's described array did not contain objects");
                }

                $this->_storeOne($data);
            }
        } else {
            $decoded = json_decode($encoded, TRUE);
            if (!is_array($decoded)) {
                throw new \InvalidArgumentException("Error: input file did not describe an object or array of objects");
            }

            $this->_storeOne($decoded);
        }
    }

    protected function _storeOne($data)
    {
        $id_attribute_name = $this->getCmsDriver()->getIdAttributeName();
        $entry = null;
        if( isset($data[$id_attribute_name]) ){
            $entry = $this->getCmsDriver()->loadId(intval($data[$id_attribute_name]));
        } elseif( isset($data['identifier']) ) {
            $entry = $this->getCmsDriver()->loadIdentifier($data['identifier']);
        }

        if ($entry === null) {
            $entry = $this->getCmsDriver()->create();
        }

        foreach ($data as $key => $value) {
            if ($key === $id_attribute_name) continue;
            $entry->setData($key, $value);
        }

        try {
            $entry->save();
        } catch(\Exception $e) {
            throw new \Exception("Failed to save (".$e->getMessage()."): ".print_r($data, TRUE));
        }
    }
}
