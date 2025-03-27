<?php

defined('FACIAL_PATH') or die('Hacking attempt!');

// add a prefilter on phooto page
function facial_loc_begin_picture()
{
	global $template;
	$template->set_prefilter('picture', 'facial_add_to_pic_info');

}

function facial_add_to_pic_info($content)
{
	$search = '#class="imageInfoTable">#';

	$replacement = 'class="imageInfoTable">
	<div id="Facial Name" class="imageInfo">
		<dt>{\'Facial\'|@translate}</dt>
		<dd>
{if $FACES}
			Faces #: {$NUM_FACES}<br/>
      Misc: {$FACIAL_DATA}
{/if}
    </dd>
	</div>';

	return preg_replace($search, $replacement, $content, 1);
}

function facial_add_image_vars_to_template()
{
  global $page, $template, $conf;

  if(empty($page['image_id'])) {
    return;
  }

  $imageInfo = array();

  $query = 'SELECT file, path FROM ' . IMAGES_TABLE . ' WHERE id = ' . $page['image_id'] . ' LIMIT 1;';
  $result = pwg_query($query);
  while($row = pwg_db_fetch_assoc($result)) {
    $imageInfo['image_file'] = $row['file'];
    $imageInfo['image_path'] = $row['path'];
  }

  $foo = "";
  $facialConfig = safe_unserialize($conf['facial']);
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $facialConfig['compreface_api_url'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: multipart/form-data",
        "x-api-key: " . $facialConfig['compreface_api_key']
    ],
    CURLOPT_POSTFIELDS => [
        "file" => new CURLFile($imageInfo['image_path'])
    ]
]);
  $output = curl_exec($ch);

  if (curl_errno($ch)) {
    $foo = curl_error($ch);
    echo 'Error:' . $foo;

  }
  curl_close($ch);

  $facialOutput = json_decode($output, true);
  $facialData = $facialConfig['compreface_api_url'];
  $numFaces = 0;

  if(isset($facialOutput['code'])) {
    // TODO: handle errors this doesn't work
    if($facialOutput['code'] == '28') {
      $facialData = "Err 28: No faces detected";
    }
    else {
      $facialData = $facialOutput['code'] . "Unknown Error";
    }
    $facialData = $facialOutput['code'];
  }

  if(isset($facialOutput['result'])) {
    $numFaces = count($facialOutput['result']);
  }
  $template->assign(
    array	(
      'FACES' => "1",
      'NUM_FACES' => $numFaces,
      'FACIAL_DATA' => $facialData
    )
  );
}
