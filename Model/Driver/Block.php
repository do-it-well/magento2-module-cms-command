<?php
namespace DIW\CmsCommand\Model\Driver;

use DIW\CmsCommand\Model\AbstractDriver;

class Block extends AbstractDriver {
    protected $_factory;
    protected $_repository;

    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Cms\Model\BlockFactory $factory,
        \Magento\Cms\Api\BlockRepositoryInterface $repository
    ) {
        $this->_factory = $factory;
        $this->_repository = $repository;

        parent::__construct($searchCriteriaBuilder);
    }

    public function getEntryType(){ return 'block'; }
    protected function getCmsFactory(){ return $this->_factory; }
    protected function getCmsRepository(){ return $this->_repository; }
}
