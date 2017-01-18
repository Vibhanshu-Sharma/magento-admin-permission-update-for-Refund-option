<?php

class Demac_AdminPermissions_Model_Observer
{

    /**
     * Before we save the role, let's include our restrict value
     *
     * @param $observer
     */
    public function saveRolesPermissions($observer)
    {
        $request = $observer->getEvent()->getRequest();
        $role = $observer->getEvent()->getObject();
        $restrictByRegion = (bool) $request->getParam('restrict_by_region');
        $role->setRestrictByRegion($restrictByRegion);
    }

    /**
     * Format and save the region restriction
     *
     * @param $observer
     */
    public function saveUserRegionRestriction($observer)
    {
        $user = $observer->getEvent()->getObject();
        if ($user->getRegionRestrictions()) {
            $regionRestrictions = implode(',', $user->getRegionRestrictions());
            $user->setRegionRestrictions($regionRestrictions);
        }
    }

    /**
     * Add filter to restrict orders by region
     *
     * @param $observer
     */
    public function filterOrdersByAdminRegionRestrictions($observer)
    {
        $user = Mage::getSingleton('admin/session')->getUser();
        $orderGridCollection = $observer->getEvent()->getOrderGridCollection();
        if ($user->getRegionRestrictions()) {
            $this->_filterByRegionRestriction($user, $orderGridCollection);
        }
    }

    /**
     * Add filter to restrict invoices by region
     *
     * @param $observer
     */
    public function filterInvoicesByAdminRegionRestrictions($observer)
    {
        $user = Mage::getSingleton('admin/session')->getUser();
        $invoiceGridCollection = $observer->getEvent()->getOrderInvoiceGridCollection();
        if ($user->getRegionRestrictions()) {
            $this->_filterByRegionRestriction($user, $invoiceGridCollection);
        }
    }

    /**
     * Add filter to restrict shipments by region
     *
     * @param $observer
     */
    public function filterShipmentsByAdminRegionRestrictions($observer)
    {
        $user = Mage::getSingleton('admin/session')->getUser();
        $shipmentGridCollection = $observer->getEvent()->getOrderShipmentGridCollection();
        if ($user->getRegionRestrictions()) {
            $this->_filterByRegionRestriction($user, $shipmentGridCollection);
        }
    }

    /**
     * Add filter to restrict credit memos by region
     *
     * @param $observer
     */
    public function filterCreditmemosByAdminRegionRestrictions($observer)
    {
        $user = Mage::getSingleton('admin/session')->getUser();
        $creditmemoGridCollection = $observer->getEvent()->getOrderCreditmemoGridCollection();
        if ($user->getRegionRestrictions()) {
            $this->_filterByRegionRestriction($user, $creditmemoGridCollection);
        }
    }

    /**
     * Add filter to restrict transactions / payments by region
     *
     * @param $observer
     */
    public function filterPaymentsByAdminRegionRestrictions($observer)
    {
        $user = Mage::getSingleton('admin/session')->getUser();
        $creditmemoGridCollection = $observer->getEvent()->getOrderCreditmemoGridCollection();
        if ($user->getRegionRestrictions()) {
            $this->_filterByRegionRestriction($user, $creditmemoGridCollection);
        }
    }

    /**
     * Apply the region filters for admin user
     *
     * @param $user
     * @param $collection
     * @return mixed
     */
    protected function _filterByRegionRestriction($user, $collection)
    {
        $regionRestrictions = explode(',', $user->getRegionRestrictions());
        $collection
            ->getSelect()
            ->distinct()
            ->join(array('oa' => 'sales_flat_order_address'), 'main_table.entity_id = oa.parent_id', array('region_id'));
        $collection->addFieldToFilter('oa.address_type', 'shipping');

        $orConditions = array();
        foreach ($regionRestrictions as $regionRestriction) {
            $orConditions[] = array(
                'eq'    => intval($regionRestriction)
            );
        }
        $collection
            ->addFieldToFilter(
                array('oa.region_id'),
                array(
                    $orConditions
                )
            );

        return $collection;
    }

}