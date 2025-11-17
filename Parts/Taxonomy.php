<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Position;
use MapasCulturais\App;
use MapasCulturais\Definitions\Taxonomy as DefinitionsTaxonomy;
use MapasCulturais\Themes\BaseV2\Theme;

/** @package CustomEntity\Parts */
class Taxonomy extends Part
{
    use Traits\Keywords;
    use Traits\PartPosition;

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

    protected function getDefaultEditPosition(): Position
    {
        return new Position(section: 'aside', anchor: 'begin');
    }

    protected function getDefaultSinglePosition(): Position
    {
        return new Position(section: 'aside', anchor: 'begin');
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

        $this->editTemplateHook($entity_definition, function () use ($app, $self) {
            /** @var Theme $this */

            $taxonomy = $app->getRegisteredTaxonomyBySlug($self->taxonomySlug);
            $this->part('custom-entity/edit/taxonomy', ['taxonomy' => $taxonomy]);
        });

        $this->singleTemplateHook($entity_definition, function () use ($app, $self) {
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

        // keywords
        if($this->useAsKeyword) {
            $taxonomy_slug = $this->taxonomySlug;
            $t_alias = uniqid("{$taxonomy_slug}_t_");
            $tr_alias = uniqid("{$taxonomy_slug}_tr_");

            $this->keywordJoin($entity_definition, function (&$joins, $keyword, $alias) use($taxonomy_slug, $tr_alias, $t_alias) {
                /** @var \MapasCulturais\Repository $this */
                /** @var Taxonomy $self */

                $joins .= "LEFT JOIN e.__termRelations {$tr_alias}
                    LEFT JOIN $tr_alias.term {$t_alias}
                        WITH
                            {$t_alias}.taxonomy = '$taxonomy_slug'";
            });

            $this->keywordWhere($entity_definition, function (&$where, $keyword, $alias) use ($t_alias, $self) {
                /** @var \MapasCulturais\Repository $this */
                /** @var Taxonomy $self */
                $alias = ":$alias";
                $t_alias = "$t_alias.term";

                if($self->unnaccentKeyword) {
                    $t_alias = "unaccent($t_alias)";
                    $alias = "unaccent($alias)";
                }

                if($self->lowerKeyword) {
                    $t_alias = "lower($t_alias)";
                    $alias = "lower($alias)";
                }
                $where .= " OR {$t_alias} LIKE {$alias} ";
            });
        }
    }
}
