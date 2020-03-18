<?php
namespace Thai\S3\Console\Command\StorageExportCommand;

/**
 * Interceptor class for @see \Thai\S3\Console\Command\StorageExportCommand
 */
class Interceptor extends \Thai\S3\Console\Command\StorageExportCommand implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\State $state, \Magento\MediaStorage\Helper\File\Storage\Database $storageHelper, \Magento\MediaStorage\Helper\File\StorageFactory $coreFileStorageFactory, \Thai\S3\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($state, $storageHelper, $coreFileStorageFactory, $helper);
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
