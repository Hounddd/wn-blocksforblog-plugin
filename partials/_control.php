<?php
    use Hounddd\Blocksforblog\Classes\BlocksHelper;
    use Symfony\Component\Yaml\Yaml;

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

<h5><b><?= trans('hounddd.blocksforblog::lang.settings.control') ?></b></h5>
<div class="row-flush">
    <div class="col-md-10">
        <p><?= trans('hounddd.blocksforblog::lang.settings.control_description') ?></p>
    </div>
    <div class="col-md-2">
        <a
            href="#blogFieldControl"
            data-toggle="modal"
            data-size="large"
            class="btn btn-primary btn-xs wn-icon-cubes"
        >
            <?= e(trans('hounddd.blocksforblog::lang.settings.view_field_defintion')) ?>
        </a>
    </div>
</div>


<div class="control-list list-scrollable" data-control="listwidget">
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

<div class="control-popup modal fade" id="blogFieldControl" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="popup">&times;</button>
                <h4 class="modal-title">
                    <?= e(trans('hounddd.blocksforblog::lang.settings.control_field')); ?>
                </h4>
            </div>
            <div class="modal-body">
                <p><?= e(trans('hounddd.blocksforblog::lang.settings.control_field_description')); ?></p>
                <?php
                    $blogContentBlocks =  array_filter($blocksHelper->getBlogBlocksField());
                    dump($blogContentBlocks);


                    // echo "<pre>". Yaml::dump($blogContentBlocks, 2) ."</pre>";
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= e(trans('backend::lang.form.close')); ?>
                </button>
            </div>
        </div>
    </div>
</div>
