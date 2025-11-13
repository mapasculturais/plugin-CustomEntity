<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;
use CustomEntity\Plugin;

$definition = Plugin::$intance->getEntityDefinition();

?>

<div class="field search-filter__<?= $definition->slug ?>-status">
    <label> <?= $definition->text(i::__('Status da entidade')) ?> </label>
    <?php $this->applyTemplateHook("search-filter-{$definition->slug}-field", 'before') ?>
    <label class="verified">
        <input v-model="pseudoQuery['@verified']" type="checkbox">
        <?= $definition->text(i::__('Somente verificadas')) ?>
    </label>
    <?php $this->applyTemplateHook("search-filter-{$definition->slug}-field", 'after') ?>
</div>