<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for Edge Cases and Error Handling in Facial Plugin Functions
 */
class EdgeCasesTest extends TestCase
{
    protected function setUp(): void
    {
        global $conf, $logger, $prefixeTable;
        $conf = array();
        $logger = null;
        $prefixeTable = 'piwigo_';
    }

    public function testConfigurationFunctionsWithNullConfig()
    {
        global $conf;
        $conf['facial'] = null;

        $this->assertEquals('', facial_get_recognition_api_key());
        $this->assertEquals('', facial_get_detection_api_key());
        $this->assertEquals('', facial_get_verification_api_key());
        $this->assertEquals('http://localhost:8000/api/v1', facial_get_api_base_url());
    }

    public function testConfigurationFunctionsWithInvalidSerializedData()
    {
        global $conf;
        $conf['facial'] = 'invalid-serialized-data';

        $this->assertEquals('', facial_get_recognition_api_key());
        $this->assertEquals('', facial_get_detection_api_key());
        $this->assertEquals('', facial_get_verification_api_key());
        $this->assertEquals('http://localhost:8000/api/v1', facial_get_api_base_url());
    }

    public function testFacialInsertFaceMetadataWithExtremeValues()
    {
        // Test with very large values
        $result = facial_insert_face_metadata(PHP_INT_MAX, PHP_INT_MAX, 1.0, PHP_INT_MAX, PHP_INT_MAX, PHP_INT_MAX, PHP_INT_MAX);
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithFloatValues()
    {
        // Test that floats are properly handled
        $result = facial_insert_face_metadata(123.456, 1.789, 0.95123, 100.1, 150.9, 200.7, 250.3);
        $this->assertTrue($result);
    }

    public function testFacialApiBaseUrlWithComplexConfiguration()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_ssl' => '1', // String instead of boolean
            'facial_cf_host' => '   localhost   ', // With whitespace
            'facial_cf_port' => 0 // Zero port
        ));

        $result = facial_get_api_base_url();
        $this->assertStringContainsString('api/v1', $result);
    }

    public function testFacialApiBaseUrlWithArrayValues()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_ssl' => array(), // Array instead of boolean
            'facial_cf_host' => array('localhost'), // Array instead of string
            'facial_cf_port' => array(8000) // Array instead of string/int
        ));

        $result = facial_get_api_base_url();
        $this->assertEquals('http://localhost:8000/api/v1', $result);
    }

    public function testFacialAddTagToImageWithNullValues()
    {
        $result = facial_add_tag_to_image(null, null);
        // Should handle null gracefully
        $this->assertTrue(is_bool($result));
    }

    public function testFacialAddTagToImageWithBooleanValues()
    {
        $result = facial_add_tag_to_image(true, false);
        // Should convert boolean to string/int
        $this->assertTrue(is_bool($result));
    }

    public function testFacialAddTagToImageWithArrayValues()
    {
        $result = facial_add_tag_to_image(array('tag'), array(123));
        // Should handle arrays gracefully
        $this->assertTrue(is_bool($result));
    }

    public function testConfigurationWithMixedDataTypes()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 123, // Number instead of string
            'facial_cf_api_detection_key' => array('key'), // Array instead of string
            'facial_cf_api_verification_key' => true, // Boolean instead of string
            'facial_cf_ssl' => 'yes', // String instead of boolean
            'facial_cf_host' => 0, // Number instead of string
            'facial_cf_port' => true // Boolean instead of string/number
        ));

        // These should not throw errors and return reasonable defaults
        $recognitionKey = facial_get_recognition_api_key();
        $detectionKey = facial_get_detection_api_key();
        $verificationKey = facial_get_verification_api_key();
        $baseUrl = facial_get_api_base_url();

        $this->assertTrue(is_string($recognitionKey));
        $this->assertTrue(is_string($detectionKey));
        $this->assertTrue(is_string($verificationKey));
        $this->assertTrue(is_string($baseUrl));
    }

    public function testFacialInsertFaceMetadataWithStringNumbers()
    {
        // Test with numeric strings that should be converted properly
        $result = facial_insert_face_metadata('123', '1', '0.95', '100', '150', '200', '250');
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithNonNumericStrings()
    {
        // Test with non-numeric strings that should be converted to 0
        $result = facial_insert_face_metadata('abc', 'def', 'ghi', 'jkl', 'mno', 'pqr', 'stu');
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithNullValues()
    {
        // Test with null values
        $result = facial_insert_face_metadata(null, null, null, null, null, null, null);
        $this->assertTrue($result);
    }

    public function testFacialAddTagToImageWithVeryLongString()
    {
        // Test with extremely long tag name
        $longTag = str_repeat('a', 10000);
        $result = facial_add_tag_to_image($longTag, 123);
        $this->assertTrue(is_bool($result));
    }

    public function testFacialAddTagToImageWithBinaryData()
    {
        // Test with binary data
        $binaryTag = "\x00\x01\x02\x03\x04\x05";
        $result = facial_add_tag_to_image($binaryTag, 123);
        $this->assertTrue(is_bool($result));
    }
}
