<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\Definitions\Taxonomy as DefinitionsTaxonomy;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

/** @package CustomEntity\Parts */
class Taxonomy extends Part
{
    public readonly string $taxonomyDescription;
    protected array $restrictedTerms = [];

    public function __construct(
        public readonly string $taxonomySlug
    ) {}

    static function add($taxonomy_slug = null): static
    {
        $instance = parent::add($taxonomy_slug);

        return $instance;
    }

    public function description(string $description): static
    {
        $this->taxonomyDescription = $description;

        return $this;
    }

    public function terms(array $terms): static
    {
        $this->restrictedTerms = $terms;

        return $this;
    }

    public function getSubParts(): array
    {
        return [
            TermRelations::add()
        ];
    }

    public function register(EntityDefinition $entity_definition)
    {
        $app = App::i();

        if ($registeredTaxonomy = $app->getRegisteredTaxonomyBySlug($this->taxonomySlug)) {
            $registeredTaxonomy->entities[] = $entity_definition->entityClassName;
            $app->registerTaxonomy($entity_definition->entityClassName, $registeredTaxonomy);
            return;
        }


        $definition = new DefinitionsTaxonomy(
            id: crc32($this->taxonomySlug),
            slug: $this->taxonomySlug,
            description: $this->taxonomyDescription,
            restrictedTerms: $this->restrictedTerms,
            taxonomy_required: $this->isRequired,
            entities: [$entity_definition->entityClassName]
        );

        $app->registerTaxonomy($entity_definition->entityClassName, $definition);
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $self = $this;

        $app->hook("template({$entity_definition->slug}.edit.tab-info--content--right):begin", function () use ($app, $self) {
            /** @var Theme $this */

            $taxonomy = $app->getRegisteredTaxonomyBySlug($self->taxonomySlug);
            $this->part('custom-entity/edit/taxonomy', ['taxonomy' => $taxonomy]);
        });


        $app->hook("template({$entity_definition->slug}.single.tab-info--aside):begin", function () use ($app, $self) {
            /** @var Theme $this */

            $taxonomy = $app->getRegisteredTaxonomyBySlug($self->taxonomySlug);
            $this->part('custom-entity/single/taxonomy', ['taxonomy' => $taxonomy]);
        });

        $app->hook("template(search.{$entity_definition->slug}.search-filter-{$entity_definition->slug}):after", function () use ($app, $self) {
            /** @var Theme $this */

            $taxonomy = $app->getRegisteredTaxonomyBySlug($self->taxonomySlug);
            $this->part('custom-entity/search/taxonomy', ['taxonomy' => $taxonomy]);
        });

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () use ($app, $self) {
            /** @var Theme $this */
            $taxonomy = $app->getRegisteredTaxonomyBySlug($self->taxonomySlug);
            
            if($taxonomy->required) {
                $this->part('custom-entity/edit/taxonomy', ['taxonomy' => $taxonomy]);
            }
        });
    }
}
