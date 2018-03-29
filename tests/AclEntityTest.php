<?php

namespace AclEntity\Tests;

use AclEntity\Tests\Entity\Element;

class AclEntityTest extends \PHPUnit\Framework\TestCase
{

    private $entity;

    public function setUp()
    {
        $this->entity = new Element();
    }

    public function testRead()
    {
        $elements = ['id', 'name'];
        $roles = $this->entity->getAclProperties('internal', 'view');
        $this->assertEquals($elements, $roles);
    }

    public function testWrite()
    {
        $elements = ['value'];
        $roles = $this->entity->getAclProperties('internal', 'edit');
        $this->assertEquals($elements, $roles);
    }

    public function testProperty()
    {
        $elements = ['id', 'name', 'value'];
        $props = $this->entity->getAllProperties();
        $this->assertEquals($elements, $props);
    }
}
