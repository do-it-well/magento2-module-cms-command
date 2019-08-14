<?php
namespace DIW\CmsCommand\Console\Command\Page;

use DIW\CmsCommand\Console\Command\AbstractLoadCommand;

class LoadCommand extends AbstractLoadCommand {
    protected $_driver;

    public function __construct(
        \DIW\CmsCommand\Model\Driver\Page $driver
    ) {
        $this->_driver = $driver;
        parent::__construct();
    }

    protected function getCmsDriver(){ return $this->_driver; }
}
