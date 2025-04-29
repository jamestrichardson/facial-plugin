facial_compreface_table:

|Field name | Type               | Null | Key | Description |
|IMAGE_ID   | mediumint unisgned | NO   | PRI | This is a reference to the IMAGE_ID in pwigo_images |
|TAG_ID     | smallint           | NO   | PRI | For the given image, the # of the tag in the image (i.e. First face, 2nd, etc) |
|PROBABILITY| smallint unsigned  | NO   |     | The probability that this is an actual face |
|BOX_XMIN   | smallint unsigned  | NO   |     | coordinates of the frame containing the face |
|BOX_YMIN   | smallint unsigned  | NO   |     | coordinates of the frame containing the face |
|BOX_XMAX   | smallint unsigned  | NO   |     | coordinates of the frame containing the face |
|BOX_YMAX   | smallint unsigned  | NO   |     | coordinates of the frame containing the face |
|AGE_PROB   | float              | YES  |     | detected age range. Return only if age plugin is enabled |
|AGE_LOW    | tinyint            | YES  |     | detected age range. Return only if age plugin is enabled |
|AGE_HIGH   | tinyint            | YES  |     | detected age range. Return only if age plugin is enabled |
|GENDER_PROB| float              | YES  |     | detected gender. Return only if gender plugin is enabled |
|GENDER     | enum {male, female}| YES  |     | detected gender. Return only if gender plugin is enabled |
|POSE_PITCH | float signed       | YES  |     | detected head pose. Return only if pose plugin is enabled |
|POSE_ROLL  | float signed       | YES  |     | detected head pose. Return only if pose plugin is enabled |
|POSE_YAW   | float signed       | YES  |     | detected head pose. Return only if pose plugin is enabled |
|landmarks  | varchar(255)       | YES  |     | list of the coordinates of the frame containing the face-landmarks. Return only if landmarks plugin is enabled |
