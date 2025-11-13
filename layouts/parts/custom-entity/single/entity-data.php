<?php 
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 * 
 * @var string $property
 * @var string $label
 */

$this->import('entity-data');
?>

<div v-if="entity.<?= $property ?>" class="<?= $col ?? 'col-12' ?>">
    <entity-data 
        :entity="entity" 
        prop="<?= $property ?>" 
        <?php if($label ?? false): ?> label="<?= $label?>" <?php endif; ?>
        class="additional-info__item <?= $col ?? 'col-12' ?>"
    ></entity-data>
</div>