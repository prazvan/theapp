<?php
header('Content-Type: application/json');

//-- build array for json
$json_array = array
(
    'status'    => $status,
    'errors'    => $errors,
    'user'      => $data['team_member'],
    'data'      => array
    (
        'devices_count' => count($data['devices']),
        'devices'       => $data['devices']
    ),
);

//-- print json and exit script
echo json_encode($json_array);
exit;
?>