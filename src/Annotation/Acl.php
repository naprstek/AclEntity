<?php

namespace AclEntity\Annotation;

use Zend\Form\Annotation\AbstractArrayAnnotation;

/**
 * Role annotation
 *
 * Allows passing element.
 * 
 * The value should be an associative array: {"rightName":{"roleName", ...}, ...}.
 *
 * @Annotation
 */
class Acl extends AbstractArrayAnnotation
{
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
