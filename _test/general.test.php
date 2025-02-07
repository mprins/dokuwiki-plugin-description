<?php

/**
 * General tests for the description plugin.
 *
 * @group plugin_description
 * @group plugins
 *
 * @author Mark C. Prins <mprins@users.sf.net>
 *
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 * @noinspection AutoloadingIssuesInspection
 */
class general_plugin_description_test extends DokuWikiTest
{

    protected $pluginsEnabled = array('description');

    /**
     * Simple test to make sure the plugin.info.txt is in correct format
     */
    final public function testPluginInfo(): void
    {
        $file = __DIR__ . '/../plugin.info.txt';
        $this->assertFileExists($file);

        $info = confToHash($file);

        $this->assertArrayHasKey('base', $info);
        $this->assertArrayHasKey('author', $info);
        $this->assertArrayHasKey('email', $info);
        $this->assertArrayHasKey('date', $info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('desc', $info);
        $this->assertArrayHasKey('url', $info);

        $this->assertEquals('description', $info['base']);
        $this->assertRegExp('/^https?:\/\//', $info['url']);
        $this->assertTrue(mail_isvalid($info['email']));
        $this->assertRegExp('/^\d\d\d\d-\d\d-\d\d$/', $info['date']);
        $this->assertNotFalse(strtotime($info['date']));
    }

    /**
     * test if plugin is loaded.
     */
    final public function testDescriptionPluginIsLoaded(): void
    {
        global $plugin_controller;
        $this->assertContains(
            'description',
            $plugin_controller->getList(),
            "description plugin is loaded"
        );
    }

    /**
     * test if plugin is loaded.
     */
    final public function testSyntaxLoading(): void
    {
        $index = plugin_load('syntax', 'description');
        $this->assertInstanceOf(syntax_plugin_description::class, $index);
    }
}
