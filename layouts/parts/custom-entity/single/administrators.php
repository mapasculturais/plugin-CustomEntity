<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-admins
');
?>

<div class="col-12">
    <entity-admins :entity="entity" classes="col-12"></entity-admins>
</div>

