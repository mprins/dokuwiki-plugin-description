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
class global_plugin_description_test extends DokuWikiTest
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
        $conf['plugin']['description']['keyword_source'] = 'global';
        $conf['plugin']['description']['global_description'] = 'my global description';
    }

    /**
     * @throws Exception if anything goes wrong
     */
    final public function testHeaderFromSyntax(): void
    {
        $index = plugin_load('syntax', 'description');
        $this->assertInstanceOf(syntax_plugin_description::class, $index);

        $request = new TestRequest();
        $response = $request->get(array('id' => 'description_syntax'));

        // check description meta headers, set from file
        $this->assertEquals(
            'my global description',
            $response->queryHTML('meta[name="description"]')->attr('content')
        );
    }
}