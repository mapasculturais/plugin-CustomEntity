<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;
use CustomEntity\Plugin;

$this->import('
    mc-tab
    search-map
    search-filter-custom-entity
');

$definition = Plugin::$intance->getEntityDefinition();
?>

<mc-tab icon="map" label="<?php i::esc_attr_e('Mapa') ?>" slug="map">
    <div class="search__tabs--map">
        <search-map type="<?= $definition->slug ?>" :pseudo-query="pseudoQuery">
            <template #filter>
                <search-filter-custom-entity :pseudo-query="pseudoQuery" position="map"></search-filter-custom-entity>
            </template>
        </search-map>
    </div>
</mc-tab>