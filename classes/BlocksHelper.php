<?php

declare(strict_types=1);

namespace Hounddd\Blocksforblog\Classes;

use Winter\Blocks\Classes\BlockManager;
use Winter\Storm\Support\Traits\Singleton;

class BlocksHelper
{
    use Singleton;

    const ALLOWED_BLOCK = 'allowed_block';
    const ALLOWED_TAGS = 'allowed_tags';
    const IGNORED_BLOCKS = 'ignored_blocks';
    const IGNORED_TAGS = 'ignored_tags';
    const NOT_ALLOWED_BLOCK = 'not_allowed_block';
    const NOT_ALLOWED_TAGS = 'not_allowed_tags';

    /**
     * Liste of all available blocks
     */
    protected array $blocks = [];

    /**
     * Allowed blocks
     */
    protected array $allow = [];

    /**
     * Ignored blocks
     */
    protected array $ignore = [];


    public function init()
    {
        foreach (BlockManager::instance()->getConfigs() as $code => $config) {
            $definitions[$code] = [
                'code' => $code,
                'name' => trans(array_get($config, 'name')),
                'tags' => array_get($config, 'tags', null),
            ];
        }
        uasort($definitions, fn ($a, $b) => $a['name'] <=> $b['name']);

        $this->blocks = $definitions;
    }


    public function setAllow($allow): void
    {
        $this->allow = $allow;
    }

    public function setIgnore($ignore): void
    {
        $this->ignore = $ignore;
    }

    public function getAllow(): array
    {
        return $this->allow;
    }

    public function getIgnore(): array
    {
        return $this->ignore;
    }

    /**
     * Set allowed and ignored blocks based on the restrictions
     */
    public function setRestrictions(array $restrictions): void
    {

        $blocksAllowed = $this->getRestriction($restrictions, 'allowed_blocks');
        $tagsAllowed = $this->getRestriction($restrictions, 'allowed_tags');
        $blocksIgnored = $this->getRestriction($restrictions, 'ignored_blocks');
        $tagsIgnored = $this->getRestriction($restrictions, 'ignored_tags');

        $allow = [];
        $ignore = [];

        // Set allowed blocks
        if (count($tagsAllowed)) {
            $allow = [
                ...(count($blocksAllowed) ? ['blocks' => $blocksAllowed] : []),
                // ...(count($blocksAllowed) ? $blocksAllowed : []),
                'tags' => $tagsAllowed,
            ];
        } elseif (count($blocksAllowed) > 0) {
            $allow = $blocksAllowed;
        }

        // Set ignored blocks
        if (count($tagsIgnored)) {
            $ignore = [
                ...(count($blocksIgnored) ? ['blocks' => $blocksIgnored] : []),
                // ...(count($blocksIgnored) ? $blocksIgnored : []),
                'tags' => $tagsIgnored,
            ];

        } elseif (count($blocksIgnored)) {
            $ignore = $blocksIgnored;
        }

        $this->allow = $allow;
        $this->ignore = $ignore;
    }

    /**
     * Return blocks
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * Return an associative array of blocks with code as key and name as value
     */
    public function getBlocksNames(): array
    {
        return array_map(function($item) {
            return $item['name'];
        }, $this->blocks);
    }

    /**
     * Return all tags definned in available blocks
     */
    public function getTags(): array
    {
        return array_unique(array_merge(...array_pluck($this->blocks, 'tags')));
    }

    /**
     * Get the field definition for the blog blocks field
     */
    public function getBlogBlocksField(): array
    {
        return array_merge([
                'tab' => 'winter.blog::lang.post.tab_edit',
                'span' => 'full',
                'type' => 'blocks',
            ],[
                'allow' => $this->getAllow(),
                'ignore' => $this->getIgnore(),
            ]
        );
    }


    /**
     * Determines if a block is allowed according to the widget's ignore/allow list.
     */
    public function isBlockAllowed(string $code, array|string $blockTags): array
    {
        $blockTags = is_array($blockTags) ? $blockTags : [$blockTags];

        if (isset($this->ignore['blocks']) || isset($this->ignore['tags'])) {
            $ignoredBlocks = isset($this->ignore['blocks']) ? $this->ignore['blocks'] : [];
            $ignoredTags = isset($this->ignore['tags']) ? $this->ignore['tags'] : [];
        } else {
            $ignoredBlocks = $this->ignore;
            $ignoredTags = [];
        }
        if (isset($this->allow['blocks']) || isset($this->allow['tags'])) {
            $allowedBlocks = isset($this->allow['blocks']) ? $this->allow['blocks'] : [];
            $allowedTags = isset($this->allow['tags']) ? $this->allow['tags'] : [];
        } else {
            $allowedBlocks = $this->allow;
            $allowedTags = [];
        }

        // Reject explicitly ignored blocks
        if (count($ignoredBlocks) && in_array($code, $ignoredBlocks)) {
            return [
                'active' => false,
                'reason' => self::IGNORED_BLOCKS,
            ];
        }

        // Reject blocks that have any ignored tags
        if (count($ignoredTags) && array_intersect($blockTags, $ignoredTags)) {
            return [
                'active' => false,
                'reason' => self::IGNORED_TAGS,
            ];
        }

        // Reject blocks that are not explicitly allowed
        if (count($allowedBlocks) && !in_array($code, $allowedBlocks)) {
            return [
                'active' => false,
                'reason' => self::NOT_ALLOWED_BLOCK,
            ];
        }

        // Reject blocks that do not have any allowed tags
        if (count($allowedTags) && !array_intersect($blockTags, $allowedTags)) {
            return [
                'active' => false,
                'reason' => self::NOT_ALLOWED_TAGS,
            ];
        }

        return [
            'active' => true,
            'reason' => '',
        ];
    }

    /**
     * Get a restriction from the restrictions array
     */
    protected function getRestriction(array $restrictions, string $key): array
    {
        $value = array_get($restrictions, $key, '');

        if (is_string($value)) {
            $value = explode(',', $value);
        } else {
            $value = [];
        }

        return (array) array_filter($value);
    }
}
