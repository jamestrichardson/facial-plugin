# Facial Plugin Testing Guide

This directory contains comprehensive PHPUnit test coverage for the Facial Recognition Plugin.

## Test Structure

### Test Files

- **`ConfigurationFunctionsTest.php`** - Tests for API configuration functions
  - `facial_get_recognition_api_key()`
  - `facial_get_detection_api_key()`
  - `facial_get_verification_api_key()`
  - `facial_get_api_base_url()`

- **`DatabaseFunctionsTest.php`** - Tests for database operations
  - `facial_insert_face_metadata()`

- **`TaggingFunctionsTest.php`** - Tests for Piwigo tagging integration
  - `facial_add_tag_to_image()`

- **`ApiIntegrationFunctionsTest.php`** - Tests for CompreFace API integration
  - `facial_get_subjects()`
  - `facial_delete_subject()`
  - `facial_add_subject()`
  - `facial_rename_subject()`
  - `facial_compreface_add_example()`
  - `facial_recognize_face()`
  - `facial_recognize_faces_all()`
  - `facial_recognize_faces_by_image_id()`
  - `facial_detect_and_store_faces()`

- **`EdgeCasesTest.php`** - Tests for edge cases and error handling
  - Null/invalid inputs
  - Type conversion
  - Extreme values
  - Security (SQL injection prevention)

## Running Tests

### Prerequisites

1. **PHP 7.4 or higher**
2. **Composer** (for dependency management)

### Installation

```bash
# Install test dependencies
composer install --dev
```

### Running Tests

#### Option 1: Using the test runner script
```bash
./run-tests.sh
```

#### Option 2: Using Composer scripts
```bash
# Run all tests
composer test

# Run tests with HTML coverage report
composer test-coverage

# Run tests with text coverage report
composer test-coverage-text
```

#### Option 3: Using PHPUnit directly
```bash
# Run all tests
phpunit

# Run specific test file
phpunit tests/ConfigurationFunctionsTest.php

# Run with coverage
phpunit --coverage-html tests/coverage
```

## Test Coverage

The test suite provides comprehensive coverage including:

- ✅ **Happy path scenarios** - Normal usage with valid inputs
- ✅ **Edge cases** - Boundary conditions and unusual inputs
- ✅ **Error handling** - Invalid inputs and error conditions
- ✅ **Type safety** - Different data types and conversions
- ✅ **Security** - SQL injection prevention and input sanitization
- ✅ **Configuration scenarios** - Various configuration states

### Coverage Reports

After running tests with coverage, reports are generated in:
- **HTML Report**: `tests/coverage/index.html`
- **Text Report**: `tests/coverage.txt`
- **XML Report**: `tests/results.xml`

## Test Design Patterns

### Mocking Strategy

The tests use a lightweight mocking approach via the `bootstrap.php` file:

- **Piwigo Functions**: Mocked to prevent database dependencies
- **Global Variables**: Initialized with test-friendly defaults
- **HTTP Requests**: Mocked to avoid external API calls during tests

### Test Categories

1. **Unit Tests**: Test individual functions in isolation
2. **Integration Tests**: Test function interactions (limited due to mocking)
3. **Edge Case Tests**: Test boundary conditions and error scenarios

## Continuous Integration

The project includes GitHub Actions workflow (`.github/workflows/tests.yml`) that:

- Runs tests on multiple PHP versions (7.4, 8.0, 8.1, 8.2)
- Generates coverage reports
- Runs code quality checks (PHPCS, PHPStan)
- Uploads coverage to Codecov

## Best Practices

### Writing New Tests

1. **Follow naming conventions**: `testFunctionNameWithCondition()`
2. **Test one thing**: Each test should verify a single behavior
3. **Use descriptive names**: Test names should explain what is being tested
4. **Include edge cases**: Test boundary conditions and error scenarios
5. **Mock external dependencies**: Avoid real database or HTTP calls

### Example Test Structure

```php
public function testFunctionNameWithValidInput()
{
    // Arrange
    $input = 'valid-input';
    $expected = 'expected-output';

    // Act
    $result = function_under_test($input);

    // Assert
    $this->assertEquals($expected, $result);
}
```

## Troubleshooting

### Common Issues

1. **"Bootstrap file not found"**
   - Ensure `tests/bootstrap.php` exists
   - Check file permissions

2. **"Class not found"**
   - Run `composer install --dev`
   - Verify autoloading in `composer.json`

3. **"Function not defined"**
   - Check that functions are properly included in `bootstrap.php`
   - Verify function names and signatures

### Debug Mode

To enable verbose output:
```bash
phpunit --verbose
```

To run a single test:
```bash
phpunit --filter testSpecificFunctionName
```

## Contributing

When adding new functions to the plugin:

1. **Add corresponding tests** in the appropriate test file
2. **Update this README** if new test categories are added
3. **Run the full test suite** to ensure nothing breaks
4. **Check coverage** to ensure new code is tested

## Dependencies

- **PHPUnit 9.5+**: Testing framework
- **PHP_CodeSniffer**: Code style checking
- **PHPStan**: Static analysis
- **Codecov**: Coverage reporting (CI only)
