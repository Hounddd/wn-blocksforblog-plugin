<h5><b><?= trans('hounddd.blocksforblog::lang.settings.control') ?></b></h5>
<p><?= trans('hounddd.blocksforblog::lang.settings.control_description') ?></p>
<?php
    use Hounddd\Blocksforblog\Classes\BlocksHelper;

    $blocksHelper = BlocksHelper::instance();

    $blocksHelper->setRestrictions([
        'allowed_blocks' => $formModel->allowed_blocks,
        'allowed_tags' => $formModel->allowed_tags,
        'ignored_blocks' => $formModel->ignored_blocks,
        'ignored_tags' => $formModel->ignored_tags,
    ]);


    $blocks = $blocksHelper->getBlocks();

    // Set block statuses
    array_walk(
        $blocks,
        function (&$block, $key) use ($blocksHelper) {
            $block = array_merge(
                $block,
                $blocksHelper->isBlockAllowed($block['code'], $block['tags'])
            );
        }
    );

    // Sort blocks by active status
    uasort($blocks, function ($a, $b) {
        if ($a['active'] !== $b['active']) {
            return $b['active'] ? 1 : -1;
        }
        // If both have the same active status, maintain original order
        return 0;
    });
?>
<div class="control-list">
    <table class="table data table-hover">
        <thead>
            <tr>
                <th><span><?= trans('hounddd.blocksforblog::lang.settings.name'); ?></span></th>
                <th><span><?= trans('hounddd.blocksforblog::lang.settings.tags'); ?></span></th>
                <th><span><?= trans('hounddd.blocksforblog::lang.settings.status'); ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($blocks as $block) : ?>
            <tr class="<?= $block['active'] ? 'active success' : 'negative'; ?>">
                <td><?= $block['name']; ?></td>
                <td><?= implode(', ', $block['tags']); ?></td>
                <td>
                    <span class="list-badge badge-<?= $block['active'] ? 'success' : 'danger'; ?>">
                        <i class="icon-<?= $block['active'] ? 'check' : 'times'; ?>"></i>
                    </span>
                    <?php if ($block['reason'] !== '') : ?>
                    <?= trans('hounddd.blocksforblog::lang.settings.'. $block['reason']); ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
