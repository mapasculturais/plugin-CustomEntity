# CustomEntity

Plugin para o Mapas Culturais que permite criar novos tipos de entidade de forma declarativa, sem necessidade de escrever código boilerplate.

## Como funciona

O plugin utiliza uma arquitetura baseada em composição. Você define uma `EntityDefinition` informando o slug, ícone, cor e uma lista de `Parts` (partes). Cada `Part` adiciona uma funcionalidade específica à entidade (nome, descrição, localização, galeria de imagens, etc.).

A partir dessa definição, o plugin gera automaticamente:

- **Classe da entidade** (Doctrine ORM) com os traits e validações necessários
- **Classe do controller** com os traits necessários
- **Classes auxiliares** (File, AgentRelation, Meta, TermRelation, PermissionCache)
- **Arquivo CSS** com classes de cor baseadas na cor definida
- **Rotas** de busca, painel e edição
- **Atualização do schema** do banco de dados

O sistema de cache por hash evita re-gerações desnecessárias — os arquivos só são recriados quando a definição muda.

## Ativação

Configure o plugin no arquivo `dev/config.d/plugins.php`:

```php
<?php

use CustomEntity\EntityDefinition;
use CustomEntity\Parts as parts;

return [
    'plugins' => [
        'CustomEntity' => [
            'entities' => fn() => [
                new EntityDefinition(
                    slug: 'especie',
                    owner: parts\OwnerAgent::add()
                        ->label('Guardião'),

                    icon: 'f7:tree',
                    color: '#b86a6a',
                    texts: [
                        'entidades' => 'espécies',
                        'entidade' => 'espécie',
                    ],

                    parts: [
                        parts\Panel::add(),
                        parts\Search::add(),

                        parts\Avatar::add(),
                        parts\Header::add(),
                        parts\Links::add(),
                        parts\Downloads::add(),

                        parts\Type::add([
                            1 => 'Frutífera',
                            2 => 'Extrativista',
                            3 => 'Medicinal',
                            4 => 'Outras'
                        ]),

                        parts\Name::add()->required(),
                        parts\ShortDescription::add(),

                        parts\GeoLocation::add()
                            ->showLatLongFields(true)
                            ->required(),

                        parts\MetadataField::add('setor')
                            ->label('Setor / Região'),

                        parts\MetadataField::add('nomeCientifico')
                            ->label('Nome Científico'),

                        parts\MetadataField::add('pontoDeReferencia')
                            ->label('Ponto de Referência'),

                        parts\MetadataField::add('situacao')
                            ->label('Situação')
                            ->type('select')
                            ->options([
                                'Preservado',
                                'Em risco',
                                'Em manejo'
                            ]),

                        parts\LongDescription::add(),

                        parts\Taxonomy::add('usos')
                            ->description('Usos Tradicionais')
                            ->terms([
                                'Alimentar',
                                'Cosmético',
                                'Econômico',
                                'Fitoterápico',
                                'Medicinal',
                            ]),

                        parts\ImageGallery::add(),
                        parts\VideoGallery::add(),

                        parts\Administrators::add(),
                        parts\RelatedAgents::add(),

                        parts\Statuses::add(),
                    ],
                ),
            ]
        ],
    ]
];
```

## Parâmetros do EntityDefinition

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `slug` | `string` | Sim | Identificador único da entidade (usado em URLs, rotas e tabela do banco) |
| `owner` | `OwnerPart` | Sim | Agente proprietário da entidade. Use `parts\OwnerAgent::add()->label('Rótulo')` |
| `icon` | `string` | Não | Ícone da entidade (nome do ícone do iconify, ex: `f7:tree`, `mdi:leaf`) |
| `color` | `string` | Não | Cor hexadecimal principal (default: `#19d758`). Gera variações CSS automaticamente |
| `texts` | `array` | Não | Substituições de texto. Chaves: `entidade`, `entidades`, `sua entidade`, `suas entidades`, `minha entidade`, `minhas entidades`, `a entidade`, `as entidades`, `da entidade`, `das entidades` |
| `parts` | `Part[]` | Não | Lista de partes que compõem a entidade |
| `entity` | `string` | Não | Nome da classe gerada (default: `ucfirst($slug)`) |
| `table` | `string` | Não | Nome da tabela no banco (default: `$slug`) |
| `editSections` | `array` | Não | Seções do formulário de edição (default: `['more-info' => 'Mais informações']`) |
| `singleSections` | `array` | Não | Seções da página de visualização (default: `['more-info' => 'Mais informações']`) |

## Catálogo de Parts

### Estruturais

| Part | Descrição |
|------|-----------|
| `Panel::add()` | Adiciona entrada no painel do usuário ("Minhas Entidades") e habilita a API |
| `Search::add()` | Adiciona página de busca e entrada no menu principal, e habilita a API |
| `Statuses::add()` | Agrupa os status: rascunho, arquivado, privado e exclusão suave |

### Campos básicos

| Part | Descrição | API fluente |
|------|-----------|-------------|
| `Name::add()` | Campo de nome (varchar 255) | `->required($msg)`, `->fieldType($type)`, `->options([$id => $label])` |
| `ShortDescription::add()` | Descrição curta (text) | `->required($msg)` |
| `LongDescription::add()` | Descrição longa com editor rico (text) | `->required($msg)` |
| `Type::add([$id => $label])` | Classificação por tipos (smallint) | `->required($msg)` |
| `MetadataField::add($key)` | Campo de metadado genérico | `->label($str)`, `->type($str)`, `->fieldType($str)`, `->options($arr)`, `->defaultValue($val)`, `->required($msg)`, `->unique($msg)`, `->private()`, `->readonly()`, `->serializer($fn)`, `->unserializer($fn)`, `->useAsKeyword($unnaccent, $lower)` |

### Mídia

| Part | Descrição |
|------|-----------|
| `Avatar::add()` | Imagem de avatar (jpeg/png) |
| `Header::add()` | Imagem de cabeçalho/banner (jpeg/png) |
| `ImageGallery::add()` | Galeria de imagens (jpeg/png/gif/webp) |
| `VideoGallery::add()` | Galeria de vídeos (links) |
| `Downloads::add()` | Arquivos para download |

### Localização

| Part | Descrição | API fluente |
|------|-----------|-------------|
| `GeoLocation::add()` | Ponto geográfico (latitude/longitude) com mapa | `->required($msg)`, `->showLatLongFields($bool)` |

### Classificação

| Part | Descrição | API fluente |
|------|-----------|-------------|
| `Taxonomy::add($slug)` | Taxonomia com termos pré-definidos | `->description($str)`, `->terms($arr)`, `->useAsKeyword($unnaccent, $lower)` |
| `Links::add()` | Links externos | — |
| `SocialMedia::add()` | Links de redes sociais (facebook, instagram, twitter, linkedin, youtube, tiktok, etc.) | — |

### Relacionamentos

| Part | Descrição | API fluente |
|------|-----------|-------------|
| `OwnerAgent::add()` | Agente proprietário (obrigatório) | `->label($str)` |
| `Administrators::add()` | Agentes administradores | — |
| `RelatedAgents::add()` | Agentes relacionados genéricos | — |
| `Seals::add()` | Selos associados à entidade | — |

### Status (geralmente usados via `Statuses::add()`)

| Part | Descrição |
|------|-----------|
| `StatusDraft::add()` | Permite salvar como rascunho |
| `StatusArchive::add()` | Permite arquivar |
| `StatusPrivate::add()` | Permite tornar privado |
| `SoftDelete::add()` | Exclusão suave (marcar como excluído sem remover do banco) |

## API comum a todas as Parts

| Método | Descrição |
|--------|-----------|
| `->required($msg)` | Torna o campo obrigatório com mensagem de erro customizada |
| `->editPosition($section, $anchor, $priority)` | Posição no formulário de edição |
| `->singlePosition($section, $anchor, $priority)` | Posição na página de visualização |

## Múltiplas entidades

O plugin suporta a criação de múltiplas entidades basta adicionar mais `EntityDefinition` no array:

```php
'CustomEntity' => [
    'entities' => fn() => [
        new EntityDefinition(slug: 'especie', /* ... */),
        new EntityDefinition(slug: 'projeto', /* ... */),
        new EntityDefinition(slug: 'iniciativa', /* ... */),
    ]
],
```

## Arquitetura

```
CustomEntity/
├── Plugin.php                # Registro e inicialização
├── EntityDefinition.php      # Definição declarativa da entidade
├── EntityGenerator.php       # Gera a classe Entity em runtime
├── ControllerGenerator.php   # Gera a classe Controller em runtime
├── EntityCssGenerator.php    # Gera CSS com variações de cor
├── Part.php                  # Classe base abstrata das Parts
├── OwnerPart.php             # Configuração do agente proprietário
├── Position.php              # Posicionamento em templates (section/anchor/priority)
├── Color.php                 # Utilitário de manipulação de cores
├── Repository.php            # Repository base com suporte a keywords
├── Parts/                    # Parts disponíveis para composição
│   ├── Name.php
│   ├── Type.php
│   ├── MetadataField.php
│   ├── GeoLocation.php
│   ├── Taxonomy.php
│   ├── ... (e demais Parts)
│   └── Traits/               # Traits auxiliares das Parts
│       ├── PartPosition.php
│       └── Keywords.php
├── Traits/                   # Traits de entidade (campos Doctrine)
│   ├── EntityType.php
│   ├── EntityName.php
│   ├── EntityShortDescription.php
│   └── EntityLongDescription.php
├── templates/                # Templates usados na geração de código
│   ├── Entity.php
│   ├── Controller.php
│   ├── EntityMeta.php
│   ├── EntityFile.php
│   ├── EntityAgentRelation.php
│   ├── EntityTermRelation.php
│   ├── EntitySealRelation.php
│   ├── EntityPermissionCache.php
│   └── style.css
├── Entities/                 # Classes geradas em runtime (gitignored)
├── Controllers/              # Classes geradas em runtime (gitignored)
├── assets/css/               # CSS gerado em runtime (gitignored)
├── views/                    # Views de busca, edição e painel
├── layouts/                  # Layouts de edição e visualização
└── components/               # Componentes Vue.js (filtros, busca, criação)
```
