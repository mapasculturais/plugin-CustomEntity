<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */
use MapasCulturais\i;

$this->import('entity-map');
?>
<div class="col-12 grid-12">
    <div class="field col-12">
        <label class="field__title">
            <?php i::_e('Localização no Mapa'); ?>
        </label>
        <div class="field__input">
            <entity-map :entity="entity" editable></entity-map>
        </div>
    </div>
</div>

