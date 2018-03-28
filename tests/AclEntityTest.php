<?php

namespace AclEntity\Tests;

use AclEntity\Tests\Entity\Element;

class AclEntityTest extends \PHPUnit\Framework\TestCase
{

    private $entity;

    public function setUp()
    {
        $this->entity = new Element([]);
    }

    public function testAcl()
    {
        $aclList = $this->entity->getAclList();
        $this->assertGreaterThan(0, count($aclList));
    }

    public function testRead()
    {
        $elements = ['id', 'name'];
        $roles = $this->entity->getItems('internal', 'view');
        $this->assertEquals($elements, $roles);
    }

    public function testWrite()
    {
        $elements = ['value'];
        $roles = $this->entity->getItems('internal', 'edit');
        $this->assertEquals($elements, $roles);
    }
}
