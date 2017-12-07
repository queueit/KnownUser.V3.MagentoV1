<?php

/**
 * Class Queueit_Knownuser_Model_Integrationinfo
 *
 * @method getInfo() string;
 * @method setInfo(string $info);
 */
class Queueit_Knownuser_Model_Integrationinfo extends Mage_Core_Model_Abstract
{
    protected $_cacheTag = 'QUEUEIT_CACHE';

    /**
     * Construct Model
     */
    protected function _construct()
    {
        $this->_init('queueit_knownuser/integrationinfo');
    }
}