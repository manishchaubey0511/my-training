<?php
namespace Magento\MediaStorage\Helper\File\Storage\Database;

/**
 * Interceptor class for @see \Magento\MediaStorage\Helper\File\Storage\Database
 */
class Interceptor extends \Magento\MediaStorage\Helper\File\Storage\Database implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\MediaStorage\Model\File\Storage\DatabaseFactory $dbStorageFactory, \Magento\MediaStorage\Model\File\Storage\File $fileStorage, \Magento\Framework\Filesystem $filesystem)
    {
        $this->___init();
        parent::__construct($context, $dbStorageFactory, $fileStorage, $filesystem);
    }

    /**
     * {@inheritdoc}
     */
    public function checkDbUsage()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'checkDbUsage');
        if (!$pluginInfo) {
            return parent::checkDbUsage();
        } else {
            return $this->___callPlugins('checkDbUsage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageDatabaseModel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStorageDatabaseModel');
        if (!$pluginInfo) {
            return parent::getStorageDatabaseModel();
        } else {
            return $this->___callPlugins('getStorageDatabaseModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageFileModel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStorageFileModel');
        if (!$pluginInfo) {
            return parent::getStorageFileModel();
        } else {
            return $this->___callPlugins('getStorageFileModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceStorageModel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResourceStorageModel');
        if (!$pluginInfo) {
            return parent::getResourceStorageModel();
        } else {
            return $this->___callPlugins('getResourceStorageModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveFile($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveFile');
        if (!$pluginInfo) {
            return parent::saveFile($filename);
        } else {
            return $this->___callPlugins('saveFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renameFile($oldName, $newName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'renameFile');
        if (!$pluginInfo) {
            return parent::renameFile($oldName, $newName);
        } else {
            return $this->___callPlugins('renameFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function copyFile($oldName, $newName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'copyFile');
        if (!$pluginInfo) {
            return parent::copyFile($oldName, $newName);
        } else {
            return $this->___callPlugins('copyFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fileExists($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'fileExists');
        if (!$pluginInfo) {
            return parent::fileExists($filename);
        } else {
            return $this->___callPlugins('fileExists', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueFilename($directory, $filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUniqueFilename');
        if (!$pluginInfo) {
            return parent::getUniqueFilename($directory, $filename);
        } else {
            return $this->___callPlugins('getUniqueFilename', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveFileToFilesystem($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveFileToFilesystem');
        if (!$pluginInfo) {
            return parent::saveFileToFilesystem($filename);
        } else {
            return $this->___callPlugins('saveFileToFilesystem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaRelativePath($fullPath)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMediaRelativePath');
        if (!$pluginInfo) {
            return parent::getMediaRelativePath($fullPath);
        } else {
            return $this->___callPlugins('getMediaRelativePath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFolder($folderName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'deleteFolder');
        if (!$pluginInfo) {
            return parent::deleteFolder($folderName);
        } else {
            return $this->___callPlugins('deleteFolder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFile($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'deleteFile');
        if (!$pluginInfo) {
            return parent::deleteFile($filename);
        } else {
            return $this->___callPlugins('deleteFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveUploadedFile($result)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveUploadedFile');
        if (!$pluginInfo) {
            return parent::saveUploadedFile($result);
        } else {
            return $this->___callPlugins('saveUploadedFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaBaseDir()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMediaBaseDir');
        if (!$pluginInfo) {
            return parent::getMediaBaseDir();
        } else {
            return $this->___callPlugins('getMediaBaseDir', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isModuleOutputEnabled');
        if (!$pluginInfo) {
            return parent::isModuleOutputEnabled($moduleName);
        } else {
            return $this->___callPlugins('isModuleOutputEnabled', func_get_args(), $pluginInfo);
        }
    }
}
