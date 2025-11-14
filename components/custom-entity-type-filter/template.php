<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */
?>

<div class="field" v-if="hasItems">
    <label class="field__label">
        {{ text('Tipo da entidade') }}
    </label>

    <select v-model="pseudoQuery['type']">
        <option :value="undefined">
            {{ text('Todos os tipos') }}
        </option>
        
        <option v-for="item in items" :key="item.value" :value="item.value">
            {{ item.label }}
        </option>
    </select>
</div>

