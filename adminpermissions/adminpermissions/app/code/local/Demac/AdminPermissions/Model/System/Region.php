<?php

class Demac_AdminPermissions_Model_System_Region extends Varien_Object
{

    protected function _getRegionCollection()
    {
        $countryId = Mage::helper('core')->getDefaultCountry();
        return Mage::getModel('directory/region')->getResourceCollection()
            ->addCountryFilter($countryId)
            ->load();
    }

    /**
     * Retrieve region values for form
     *
     * @param bool $empty
     * @param bool $all
     * @return array
     */
    public function getRegionValuesForForm()
    {
        $options = array();
        $regions = $this->_getRegionCollection();

        foreach ($regions as $region) {
            $options[] = array(
                'label' => $region->getDefaultName(),
                'value' => $region->getRegionId()
            );
        }

        return $options;
    }
}