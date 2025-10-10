<?php

defined('FACIAL_PATH') or die('Hacking attempt!');

/**
 * Inserts a detected face's metadata into the facial table.
 *
 * @param int $imageId The ID of the image in piwigo_images.
 * @param int $faceNum The number of the face in the image (1st, 2nd, etc).
 * @param float $probability The probability that the detection is a face.
 * @param int $x_min The minimum x bound of the face in the image.
 * @param int $y_min The minimum y bound of the face in the image.
 * @param int $x_max The maximum x bound of the face in the image.
 * @param int $y_max The maximum y bound of the face in the image.
 * @return bool True on success, false on failure.
 */
function facial_insert_face_metadata($imageId, $faceNum, $probability, $x_min, $y_min, $x_max, $y_max)
{
  global $prefixeTable, $logger;
  $table = $prefixeTable . 'facial_faces';
  $query = sprintf(
    "INSERT INTO %s (image_id, face_num, probability, x_min, y_min, x_max, y_max) VALUES ('%d', '%d', '%f', '%d', '%d', '%d', '%d')",
    $table,
    intval($imageId),
    intval($faceNum),
    floatval($probability),
    intval($x_min),
    intval($y_min),
    intval($x_max),
    intval($y_max)
  );
  if (isset($logger)) {
    $logger->debug("facial_insert_face_metadata SQL: $query");
  }
  $result = pwg_query($query);
  if (!$result && isset($logger)) {
    $logger->error("facial_insert_face_metadata failed for imageId=$imageId, faceNum=$faceNum");
  }
  return $result;
}

/**
 * Returns the Compreface Recognition API key from configuration.
 *
 * @return string Recognition API key
 */
function facial_get_recognition_api_key()
{
  global $conf;
  $facialConfig = safe_unserialize($conf['facial']);
  return isset($facialConfig['facial_cf_api_recoginition_key']) ? $facialConfig['facial_cf_api_recoginition_key'] : '';
}

/**
 * Returns the Compreface Detection API key from configuration.
 *
 * @return string Detection API key
 */
function facial_get_detection_api_key()
{
  global $conf;
  $facialConfig = safe_unserialize($conf['facial']);
  return isset($facialConfig['facial_cf_api_detection_key']) ? $facialConfig['facial_cf_api_detection_key'] : '';
}

/**
 * Returns the Compreface Verification API key from configuration.
 *
 * @return string Verification API key
 */
function facial_get_verification_api_key()
{
  global $conf;
  $facialConfig = safe_unserialize($conf['facial']);
  return isset($facialConfig['facial_cf_api_verification_key']) ? $facialConfig['facial_cf_api_verification_key'] : '';
}

/**
 * Assembles the Compreface API base URL from configuration variables.
 *
 * Uses host, port, and SSL settings to construct the API endpoint URL.
 *
 * @return string The assembled API base URL (e.g., https://host:port/api/v1)
 */
function facial_get_api_base_url()
{
  global $conf;
  $facialConfig = safe_unserialize($conf['facial']);
  $protocol = (!empty($facialConfig['facial_cf_ssl']) && $facialConfig['facial_cf_ssl']) ? 'https' : 'http';
  $host = !empty($facialConfig['facial_cf_host']) ? $facialConfig['facial_cf_host'] : 'localhost';
  $port = !empty($facialConfig['facial_cf_port']) ? $facialConfig['facial_cf_port'] : '8000';
  // Optionally append /api/v1 or similar if needed
  return sprintf('%s://%s:%s/api/v1', $protocol, $host, $port);
}

/**
 * Retrieves the list of facial recognition subjects from the Compreface API.
 *
 * Connects to the Compreface recognition subjects endpoint using the configured API URL and key.
 * Returns an array of subject names if available.
 *
 * @return array List of subject names recognized by Compreface.
 */
function facial_get_subjects()
{
  global $conf;

  $subjects = array();

  $facialConfig = safe_unserialize($conf['facial']);
  $baseUrl = facial_get_api_base_url();
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/recognition/subjects/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "x-api-key: " . facial_get_recognition_api_key()
    ],
  ]);
  $response = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($response, true);
  if (isset($data['subjects']) && is_array($data['subjects'])) {
    foreach ($data['subjects'] as $subject) {
      $subjects[] = $subject;
    }
  }

  return $subjects;
}


/**
 * Detects faces in an image using the image ID, stores metadata, and returns the number of faces detected.
 *
 * @param int $imageId The image ID in the database.
 * @param bool $overwrite If true, delete existing face metadata for this imageId before inserting new results. Default: true.
 * @return int Number of faces detected in the image, or 0 if not found or error.
 */
function facial_detect_and_store_faces($imageId, $overwrite = true)
{
  global $conf, $logger;

  $logger->debug("facial_detect_and_store_faces called with imageId: $imageId");

  // Query the database for the image path
  $query = 'SELECT path FROM ' . IMAGES_TABLE . ' WHERE id = ' . intval($imageId) . ' LIMIT 1;';
  $logger->debug("SQL Query: $query");
  $result = pwg_query($query);
  $imagePath = null;
  if ($row = pwg_db_fetch_assoc($result)) {
    $imagePath = $row['path'];
    $logger->debug("Image path found: $imagePath");
  } else {
    $logger->debug("No image found for imageId: $imageId");
    return 0;
  }

  if ($imagePath && file_exists($imagePath)) {
    $facialConfig = safe_unserialize($conf['facial']);
    $baseUrl = facial_get_api_base_url();
    $logger->debug("facial_detect_and_store_faces: Detecting faces for imagePath: $imagePath");
    try {
      if ($overwrite) {
        global $prefixeTable;
        $table = $prefixeTable . 'facial_faces';
        $deleteQuery = 'DELETE FROM ' . $table . ' WHERE image_id = ' . intval($imageId);
        if (isset($logger)) {
          $logger->debug("Deleting existing face metadata for imageId=$imageId: $deleteQuery");
        }
        pwg_query($deleteQuery);
      }
      $ch = curl_init();
      $apiKey = facial_get_detection_api_key();
      $logger->debug("API Request: POST " . $baseUrl . '/detection/detect' . " with x-api-key: $apiKey");
      curl_setopt_array($ch, [
        CURLOPT_URL => $baseUrl . '/detection/detect',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: multipart/form-data",
            "x-api-key: " . $apiKey
        ],
        CURLOPT_POSTFIELDS => [
            "file" => new CURLFile($imagePath)
        ]
      ]);

      $response = curl_exec($ch);
      if (curl_errno($ch)) {
        $logger->error('cURL error: ' . curl_error($ch));
        throw new Exception('cURL error: ' . curl_error($ch));
      }
      curl_close($ch);

      $logger->debug("API Response: $response");
      $data = json_decode($response, true);
      if (!is_array($data)) {
        $logger->error('Invalid response from Compreface API');
        throw new Exception('Invalid response from Compreface API');
      }
      if (isset($data['result']) && is_array($data['result'])) {
        $logger->debug("Faces detected: " . count($data['result']));
        // Insert each detected face into the database
        foreach ($data['result'] as $i => $face) {
          $box = isset($face['box']) ? $face['box'] : [];
          $probability = isset($box['probability']) ? floatval($box['probability']) : 0.0;
          $x_min = isset($box['x_min']) ? intval($box['x_min']) : 0;
          $y_min = isset($box['y_min']) ? intval($box['y_min']) : 0;
          $x_max = isset($box['x_max']) ? intval($box['x_max']) : 0;
          $y_max = isset($box['y_max']) ? intval($box['y_max']) : 0;
          facial_insert_face_metadata($imageId, $i+1, $probability, $x_min, $y_min, $x_max, $y_max);
        }
        return count($data['result']);
      }
      $logger->debug("No faces detected.");
      return 0;
    } catch (Exception $e) {
      error_log('facial_detect_and_store_faces error: ' . $e->getMessage());
      if (isset($logger)) {
        $logger->error('facial_detect_and_store_faces error: ' . $e->getMessage());
      }
      return 0;
    }
  }
  $logger->debug("Image file does not exist: $imagePath");
  return 0;
}

/**
 * Deletes a subject from Compreface using the Recognition API.
 *
 * @param string $subject The subject name to delete.
 * @return bool True on success, false on failure.
 */
function facial_delete_subject($subject)
{
  global $conf, $logger;

  $logger->debug("facial_delete_subject called with subject: $subject");

  $baseUrl = facial_get_api_base_url();
  $apiKey = facial_get_recognition_api_key();
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/recognition/subjects/' . rawurlencode($subject),
    CURLOPT_CUSTOMREQUEST => 'DELETE',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "x-api-key: $apiKey"
    ],
  ]);
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    if (isset($logger)) $logger->error('facial_delete_subject cURL error: ' . curl_error($ch));
    curl_close($ch);
    return false;
  }
  curl_close($ch);
  if ($httpCode === 200 || $httpCode === 204) {
    if (isset($logger)) $logger->debug("Deleted subject '$subject' via Compreface API.");
    return true;
  } else {
    if (isset($logger)) $logger->error("Failed to delete subject '$subject'. HTTP code: $httpCode. Response: $response");
    return false;
  }
}

/**
 * Renames a subject in Compreface using the Recognition API.
 *
 * @param string $oldName The current subject name.
 * @param string $newName The new subject name.
 * @return bool True on success, false on failure.
 */
function facial_rename_subject($oldName, $newName)
{
  global $conf, $logger;

  $logger->debug("facial_rename_subject called with oldName: $oldName, newName: $newName");
  $logger->debug("facial_rename_subject is not implemented yet.");

  return;

  $baseUrl = facial_get_api_base_url();
  $apiKey = facial_get_recognition_api_key();
  $ch = curl_init();
  $payload = json_encode(["subject" => $newName]);
  curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/recognition/subjects/' . urlencode($oldName),
    CURLOPT_CUSTOMREQUEST => 'PATCH',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "x-api-key: $apiKey"
    ],
    CURLOPT_POSTFIELDS => $payload
  ]);
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    if (isset($logger)) $logger->error('facial_rename_subject cURL error: ' . curl_error($ch));
    curl_close($ch);
    return false;
  }
  curl_close($ch);
  if ($httpCode === 200) {
    if (isset($logger)) $logger->debug("Renamed subject '$oldName' to '$newName' via Compreface API.");
    return true;
  } else {
    if (isset($logger)) $logger->error("Failed to rename subject '$oldName' to '$newName'. HTTP code: $httpCode. Response: $response");
    return false;
  }
}

/**
 * Adds a new subject to Compreface using the Recognition API.
 *
 * @param string $subject The subject name to add.
 * @return bool True on success, false on failure.
 */
function facial_add_subject($subject)
{
  global $conf, $logger;
  $baseUrl = facial_get_api_base_url();
  $apiKey = facial_get_recognition_api_key();
  $ch = curl_init();
  $payload = json_encode(["subject" => $subject]);
  curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/recognition/subjects',
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "x-api-key: $apiKey"
    ],
    CURLOPT_POSTFIELDS => $payload
  ]);
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    if (isset($logger)) $logger->error('facial_add_subject cURL error: ' . curl_error($ch));
    curl_close($ch);
    return false;
  }
  curl_close($ch);
  if ($httpCode === 200 || $httpCode === 201) {
    if (isset($logger)) $logger->debug("Added subject '$subject' via Compreface API.");
    return true;
  } else {
    if (isset($logger)) $logger->error("Failed to add subject '$subject'. HTTP code: $httpCode. Response: $response");
    return false;
  }
}

/**
 * Handles the submission of face metadata assignments from the form.
 *
 * Processes POST data for subject assignments to detected faces, calls
 * facial_assign_subjects_to_faces to update the database, and for each face
 * assignment, calls the Compreface API to add the thumbnail as an example for the subject.
 *
 * @global array $page
 * @global array $conf
 * @global object $logger
 * @return void
 */
function facial_form_assign_subjects()
{
  global $page, $conf, $logger;

  $logger->debug("facial_form_assign_subjects called");

  if (isset($_POST['save_face_metadata']) && $page['image_id']) {
    if (isset($logger)) $logger->debug('Processing face metadata form for image_id: ' . $page['image_id']);
    $faceAssignments = array();
    foreach ($_POST as $key => $value) {
      if (strpos($key, 'subject_') === 0 && !empty($value)) {
        $faceNum = intval(substr($key, 8));
        $faceAssignments[$faceNum] = $value;
        if (isset($logger)) $logger->debug("Assigned subject '$value' to face #$faceNum");

        // Check for thumbnail for this face
        $thumbKey = 'thumbnail_' . $faceNum;
        if (isset($_POST[$thumbKey]) && !empty($_POST[$thumbKey])) {
          $thumbnailData = $_POST[$thumbKey];
          if (isset($logger)) $logger->debug("Submitting thumbnail for subject '$value', face #$faceNum");
          facial_compreface_add_example($value, $thumbnailData);
        } else {
          if (isset($logger)) $logger->debug("No thumbnail found for subject '$value', face #$faceNum");
        }
      }
    }

    // This is to update a piwigo specific database of faces/tags/assigments, that isn't there yet
    //
    // if (!empty($faceAssignments)) {
    //   if (isset($logger)) $logger->debug('Calling facial_assign_subjects_to_faces with assignments: ' . var_export($faceAssignments, true));
    //   facial_assign_subjects_to_faces($page['image_id'], $faceAssignments);
    // } else {
    //   if (isset($logger)) $logger->debug('No face assignments found in POST data.');
    // }
  }
}

/**
 * Calls the Compreface API to add a base64 thumbnail as an example for a subject.
 *
 * @param string $subject The subject name.
 * @param string $thumbnailData The base64-encoded image data (data URI).
 * @return bool True on success, false on failure.
 */
function facial_compreface_add_example($subject, $thumbnailData)
{
  global $conf, $logger;
  $baseUrl = facial_get_api_base_url();
  $apiKey = facial_get_recognition_api_key();

  $logger->debug("facial_compreface_add_example called for subject: $subject");

  // Remove data URI prefix if present
  if (strpos($thumbnailData, 'base64,') !== false) {
    $thumbnailData = explode('base64,', $thumbnailData, 2)[1];
  }

  $ch = curl_init();
  $payload = json_encode([
    "file" => $thumbnailData
  ]);
  curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/recognition/faces?subject=' . rawurlencode($subject),
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "x-api-key: $apiKey"
    ],
    CURLOPT_POSTFIELDS => $payload
  ]);
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    if (isset($logger)) $logger->error('facial_compreface_add_example cURL error: ' . curl_error($ch));
    curl_close($ch);
    return false;
  }
  curl_close($ch);
  if ($httpCode === 200 || $httpCode === 201) {
    if (isset($logger)) $logger->debug("Added example for subject '$subject' via Compreface API.");
    return true;
  } else {
    if (isset($logger)) $logger->error("Failed to add example for subject '$subject'. HTTP code: $httpCode. Response: $response");
    return false;
  }
}

/**
 * Recognizes faces in a base64-encoded image using the Compreface recognize API.
 *
 * @param string $base64Image Base64-encoded image data (optionally with data URI prefix).
 * @param float $detProbThreshold Detection probability threshold (optional).
 * @return array|null The full API result for the first detected face, or null if no match found.
 */
function facial_recognize_face($base64Image, $detProbThreshold = 0.9)
{
  global $conf, $logger;
  if (empty($base64Image)) {
    if (isset($logger)) $logger->error("facial_recognize_face: No image data provided.");
    return null;
  }
  // Remove data URI prefix if present
  if (strpos($base64Image, 'base64,') !== false) {
    $base64Image = explode('base64,', $base64Image, 2)[1];
  }
  $baseUrl = facial_get_api_base_url();
  $apiKey = facial_get_recognition_api_key();
  $ch = curl_init();
  $url = $baseUrl . '/recognition/recognize?face_plugins=age,gender&det_prob_threshold=' . urlencode($detProbThreshold);
  $payload = json_encode([
    "file" => $base64Image
  ]);
  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "x-api-key: $apiKey"
    ],
    CURLOPT_POSTFIELDS => $payload
  ]);
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if (curl_errno($ch)) {
    if (isset($logger)) $logger->error('facial_recognize_face cURL error: ' . curl_error($ch));
    curl_close($ch);
    return null;
  }
  curl_close($ch);
  if ($httpCode !== 200) {
    if (isset($logger)) $logger->error("facial_recognize_face failed. HTTP code: $httpCode. Response: $response");
    return null;
  }
  $data = json_decode($response, true);
  if (!is_array($data) || !isset($data['result']) || !is_array($data['result']) || count($data['result']) === 0) {
    if (isset($logger)) $logger->debug("facial_recognize_face: No results found.");
    return null;
  }
  // Return the full result for the first detected face
  $firstFace = $data['result'][0];
  if (isset($logger)) $logger->debug("facial_recognize_face: API result: " . json_encode($firstFace));
  return $firstFace;
}
