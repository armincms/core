<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <p <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showField): ?>
    <?= Form::radio($name, $options['value'], $options['checked'], $options['attr']) ?>

    <?php include 'help_block.php' ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false): ?>
    <?php if ($options['is_child']): ?>
        <label <?= $options['labelAttrs'] ?>><?= $options['label'] ?></label>
    <?php else: ?>
        <?= Form::label($name, $options['label'], $options['label_attr']) ?>
    <?php endif; ?>
<?php endif; ?>

<?php include 'errors.php' ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </p>
    <?php endif; ?>
<?php endif; ?>
