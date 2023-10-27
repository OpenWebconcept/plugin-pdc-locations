<?php

namespace OWC\PDC\Locations\Metabox;

use CMB2;
use OWC\PDC\Base\Foundation\ServiceProvider;

class MetaboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->plugin->loader->addAction('cmb2_admin_init', $this, 'registerMetaboxes', 10, 0);
    }

    public function registerMetaboxes(): void
    {
        $configMetaboxes = $this->plugin->config->get('cmb2_metaboxes');

        if (! is_array($configMetaboxes)) {
            return;
        }

        $configMetaboxes = apply_filters('owc/pdc-locations/before-register-metaboxes', $configMetaboxes);

        foreach ($configMetaboxes as $configMetabox) {
            if (! is_array($configMetabox)) {
                continue;
            }

            $this->registerMetabox(apply_filters(
                'owc/pdc-locations/before-register-metabox',
                $configMetabox
            ));
        }

    }

    protected function registerMetabox(array $configMetabox): void
    {
        $fields = $configMetabox['fields'] ?? [];
        unset($configMetabox['fields']); // Fields will be added later on.

        $metabox = \new_cmb2_box($configMetabox);

        if (empty($fields) || ! is_array($fields)) {
            return;
        }

        $this->registerMetaboxFields($metabox, $fields);
    }

    protected function registerMetaboxFields(CMB2 $metabox, array $fields): void
    {
        foreach ($fields as $field) {
            $fieldKeys = array_keys($field);

            foreach ($fieldKeys as $fieldKey) {
                if (! is_array($field[$fieldKey])) {
                    continue;
                }

                if (! empty($field[$fieldKey]['id'])) {
                    $field[$fieldKey]['id'] = sprintf('_owc_%s', $field[$fieldKey]['id']);
                }

                $metabox->add_field($field[$fieldKey]);
            }
        }
    }

}
