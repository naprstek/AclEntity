<?php

namespace AclEntity\Tests\Entity;

use AclEntity\IEntity;
use AclEntity\Entity\Entity;
use AclEntity\Annotation\Acl;

class Element extends Entity implements IEntity {
    /**
     * @var integer
     * @Acl({"view":{"internal", "external"}, "edit":{}})
     */
    private $id;

    /**
     * @var string
     * @Acl({"view":{"internal"}, "edit":{"external"}})
     */
    private $name;

    /**
     * @var string
     * @Acl({"view":{"external"}, "edit":{"internal"}})
     */
    private $value;
}