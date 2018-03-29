# AclEntity
Support for entities annotated for ACL.

To install this library, run the command below and you will get the latest
version:

```sh
composer require naprstek/acl-entity
```

# Usage

Create data entity this way:
```php
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
```

For property id: role "internal" and "external" has right "view" and no role has right "edit" (you can ommit this definition, but this way is more describing).

Then in your ACL (based on Zend\Permissions\Acl\Acl for example) you define:

```php
//Roles
$this->addRole(new Role('internal'));
$this->addRole(new Role('external'));

//Resources: it is our data propertis
$my = new Element();
foreach($my->getAllProperties() as $resource) {
    $this->addResource(new Resource($resource));
}

//define privilleges
$this->allow('internal', $my->getAclProperties('internal', 'view'), 'view');
$this->allow('internal', $my->getAclProperties('internal', 'edit'), 'edit');
$this->allow('external', $my->getAclProperties('external', 'view'), 'view');
$this->allow('external', $my->getAclProperties('external', 'edit'), 'edit');
```
