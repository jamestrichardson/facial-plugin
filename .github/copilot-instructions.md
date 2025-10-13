# Facial Recognition Plugin for Piwigo - Copilot Instructions

## Architecture Overview

This is a **Piwigo CMS plugin** that integrates facial recognition capabilities using the **CompreFace API**. The plugin follows Piwigo's event-driven architecture and hooks into the gallery workflow.

### Core Components

- **`main.inc.php`**: Plugin entry point with event handlers and constants
- **`include/functions.inc.php`**: Core API integration functions and database operations
- **`include/admin_events.inc.php`**: Admin interface event handlers (batch manager, photo tabs)
- **`include/public_events.inc.php`**: Public-facing event handlers (picture display, face thumbnails)
- **`maintain.class.php`**: Plugin installation/activation logic and database schema
- **`admin/`**: Admin interface pages (config, subjects, photo management)

### Database Schema

Three main tables (prefixed with `piwigo_`):
- **`facial_faces`**: Stores face coordinates and detection metadata per image
- **`facial_known_people`**: Manages known subjects/individuals
- **`facial_recognition`**: Maps detected faces to known people

## Key Patterns & Conventions

### Event Handler Pattern
```php
// Admin handlers in include/admin_events.inc.php
add_event_handler('loc_end_element_set_global', 'facial_batch_global');

// Public handlers in include/public_events.inc.php
add_event_handler('loc_end_picture', 'facial_command_center');
```

### CompreFace API Integration

#### API Configuration Assembly
```php
function facial_get_api_base_url() {
  $facialConfig = safe_unserialize($conf['facial']);
  $protocol = $facialConfig['facial_cf_ssl'] ? 'https' : 'http';
  $host = $facialConfig['facial_cf_host'] ?: 'localhost';
  $port = $facialConfig['facial_cf_port'] ?: '8000';
  return sprintf('%s://%s:%s/api/v1', $protocol, $host, $port);
}
```

#### Standard cURL Pattern for API Calls
```php
$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => $baseUrl . '/endpoint',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => ["x-api-key: " . $apiKey],
  // POST/DELETE/PATCH specific options...
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
```

#### File Upload Pattern (Detection API)
```php
CURLOPT_POST => true,
CURLOPT_HTTPHEADER => ["Content-Type: multipart/form-data", "x-api-key: " . $apiKey],
CURLOPT_POSTFIELDS => ["file" => new CURLFile($imagePath)]
```

#### JSON Payload Pattern (Recognition API)
```php
CURLOPT_HTTPHEADER => ["Content-Type: application/json", "x-api-key: " . $apiKey],
CURLOPT_POSTFIELDS => json_encode(["subject" => $subjectName])
```

### Error Handling Patterns

#### Consistent Error Checking and Logging
```php
if (curl_errno($ch)) {
  if (isset($logger)) $logger->error('Function cURL error: ' . curl_error($ch));
  curl_close($ch);
  return false;
}
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode !== 200) {
  if (isset($logger)) $logger->error("Function failed. HTTP: $httpCode. Response: $response");
  return false;
}
```

#### Logger Usage Pattern
Always check logger exists before using: `if (isset($logger)) $logger->debug/error/info(...)`

#### Exception Handling in Detection Functions
```php
try {
  // API operations
  return $successValue;
} catch (Exception $e) {
  error_log('function_name error: ' . $e->getMessage());
  if (isset($logger)) $logger->error('function_name error: ' . $e->getMessage());
  return $failureValue;
}
```

### Configuration Management
All settings stored as serialized array in `$conf['facial']`:
```php
// Access pattern throughout codebase
$facialConfig = safe_unserialize($conf['facial']);
$apiKey = $facialConfig['facial_cf_api_recoginition_key'];
```

### CompreFace API Integration
- **Detection API**: Finds faces in images
- **Recognition API**: Identifies known subjects
- **Verification API**: Compares face similarity
- URLs constructed via `facial_get_api_base_url()` using host/port/SSL config

### Database Conventions
- Use `pwg_query()` for all database operations
- Table names via `$prefixeTable . 'facial_faces'` pattern
- SQL injection protection with `intval()`, `floatval()` casting

### Template System
- Smarty templates in `template/` directory
- Admin templates use tabsheet navigation system
- Template variables assigned via `$template->assign()`

## Development Workflows

### Adding New CompreFace API Calls
1. Add configuration keys in `maintain.class.php` `$default_conf`
2. Create getter function in `include/functions.inc.php` following `facial_get_*_api_key()` pattern
3. Implement API call function with cURL and error handling
4. Add admin interface in appropriate `admin/*.php` file

### Database Changes
1. Modify table creation in `maintain.class.php::install()`
2. Add upgrade logic in `activate()` method
3. Create helper functions in `include/functions.inc.php`

### New Admin Features
1. Add tab in `admin.php` tabsheet
2. Create corresponding `admin/{tab}.php` file
3. Add template file in `admin/template/{tab}.tpl`
4. Register event handlers in `main.inc.php` if needed

## Critical Files & Dependencies

- **`include/functions.inc.php`**: Contains all CompreFace API integration logic
- **`maintain.class.php`**: Database schema and plugin lifecycle management
- **Configuration**: All settings via `$conf['facial']` serialized array
- **Constants**: Defined in `main.inc.php` (FACIAL_PATH, FACIAL_ADMIN, etc.)

## Integration Points

- **Piwigo Core**: Hooks into image display, batch manager, admin interface
- **CompreFace**: External facial recognition service via REST API
- **Database**: Extends Piwigo schema with face detection tables
- **Templates**: Integrates with Piwigo's Smarty template system

## Debugging & Development

- Enable debug mode via `facial_plugin_debug` config option
- Uses Piwigo's `$logger` for debug output when available
- Error handling follows Piwigo patterns with `$page['errors'][]`
- Configuration validation in admin interface with form submission handlers
