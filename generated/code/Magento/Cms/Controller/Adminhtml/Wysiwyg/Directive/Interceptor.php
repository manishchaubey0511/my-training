<?php
namespace Magento\Cms\Controller\Adminhtml\Wysiwyg\Directive;

/**
 * Interceptor class for @see \Magento\Cms\Controller\Adminhtml\Wysiwyg\Directive
 */
class Interceptor extends \Magento\Cms\Controller\Adminhtml\Wysiwyg\Directive implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Url\DecoderInterface $urlDecoder, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, ?\Magento\Framework\Image\AdapterFactory $adapterFactory = null, ?\Psr\Log\LoggerInterface $logger = null, ?\Magento\Cms\Model\Wysiwyg\Config $config = null, ?\Magento\Cms\Model\Template\Filter $filter = null)
    {
        $this->___init();
        parent::__construct($context, $urlDecoder, $resultRawFactory, $adapterFactory, $logger, $config, $filter);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
