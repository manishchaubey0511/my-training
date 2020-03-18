<?php

namespace Ktpl\S3\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;

class ConfigChange extends ConfigProviderAbstract implements ObserverInterface
{
    const XML_PATH_FAQ_URL = 'ktpl_s3/prefix/bucket_folder';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ReinitableConfigInterface
     */
    private $reinitableConfig;

    /**
     * ConfigChange constructor.
     * @param RequestInterface $request
     * @param WriterInterface $configWriter
     */
    public function __construct(
        RequestInterface $request,
        WriterInterface $configWriter,
        ReinitableConfigInterface $reinitableConfig
    ) {
        $this->request = $request;
        $this->configWriter = $configWriter;
        $this->reinitableConfig = $reinitableConfig;

    }

    public function execute(EventObserver $observer)
    {
        $ktplParams = $this->request->getParam('groups');
        $bucket = $ktplParams['general']['fields']['bucket']['value'];
        $region = $ktplParams['general']['fields']['region']['value'];
        $bucketFolder = $ktplParams['prefix']['fields']['bucket_folder']['value'];
        if($bucketFolder && $bucket && $region) {
            $this->configWriter->save('web/unsecure/base_media_url',  'https://s3.'.$region.'.amazonaws.com/' . $bucket . '/' . $bucketFolder.'/', $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            $this->configWriter->save('web/secure/base_media_url',  'https://s3.'.$region.'.amazonaws.com/' . $bucket . '/' . $bucketFolder.'/', $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            $this->reinitableConfig->reinit();
            $this->clean();
        }
        return $this;
    }

}
