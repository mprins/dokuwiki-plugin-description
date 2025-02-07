<?php

/**
 * Syntax 'keyword_source' tests for the description plugin.
 *
 * @group plugin_description
 * @group plugins
 *
 * @author Mark C. Prins <mprins@users.sf.net>
 *
 * @noinspection AutoloadingIssuesInspection
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 */
class syntax_plugin_description_test extends DokuWikiTest
{
    protected $pluginsEnabled = array('description');

    /**
     * copy data and pages.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        TestUtils::rcopy(TMP_DIR, __DIR__ . '/data/');
    }

    final public function setUp(): void
    {
        global $conf;
        parent::setUp();
        $conf['plugin']['description']['keyword_source'] = 'syntax';
    }

    /**
     * @throws Exception if anything goes wrong
     */
    final public function testHeaderFromSyntax(): void
    {
        $request = new TestRequest();
        $response = $request->get(array('id' => 'description_syntax'));

        // check description meta headers, set from file
        $this->assertStringContainsString(
            'Place the page description here',
            $response->queryHTML('meta[name="description"]')->attr('content')
        );
    }
}
