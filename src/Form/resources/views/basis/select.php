@if($showLabel && $showField)
    @if($options['wrapper'] !== false)
    <p <?= $options['wrapperAttrs'] ?> >
    @endif
@endif

@if($showLabel && $options['label'] !== false)
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>
@endif

@if($showField)
    <?php $emptyVal = $options['empty_value'] ? ['' => $options['empty_value']] : null; ?>
    <?= Form::select($name, (array)$emptyVal + $options['choices'], $options['selected'], $options['attr']) ?>
    @include'help_block.php' ?>
@endif

@include'errors.php' ?>

@if($showLabel && $showField)
    @if($options['wrapper'] !== false)
    </p>
    @endif
@endif
