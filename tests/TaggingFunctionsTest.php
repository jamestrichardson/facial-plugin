<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for Facial Plugin Tagging Functions
 */
class TaggingFunctionsTest extends TestCase
{
    protected function setUp(): void
    {
        global $logger;
        // Create a mock logger object
        $logger = new class {
            public function debug($message) { /* no-op */ }
            public function info($message) { /* no-op */ }
            public function error($message) { /* no-op */ }
            public function warning($message) { /* no-op */ }
        };
    }

    public function testFacialAddTagToImageWithNewTag()
    {
        $result = facial_add_tag_to_image('new-person', 123);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithExistingTag()
    {
        $result = facial_add_tag_to_image('existing-tag', 456);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithEmptyTag()
    {
        $result = facial_add_tag_to_image('', 123);
        $this->assertTrue($result); // Should still work with empty string
    }

    public function testFacialAddTagToImageWithSpecialCharacters()
    {
        $result = facial_add_tag_to_image('person-with-special-chars-!@#$%', 789);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithLongTagName()
    {
        $longTag = str_repeat('a', 255); // Very long tag name
        $result = facial_add_tag_to_image($longTag, 999);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithZeroImageId()
    {
        $result = facial_add_tag_to_image('test-tag', 0);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithNegativeImageId()
    {
        $result = facial_add_tag_to_image('test-tag', -1);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithStringImageId()
    {
        $result = facial_add_tag_to_image('test-tag', '123');
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithUnicodeTag()
    {
        $result = facial_add_tag_to_image('personne-françàise', 123);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithSqlInjectionAttempt()
    {
        $maliciousTag = "'; DROP TABLE piwigo_tags; --";
        $result = facial_add_tag_to_image($maliciousTag, 123);
        $this->assertTrue($result); // Should be safely escaped
    }
}
