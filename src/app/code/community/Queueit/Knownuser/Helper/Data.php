<?php

class Queueit_Knownuser_Helper_Data extends Mage_Core_Helper_Abstract
{
    /** Cache Tag */
    const CACHE_TAG = 'QUEUEIT_CACHE';

    /** Cache Key */
    const CACHE_KEY = '_queueit_integrationinfo';

    /** Cache Identifier */
    const CACHE_IDENTIFIER = 'queueit';

    /** Cache Lifetime (5 min) */
    const CACHE_LIFETIME = 300;

    const XML_PATH_ENABLED = 'queueit_knownuser/general/enabled';
    const XML_PATH_CUSTOMER_ID = 'queueit_knownuser/general/customer_id';
    const XML_PATH_SECRET_KEY = 'queueit_knownuser/general/secretkey';
    const XML_PATH_UPDATE_CONFIG_METHOD = 'queueit_knownuser/general/update_config';


    /**
     * @param int|null $store_id
     *
     * @return bool
     */
    public function getIsEnabled($store_id = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store_id);
    }

    /**
     * @param int|null $store_id
     *
     * @return string
     */
    public function getCustomerId($store_id = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMER_ID, $store_id);
    }

    /**
     * @param int|null $store_id
     *
     * @return string
     */
    public function getSecretKey($store_id = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_SECRET_KEY, $store_id);
    }

    /**
     * @param int|null $store_id
     *
     * @return string
     */
    public function getUpdateConfigMethod($store_id = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_UPDATE_CONFIG_METHOD, $store_id);
    }


    /**
     * @param $integrationInfo
     * @param $hash
     *
     * @return bool
     */
    public function validateHash($integrationInfo, $hash)
    {
        $calculatedHash = hash_hmac('sha256', $integrationInfo, $this->getSecretKey());

        return $calculatedHash === $hash;
    }

    /**
     * @param bool $useCache
     *
     * @return bool|string
     */
    public function getIntegrationinfo($useCache = true)
    {

        $app = Mage::app();
        $info = false;
        if ($useCache && $app->useCache(self::CACHE_IDENTIFIER)) {
            $info = $app->loadCache(self::CACHE_KEY);
        }

        if (!$info) {
            /** @var Queueit_Knownuser_Model_Resource_Integrationinfo_Collection $collection */
            $collection = Mage::getResourceModel('queueit_knownuser/integrationinfo_collection')
                ->setPageSize(1)
                ->setOrder('id', Zend_Db_Select::SQL_DESC);

            $info = $collection->getFirstItem()->getInfo();
            $app->saveCache($info, self::CACHE_KEY, array(self::CACHE_TAG), self::CACHE_LIFETIME);
        }

        return hex2bin($info);
    }

    /**
     * Clean cache tag reference
     */
    public function cleanQueueitCache()
    {
        Mage::app()->cleanCache(array(self::CACHE_TAG));
    }
}
