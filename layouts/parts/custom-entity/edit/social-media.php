<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    entity-social-media
');
?>

<div class="col-12">
    <entity-social-media :entity="entity" classes="col-12" editable></entity-social-media>
</div>

