<?php
if (!class_exists('ThisDB')) {

  /**
   * ThisDB PHP Client
   * @package thisdb-php
   * @version 1.0.0
   * @author  https://www.thisdb.com
   * @license http://www.opensource.org/licenses/mit-license.php MIT
   */
  class ThisDB
  {

    /**
     * API key
     * @access private
     * @type string $apiKey ThisDB.com API key
     */
    private $apiKey = '';

    /**
     * API Endpoint
     * @access public
     * @type string URL for ThisDB.com API
     */
    public $endpoint = 'https://api.thisdb.com/v1/';

    /**
     * Current Version
     * @access public
     * @type string Current version number
     */
    public $version = '1.0.0';

    /**
     * User Agent
     * @access public
     * @type string API User-Agent string
     */
    public $agent = 'ThisDB API Client';

    /**
     * Debug Variable
     * @access public
     * @type bool Debug API requests
     */
    public $debug = FALSE;

    /**
     * Response code variable
     * @access public
     * @type int Holds HTTP response code from API
     * */
    public $response_code = 0;

    /**
     * Response code variable
     * @access public
     * @type bool Determines whether to include the response code, default: false
     * */
    public $get_code = false;

    /**
     * Bool variable
     * @access public
     * @type bool Determines whether to return a boolean, default: false
     * */
    public $get_bool = false;

    /**
     * Constructor function
     * @param string $apiKey
     * @return void
     */
    public function __construct($apiKey)
    {
      $this->apiKey = $apiKey;
    }

    public function get($bucket, $key)
    {
        return self::methodGet($bucket.'/'.$key);
    }

    public function set($bucket, $key, $value)
    {
        return self::code($bucket.'/'.$key, $value);
    }

    public function increment($bucket, $key, $value)
    {
        $this->get_bool = false;
        return self::methodPatch($bucket.'/'.$key, $value);
    }

    public function createToken($bucket, $prefix, $permissions, $ttl)
    {
      $this->get_bool = false;
      return self::methodPost('tokens', array(
        'bucket' => $bucket,
        'prefix' => $prefix,
        'permissions' => $permissions,
        'ttl' => $ttl
      ));
    }

    public function createBucket($defaultTTL = "")
    {
      $this->get_bool = false;
      if ($defaultTTL !== "") {
        return self::methodPost('', array('default_ttl' => $defaultTTL));
      }
      return self::methodPost('', '');
    }

    public function updateBucket($bucket, $defaultTTL = "")
    {
      $this->get_bool = true;
      if ($defaultTTL !== "") {
        return self::methodPatch($bucket, array('default_ttl' => $defaultTTL));
      }
      return self::methodPatch($bucket, '');
    }

    public function deleteBucket($bucket)
    {
      $this->get_bool = true;
      return self::methodDelete($bucket, '');
    }

    /**
     * GET Method
     * @param string $method
     * @param mixed $args
     */
    public function methodGet($method, $args = FALSE)
    {
        $this->request_type = 'GET';
        $this->get_code = false;
        $this->get_bool = false;
        return self::query($method, $args);
    }

    /**
     * CODE Method
     * @param string $method
     * @param mixed $args
     * @return mixed if no exceptions thrown
     * */
    public function code($method, $args = FALSE)
    {
        $this->request_type = 'POST';
        $this->get_code = true;
        $this->get_bool = false;
        return self::query($method, $args);
    }

    /**
     * POST Method
     * @param string $method
     * @param mixed $args
     */
    private function methodPost($method, $args)
    {
        $this->request_type = 'POST';
        return self::query($method, $args);
    }

    /**
     * PATCH Method
     * @param string $method
     * @param mixed $args
     */
    private function methodPatch($method, $args)
    {
        $this->request_type = 'PATCH';
        return self::query($method, $args);
    }

    /**
     * DELETE Method
     * @param string $method
     * @param mixed $args
     */
    private function methodDelete($method, $args)
    {
        $this->request_type = 'DELETE';
        return self::query($method, $args);
    }

    /**
     * API Query Function
     * @param string $method
     * @param mixed $args
     */
    private function query($method, $args)
    {

        $url = $this->endpoint . $method;

        if ($this->debug)
            echo $this->request_type . ' ' . $url . PHP_EOL;

        $_defaults = array(
            CURLOPT_USERAGENT => sprintf('%s v%s (%s)', $this->agent, $this->version, 'https://www.thisdb.com'),
            CURLOPT_HEADER => 0,
            CURLOPT_VERBOSE => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTP_VERSION => '1.0',
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => array('X-Api-Key: '.$this->apiKey)
        );

        switch ($this->request_type) {

            case 'POST':
                if (is_array($args)) {
                  $post_data = http_build_query($args);
                } else {
                  $post_data = $args;
                }
                $_defaults[CURLOPT_URL] = $url;
                $_defaults[CURLOPT_POST] = 1;
                $_defaults[CURLOPT_POSTFIELDS] = $post_data;
                break;

            case 'PATCH':
                if (is_array($args)) {
                  $post_data = http_build_query($args);
                } else {
                  $post_data = $args;
                }
                $_defaults[CURLOPT_URL] = $url;
                $_defaults[CURLOPT_POST] = 1;
                $_defaults[CURLOPT_POSTFIELDS] = $post_data;
                $_defaults[CURLOPT_CUSTOMREQUEST] = 'PATCH';
                break;

            case 'DELETE':
                if (is_array($args)) {
                  $post_data = http_build_query($args);
                } else {
                  $post_data = $args;
                }
                $_defaults[CURLOPT_URL] = $url;
                $_defaults[CURLOPT_POST] = 1;
                $_defaults[CURLOPT_POSTFIELDS] = $post_data;
                $_defaults[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                break;

            case 'GET':
                if ($args !== FALSE) {
                    $get_data = http_build_query($args);
                    $_defaults[CURLOPT_URL] = $url . '?' . $get_data;
                } else {
                    $_defaults[CURLOPT_URL] = $url;
                }
                break;

            default:break;
        }
        $apisess = curl_init();
        curl_setopt_array($apisess, $_defaults);
        $response = curl_exec($apisess);
        $httpCode = curl_getinfo($apisess, CURLINFO_HTTP_CODE);

        /**
         * Check to see if there were any API exceptions thrown
         * If so, then error out, otherwise, keep going.
         */
        self::isAPIError($apisess, $response);

        /**
         * Close our session
         * Return the response
         */
        curl_close($apisess);

        if ($this->get_code) {
            return (int) $this->response_code;
        }

        if ($this->get_bool) {
            return ($this->response_code === 200) ? true : false;
        }

        return $response;
    }

    public function getCode()
    {
        return (int) $this->response_code;
    }

    public function checkConnection()
    {
        return $this->getCode() == 200 ? true : false;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * API Error Handling
     * @param cURL_Handle $response_obj
     * @param string $response
     * @throws Exception if invalid API location is provided
     * @throws Exception if API token is missing from request
     * @throws Exception if API method does not exist
     * @throws Exception if Internal Server Error occurs
     * @throws Exception if the request fails otherwise
     */
    public function isAPIError($response_obj, $response)
    {

        $code = curl_getinfo($response_obj, CURLINFO_HTTP_CODE);
        $this->response_code = $code;

        if ($this->debug)
            echo $code . PHP_EOL;

        switch ($code) {
            case 200: break;
            case 400: throw new Exception('Bad Request. Your request was invalid');
                break;
            case 403: throw new Exception('Invalid or missing API key. Check that your API key is present and matches your assigned key');
                break;
            case 404: throw new ResourceNotFoundException('Not found. The resource you were looking for could not be found');
                break;
            case 405: throw new Exception('Invalid HTTP method. Check that the method (POST|GET) matches what the documentation indicates');
                break;
            case 406: throw new Exception('Not acceptable. You requested a format that is not supported');
                break;
            case 500: throw new Exception('Internal server error. Try again at a later time');
                break;
            case 503: throw new Exception('Service unavailable. The was a problem processing your request');
                break;
            case 429: throw new Exception('Too many requests. The rate limit has been reached.');
                break;
            default: throw new Exception('HTTP Error '.$code);
        }
    }
  }
}
class ResourceNotFoundException extends Exception {
  public function errorMessage() {
    return "Resource not found";
  }
}
