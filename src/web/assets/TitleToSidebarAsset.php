<?php

namespace thejoshsmith\titletosidebar\web\assets;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Title To Sidebar AssetBundle
 * @author    Josh Smith
 * @package   ExpandedMatrix
 * @since     1.0.0
 */
class TitleToSidebarAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    public function init()
    {
        $this->sourcePath = "@vendor/thejoshsmith/craft-title-to-sidebar/src/web/assets/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/titleToSidebar.js',
        ];

        parent::init();
    }
}
