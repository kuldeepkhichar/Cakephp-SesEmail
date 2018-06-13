CakePHP Amazon SES Plugin
======================

This package provides Api transports for sending email through Amazon, Using AWS Php SDK


Requirements
------------
Cakephp 2
[AWS SDK for PHP](https://github.com/aws/aws-sdk-php)
Login at [AWS](https://console.aws.amazon.com) account and get Access Key, Secret from AWS console(My Security Credentials).


Installation
------------
This plugin require [AWS SDK for PHP](https://github.com/aws/aws-sdk-php)- Please download in /app/Vendor/aws - Or if you have downloaded already - please update ApiTransport.php to load correctly

    App::import('Vendor', 'aws', array('file' => 'aws'. DS . 'aws-autoloader.php'));


Download and place in /app/Plugin folder, After upload ApiTransport location look like - */app/Plugin/Ses/Network/Email/ApiTransport.php*

Load the plugin in your bootstrap.php


    CakePlugin::load('Ses');


How to use
----------
To enable the transport, add the following information to your Config/email.php:

    class EmailConfig {
        public $ses = array(
            'transport' => 'Ses.Api',
            'key' => $S3_ACCESS,
            'secret' => $S3_SECRET,
            'region' => $SNS_REGION,
	    //'charset' => 'utf-8',
	    //'headerCharset' => 'utf-8',
        );
    }


Update S3_ACCESS, S3_SECRET, SNS_REGION with valid values.

And that's it, now you can send email as simple as it was in basic Cakephp email - Just put 'ses' - 

    $email = new CakeEmail('ses');
    $email->from(array('Example' => 'from@example.com'))
        ->replyTo('replyto@example.com')
        ->emailFormat('html')
        ->to('to@example.com')
        ->subject('SES Welcome Email')
        ->send('Testing Email Body.');
