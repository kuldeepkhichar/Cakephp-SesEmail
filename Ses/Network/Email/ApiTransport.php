<?php
/**
 * Aws Api class
 *
 * Enables sending of email from Cakephp 2 over SES via AWS SDK for PHP - Version 3
 *
 * Licensed under The MIT License
 * 
 * @author Kuldeep Khichar <erkuldeepkhichar@gmail.com>
 * 
 * @link https://github.com/kuldeepkhichar
 *
 * @license MIT License ( http://www.opensource.org/licenses/mit-license.php )
*/

/* Path to load AWS sdk */
App::import('Vendor', 'aws', array('file' => 'aws'. DS . 'aws-autoloader.php'));

class ApiTransport extends AbstractTransport {
	
	protected $_config = array();
	
	/*
		@params CakeEmail $email
		@return array('ses_message_id' => 'message_id')
	*/
	public function send(CakeEmail $email) {
		$sesEmail = new \Aws\Ses\SesClient(array(
			'credentials' => array(
				'key' => $this->_config['key'],
				'secret' => $this->_config['secret'],
			),
			'region' => $this->_config['region'],
			'version' => '2010-12-01'
		));
		
		$headers = $email->getHeaders(array('from', 'sender', 'replyTo', 'readReceipt', 'returnPath', 'to', 'cc', 'subject'));
		$headers = $this->_headersToString($headers);
		
		$lines = $email->message();
		$messages = array();
		foreach ($lines as $line) {
			if ((!empty($line)) && ($line[0] === '.')) {
				$messages[] = '.' . $line;
			}
			else {
				$messages[] = $line;
			}
		}
		
		$message = implode("\r\n", $messages);
		$complete_message = $headers . "\r\n\r\n" . $message . "\r\n\r\n\r\n";
		
		try {
			$result = $sesEmail->sendRawEmail(array(
				'RawMessage' => array(
					'Data' => $complete_message
				)
			));
			return array('ses_message_id' => $result->get('MessageId'));
		}
		catch (MessageRejectedException $e) {
			return false;
		}
	}
}
