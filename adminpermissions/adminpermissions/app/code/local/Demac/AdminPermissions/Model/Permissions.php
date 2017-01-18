<?php

class Demac_AdminPermissions_Model_Permissions extends Mage_Core_Model_Abstract
{

    /**
     * Get all of the admin user region restrictions if they exist
     *
     * @param bool $user
     * @return array|bool
     */
    public function getRegionRestrictions($user = false)
    {
        if (!$user) {
            $user = Mage::getSingleton('admin/session')->getUser();
        }
        if (!$user) {
            return false;
        }

        $roles = $user->getRoles();
        $totalRestrictions = array();
        foreach ($roles as $roleId) {
            $role = Mage::getModel('admin/role')->load($roleId);
            if ($role && $role->getRestrictByRegion()) {
                if ($regionRestrictions = $user->getRegionRestrictions()) {
                    foreach (explode(',', $regionRestrictions) as $regionRestriction) {
                        $totalRestrictions[] = $regionRestriction;
                    }
                }
            }
        }
        $totalRestrictions = array_unique($totalRestrictions);

        if (empty($totalRestrictions)) {
            return false;
        }

        return $totalRestrictions;
    }

}