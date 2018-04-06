<?php

namespace AclEntity\Entity;

use AclEntity\Annotation\Acl;
use AclEntity\IEntity;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Zend\Code\Reflection\ClassReflection;

/**
 *
 */
abstract class Entity implements IEntity
{
    /**
     * generated ACL List [role -> [privillege -> [items]]]
     * Internal purpose only.
     */
    protected $_aclList = null;

    /**
     * constructor.
     * @param array $data parameters of entity, keys corresponds to property names, types are preserved
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    protected function parseAcl()
    {
        $this->_aclList = [];

        $doctrineAnnotationReader = new AnnotationReader();
        AnnotationRegistry::registerFile(__DIR__ . '/../Annotation/Acl.php');
        $reflection = new ClassReflection($this);
        foreach ($reflection->getProperties() as $property) {
            $annotations = $doctrineAnnotationReader->getPropertyAnnotations($property);
            foreach ($annotations as $annotation) {
                //only AclEntity\Annotation\Acl
                if ($annotation instanceof Acl) {
                    $acl = $annotation->getAcl();
                    $this->_aclList['__properties__'][] = $property->getName();
                    foreach ($acl as $privillege => $roles) {
                        foreach ($roles as $role) {
                            $this->_aclList[$role][$privillege][] = $property->getName();
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns list of entity properties conforms role and privillege annotations.
     *
     * @param string $role Name of role (used in annotations)
     * @param string $privillege Name of privillege (used in annotations)
     * @return array List of property names.
     */
    public function getAclProperties(string $role, string $privillege): array
    {
        if (is_null($this->_aclList)) {
            $this->parseAcl();
        }
        return $this->_aclList[$role][$privillege] ?? [];
    }

    /**
     * Returns list of all properties described with Acl annotation
     * @return array List of property names.
     */
    public function getAllProperties(): array
    {
        if (is_null($this->_aclList)) {
            $this->parseAcl();
        }
        return $this->_aclList['__properties__'];
    }
}
