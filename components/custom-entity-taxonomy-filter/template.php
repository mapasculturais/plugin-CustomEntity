<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('
    mc-multiselect
    mc-tag-list
');
?>

<div class="field" v-if="item">
    <label class="field__label">
        {{ item.description }}
    </label>
    <mc-multiselect
        :model="pseudoQuery['term:' + item.slug]"
        :items="item.terms"
        :placeholder="text('Selecione os termos')"
        hide-filter
        hide-button
    ></mc-multiselect>
    <mc-tag-list
        editable
        :tags="pseudoQuery['term:' + item.slug]"
        :labels="item.terms"
        :classes="item.slug + '__background ' + item.slug + '__color'"
    ></mc-tag-list>
</div>

