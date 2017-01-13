<?php
class HubCo_ImageResize_Block_Adminhtml_Image_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        /**
         * Tell Magento which collection to use to display in the grid.
         */
        $collection = Mage::getResourceModel(
            'hubco_imageresize/image_collection'
        );
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    public function getRowUrl($row)
    {
        /**
         * When a grid row is clicked, this is where the user should
         * be redirected to - in our example, the method editAction of
         * BrandController.php in BrandDirectory module.
         */
        return null;
        return $this->getUrl(
            'hubco_imageresize_admin/image/edit',
            array(
                'id' => $row->getId()
            )
        );
    }

    protected function _prepareColumns()
    {
        /**
         * Here, we'll define which columns to display in the grid.
         */
        $this->addColumn('imgPath', array(
            'header' => $this->_getHelper()->__('Path'),
            'type' => 'text',
            'index' => 'imgPath',
        ));

        $this->addColumn('productID', array(
            'header' => $this->_getHelper()->__('Product ID'),
            'type' => 'number',
            'index' => 'productID',
        ));

        $this->addColumn('cleaned', array(
            'header' => $this->_getHelper()->__('Cleaned'),
            'type' => 'number',
            'index' => 'cleaned',
        ));

        $this->addColumn('Owidth', array(
            'header' => $this->_getHelper()->__('Width'),
            'type' => 'number',
            'index' => 'Owidth',
        ));

        $this->addColumn('Oheight', array(
            'header' => $this->_getHelper()->__('Height'),
            'type' => 'number',
            'index' => 'Oheight',
        ));
        return parent::_prepareColumns();
    }

    protected function _getHelper()
    {
        return Mage::helper('hubco_imageresize');
    }
}