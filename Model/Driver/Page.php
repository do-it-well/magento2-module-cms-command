<?php
namespace DIW\CmsCommand\Model\Driver;

use DIW\CmsCommand\Model\AbstractDriver;

class Page extends AbstractDriver {
    protected $_factory;
    protected $_repository;

    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Cms\Model\PageFactory $factory,
        \Magento\Cms\Api\PageRepositoryInterface $repository
    ) {
        $this->_factory = $factory;
        $this->_repository = $repository;

        parent::__construct($searchCriteriaBuilder);
    }

    public function getEntryType(){ return 'page'; }
    protected function getCmsFactory(){ return $this->_factory; }
    protected function getCmsRepository(){ return $this->_repository; }
}
