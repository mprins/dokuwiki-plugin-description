<?php

/**
 *  Description action plugin.
 *
 * @license      GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author       Ikuo Obataya <I.Obataya@gmail.com>
 * @author       Matthias Schulte <dokuwiki@lupo49.de>.
 * @author       Mark C. Prins <mprins@users.sf.net>
 *
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 * @noinspection AutoloadingIssuesInspection
 */

use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\Event;
use dokuwiki\Extension\EventHandler;

const KEYWORD_SOURCE_ABSTRACT = 'abstract';
const KEYWORD_SOURCE_GLOBAL = 'global';
const KEYWORD_SOURCE_SYNTAX = 'syntax';

class action_plugin_description extends ActionPlugin
{
    final public function register(EventHandler $controller): void
    {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'description', []);
    }

    /**
     * Add an abstract, global value or a specified string to meta header
     */
    final public function description(Event $event): void
    {
        if (empty($event->data) || empty($event->data['meta'])) {
            return;
        }

        global $ID;
        $source = $this->getConf('keyword_source');
        if (empty($source)) {
            $source = 'abstract';
        }

        $metaContent = '';
        switch ($source) {
            case KEYWORD_SOURCE_ABSTRACT:
                if (auth_quickaclcheck($ID) < AUTH_READ) {
                    // don't add meta header when user has no read permissions
                    return;
                }
                $d = p_get_metadata($ID, 'description');
                if (empty($d)) {
                    return;
                }
                $metaContent = str_replace("\n", " ", $d['abstract']);
                if (empty($metaContent)) {
                    return;
                }
                break;
            case KEYWORD_SOURCE_GLOBAL:
                $metaContent = $this->getConf('global_description');
                if (empty($metaContent)) {
                    return;
                }
                break;
            case KEYWORD_SOURCE_SYNTAX:
                if (auth_quickaclcheck($ID) < AUTH_READ) {
                    // don't add meta header when user has no read permissions
                    return;
                }
                $metadata = p_get_metadata($ID);

                // Normalize to an array
                if (!is_array($metadata)) {
                    $metadata = [];
                }

                // Safely read keywords if the structure is present and an array
                if (isset($metadata['plugin_description']) && is_array($metadata['plugin_description'])) {
                    $metaContent = $metadata['plugin_description']['keywords'] ?? '';
                }

                if (empty($metaContent)) {
                    return;
                }
                break;
        }

        $event->data['meta'][] = ["name" => "description", "content" => $metaContent];
    }
}
