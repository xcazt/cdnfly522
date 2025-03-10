<?php
function load_data() {
    $data = file_get_contents('data.json');
    return json_decode($data, true);
}

function verify_license($license_key) {
    $data = load_data();
    foreach ($data as $client) {
        if ($client['license_key'] === $license_key) {
            $current_date = date('Y-m-d');
            if ($client['expiry_date'] >= $current_date) {
                return true;
            } else {
                return "授权到期";
            }
        }
    }
    return "未授权";
}

$license_key = $_GET['license_key'];

$result = verify_license($license_key);

if ($result === true) {
    echo "授权成功";
} else {
    echo $result;
}
?>
