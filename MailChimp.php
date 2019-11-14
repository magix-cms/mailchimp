<?php

namespace Drewm;

/**
 * Super-simple, minimum abstraction MailChimp API v3 wrapper
 *
 * Uses curl if available, falls back to file_get_contents and HTTP stream.
 * This probably has more comments than code.
 *
 * Contributors:
 * Michael Minor <me@pixelbacon.com>
 * Lorna Jane Mitchell, github.com/lornajane
 * Salvatore Di Salvo, github.com/xarksass
 *
 * @author Drew McLellan <drew.mclellan@gmail.com>
 * @version 2.0
 */
class MailChimp
{
    private $api_key;
    private $api_endpoint = 'https://<dc>.api.mailchimp.com/3.0';
    private $verify_ssl   = false;

    /**
     * Create a new instance
     * @param string $api_key Your MailChimp API key
     */
    function __construct($api_key)
    {
        $this->api_key = $api_key;
        list(, $datacentre) = explode('-', $this->api_key);
        $this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);
    }

    /**
     * Call an API method. Every request needs the API key, so that is added automatically -- you don't need to pass it in.
     * @param  string $method The API method to call, e.g. 'lists/list'
     * @param  array  $args   An array of arguments to pass to the method. Will be json-encoded for you.
     * @return array          Associative array of json decoded API response.
     */
    public function call($method, $type='GET', $args=array(), $timeout = 10)
    {
        return $this->makeRequest($method, $type, $args, $timeout);
    }

    /**
     * Performs the underlying HTTP request. Not very exciting
     * @param  string $method The API method to be called
     * @param  array  $args   Assoc array of parameters to be passed
     * @return array          Assoc array of decoded result
     */
    private function makeRequest($method, $type, $args=array(), $timeout = 10)
    {
        $result = false;

		if(!empty($method) && ($type === 'POST' || $type === 'PUT' || $type === 'DELETE' || $type === 'GET')){
			$url = $this->api_endpoint.'/'.$method;

			if (function_exists('curl_init') && function_exists('curl_setopt')){
				$ch = curl_init();
				if(!empty($args)) {
					if($type === 'GET') {
						$params = http_build_query($args);
						curl_setopt($ch, CURLOPT_URL, $url.'?'.$params );
					}
					elseif($type === 'POST' || $type === 'PUT' || $type === 'DELETE') {
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
					}
				}
				else {
					curl_setopt($ch, CURLOPT_URL, $url);
				}

				curl_setopt($ch, CURLOPT_USERPWD, "magixcms:$this->api_key");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_POST, $type === 'POST');
				if($type === 'PUT' || 'DELETE') curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
				$result = curl_exec($ch);
				curl_close($ch);
			} else {
				$json_data = json_encode($args);
				$result    = file_get_contents($url, null, stream_context_create(array(
					'http' => array(
						'protocol_version' => 1.1,
						'user_agent'       => 'PHP-MCAPI/2.0',
						'method'           => $type,
						'header'           => "Content-type: application/json\r\n".
							"Connection: close\r\n" .
							"Content-length: " . strlen($json_data) . "\r\n",
						'content'          => $json_data,
					),
				)));
			}
		}

        return $result ? json_decode($result, true) : $result;
    }
}