<?php
namespace Ktpl\S3\Model\MediaStorage\File\Storage;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Magento\Framework\DataObject;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Ktpl\S3\Helper\Data as DataHelper;

class S3 extends DataObject
{
    /**
     * Store media base directory path
     *
     * @var string
     */
    protected $mediaBaseDirectory = null;

    /**
     * @var S3Client
     */
    private $client;

    /**
     * @var S3Helper
     */
    private $helper;

    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    private $storageHelper;

    /**
     * @var \Magento\MediaStorage\Helper\File\Media
     */
    private $mediaHelper;

    /**
     * Collect errors during sync process
     *
     * @var string[]
     */
    private $errors = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SerializerInterface|\Zend_Serializer
     */
    private $serializer;

    /**
     * @var array
     */
    private $objects = [];

    /**
     * @param DataHelper $helper
     * @param \Magento\MediaStorage\Helper\File\Media $mediaHelper
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $storageHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        DataHelper $helper,
        \Magento\MediaStorage\Helper\File\Media $mediaHelper,
        \Magento\MediaStorage\Helper\File\Storage\Database $storageHelper,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->helper = $helper;
        $this->mediaHelper = $mediaHelper;
        $this->storageHelper = $storageHelper;
        $this->logger = $logger;
        $this->serializer = $this->getSerializer();

        $options = [
            'version' => 'latest',
            'region' => $this->helper->getRegion(),
            'credentials' => [
                'key' => $this->helper->getAccessKey(),
                'secret' => $this->helper->getSecretKey(),
            ],
        ];

        if ($this->helper->getEndpointEnabled()) {
            if ($this->helper->getEndpoint()) {
                $options['endpoint'] = $this->helper->getEndpoint();
            }

            if ($this->helper->getEndpointRegion()) {
                $options['region'] = $this->helper->getEndpointRegion();
            }
        }

        $this->client = new \Aws\S3\S3Client($options);
    }

    /**
     * Initialisation
     *
     * @return $this
     */
    public function init()
    {
        return $this;
    }

    /**
     * Retrieve Serializer depending the Magento Version
     *
     * @return bool|\Zend_Serializer|\Magento\Framework\Serialize\Serializer\Json
     */
    protected function getSerializer()
    {
        // Magento Version 2.1.*
        $serializer = \Zend\Serializer\Serializer::factory(\Zend\Serializer\Adapter\Json::class);

        // Magento Version 2.2.*
        if (class_exists(\Magento\Framework\Serialize\Serializer\Json::class)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Serialize\Serializer\Json::class
            );
        }

        return $serializer;
    }

    /**
     * Return storage name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getStorageName()
    {
        return __('Amazon S3');
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function loadByFilename($filename)
    {
        $fail = false;
        try {
            $object = $this->client->getObject([
                'Bucket' => $this->getBucket(),
                'Key' => $this->getBucketFolder().'/'.$filename,
            ]);

            if ($object['Body']) {
                $this->setData('id', $filename);
                $this->setData('filename', $filename);
                $this->setData('content', (string)$object['Body']);
            } else {
                $fail = true;
            }
        } catch (S3Exception $e) {
            $fail = true;

            $this->logger->critical($e->getMessage());
        }

        if ($fail) {
            $this->unsetData();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * @return $this
     * @throws \Aws\S3\Exception\DeleteMultipleObjectsException
     */
    public function clear()
    {
        /*$batch = \Aws\S3\BatchDelete::fromListObjects($this->client, [
            'Bucket' => $this->getBucket(),
        ]);
        $batch->delete();*/

        /*Deleted Only Bucket Folder and Recreated By : KTPL*/
        $results = $this->client->listObjectsV2([
            'Bucket' => $this->getBucket(),
            'Prefix' => $this->getBucketFolder().'/',
        ]);

        if (isset($results['Contents'])) {
            foreach ($results['Contents'] as $result) {
                $this->client->deleteObject([
                    'Bucket' => $this->getBucket(),
                    'Key' => $result['Key']
                ]);
            }
        }

        $this->client->putObject($this->getAllParams([
            'Body' => "",
            'Bucket' => $this->getBucket(),
            'Key' => $this->getBucketFolder().'/',
        ]));
        /*Deleted Only Bucket Folder and Recreated*/
        return $this;
    }

    /**
     * @param int $offset
     * @param int $count
     * @return bool
     */
    public function exportDirectories($offset = 0, $count = 100)
    {
        return false;
    }

    /**
     * @param array $dirs
     * @return $this
     */
    public function importDirectories(array $dirs = [])
    {
        return $this;
    }

    /**
     * Retrieve connection name
     *
     * @return null
     */
    public function getConnectionName()
    {
        return null;
    }

    /**
     * @param int $offset
     * @param int $count
     * @return array|bool
     */
    public function exportFiles($offset = 0, $count = 100)
    {
        $files = [];

        if (empty($this->objects)) {
            $this->objects = $this->client->listObjects([
                'Bucket' => $this->getBucket(),
                'MaxKeys' => $count,
            ]);
        } else {
            $this->objects = $this->client->listObjects([
                'Bucket' => $this->getBucket(),
                'MaxKeys' => $count,
                'Marker' => $this->objects[count($this->objects) - 1],
            ]);
        }

        if (empty($this->objects)) {
            return false;
        }

        foreach ($this->objects as $object) {
            if (isset($object['Contents']) && substr($object['Contents'], -1) != '/') {
                $content = $this->client->getObject([
                    'Bucket' => $this->getBucket(),
                    'Key' => $this->getBucketFolder().'/'.$object['Key'],
                ]);
                if (isset($content['Body'])) {
                    $files[] = [
                        'filename' => $object['Key'],
                        'content' => (string)$content['Body'],
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * @param array $files
     * @return $this
     */
    public function importFiles(array $files = [])
    {
        foreach ($files as $file) {
            try {
                /*Directory path check starts By : KTPL*/
                $directoryPath = "";
                if(isset($file['directory'])) {
                    $directoryPath = '/'.$file['directory'];
                }
                /*Directory path check ends*/
                $this->client->putObject($this->getAllParams([
                    'Body' => $file['content'],
                    'Bucket' => $this->getBucket(),
                    'ContentType' => \GuzzleHttp\Psr7\mimetype_from_filename($file['filename']),
                    'Key' => $this->getBucketFolder() . $directoryPath . '/' . $file['filename'],
                ]));
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
                $this->logger->critical($e);
            }
        }

        return $this;
    }

    /**
     * @param string $filename
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveFile($filename)
    {
        $file = $this->mediaHelper->collectFileInfo($this->getMediaBaseDirectory(), $filename);
        //echo $filename;die;
        $this->client->putObject($this->getAllParams([
            'Body' => $file['content'],
            'Bucket' => $this->getBucket(),
            'ContentType' => \GuzzleHttp\Psr7\mimetype_from_filename($file['filename']),
            'Key' => $this->getBucketFolder().'/'.$filename,
        ]));

        return $this;
    }

    /**
     * @param array $headers
     * @return array
     */
    public function getAllParams(array $headers = [])
    {
        $headers['FULL_CONTROL'] = 'WRITE_ACP';
        //$headers['ACL'] = 'public-read';
        $metadata = $this->getMetadata();
        if (count($metadata) > 0) {
            $headers['Metadata'] = $metadata;
        }

        if ($this->helper->getExpires()) {
            $headers['Expires'] = $this->helper->getExpires();
        }

        if ($this->helper->getCacheControl()) {
            $headers['CacheControl'] = $this->helper->getCacheControl();
        }

        return $headers;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        $meta = [];
        $headers = $this->helper->getCustomHeaders();

        if ($headers) {
            $unserializedHeaders = $this->serializer->unserialize($headers);
            foreach ($unserializedHeaders as $header) {
                $meta[$header['header']] = $header['value'];
            }
        }

        return $meta;
    }

    /**
     * @param $filename
     * @return bool
     */
    public function fileExists($filename)
    {
        return $this->client->doesObjectExist($this->getBucket(), $this->getBucketFolder().'/'.$filename);
    }

    /**
     * @param string $oldFilePath
     * @param string $newFilePath
     * @return $this
     */
    public function copyFile($oldFilePath, $newFilePath)
    {
        $this->client->copyObject($this->getAllParams([
            'Bucket' => $this->getBucket(),
            'Key' => $this->getBucketFolder().'/'.$newFilePath,
            'CopySource' => $this->getBucket() . '/'.$this->getBucketFolder().'/'. $oldFilePath,
        ]));
        $this->client->deleteObject([
            'Bucket' => $this->getBucket(),
            'Key' => $this->getBucketFolder().'/'.$oldFilePath,
        ]);

        return $this;
    }

    /**
     * @param string $oldFilePath
     * @param string $newFilePath
     * @return $this
     */
    public function renameFile($oldFilePath, $newFilePath)
    {
        $this->client->copyObject($this->getAllParams([
            'Bucket' => $this->getBucket(),
            'Key' => $this->getBucketFolder().'/'.$newFilePath,
            'CopySource' => $this->getBucket() . '/'.$this->getBucketFolder().'/'. $oldFilePath,
        ]));

        $this->client->deleteObject([
            'Bucket' => $this->getBucket(),
            'Key' => $this->getBucketFolder().'/'.$oldFilePath,
        ]);

        return $this;
    }

    /**
     * Delete file from Amazon S3
     *
     * @param string $path
     * @return $this
     */
    public function deleteFile($path)
    {
        //echo $path;die;
        $this->client->deleteObject([
            'Bucket' => $this->getBucket(),
            'Key' => $this->getBucketFolder().'/'.$path,
        ]);

        return $this;
    }

    /**
     * @param string $path
     * @return array
     */
    public function getSubdirectories($path)
    {
        $subdirectories = [];

        $prefix = $this->storageHelper->getMediaRelativePath($path);
        $prefix = rtrim($prefix, '/') . '/';

        $objects = $this->client->listObjects([
            'Bucket' => $this->getBucket(),
            'Prefix' => $prefix,
            'Delimiter' => '/',
        ]);

        if (isset($objects['CommonPrefixes'])) {
            foreach ($objects['CommonPrefixes'] as $object) {
                if (!isset($object['Prefix'])) {
                    continue;
                }

                $subdirectories[] = [
                    'name' => $object['Prefix'],
                ];
            }
        }
        //echo "<pre>";print_r($subdirectories);//die;
        return $subdirectories;
    }

    /**
     * @param string $path
     * @return array
     */
    public function getDirectoryFiles($path)
    {
        $files = [];

        $prefix = $this->storageHelper->getMediaRelativePath($path);
        $prefix = rtrim($prefix, '/') . '/';

        $objects = $this->client->listObjects([
            'Bucket' => $this->getBucket(),
            'Prefix' => $prefix,
            'Delimiter' => '/',
        ]);

        if (isset($objects['Contents'])) {
            foreach ($objects['Contents'] as $object) {
                if (isset($object['Key']) && $object['Key'] != $prefix) {
                    $content = $this->client->getObject([
                        'Bucket' => $this->getBucket(),
                        'Key' => $object['Key'],
                    ]);
                    if (isset($content['Body'])) {
                        $files[] = [
                            'filename' => $object['Key'],
                            'content' => (string)$content['Body'],
                        ];
                    }
                }
            }
        }
//echo "<pre>";print_r($files);//die;
        return $files;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function deleteDirectory($path)
    {
        $mediaRelativePath = $this->storageHelper->getMediaRelativePath($path);
        $prefix = rtrim($mediaRelativePath, '/') . '/';
        $this->client->deleteMatchingObjects($this->getBucket(), $this->getBucketFolder().'/'.$prefix);

        return $this;
    }

    /**
     * @return string
     */
    protected function getBucket()
    {
        return $this->helper->getBucket();
    }

    /**
     * Retrieve media base directory path
     *
     * @return string
     */
    public function getMediaBaseDirectory()
    {
        if (null === $this->mediaBaseDirectory) {
            $this->mediaBaseDirectory = $this->storageHelper->getMediaBaseDir();
        }

        return $this->mediaBaseDirectory;
    }

    /**
     * @return string
     */
    protected function getBucketFolder()
    {
        return $this->helper->getUploadFolder();
    }
}
