<?php
class HubCo_ImageResize_Model_Image
    extends Mage_Core_Model_Abstract
{
  var $writeCon;
  var $resource;
  protected function _construct()
  {
    /**
       * This tells Magento where the related resource model can be found.
       *
       * For a resource model, Magento will use the standard model alias -
       * in this case 'hubco_brand' - and look in
       * config.xml for a child node <resourceModel/>. This will be the
       * location that Magento will look for a model when
       * Mage::getResourceModel() is called - in our case,
       * HubCo_Brand_Model_Resource.
       */
    $this->_init('hubco_imageresize/image');
    $this->writeCon = Mage::getSingleton('core/resource')->getConnection('core_write');
    $this->resource = Mage::getSingleton ( 'core/resource' );
  }

  public function rescan() {
    $products = Mage::getStoreConfig('imageresize_options/img/scan_size');
    $start = Mage::getStoreConfig('imageresize_options/img/last_scanned');
    $sessionStart = Mage::getSingleton('core/session')->getHubImageStart();
    $start = max($start, $sessionStart);

    $productsCollection = Mage::getModel('catalog/product')->getCollection();
    $productsCollection->getSelect()->limit($products, $start);
    //echo $productsCollection->getSelect()->__toString();
    $i = 0;
    $table = $this->resource->getTableName('hubco_imageresize/image');
    foreach ($productsCollection as $product) {
      foreach (Mage::getModel('catalog/product')->load($product->getId())->getMediaGalleryImages() as $image) {
        $query = "INSERT IGNORE INTO `$table` (imgPath, productID) VALUES ('{$image->getFile()}', {$product->getId()})";
        $this->writeCon->query($query);
      }
      $i++;
    }
    if ($i < $products) {
      Mage::getModel('core/config')->saveConfig('imageresize_options/img/last_scanned', 0);
      Mage::getSingleton('core/session')->setHubImageStart(0);
    }
    else {
      Mage::getModel('core/config')->saveConfig('imageresize_options/img/last_scanned', $products+$start);
      Mage::getSingleton('core/session')->setHubImageStart($products+$start);
    }

    # refresh magento configuration cache
    Mage::app()->getCacheInstance()->cleanType('config');
  }

  public function resize() {
    $baseDir = Mage::getBaseDir('media');
    $qty = Mage::getStoreConfig('imageresize_options/img/resize_qty');
    $width = Mage::getStoreConfig('imageresize_options/img/resize_width');

    $collection = Mage::getModel('hubco_imageresize/image')->getCollection();
    $collection->addFieldToFilter('cleaned', array('0',array('null' => true)));
    $collection->getSelect()->limit($qty);
    //echo $collection->getSelect();  exit;
    foreach ($collection as $image) {
      $imagePath = $baseDir.'/catalog/product'.$image['imgPath'];
      if (realpath($imagePath) !== false && file_exists(realpath($imagePath)) && filesize(realpath($imagePath)) > 0) {
        $imagick = new \Imagick(realpath($imagePath));
        $imagick->setImageCompressionQuality(80);
        $imagick->stripImage();
        $imagick->scaleImage($width, 0);
        $imagick->writeImage($imagePath);
        $imagick->destroy();
      }
      $image->setCleaned(1);
      $image->save();
    }

  }
}