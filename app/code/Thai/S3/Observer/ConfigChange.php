<?php

namespace Thai\S3\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;

class ConfigChange extends ConfigProviderAbstract implements ObserverInterface
{
    const XML_PATH_FAQ_URL = 'thai_s3/prefix/bucket_folder';

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
        $thaiParams = $this->request->getParam('groups');
        //echo "<pre>";print_r($thaiParams);die;
        $bucket = $thaiParams['general']['fields']['bucket']['value'];
        $region = $thaiParams['general']['fields']['region']['value'];
        $bucketFolder = $thaiParams['prefix']['fields']['bucket_folder']['value'];
        if($bucketFolder && $bucket && $region) {
            $this->configWriter->save('web/unsecure/base_media_url',  'https://s3.'.$region.'.amazonaws.com/' . $bucket . '/' . $bucketFolder.'/', $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            $this->configWriter->save('web/secure/base_media_url',  'https://s3.'.$region.'.amazonaws.com/' . $bucket . '/' . $bucketFolder.'/', $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            //echo "<pre>";print_r(get_class_methods($this->reinitableConfig->reinit()));die;
            $this->reinitableConfig->reinit();
            $this->clean();
        }
        return $this;
    }

}
