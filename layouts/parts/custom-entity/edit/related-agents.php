<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-related-agents
');
?>

<div class="col-12">
    <entity-related-agents :entity="entity" classes="col-12" editable></entity-related-agents>
</div>

