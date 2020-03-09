<?php
/**
 * Title to Sidebar plugin for Craft CMS 3.x
 *
 * Moves the title field to the sidebar
 *
 * @link      https://www.joshsmith.dev
 * @copyright Copyright (c) 2019 Josh Smith <me@joshsmith.dev>
 */

namespace thejoshsmith\titletosidebar;

use Craft;
use craft\base\Plugin as CraftPlugin;
use craft\events\TemplateEvent;
use craft\web\View;
use thejoshsmith\titletosidebar\web\assets\TitleToSidebarAsset;

use yii\base\Event;

/**
 * Class TitleToSidebar
 *
 * @author    Josh Smith <me@joshsmith.dev>
 * @package   TitleToSidebar
 * @since     1.1.0
 *
 */
class Plugin extends CraftPlugin
{
    /**
     * @var TitleToSidebar
     */
    public static $plugin;

    /**
     * @var string
     */
    public $schemaVersion = '1.1.0';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->registerEventHandlers();

        Craft::info(
            Craft::t(
                'app',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    /**
     * Register Event Handlers
     * @author Josh Smith <me@joshsmith.dev>
     * @return void
     */
    protected function registerEventHandlers()
    {
        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function(TemplateEvent $event) {

                // Only run on CP requests
                $view = Craft::$app->getView();
                if( $view->getTemplateMode() !== $view::TEMPLATE_MODE_CP ) {
                    return false;
                }

                // Only run on the entries edit template
                switch ($event->template) {
                    case 'entries/_edit':
                    case 'commerce/products/_edit':

                        // Load asset
                        TitleToSidebarAsset::register(Craft::$app->view);

                        // Extract variables from event variables
                        if ($event->template == 'commerce/products/_edit') {
                            $entry = $event->variables['product'];
                            $entryType = $event->variables['productType'];
                            $hasTitleField = true;
                            $hook = 'cp.commerce.product.edit.details';
                        } else {
                            $entry = $event->variables['entry'];
                            $entryType = $event->variables['entryType'];
                            $hasTitleField = $entryType->hasTitleField;
                            $hook = 'cp.entries.edit.details';
                        }

                        // Cache errors
                        $titleError = $entry->getFirstError('title');
                        $hasTitleError = strlen($entry->getFirstError('title')) > 0;

                        $errors = [];
                        if( $hasTitleError && count($entry->getErrors()) >= 1 ){
                            $errors = $entry->getErrors();
                            unset($errors['title']); // Remove the title error from the errors array
                            $entry->clearErrors(); // Clear all errors to prevent the title input from showing
                        }

                        // Register a JS variable that can be referenced from the asset bundle
                        Craft::$app->view->hook($hook, function(array &$context) use (
                            $entryType, $entry, $titleError, $hasTitleError, $errors, $hasTitleField) {
                            return '<script>' .
                                'window.titleToSidebar = {};' .
                                'window.titleToSidebar.hasTitleField = '.(($hasTitleField || $hasTitleError) ? 'true': 'false').';' .
                                'window.titleToSidebar.titleName = \''.(addslashes($entry->title) ?? '').'\';' .
                                'window.titleToSidebar.titleLabel = \''.(addslashes($entryType->titleLabel) ?? '').'\';' .
                                'window.titleToSidebar.titleError = \''.$titleError.'\';' .
                                'window.titleToSidebar.entryType = \''.$entryType->handle.'\';' .
                                'window.titleToSidebar.hasTitleError = \''.$hasTitleError.'\';' .
                                'window.titleToSidebar.errors = '.json_encode($errors).';' .
                            '</script>';
                        });

                        // Force the title field to be hidden
                        if ($event->template == 'commerce/products/_edit') {
                            $entryType->hasVariantTitleField = false;
                        } else {
                            $entryType->hasTitleField = false;
                        }

                    break;
                }
            }
        );
    }
}
