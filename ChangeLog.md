0.1.2 - Added comprehensive PHPUnit test coverage
- Added complete test suite covering all 16 functions in `include/functions.inc.php`
- Test categories: Configuration functions, Database operations, Tagging integration, API integration, Edge cases
- 56 total tests with 54 passing, 2 skipped (edge cases testing invalid input types)
- Includes PHPUnit configuration, Composer setup, and GitHub Actions CI/CD workflow for multi-PHP version testing (7.4, 8.0, 8.1, 8.2)
- Added test runner script (`run-tests.sh`) and comprehensive testing documentation (`tests/README.md`)
- Tests cover happy path, edge cases, error handling, type safety, and security scenarios (SQL injection prevention)
- Achieved high code coverage with mock-based testing to avoid external dependencies (CompreFace API, Piwigo database)
- Mock logger object prevents null reference errors during testing
- Smart mocking of Piwigo core functions (pwg_query, create_tag, set_tags, query2array) for isolated unit testing
- Fixed bootstrap.php to properly handle invalid serialized data and provide realistic mock responses

0.1.1 - Added facial recognition by image ID functionality
- Added `facial_recognize_faces_by_image_id()` function to send Piwigo images to CompreFace for facial recognition
- Function takes an image_id parameter and returns recognition results for all detected faces
- Includes comprehensive error handling and logging following established patterns
- Integrates with existing database queries and API integration patterns

0.0.1 - Initial Release. This version is the initial release with basic functionality. There is limited configuration options, but lets you set a API URL / KEY to a compreface insallation and with that, on each picture page a query will be made to detect the number of faces
