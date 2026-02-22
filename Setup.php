<?php
namespace TylerAustin\AdditionalContactDetails;

use XF\AddOn\AbstractSetup;

class Setup extends AbstractSetup
{
    public function install(array $stepParams = [])
    {
        $this->applyUserFields();
    }

    public function upgrade(array $stepParams = [])
    {
        $this->applyUserFields();
    }

    protected function applyUserFields(): void
    {
        $this->createOrUpdateUserField(
            'cameo',
            'TylerAustin\\AdditionalContactDetails\\Validate\\Cameo::validate',
            100
        );
        $this->createOrUpdateUserField(
            'imdb',
            'TylerAustin\\AdditionalContactDetails\\Validate\\Imdb::validate',
            110
        );
        $this->createOrUpdateUserField(
            'steam',
            'TylerAustin\\AdditionalContactDetails\\Validate\\Steam::validate',
            120
        );
        $this->createOrUpdateUserField(
            'bluesky',
            'TylerAustin\\AdditionalContactDetails\\Validate\\Bluesky::validate',
            130
        );
    }

    protected function createOrUpdateUserField(
        string $fieldId,
        string $callback,
        int $displayOrder
    ): void {
        $em = \XF::em();
        $field = $em->find('XF:UserField', $fieldId);
        if (!$field) {
            $field = $em->create('XF:UserField');
            $field->field_id = $fieldId;
        }

        $field->display_group = 'contact';
        $field->display_order = $displayOrder;
        $field->field_type = 'textbox';
        $field->match_type = 'callback';

        [$class, $method] = explode('::', $callback, 2);
        $field->match_params = [
            'callback_class' => $class,
            'callback_method' => $method
        ];

        $field->max_length = 200;
        $field->user_editable = 'yes';
        $field->viewable_profile = true;
        $field->viewable_message = false;
        $field->display_template = '<a href="{$value}" target="_blank" rel="nofollow noopener">{$value}</a>';

        $field->save();
    }

    public function uninstall(array $stepParams = [])
    {
        $fieldIds = ['cameo', 'imdb', 'steam', 'bluesky'];

        foreach ($fieldIds as $fieldId) {
            $field = \XF::em()->find('XF:UserField', $fieldId);
            if ($field) {
                $field->delete();
            }

            foreach ([
                "user_field_title.{$fieldId}",
                "user_field_desc.{$fieldId}",
            ] as $phraseId) {
                $this->deleteMasterPhrase($phraseId);
            }
        }

        foreach ([
            'please_enter_valid_cameo_url',
            'please_enter_valid_imdb_url',
            'please_enter_valid_steam_url',
            'please_enter_valid_bluesky_handle',
        ] as $phraseId) {
            $this->deleteMasterPhrase($phraseId);
        }
    }

    protected function deleteMasterPhrase(string $title): void
    {
        $phrases = \XF::finder('XF:Phrase')
            ->where('title', $title)
            ->fetch();

        foreach ($phrases as $phrase) {
            $phrase->delete();
        }
    }
}
