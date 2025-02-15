<?php namespace Hounddd\BlocksForBlog;

use Backend\Models\UserRole;
use System\Classes\PluginBase;
use System\Classes\PluginManager;

/**
 * BlocksForBlog Plugin Information File
 */
class Plugin extends PluginBase
{
    use \Hounddd\BlocksForBlog\Classes\Boot\BootWinterBlog;

    /**
     * @var array Plugin dependencies
     */
    public $require = ['Winter.Blocks'];

    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'hounddd.blocksforblog::lang.plugin.name',
            'description' => 'hounddd.blocksforblog::lang.plugin.description',
            'author'      => 'Hounddd',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {
    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot(): void
    {
        $this->pluginManager = PluginManager::instance();

        if ($this->pluginManager->exists('Winter.Blocks')) {

            //
            // Extend Winter.Blog plugin
            //
            if ($this->pluginManager->exists('Winter.Blog')) {
                $this->BootWinterBlog();
            }

        }
    }


    public function registerBlocks(): array
    {
        return [
            'blog_featured_image' => '$/hounddd/blocksforblog/blocks/blog_featured_image.block',
        ];
    }

    /**
     * Registers any frontend components implemented in this plugin.
     */
    public function registerComponents(): array
    {
        return [];
    }

    /**
     * Registers any backend permissions used by this plugin.
     */
    public function registerPermissions(): array
    {
        return [
            'hounddd.blocksforblog.access_settings' => [
                'tab' => 'hounddd.blocksforblog::lang.plugin.name',
                'label' => 'hounddd.blocksforblog::lang.permissions.access_settings',
                'roles' => [
                    UserRole::CODE_DEVELOPER,
                    UserRole::CODE_PUBLISHER
                ],
            ],
        ];
    }

    /**
     * Registers backend navigation items for this plugin.
     */
    public function registerNavigation(): array
    {
        return [];
    }

    /**
     * Registers the settings provided by this plugin.
     *
     * This plugin extends the Winter.Blog plugin settings.
     */
    public function registerSettings(): array
    {
        return [];
    }
}
