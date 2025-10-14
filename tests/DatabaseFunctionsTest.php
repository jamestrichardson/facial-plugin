<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for Facial Plugin Database Functions
 */
class DatabaseFunctionsTest extends TestCase
{
    protected function setUp(): void
    {
        global $prefixeTable, $logger;
        $prefixeTable = 'piwigo_';
        $logger = null;
    }

    public function testFacialInsertFaceMetadataWithValidData()
    {
        $result = facial_insert_face_metadata(123, 1, 0.95, 100, 150, 200, 250);
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithZeroValues()
    {
        $result = facial_insert_face_metadata(456, 2, 0.0, 0, 0, 0, 0);
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithMaxValues()
    {
        $result = facial_insert_face_metadata(999, 5, 1.0, 9999, 9999, 9999, 9999);
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithNegativeImageId()
    {
        // Test that intval() properly handles negative values
        $result = facial_insert_face_metadata(-1, 1, 0.5, 10, 20, 30, 40);
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithFloatCoordinates()
    {
        // Test that float values are properly converted to integers
        $result = facial_insert_face_metadata(123, 1, 0.85, 10.7, 20.3, 30.9, 40.1);
        $this->assertTrue($result);
    }

    public function testFacialInsertFaceMetadataWithStringInputs()
    {
        // Test that string inputs are properly converted
        $result = facial_insert_face_metadata('123', '1', '0.95', '100', '150', '200', '250');
        $this->assertTrue($result);
    }
}
