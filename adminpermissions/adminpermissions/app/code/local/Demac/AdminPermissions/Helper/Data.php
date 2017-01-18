<?php

class Demac_AdminPermissions_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Can we restrict the current admin user by region?
     *
     * @param $user
     * @return bool
     */
    public function canRestrictByRegion($user)
    {
        $roles = $user->getRoles();
        foreach ($roles as $roleId) {
            $role = Mage::getModel('admin/role')->load($roleId);
            if ($role && $role->getRestrictByRegion()) {
                return true;
            }
        }

        return false;
    }

}