<?php
namespace DIW\CmsCommand\Model;

use DIW\CmsCommand\Model\DriverInterface;

abstract class AbstractDriver implements DriverInterface {
    protected $_searchCriteriaBuilder;

    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    abstract public function getEntryType();
    abstract protected function getCmsFactory();
    abstract protected function getCmsRepository();

    public function getEntryTypeName(){ return ucfirst($this->getEntryType()); }
    public function getIdAttributeName(){ return $this->getEntryType().'_id'; }

    public function create()
    {
        return $this->getCmsFactory()->create();
    }

    public function list()
    {
        $searchCriteria = $this->_searchCriteriaBuilder->create();
        return $this->getCmsRepository()->getList($searchCriteria)->getItems();
    }

    public function loadId(int $id)
    {
        $id_attribute_name = $this->getIdAttributeName();
        $searchCriteria = $this->_searchCriteriaBuilder->addFilter($id_attribute_name, $id, 'eq')->create();
        $entries = $this->getCmsRepository()->getList($searchCriteria)->getItems();
        foreach( $entries as $entry ){
            return $entry;
        }

        return null;
    }

    public function loadIdentifier(string $identifier)
    {
        $searchCriteria = $this->_searchCriteriaBuilder->addFilter('identifier', $identifier, 'eq')->create();
        $entries = $this->getCmsRepository()->getList($searchCriteria)->getItems();

        if (count($entries) > 1) {
            throw new \Exception('Unable to load '.$this->getEntryTypeName().": identifier '".$identifier."' is ambiguous");
        }

        foreach( $entries as $entry ){
            return $entry;
        }

        return null;
    }
}
