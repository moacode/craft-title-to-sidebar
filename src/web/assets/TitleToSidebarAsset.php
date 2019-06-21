<?php

namespace thejoshsmith\titletosidebar\web\assets;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Expanded Matrix AssetBundle
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

        // $this->css = [
        //     'css/animate.min.css',
        //     'css/expandedMatrix.css',
        // ];

        parent::init();
    }
}
