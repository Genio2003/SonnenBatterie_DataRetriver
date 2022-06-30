<?php

// Sonnen Batterie Data Manager //

// Sonnen Batterie IP:
$IP = "192.168.1.7";

// MySQL Connection Parameter:
$host = "192.168.1.2";
$username = "sonnen";
$password = "batterie";
$database = "SonnenBatterie_Data";

// ----------------------------- //

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

function MySQL_Push($host,$username,$password,$database)
{
    $Response=json_decode(CallAPI("GET","http://192.168.1.7/api/v2/status"),true);

    $conn = mysqli_connect($host,$username,$password,$database);

    $query = "INSERT INTO Status_Data VALUES ('".$Response['Timestamp']."', '".$Response['Apparent_output']."', '".$Response['BatteryCharging']."', '".$Response['BatteryDischarging']."', '".$Response['Consumption_Avg']."', '".$Response['Consumption_W']."', '".$Response['FlowConsumptionBattery']."', '".$Response['FlowConsumptionGrid']."', '".$Response['FlowConsumptionProduction']."', '".$Response['FlowGridBattery']."', '".$Response['FlowProductionBattery']."', '".$Response['FlowProductionGrid']."', '".$Response['Production_W']."', '".$Response['GridFeedIn_W']."', '".$Response['RemainingCapacity_Wh']."')";

    mysqli_query($conn,$query);

    mysqli_close($conn);
}

for ($i=0;$i=1;$i--)
{
    MySQL_Push($host,$username,$password,$database);

    ini_set('max_execution_time', '0');
    sleep(60);
}


?>