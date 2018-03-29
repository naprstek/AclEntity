<?php

namespace AclEntity;

interface IEntity {
    /**
     * returns list of properties
     */
    public function getAclProperties(string $role, string $right) : array;
}