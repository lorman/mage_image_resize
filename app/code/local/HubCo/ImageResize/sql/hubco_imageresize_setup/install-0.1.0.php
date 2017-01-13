<?php
$this->startSetup ();

/**
 * Note: there are many ways in Magento to achieve the same result below
 * of creating a database table.
 * For this tutorial we have gone with the
 * Varien_Db_Ddl_Table method but feel free to explore what Magento do in
 * CE 1.8.0.0 and ealier versions if you are interested.
 */
$table = new Varien_Db_Ddl_Table ();

/**
 * This is an alias to the real name of our database table, which is
 * configured in config.xml.
 * By using an alias we can reference the same
 * table throughout our code if we wish and if the table name ever had to
 * change we could simply update a single location, config.xml
 * - smashingmagazine_branddirectory is the model alias
 * - brand is the table reference
 */
$table->setName ( $this->getTable ( 'hubco_imageresize/image' ) );
/**
 * Add the columns we need for now.
 * If you need more in the future you can
 * always create a new setup script as an upgrade, we will introduce that
 * later on in the tutorial.
 */
$table->addColumn ( 'imgPath', Varien_Db_Ddl_Table::TYPE_VARCHAR, 128, array (
    'auto_increment' => false,
    'nullable' => false,
    'primary' => true
) );
$table->addColumn ( 'productID', Varien_Db_Ddl_Table::TYPE_INTEGER, 15, array (
    'nullable' => true
) );
$table->addColumn ( 'Owidth', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array (
    'nullable' => true
) );
$table->addColumn ( 'Oheight', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array (
    'nullable' => true
) );
$table->addColumn ( 'cleaned', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array (
    'nullable' => true
) );
/**
 * A couple of important lines that are often missed.
 */
$table->setOption ( 'type', 'InnoDB' );
$table->setOption ( 'charset', 'utf8' );

if (!$this->getConnection ()->isTableExists ( $this->getTable ( 'hubco_imageresize/image' )  )) {
  /**
   * Create the table!
   */
  $this->getConnection()->createTable($table);
}
// add index
$tableName = $this->getTable('hubco_imageresize/image');
// Check if the table already exists
if ($this->getConnection ()->isTableExists ( $tableName )) {
  $table = $this->getConnection ();

  $table->addIndex ( $this->getTable ( 'hubco_imageresize/image' ), $this->getIdxName ( 'hubco_imageresize/image', array (
      'cleaned'
  ), Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX ), array (
      'cleaned'
  ), Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX );
}

$this->endSetup ();
