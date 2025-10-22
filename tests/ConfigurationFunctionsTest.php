<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for Facial Plugin Configuration Functions
 */
class ConfigurationFunctionsTest extends TestCase
{
    protected function setUp(): void
    {
        global $conf;
        // Reset configuration for each test
        $conf = array();
    }

    public function testFacialGetRecognitionApiKeyWithValidConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-recognition-key'
        ));

        $result = facial_get_recognition_api_key();
        $this->assertEquals('test-recognition-key', $result);
    }

    public function testFacialGetRecognitionApiKeyWithEmptyConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array());

        $result = facial_get_recognition_api_key();
        $this->assertEquals('', $result);
    }

    public function testFacialGetDetectionApiKeyWithValidConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_detection_key' => 'test-detection-key'
        ));

        $result = facial_get_detection_api_key();
        $this->assertEquals('test-detection-key', $result);
    }

    public function testFacialGetDetectionApiKeyWithEmptyConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array());

        $result = facial_get_detection_api_key();
        $this->assertEquals('', $result);
    }

    public function testFacialGetVerificationApiKeyWithValidConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_verification_key' => 'test-verification-key'
        ));

        $result = facial_get_verification_api_key();
        $this->assertEquals('test-verification-key', $result);
    }

    public function testFacialGetVerificationApiKeyWithEmptyConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array());

        $result = facial_get_verification_api_key();
        $this->assertEquals('', $result);
    }

    public function testFacialGetApiBaseUrlWithHttpsEnabled()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_ssl' => true,
            'facial_cf_host' => 'example.com',
            'facial_cf_port' => '8443'
        ));

        $result = facial_get_api_base_url();
        $this->assertEquals('https://example.com:8443/api/v1', $result);
    }

    public function testFacialGetApiBaseUrlWithHttpsDisabled()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_ssl' => false,
            'facial_cf_host' => 'example.com',
            'facial_cf_port' => '8080'
        ));

        $result = facial_get_api_base_url();
        $this->assertEquals('http://example.com:8080/api/v1', $result);
    }

    public function testFacialGetApiBaseUrlWithDefaults()
    {
        global $conf;
        $conf['facial'] = serialize(array());

        $result = facial_get_api_base_url();
        $this->assertEquals('http://localhost:8000/api/v1', $result);
    }

    public function testFacialGetApiBaseUrlWithPartialConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_host' => 'custom-host'
        ));

        $result = facial_get_api_base_url();
        $this->assertEquals('http://custom-host:8000/api/v1', $result);
    }
}
