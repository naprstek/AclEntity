<?php

namespace AclEntity;

interface IEntity {
    /**
     * returns list of properties
     */
    public function getItems(string $role, string $right) : array;
}