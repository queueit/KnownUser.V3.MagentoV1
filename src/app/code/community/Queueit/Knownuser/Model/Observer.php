<?php
require_once Mage::getBaseDir('lib') . DS . 'Queueit' . DS . 'KnownUser' . DS . 'KnownUser.php';

class Queueit_Knownuser_Model_Observer
{
    /**
     * Temporary storage of the cookie value, easier for validation.
     *
     * @var string
     */
    protected $tempCookieValue;

    /**
     * @param $observer
     */
    public function controllerActionPredispatch($observer)
    {
        $helper = $this->getHelper();

        if (!$helper->getIsEnabled() || !$helper->getCustomerId() || !$helper->getSecretKey()) {
            return;
        }

        $action = $observer->getEvent()->getControllerAction();
        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $action->getRequest();
        $this->handleRequest($request, $action);
   
    }



    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @param Mage_Core_Controller_Front_Action $action
     */
    public function handleRequest(Mage_Core_Controller_Request_Http $request, Mage_Core_Controller_Front_Action $action)
    {
        $token = $request->getQuery('queueittoken', '');
        $integrationInfo = $this->getHelper()->getIntegrationinfo();
   

        try {
            $fullUrl = $this->getCurrentUrl();
            $currentUrlWithoutQueueitToken =  preg_replace ( "/([\\?&])(" ."queueittoken". "=[^&]*)/i" , "" ,  $fullUrl);

            $result = \QueueIT\KnownUserV3\SDK\KnownUser::validateRequestByIntegrationConfig(
                $currentUrlWithoutQueueitToken,
                $token,
                $integrationInfo,
                $this->getHelper()->getCustomerId(),
                $this->getHelper()->getSecretKey()
            );

            if ($result->doRedirect()) {
                    $response = $action->getResponse();
                    $response->setRedirect($result->redirectUrl);
                    $response->setHeader('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
                    $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                    $response->setHeader('Pragma', 'no-cache');
                    $action->setFlag('', $action::FLAG_NO_DISPATCH, true);
                    $request->setDispatched(true);
                    return;
            }


            if (!empty($token)) {
                // Redirect to url without token
                // This is a valid token and should be allowed to update
                $helper = Mage::helper('core/url');
                $currentUrl = $helper->removeRequestParam($helper->getCurrentUrl(), 'queueittoken');
                $action->getResponse()->setRedirect($currentUrl);
                $request->setDispatched(true);
                return;
            }



        } catch (Exception $e) {
            Mage::logException($e);
        }
    }




    /**
     * Pull mechanism until backend for push mechanism is finished
     *
     * @param $observer
     */
    public function updateConfig($observer)
    {
        if ($this->getHelper()->getUpdateConfigMethod() == Queueit_Knownuser_Model_Source_Config_Method::PULL) {
            // Get config and update
            $url = sprintf('https://%s.queue-it.net/status/integrationconfig/%s?qt=%s',
                $this->getHelper()->getCustomerId(),
                $this->getHelper()->getCustomerId(),
                time() // random number for prevention of caching
            );

            $client = new Zend_Http_Client($url);
            $response = $client->request();
            $integrationInfo = $response->getBody();

            $latest = $this->getHelper()->getIntegrationinfo();

            if ($latest != $integrationInfo) {
                Mage::getModel('queueit_knownuser/integrationinfo')
                    ->setInfo(bin2hex($integrationInfo))// Required for updating
                    ->save();
                $this->getHelper()->cleanQueueitCache();
            }
        }
    }



    /**
     * @return Queueit_Knownuser_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('queueit_knownuser');
    }




    /**
     * Strange method for getting the url, but makes sure it doesn't conflict with the other checks
     *
     * @return string
     */
    protected function getCurrentUrl()
    {
        // Get HTTP/HTTPS (the possible values for this vary from server to server)
        $myUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && !in_array(strtolower($_SERVER['HTTPS']), array('off', 'no'))) ? 'https' : 'http';
        // Get domain portion
        $myUrl .= '://' . $_SERVER['HTTP_HOST'];
        // Get path to script
        $myUrl .= $_SERVER['REQUEST_URI'];
        // Add path info, if any
        if (!empty($_SERVER['PATH_INFO'])) {
            $myUrl .= $_SERVER['PATH_INFO'];
        }

        return $myUrl;
    }
}
