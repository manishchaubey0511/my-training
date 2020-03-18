<?php
namespace Magento\MediaStorage\Service\ImageResize;

/**
 * Interceptor class for @see \Magento\MediaStorage\Service\ImageResize
 */
class Interceptor extends \Magento\MediaStorage\Service\ImageResize implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\State $appState, \Magento\Catalog\Model\Product\Media\ConfigInterface $imageConfig, \Magento\Catalog\Model\ResourceModel\Product\Image $productImage, \Magento\Framework\Image\Factory $imageFactory, \Magento\Catalog\Model\Product\Image\ParamsBuilder $paramsBuilder, \Magento\Framework\View\ConfigInterface $viewConfig, \Magento\Catalog\Model\View\Asset\ImageFactory $assertImageFactory, \Magento\Theme\Model\Config\Customization $themeCustomizationConfig, \Magento\Theme\Model\ResourceModel\Theme\Collection $themeCollection, \Magento\Framework\Filesystem $filesystem, ?\Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDatabase = null, ?\Magento\Store\Model\StoreManagerInterface $storeManager = null)
    {
        $this->___init();
        parent::__construct($appState, $imageConfig, $productImage, $imageFactory, $paramsBuilder, $viewConfig, $assertImageFactory, $themeCustomizationConfig, $themeCollection, $filesystem, $fileStorageDatabase, $storeManager);
    }

    /**
     * {@inheritdoc}
     */
    public function resizeFromImageName(string $originalImageName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resizeFromImageName');
        if (!$pluginInfo) {
            return parent::resizeFromImageName($originalImageName);
        } else {
            return $this->___callPlugins('resizeFromImageName', func_get_args(), $pluginInfo);
        }
    }
}
