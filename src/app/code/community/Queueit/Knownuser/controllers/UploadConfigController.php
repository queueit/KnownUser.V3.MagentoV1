<?php

use \DateTime;

class Queueit_Knownuser_UploadConfigController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {          
        if ($data = $this->getRequest()->getParams()) {    
			print_r('{');
			if (isset($_FILES['files'])) {

				$errors = "";
				$extensions = ['json'];

				$all_files = count($_FILES['files']['tmp_name']);
				$strConfig = "";
				if ($all_files > 0) {
					$file_name = $_FILES['files']['name'][0];
					$file_tmp = $_FILES['files']['tmp_name'][0];
					$file_type = $_FILES['files']['type'][0];
					$file_ext1 = explode('.', $file_name);
					$file_ext2 = end($file_ext1);
					$file_ext = strtolower($file_ext2);

					if (!in_array($file_ext, $extensions)) {
						$errors = 'extension not allowed: ' . $file_name . ' ' . $file_type;
					}

					if ($errors === "") {
						$strConfig = file_get_contents($file_tmp);
                        $objectConfig = json_decode($strConfig);
                        $helper = Mage::helper('queueit_knownuser');
                        $configText = $helper->updateIntegrationInfo($objectConfig->integrationInfo, $objectConfig->hash);	
                        print_r("\"stat\" : \"Successful\",");                        
						$configText =  $helper->getIntegrationInfo(false);
						print_r("\"configText\" : " . $configText);											
					}
				} else {
					$errors = 'Config file is not found in your request!';
				}
				if ($errors) {
					print_r("\"errors\" : \"");
					print_r($errors);
					print_r("\"");
				}
			}
            print_r('}');
        }
	}
	protected function _isAllowed() { return true; }
}
