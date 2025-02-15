<?php

namespace Hounddd\BlocksForBlog\Classes\Boot;

use Event;
use Hounddd\Blocksforblog\Classes\BlocksHelper;
use System\Classes\PluginManager;
use Winter\Blocks\Classes\BlockManager;
use Winter\Blog\Models\Settings as BlogSettings;
use Winter\Storm\Support\Facades\Config;
use Winter\Storm\Support\Facades\Yaml;

trait BootWinterBlog
{
    protected function BootWinterBlog()
    {
        $pluginManager = PluginManager::instance();

        if ($pluginManager->exists('Winter.Blocks')) {
            $this->updateBlogSettings();

            if (BlogSettings::get('blocks_replace_blog_editor', false)) {
                $this->updatePostFields();
            }
        }
    }

    /**
     * Update Post model form fields
     */
    public function updatePostFields(): void
    {
        //
        // Add Extra css file to backend page
        //
        \Winter\Blog\Controllers\Posts::extend(function () {
            $this->addCss('$/hounddd/blocksforblog/assets/css/blocksforblog.css');
        }, true);


        //
        // Remove extra css pane class
        //
        Event::listen('backend.form.extendFieldsBefore', function ($widget) {
            if (!($widget->getController() instanceof \Winter\Blog\Controllers\Posts
                && $widget->model instanceof \Winter\Blog\Models\Post)
            ) {
                return;
            }

            $widget->tabs['paneCssClass'][0] = '';
        });

        //
        // Update Post form fields definition
        //
        Event::listen('backend.form.extendFields', function ($widget) {
            if (!($widget->getController() instanceof \Winter\Blog\Controllers\Posts
                && $widget->model instanceof \Winter\Blog\Models\Post)
                || $widget->isNested
            ) {
                return;
            }

            $blocksHelper = BlocksHelper::instance();

            $blocksHelper->setRestrictions([
                'allowed_blocks' => BlogSettings::get('allowed_blocks', ''),
                'allowed_tags' => BlogSettings::get('allowed_tags', ''),
                'ignored_blocks' => BlogSettings::get('ignored_blocks', ''),
                'ignored_tags' => BlogSettings::get('ignored_tags', ''),
            ]);

            $blogBlocksField = array_filter(
                array_merge([
                        'tab' => 'winter.blog::lang.post.tab_edit',
                        'span' => 'full',
                        'type' => 'blocks',
                    ],[
                        'allow' => $blocksHelper->getAllow(),
                        'ignore' => $blocksHelper->getIgnore(),
                    ]
                )
            );

            $widget->addTabFields([
                'metadata[blocks]' => $blogBlocksField,
            ]);

            $widget->removeField('content');
        });


        \Winter\Blog\Models\Post::extend(function ($model) {

            //
            // Update Post model default content fields
            //
            $model->bindEvent('model.beforeSave', function () use ($model) {
                if (array_get($model->metadata, 'blocks', false)) {
                    $converter = new \League\HTMLToMarkdown\HtmlConverter();

                    $model->content_html = \Winter\Blocks\Classes\Block::renderAll($model->metadata['blocks']);
                    $model->content = $converter->convert($model->content_html);
                }
            });


            $model->addDynamicMethod('getFeaturedImageOptions', function () use ($model) {

                $images = $model->featured_images->mapWithKeys(function ($item, $key) {
                    return [$item['path'] => [$item['title'], $item['path']]];
                })->toArray();

                return $images;
            });

        }) ;
    }

    /**
     * Update blog settings page and model
     */
    public function updateBlogSettings(): void
    {
        BlogSettings::extend(function ($model) {

            $model->addDynamicMethod('getAllowedBlocksOptions', function () {
                return BlocksHelper::instance()->getBlocksNames();
            });

            $model->addDynamicMethod('getIgnoredBlocksOptions', function () {
                return BlocksHelper::instance()->getBlocksNames();
            });

            $model->addDynamicMethod('getAllowedTagsOptions', function () {
                return BlocksHelper::instance()->getTags();
            });

            $model->addDynamicMethod('getIgnoredTagsOptions', function () {
                return BlocksHelper::instance()->getTags();
            });
        });


        Event::listen('backend.form.extendFields', function ($widget) {

            if (!($widget->getController() instanceof \System\Controllers\Settings
                && $widget->model instanceof \Winter\Blog\Models\Settings)
                || $widget->isNested
            ) {
                return;
            }

            $fields = Yaml::parse(file_get_contents(plugins_path('hounddd/blocksforblog/config/blog_settings_extra_fields.yaml')))['fields'];

            $widget->addTabFields($fields);
        });
    }
}
