<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-gallery
');
?>

<div class="col-12 grid-12">
    <div class="col-12">
        <entity-gallery :entity="entity" title="<?php i::_e('Adicionar fotos na galeria') ?>" editable></entity-gallery>
    </div>
</div>
