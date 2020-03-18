<?php
namespace Ktpl\S3\Block\MediaStorage\System\Config\System\Storage\Media\Synchronise;

class Plugin
{
    public function aroundGetTemplate()
    {
        return 'Ktpl_S3::system/config/system/storage/media/synchronise.phtml';
    }
}
