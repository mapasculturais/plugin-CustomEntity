<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-files-list
');
?>

<div class="col-12 grid-12">
    <div class="col-12">
        <entity-files-list :entity="entity" group="downloads" title="<?php i::_e('Adicionar arquivos para download') ?>" editable></entity-files-list>
    </div>
</div>

