<?php

use \DateTime;
class Queueit_Knownuser_KnownuserController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $helper = Mage::helper('queueit_knownuser');
        $configText = $helper->getIntegrationinfo(false);
        $customerIntegration = json_decode($configText, true);
        $layout = $this->getLayout();
        $block = $layout->getBlock('templateBlock');
        $block->setAccountId($customerIntegration["AccountId"]);
        $block->setVersion($customerIntegration["Version"]);
        $block->setPublishDate($customerIntegration["PublishDate"]);
        $block->setUploadUrl(Mage::helper('adminhtml')->getUrl('adminhtml/uploadConfig/Index'));
        $block->setIntegrationConfig($configText);
        $this->renderLayout();
    }
    protected function _isAllowed() { return true; }
}