<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('entity-owner');
?>
<entity-owner :entity="entity" classes="col-12" title="<?php i::_e('Publicado por'); ?>" editable></entity-owner>
