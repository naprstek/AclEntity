<?php

namespace AclEntity\Entity;

use AclEntity\Annotation\Acl;
use AclEntity\IEntity;
use Zend\Code\Reflection\ClassReflection;
use Zend\Form\Annotation\AnnotationBuilder;

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

        $builder = new AnnotationBuilder();
        $parser = $builder->getAnnotationParser();
        $parser->registerAnnotation("AclEntity\Annotation\Acl");
        $annotationManager = $builder->getAnnotationManager();
        $annotationManager->attach($parser);

        $reflection = new ClassReflection($this);
        foreach ($reflection->getProperties() as $property) {
            $annotations = $property->getAnnotations($annotationManager);
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
