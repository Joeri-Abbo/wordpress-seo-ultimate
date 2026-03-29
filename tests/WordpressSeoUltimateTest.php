<?php

use PHPUnit\Framework\TestCase;

class WordpressSeoUltimateTest extends TestCase
{
    private WordpressSeoUltimate $plugin;

    protected function setUp(): void
    {
        $this->plugin = new WordpressSeoUltimate();
    }

    public function testClassExists(): void
    {
        $this->assertInstanceOf(WordpressSeoUltimate::class, $this->plugin);
    }

    public function testGetTagsReturnsAllSixHeadingsAndParagraph(): void
    {
        $method = new ReflectionMethod($this->plugin, 'getTags');
$tags = $method->invoke($this->plugin);

        $this->assertIsArray($tags);
        $this->assertCount(7, $tags);
        $this->assertSame('H1', $tags['1']);
        $this->assertSame('H6', $tags['6']);
        $this->assertSame('P', $tags['p']);
    }

    public function testCheckFieldIfHasHeadlinesReturnsTrueForText(): void
    {
        $method = new ReflectionMethod($this->plugin, 'checkFieldIfHasHeadlines');
$this->assertTrue($method->invoke($this->plugin, ['type' => 'text']));
    }

    public function testCheckFieldIfHasHeadlinesReturnsTrueForTextarea(): void
    {
        $method = new ReflectionMethod($this->plugin, 'checkFieldIfHasHeadlines');
$this->assertTrue($method->invoke($this->plugin, ['type' => 'textarea']));
    }

    public function testCheckFieldIfHasHeadlinesReturnsFalseForOtherTypes(): void
    {
        $method = new ReflectionMethod($this->plugin, 'checkFieldIfHasHeadlines');
$this->assertFalse($method->invoke($this->plugin, ['type' => 'select']));
        $this->assertFalse($method->invoke($this->plugin, ['type' => 'image']));
        $this->assertFalse($method->invoke($this->plugin, ['type' => 'flexible_content']));
    }

    public function testAddTagToHeadlinesAddsIntegerTag(): void
    {
        $method = new ReflectionMethod($this->plugin, 'addTagToHeadlines');
$field = ['type' => 'text', 'key' => 'field_abc123', 'tag' => 2];
        $result = $method->invoke($this->plugin, $field, []);

        $this->assertSame(['field_abc123' => 2], $result);
    }

    public function testAddTagToHeadlinesIgnoresNonIntegerTag(): void
    {
        $method = new ReflectionMethod($this->plugin, 'addTagToHeadlines');
$field = ['type' => 'text', 'key' => 'field_abc123', 'tag' => 'p'];
        $result = $method->invoke($this->plugin, $field, []);

        $this->assertEmpty($result);
    }

    public function testAddTagToHeadlinesIgnoresNonTextFields(): void
    {
        $method = new ReflectionMethod($this->plugin, 'addTagToHeadlines');
$field = ['type' => 'select', 'key' => 'field_abc123', 'tag' => 1];
        $result = $method->invoke($this->plugin, $field, []);

        $this->assertEmpty($result);
    }

    public function testGetTagsOfFlexibleContentExtractsSubFieldTags(): void
    {
        $method = new ReflectionMethod($this->plugin, 'getTagsOfFlexibleContent');
$layouts = [
            [
                'sub_fields' => [
                    ['type' => 'text', 'key' => 'field_sub1', 'tag' => 1],
                    ['type' => 'image', 'key' => 'field_sub2', 'tag' => 2],
                ],
            ],
        ];

        $result = $method->invoke($this->plugin, $layouts, []);

        $this->assertArrayHasKey('field_sub1', $result);
        $this->assertArrayNotHasKey('field_sub2', $result);
        $this->assertSame(1, $result['field_sub1']);
    }
}
