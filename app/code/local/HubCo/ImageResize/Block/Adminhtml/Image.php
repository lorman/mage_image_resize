<?php
class HubCo_ImageResize_Block_Adminhtml_Image
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _construct()
    {
        parent::_construct();

        $this->addButton('rescan', array(
        'label'     => Mage::helper('adminhtml')->__('Rescan Images'),
        'onclick'   => "location.href='".Mage::helper('adminhtml')->getUrl("imageresize-admin/image/rescan")."'",
        'class'     => 'go'
        ), 0, 100, 'header');

        $this->addButton('clean', array(
        'label'     => Mage::helper('adminhtml')->__('Resise Images'),
        'onclick'   => "location.href='".Mage::helper('adminhtml')->getUrl("imageresize-admin/image/resize")."'",
        'class'     => 'button'
        ), 0, 200, 'header');

        $this->addButton('bad-links', array(
        'label'     => Mage::helper('adminhtml')->__('Remove Bad Links'),
        'onclick'   => "location.href='".Mage::helper('adminhtml')->getUrl("imageresize-admin/image/badlinks")."'",
        'class'     => 'button'
        ), 0, 300, 'header');
        /**
         * The $_blockGroup property tells Magento which alias to use to
         * locate the blocks to be displayed in this grid container.
         * In our example, this corresponds to BrandDirectory/Block/Adminhtml.
         */
        $this->_blockGroup = 'hubco_imageresize_adminhtml';

        /**
         * $_controller is a slightly confusing name for this property.
         * This value, in fact, refers to the folder containing our
         * Grid.php and Edit.php - in our example,
         * BrandDirectory/Block/Adminhtml/Brand. So, we'll use 'brand'.
         */
        $this->_controller = 'image';

        /**
         * The title of the page in the admin panel.
         */
        $this->_headerText = Mage::helper('hubco_imageresize')
            ->__('Image Listing');
    }

    public function getCreateUrl()
    {
        /**
         * When the "Add" button is clicked, this is where the user should
         * be redirected to - in our example, the method editAction of
         * BrandController.php in BrandDirectory module.
         */
        return null;
        return $this->getUrl(
            'hubco_imageresize_admin/imageresize/edit'
        );
    }
}