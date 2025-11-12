<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('entity-map');
$showLatLongFields = $showLatLongFields ?? false;
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

<?php if ($showLatLongFields): ?>
    <div class="col-12 grid-12" v-if="entity.location || (entity.location = { lat: null, lng: null, latitude: null, longitude: null })">
        <div class="field col-6 sm:col-12">
            <label class="field__title">
                <?php i::_e('Latitude'); ?>
            </label>
            <div class="field__input">
                <input
                    type="number"
                    step="any"
                    placeholder="<?= i::__('Informe a latitude'); ?>"
                    v-model.number="entity.location.lat"
                    @input="entity.location.latitude = entity.location.lat">
            </div>
        </div>
        <div class="field col-6 sm:col-12">
            <label class="field__title">
                <?php i::_e('Longitude'); ?>
            </label>
            <div class="field__input">
                <input
                    type="number"
                    step="any"
                    placeholder="<?= i::__('Informe a longitude'); ?>"
                    v-model.number="entity.location.lng"
                    @input="entity.location.longitude = entity.location.lng">
            </div>
        </div>
    </div>
<?php endif; ?>