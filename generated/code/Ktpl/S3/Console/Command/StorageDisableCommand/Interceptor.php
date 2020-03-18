<?php
namespace Ktpl\S3\Console\Command\StorageDisableCommand;

/**
 * Interceptor class for @see \Ktpl\S3\Console\Command\StorageDisableCommand
 */
class Interceptor extends \Ktpl\S3\Console\Command\StorageDisableCommand implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\State $state, \Magento\Config\Model\Config\Factory $configFactory, \Magento\Framework\App\Config\Storage\WriterInterface $configWriter, \Magento\Framework\App\Config\ReinitableConfigInterface $reinitableConfig, \Magento\Framework\UrlInterface $urlInterface)
    {
        $this->___init();
        parent::__construct($state, $configFactory, $configWriter, $reinitableConfig, $urlInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function run(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'run');
        if (!$pluginInfo) {
            return parent::run($input, $output);
        } else {
            return $this->___callPlugins('run', func_get_args(), $pluginInfo);
        }
    }
}
