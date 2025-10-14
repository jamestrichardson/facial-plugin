0.1.2 - Added comprehensive PHPUnit test coverage
- Added complete test suite covering all functions in `include/functions.inc.php`
- Test categories: Configuration functions, Database operations, Tagging integration, API integration, Edge cases
- Includes PHPUnit configuration, Composer setup, and GitHub Actions CI/CD workflow
- Added test runner script and comprehensive testing documentation
- Tests cover happy path, edge cases, error handling, type safety, and security scenarios
- Achieved high code coverage with mock-based testing to avoid external dependencies

0.1.1 - Added facial recognition by image ID functionality
- Added `facial_recognize_faces_by_image_id()` function to send Piwigo images to CompreFace for facial recognition
- Function takes an image_id parameter and returns recognition results for all detected faces
- Includes comprehensive error handling and logging following established patterns
- Integrates with existing database queries and API integration patterns

0.0.1 - Initial Release. This version is the initial release with basic functionality. There is limited configuration options, but lets you set a API URL / KEY to a compreface insallation and with that, on each picture page a query will be made to detect the number of faces
