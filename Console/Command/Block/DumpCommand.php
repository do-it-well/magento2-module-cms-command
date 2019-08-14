<?php
namespace DIW\CmsCommand\Console\Command\Block;

use DIW\CmsCommand\Console\Command\AbstractDumpCommand;

class DumpCommand extends AbstractDumpCommand {
    protected $_driver;

    public function __construct(
        \DIW\CmsCommand\Model\Driver\Block $driver
    ) {
        $this->_driver = $driver;
        parent::__construct();
    }

    protected function getCmsDriver(){ return $this->_driver; }
}
