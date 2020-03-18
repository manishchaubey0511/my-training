<?php
namespace Ktpl\S3\Console\Command;

use Magento\Config\Model\Config\Factory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ktpl\S3\Model\MediaStorage\File\Storage;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * @inheritdoc
 */
class StorageDisableCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var Factory
     */
    private $configFactory;

    /**
     * @var State
     */
    private $state;

    /**
     * @var configWriter
     */
    private $configWriter;

    /**
     * @var reinitableConfig
     */
    private $reinitableConfig;

    protected $urlInterface;

    /**
     * @param State $state
     * @param Factory $configFactory
     */
    public function __construct(
        State $state,
        Factory $configFactory,
        WriterInterface $configWriter,
        ReinitableConfigInterface $reinitableConfig,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->state = $state;
        $this->configFactory = $configFactory;
        $this->configWriter = $configWriter;
        $this->reinitableConfig = $reinitableConfig;
        $this->urlInterface = $urlInterface;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('s3:storage:disable');
        $this->setDescription('Revert to using the local filesystem as your Magento 2 file storage backend.');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->state->emulateAreaCode(Area::AREA_ADMINHTML, function () use ($output) {
            $output->writeln('Updating configuration to use the local filesystem.');

            $config = $this->configFactory->create();
            $config->setDataByPath(
                'system/media_storage_configuration/media_storage',
                Storage::STORAGE_MEDIA_FILE_SYSTEM
            );
            $config->save();
            $output->writeln(sprintf('<info>Magento now uses the local filesystem for its file backend storage.</info>'));
            $this->configWriter->save('web/unsecure/base_media_url', '' , $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            $this->configWriter->save('web/secure/base_media_url',  '', $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            return 0;
        });
    }
}
