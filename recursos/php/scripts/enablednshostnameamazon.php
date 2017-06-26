<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require "/var/www/html/cloudmarket/aws/aws-autoloader.php";

use Aws\Ec2\Ec2Client;

$ec2Client = Ec2Client::factory(array(
            'version' => 'latest',
            'region' => substr($argv[4],0,-1),
            'credentials' => array(
                'key' => $argv[1],
                'secret' => $argv[2]
            ),
        ));

$ec2Client->modifyVpcAttribute(array(
    'VpcId' => $argv[3],
    'EnableDnsHostnames' => [
        'Value' => true,
    ]
));
?>