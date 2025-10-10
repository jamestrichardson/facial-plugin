<?php
defined('FACIAL_PATH') or die('Hacking attempt!');

global $conf, $logger;

if (isset($_POST['form_type']) && $_POST['form_type'] === 'subject_action') {
  // Each subject row is its own form, so subject_name is always set
  $subject = isset($_POST['subject_name']) ? $_POST['subject_name'] : null;
  $logger->debug("Subject action form submitted. Subject: " . var_export($subject, true));

  if ($subject) {
    if (isset($_POST['delete'])) {
    $logger->debug("Delete requested for subject: $subject");
      facial_delete_subject($subject);
      $logger->debug("facial_delete_subject called for: $subject");
    } elseif (isset($_POST['rename'])) {
      $new_name = isset($_POST['new_name']) ? trim($_POST['new_name']) : '';
      $logger->debug("Rename requested for subject: $subject to new name: $new_name");
      if ($new_name !== '') {
        facial_rename_subject($subject, $new_name);
        $logger->debug("facial_rename_subject called for: $subject to $new_name");
      } else {
        $logger->debug("No new name provided for subject: $subject");
      }
    } else {
      $logger->debug("No valid action taken for subject: $subject");
    }
  } else {
      $logger->debug("No subject specified for action.");
  }
}

if (isset($_POST['form_type']) && $_POST['form_type'] === 'add_subject') {
  if (isset($_POST['new_subject']) && !empty($_POST['new_subject'])) {
    $new_subject = trim($_POST['new_subject']);
    facial_add_subject($new_subject);
  }
}

$template->assign('subjects', facial_get_subjects());

// define template file
$template->set_filename('facial_content', realpath(FACIAL_PATH . 'admin/template/subjects.tpl'));
