<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-gallery-video
');
?>

<div class="col-12 grid-12">
    <div class="col-12">
        <entity-gallery-video :entity="entity" title="<?php i::_e('Adicionar vídeos') ?>" editable></entity-gallery-video>
    </div>
</div>

