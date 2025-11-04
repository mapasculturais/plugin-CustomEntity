<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */
use MapasCulturais\i;

$this->import('entity-field');
?>
<div class="col-12 grid-12">
    <entity-field :entity="entity" classes="col-12" prop="longDescription" label="<?php i::_e('Descrição'); ?>"></entity-field>
</div>

