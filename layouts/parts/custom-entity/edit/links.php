<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-links
');
?>

<div class="col-12 grid-12">
    <div class="col-12">
        <entity-links :entity="entity" title="<?php i::_e('Adicionar links'); ?>" editable></entity-links>
    </div>
</div>

