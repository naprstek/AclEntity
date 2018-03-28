<?php

namespace AclEntity\Entity;

use AclEntity\Annotation\Acl as AE;
use AclEntity\IEntity;
use Zend\Code\Reflection\ClassReflection;
use Zend\Form\Annotation\AnnotationBuilder;

/**
 *
 */
abstract class Entity implements IEntity
{
    /**
     * generated ACL List [role -> [right -> [items]]]
     */
    protected $aclList = null;

    /**
     * constructor.
     * @param array $data parameters of entity, keys corresponds to property names, types are preserved
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    protected function parseAcl()
    {
        $this->aclList = [];

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
                if ($annotation instanceof AE) {
                    $acl = $annotation->getAcl();
                    foreach ($acl as $right => $roles) {
                        foreach ($roles as $role) {
                            $this->aclList[$role][$right][] = $property->getName();
                        }
                    }
                }
            }
        }
    }

    public function getItems(string $role, string $right): array
    {
        if (is_null($this->aclList)) {
            $this->parseAcl();
        }
        return $this->aclList[$role][$right] ?? [];
    }

    public function getAclList(): array
    {
        if (is_null($this->aclList)) {
            $this->parseAcl();
        }
        return $this->aclList;
    }
}
