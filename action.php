<?php

/*
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 * @noinspection AutoloadingIssuesInspection
 */

/**
 *  Description action plugin.
 *
 * @license      GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author       Ikuo Obataya <I.Obataya@gmail.com>
 * @author       Matthias Schulte <dokuwiki@lupo49.de>.
 * @author       Mark C. Prins <mprins@users.sf.net>
 *
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
    final public function description(Event $event, $param): void
    {
        if (empty($event->data) || empty($event->data['meta'])) {
            return;
        }

        global $ID;
        $source = $this->getConf('keyword_source');
        if (empty($source)) {
            $source = 'abstract';
        }

        if ($source === KEYWORD_SOURCE_ABSTRACT) {
            if (auth_quickaclcheck($ID) < AUTH_READ) {
                // don't add meta header when user has no read permissions
                return;
            }

            $d = p_get_metadata($ID, 'description');
            if (empty($d)) {
                return;
            }

            $a = str_replace("\n", " ", $d['abstract']);
            if (empty($a)) {
                return;
            }
        }

        if ($source === KEYWORD_SOURCE_GLOBAL) {
            $a = $this->getConf('global_description');
            if (empty($a)) {
                return;
            }
        }

        if ($source === KEYWORD_SOURCE_SYNTAX) {
            if (auth_quickaclcheck($ID) < AUTH_READ) {
                // don't add meta header when user has no read permissions
                return;
            }
            $metadata = p_get_metadata($ID);
            $a = $metadata['plugin_description']['keywords'];
            if (empty($a)) {
                return;
            }
        }

        $m = ["name" => "description", "content" => $a];
        $event->data['meta'][] = $m;
    }
}
