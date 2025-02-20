<?php

/**
 * Action tests for the description plugin.
 *
 * @group plugin_description
 * @group plugins
 *
 * @author Mark C. Prins <mprins@users.sf.net>
 *
 * @noinspection AutoloadingIssuesInspection
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 */
class action_plugin_description_test extends DokuWikiTest
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
        $conf['plugin']['description']['keyword_source'] = 'abstract';
    }

    /**
     * @throws Exception if anything goes wrong
     */
    final public function testActionHeader(): void
    {
        $request = new TestRequest();
        $response = $request->get(array('id' => 'wiki:syntax'));

        // check description meta headers
        $this->assertStringContainsString(
            'DokuWiki supports some simple markup language',
            $response->queryHTML('meta[name="description"]')->attr('content')
        );
    }
}
