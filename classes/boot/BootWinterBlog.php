<?php

namespace Hounddd\BlocksForBlog\Classes\Boot;

use Event;
use Winter\Blog\Models\Settings as BlogSettings;
use System\Classes\PluginManager;

trait BootWinterBlog
{
    protected function BootWinterBlog()
    {
        $pluginManager = PluginManager::instance();

        if ($pluginManager->exists('Winter.Blocks')) {

            if (BlogSettings::get('blocks_replace_blog_editor', false)) {
                $this->updatePostFields();
            }

            $this->updateBlogSettings();
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

            $blocks = [
                'columns_two',
                'title',
                'richtext',
                'plaintext',
                'code',
                'blog_featured_image',
                'image',
                'divider',
                'button',
            ];

            // Allow other plugins to add their own blocks
            Event::fire('hounddd.blocksforblog.beforeaddblocks', [&$blocks]);

            $widget->addTabFields([
                'metadata[blocks]' => [
                    'tab' => 'winter.blog::lang.post.tab_edit',
                    'span' => 'full',
                    'type' => 'blocks',
                    'allow' => $blocks,
                ]
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
     * Update blog settings page
     */
    public function updateBlogSettings(): void
    {
        Event::listen('backend.form.extendFields', function ($widget) {

            if (!($widget->getController() instanceof \System\Controllers\Settings
                && $widget->model instanceof \Winter\Blog\Models\Settings)
                || $widget->isNested
            ) {
                return;
            }

            $widget->addTabFields([
                'section_blocks' => [
                    'tab' => 'winter.blog::lang.blog.tab_general',
                    'label' => 'Blocks plugin',
                    'span' => 'full',
                    'type' => 'section',
                    'permissions' => 'hounddd.blocksforblog.access_settings',
                ],
                'blocks_replace_blog_editor' => [
                    'tab' => 'winter.blog::lang.blog.tab_general',
                    'label' => 'Replace post content editor by blocks',
                    'span' => 'full',
                    'type' => 'switch',
                    'default' => 'false',
                    'permissions' => 'hounddd.blocksforblog.access_settings',
                ],
            ]);
        });
    }
}
