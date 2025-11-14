<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;
use MapasCulturais\Utils;

class SocialMedia extends Part
{
    public function getSubParts(): array
    {
        return [
            Metadata::add()
        ];
    }

    public function getEntityMetadata(): array
    {
        return [
            'facebook' => array(
                'type' => "socialMedia",
                'label' => i::__('Facebook'),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('facebook.com', $value);
                },
                'validations' => array(
                    "v::oneOf(v::urlDomain('facebook.com'), v::regex('/^@?([-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL válida ou o nome ou id do usuário.")
                ),
                'placeholder' => i::__('nomedousuario ou iddousuario'),
                'available_for_opportunities' => true
            ),
            'twitter' => array(
                'type' => "socialMedia",
                'label' => i::__('Twitter'),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('x.com', $value);
                },
                'validations' => array(
                    "v::oneOf(v::urlDomain('x.com'), v::regex('/^@?([-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'placeholder' => i::__('nomedousuario'),
                'available_for_opportunities' => true
            ),
            'instagram' => array(
                'type' => "socialMedia",
                'label' => i::__('Instagram'),
                'available_for_opportunities' => true,
                'serialize' => function ($value) {
                    $result = Utils::parseSocialMediaUser('instagram.com', $value);
                    if ($result && $result[0] == '@') {
                        $result = substr($result, 1);
                    }
                    return $result;
                },
                'validations' => array(
                    "v::oneOf(v::urlDomain('instagram.com'), v::regex('/^@?([-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'placeholder' => i::__('nomedousuario'),
            ),
            'linkedin' => array(
                'type' => "socialMedia",
                'label' => i::__('Linkedin'),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('linkedin.com', $value, 'linkedin');
                },
                'validations' => array(
                    "v::oneOf(v::urlDomain('linkedin.com'), v::regex('/^@?([\-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'placeholder' => i::__('nomedousuario'),
                'available_for_opportunities' => true
            ),
            'vimeo' => array(
                'type' => "socialMedia",
                'label' => i::__('Vimeo'),
                'validations' => array(
                    "v::oneOf(v::urlDomain('vimeo.com'), v::regex('/^@?([-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('vimeo.com', $value);
                },
                'placeholder' => i::__('nomedousuario'),
                'available_for_opportunities' => true
            ),
            'spotify' => array(
                'type' => "socialMedia",
                'label' => i::__('Spotify'),
                'validations' => array(
                    "v::oneOf(v::urlDomain('open.spotify.com'), v::regex('/^@?([-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('open.spotify.com', $value);
                },
                'placeholder' => i::__('nomedousuario'),
                'available_for_opportunities' => true
            ),
            'youtube' => array(
                'type' => "socialMedia",
                'label' => i::__('YouTube'),
                'validations' => array(
                    "v::oneOf(v::urlDomain('youtube.com'), v::regex('/^(@|channel\/)?([-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('youtube.com', $value);
                },
                'placeholder' => i::__('iddocanal'),
                'available_for_opportunities' => true
            ),
            'pinterest' => array(
                'type' => "socialMedia",
                'label' => i::__('Pinterest'),
                'validations' => array(
                    "v::oneOf(v::urlDomain('pinterest.com'), v::regex('/^@?([\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('pinterest.com', $value);
                },
                'placeholder' => i::__('nomedousuario'),
                'available_for_opportunities' => true
            ),
            'tiktok' => array(
                'type' => "socialMedia",
                'label' => i::__('Tiktok'),
                'serialize' => function ($value) {
                    return Utils::parseSocialMediaUser('tiktok.com', $value);
                },
                'validations' => array(
                    "v::oneOf(v::urlDomain('tiktok.com'), v::regex('/^@?([-\w\d\.]+)$/i'))" => i::__("O valor deve ser uma URL ou usuário válido.")
                ),
                'placeholder' => i::__('nomedousuario'),
                'available_for_opportunities' => true
            ),
            'fediverso' => array(
                'type' => "socialMedia",
                'label' => i::__('Fediverso'),
                'available_for_opportunities' => true,
                'serialize' => function ($value) {
                    return $value;
                },
                'validations' => array(
                    "v::url()" => i::__("A url informada é inválida.")
                ),
                'placeholder' => i::__('https://nomedoservidor.com.br/@nomedousuario'),
            ),
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();

        $app->hook("template({$entity_definition->slug}.edit.tab-info--aside):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/social-media');
        });
    }
}
