<?php

namespace MVCTheme\Traits;

trait MVCRoleTrait {
    private $roles = [];

    public function addRole($roleName, $displayName, $capabilities) {
        $this->roles[] = [
            "roleName" => $roleName,
            "displayName" => $displayName,
            "capabilities" => $capabilities,
        ];
    }

    public function initRoles() {
        foreach ($this->roles as $role) {
            add_role($role["roleName"], $role["displayName"], $role["capabilities"]);
        }
    }

    public function removeRole() {
        foreach ($this->roles as $role) {
            remove_role($role["roleName"]);
        }
    }
}