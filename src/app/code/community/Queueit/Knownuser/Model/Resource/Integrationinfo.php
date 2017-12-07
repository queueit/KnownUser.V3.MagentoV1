<?php

class Queueit_Knownuser_Model_Resource_Integrationinfo extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Construct Resource Model
     */
    protected function _construct()
    {
        $this->_init('queueit_knownuser/integrationinfo', 'id');
    }
}