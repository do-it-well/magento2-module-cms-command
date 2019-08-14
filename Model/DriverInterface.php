<?php
namespace DIW\CmsCommand\Model;

interface DriverInterface
{
    public function getEntryType();
    public function getEntryTypeName();
    public function getIdAttributeName();

    public function create();
    public function list();
    public function loadId(int $id);
    public function loadIdentifier(string $identifier);
}
