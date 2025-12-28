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

    /**
     * Test that pages without description syntax don't cause PHP errors
     * This tests the fix for issue #6
     *
     * @throws Exception if anything goes wrong
     */
    final public function testNoDescriptionSyntaxNoError(): void
    {
        $request = new TestRequest();
        $response = $request->get(array('id' => 'no_description'));

        // Page should load successfully without PHP errors
        $this->assertNotNull($response);
        
        // No description meta tag should be present
        $metaTags = $response->queryHTML('meta[name="description"]');
        $this->assertEquals(0, $metaTags->count());
    }
}
