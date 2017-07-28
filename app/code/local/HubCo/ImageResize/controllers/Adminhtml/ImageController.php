<?php
class HubCo_Imageresize_Adminhtml_ImageController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page, we will see a grid of all
     * imageresizes currently available in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction()
    {
        // instantiate the grid container
        $imageresizeBlock = $this->getLayout()
            ->createBlock('hubco_imageresize_adminhtml/image');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($imageresizeBlock)
            ->renderLayout();
    }

    public function rescanAction()
    {
      // rescan the images.
      $imageModel = Mage::getModel('hubco_imageresize/image');
      $imageModel->rescan();

      // instantiate the grid container
        $imageresizeBlock = $this->getLayout()
            ->createBlock('hubco_imageresize_adminhtml/image');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($imageresizeBlock)
            ->renderLayout();
    }

    public function resizeAction()
    {
      // rescan the images.
      $imageModel = Mage::getModel('hubco_imageresize/image');
      $imageModel->resize();

      // instantiate the grid container
        $imageresizeBlock = $this->getLayout()
            ->createBlock('hubco_imageresize_adminhtml/image');

        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($imageresizeBlock)
            ->renderLayout();
    }

    public function badlinksAction()
    {
      $products = Mage::getModel('catalog/product')->getCollection();
      $api = Mage::getModel('catalog/product_attribute_media_api');
      foreach($products as $product){
        $images = $api->items($product->getId());
        foreach($images as $img){
         if(!file_exists(Mage::getBaseDir('media').'/catalog/product'.$img['file']))
           $api->remove($product->getId(),$img['file']);
        }
      }
    }

    /**
     * Thanks to Ben for pointing out this method was missing. Without
     * this method the ACL rules configured in adminhtml.xml are ignored.
     */
    protected function _isAllowed()
    {
        /**
         * we include this switch to demonstrate that you can add action
         * level restrictions in your ACL rules. The isAllowed() method will
         * use the ACL rule we have configured in our adminhtml.xml file:
         * - acl
         * - - resources
         * - - - admin
         * - - - - children
         * - - - - - smashingmagazine_imageresizedirectory
         * - - - - - - children
         * - - - - - - - imageresize
         *
         * eg. you could add more rules inside imageresize for edit and delete.
         */
        $actionName = $this->getRequest()->getActionName();
        switch ($actionName) {
            case 'index':
            case 'edit':
            case 'delete':
                // intentionally no break
            default:
                $adminSession = Mage::getSingleton('admin/session');
                $isAllowed = $adminSession
                    ->isAllowed('hubco_imageresize/imageresize');
                break;
        }

        return $isAllowed;
    }
}