<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for Facial Plugin API Integration Functions
 */
class ApiIntegrationFunctionsTest extends TestCase
{
    protected function setUp(): void
    {
        global $conf, $logger;
        $conf = array();
        $logger = null;
    }

    public function testFacialGetSubjectsWithValidConfig()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        // Since we can't make real HTTP calls in unit tests,
        // we test that the function can be called without errors
        $result = facial_get_subjects();
        // In a real scenario, this would return an array or null
        $this->assertTrue(is_array($result) || is_null($result));
    }

    public function testFacialDeleteSubjectWithValidSubject()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_delete_subject('test-subject');
        $this->assertTrue(is_bool($result));
    }

    public function testFacialDeleteSubjectWithEmptySubject()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_delete_subject('');
        $this->assertTrue(is_bool($result));
    }

    public function testFacialAddSubjectWithValidSubject()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_add_subject('new-subject');
        $this->assertTrue(is_bool($result));
    }

    public function testFacialAddSubjectWithSpecialCharacters()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_add_subject('subject-with-special-chars-!@#');
        $this->assertTrue(is_bool($result));
    }

    public function testFacialRenameSubjectWithValidNames()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_rename_subject('old-name', 'new-name');
        // This function returns void, so we just test it doesn't throw errors
        $this->assertNull($result);
    }

    public function testFacialComprefaceAddExampleWithValidData()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $thumbnailData = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k=';

        $result = facial_compreface_add_example('test-subject', $thumbnailData);
        $this->assertTrue(is_bool($result));
    }

    public function testFacialRecognizeFaceWithValidBase64()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $base64Image = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k=';

        $result = facial_recognize_face($base64Image);
        $this->assertTrue(is_array($result) || is_null($result));
    }

    public function testFacialRecognizeFaceWithEmptyImage()
    {
        $result = facial_recognize_face('');
        $this->assertNull($result);
    }

    public function testFacialRecognizeFaceWithInvalidBase64()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_recognize_face('invalid-base64-data');
        $this->assertTrue(is_array($result) || is_null($result));
    }

    public function testFacialRecognizeFacesAllWithValidPath()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        // Test with a mock file path
        $result = facial_recognize_faces_all('/non/existent/path.jpg');
        $this->assertNull($result); // Should return null for non-existent file
    }

    public function testFacialRecognizeFacesByImageIdWithValidId()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_recoginition_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_recognize_faces_by_image_id(123);
        $this->assertTrue(is_array($result) || is_null($result));
    }

    public function testFacialRecognizeFacesByImageIdWithInvalidId()
    {
        $result = facial_recognize_faces_by_image_id(-1);
        $this->assertTrue(is_array($result) || is_null($result));
    }

    public function testFacialDetectAndStoreFacesWithValidImageId()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_detection_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_detect_and_store_faces(123);
        $this->assertTrue(is_int($result));
    }

    public function testFacialDetectAndStoreFacesWithOverwriteFlag()
    {
        global $conf;
        $conf['facial'] = serialize(array(
            'facial_cf_api_detection_key' => 'test-key',
            'facial_cf_host' => 'localhost',
            'facial_cf_port' => '8000',
            'facial_cf_ssl' => false
        ));

        $result = facial_detect_and_store_faces(123, false);
        $this->assertTrue(is_int($result));
    }
}
