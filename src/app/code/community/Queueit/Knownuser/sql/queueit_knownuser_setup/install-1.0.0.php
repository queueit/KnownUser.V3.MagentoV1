<?php

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('queueit_knownuser/integrationinfo'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'ID')
    ->addColumn('info', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
       'nullable' => false,
       'unsigned' => true
    ), 'Info');

$installer->getConnection()->createTable($table);

$installer->endSetup();
