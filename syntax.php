<?php

/**
 * Description plugin.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Matthias Schulte <dokuwiki@lupo49.de>
 * @author     Mark C. Prins <mprins@users.sf.net>
 *
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 * @noinspection AutoloadingIssuesInspection
 */

use dokuwiki\Extension\SyntaxPlugin;

class syntax_plugin_description extends SyntaxPlugin
{
    final  public function getType(): string
    {
        return 'substition';
    }

    final public function getPType(): string
    {
        return 'block';
    }

    final  public function getSort(): int
    {
        return 98;
    }

    final  public function connectTo($mode): void
    {
        $this->Lexer->addSpecialPattern('\{\{description>.+?\}\}', $mode, 'plugin_description');
    }

    final public function handle($match, $state, $pos, Doku_Handler $handler): array
    {
        $match = substr($match, 14, -2); // strip markup
        $match = hsc($match);

        return [$match];
    }

    final public function render($format, Doku_Renderer $renderer, $data): bool
    {
        $description = $data[0];
        if (empty($description)) {
            return false;
        }

        if ($format === 'metadata') {
            $renderer->meta['plugin_description']['keywords'] = $description;
            return true;
        }
        return false;
    }
}
