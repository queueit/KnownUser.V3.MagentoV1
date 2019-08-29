<?php

class Queueit_Knownuser_IntegrationinfoController extends Mage_Core_Controller_Front_Action
{
    /**
     * Update the latest integration information
     */
    public function updateAction()
    {
        $requestJson = Mage::helper('core')->jsonDecode($this->getRequest()->getRawBody());
        if ($requestJson) {
            $integrationInfo = $requestJson['integrationInfo'];
            $hash = $requestJson['hash'];

            if ($integrationInfo && $hash && $this->getHelper()->validateHash($integrationInfo, $hash)) {
                $helper = Mage::helper('queueit_knownuser');
                $configText = $helper->updateIntegrationInfo($integrationInfo, $hash);
                $this->getResponse()->setHeader('HTTP/1.0', 200, true);

                return;
            }
        }
        $this->getResponse()->setHeader('HTTP/1.0', 400, true);
    }

    /**
     * @return Queueit_Knownuser_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('queueit_knownuser');
    }
}