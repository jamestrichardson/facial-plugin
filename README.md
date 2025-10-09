# Facial Recognition Plugin for Piwigo

A powerful facial recognition plugin for Piwigo that integrates with CompreFace to detect, identify, and manage faces in your photo gallery.

* Internal name: `facial` (directory name in `plugins/`)
* Plugin page: [Piwigo Extensions](https://piwigo.org/ext/index.php?eid=1008)

## Features

### Face Detection and Recognition

- Automatically detect faces in photos using CompreFace API
- Identify known individuals across your photo collection
- Batch processing support for scanning multiple photos
- Unknown face detection and labeling system

### Management Interface

- Comprehensive admin panel for face management
- List, add, and edit known individuals
- Train the recognition system with new faces
- Configure CompreFace API settings
- Monitor face detection statistics

### Gallery Integration

- Display detected faces on photo pages
- Filter photos by detected individuals
- Batch operations through Piwigo's batch manager
- Face count indicators in album views

### User Experience

- Intuitive interface for viewing face detection results
- Easy navigation to photos containing specific individuals
- Support for multiple languages
- Responsive design that works on all devices

## Installation

### Prerequisites

1. A working Piwigo installation (version 2.9 or later)
2. CompreFace server (self-hosted or cloud instance)
3. PHP 7.4 or later
4. PHP cURL extension enabled

### Installing CompreFace

1. Follow the [CompreFace installation guide](https://github.com/exadel-inc/CompreFace#getting-started-with-compreface)
2. Note down your CompreFace server URL and API key

### Plugin Installation

1. Download the latest release from the [Piwigo Extensions page](https://piwigo.org/ext/index.php?eid=1008)
2. Extract the `facial` folder to your Piwigo's `plugins/` directory
3. Log in to your Piwigo admin panel
4. Navigate to Plugins → Manage
5. Find "Facial Recognition" in the list and click "Activate"

### Configuration

1. Go to Plugins → Facial Recognition
2. Enter your CompreFace server URL and API key
3. Configure desired detection settings:
   - Detection confidence threshold
   - Maximum faces per image
   - Processing batch size
4. Save your settings

## Usage

### Basic Operations

1. **Scanning Albums**

* Go to the Albums administration page
* Select "Scan for faces" from the batch actions
* Choose the albums to scan
* Start the detection process

2. **Managing Known Faces**
   - Navigate to Plugins → Facial Recognition
   - Use "Add Person" to create new entries
   - Select photos to train the recognition system
   - Review and confirm detected faces

3. **Viewing Results**
   - Open any photo to see detected faces
   - Use the batch manager to filter photos by detected individuals
   - View face statistics in the admin dashboard

### Advanced Features
- Batch processing for multiple albums
- Face training for improved recognition
- Export/Import of face data
- Custom detection parameters per album

## Development Status

Active and maintained. Contributions welcome!

## Database Schema

The plugin creates and manages the following tables:
- `piwigo_facial_known_people`: Stores information about known individuals
- `piwigo_facial_faces`: Tracks detected faces and their locations
- `piwigo_facial_recognition`: Maps faces to known individuals

## Support

- [Issue Tracker](https://github.com/jamestrichardson/facial-plugin/issues)
- [Documentation](./docs/)
- [Piwigo Forums](https://piwigo.org/forum/)

## License

This project is licensed under [insert license name] - see the [LICENSE](LICENSE) file for details.
