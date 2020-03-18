<?php
namespace Ktpl\S3\Console\Command\StorageEnableCommand;

/**
 * Interceptor class for @see \Ktpl\S3\Console\Command\StorageEnableCommand
 */
class Interceptor extends \Ktpl\S3\Console\Command\StorageEnableCommand implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\State $state, \Magento\Config\Model\Config\Factory $configFactory, \Magento\MediaStorage\Helper\File\StorageFactory $coreFileStorageFactory, \Ktpl\S3\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($state, $configFactory, $coreFileStorageFactory, $helper);
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
