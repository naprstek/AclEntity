<?php

namespace AclEntity\Annotation;

use Doctrine\ORM\Mapping\Annotation;

/**
 * Role annotation
 *
 * Allows passing element.
 * 
 * The value should be an associative array: {"rightName":{"roleName", ...}, ...}.
 *
 * @Annotation
 */
class Acl implements Annotation
{
    public $value;

    /**
     * Retrieve the options
     *
     * @return null|array
     */
    public function getAcl()
    {
        return $this->value;
    }
}
