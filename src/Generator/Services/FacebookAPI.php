<?php
/**
 * Created by PhpStorm.
 * User: maks
 * Date: 02.11.16
 * Time: 10:13
 */

namespace Generator\Services;

/**
 * Class FacebookAPI
 * @package Generator\Services
 */
class FacebookAPI
{
    /**
     * @var
     */
    private $app_id;

    /**
     * @var
     */
    private $app_secret;

    /**
     * @var bool
     */
    private $error = false;

    /**
     * @var bool
     */
    private $likes = false;

    /**
     * @var array
     */
    private $error_codes = [
        100 => 'Invalid Facebook url',
        210 => 'Facebook user is not visible',
        'default' => 'Cannot load information from this url'
    ];

    /**
     * FacebookAPI constructor.
     * @param $app_id
     * @param $app_secret
     */
    public function __construct($app_id, $app_secret)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
    }

    /**
     * @param $company
     * @return bool|string
     */
    public function get($company)
    {
        if ($this->getLikes($company)) {
            return $this->likes;
        } else {
            return '';
        }
    }

    /**
     * @param $company
     * @return bool
     */
    function getLikes($company)
    {
        if (!$company)
            return false;

        $url = 'https://graph.facebook.com/' . $company . '?access_token=' .
            $this->app_id . '|' . $this->app_secret . '&fields=fan_count';

        $json = file_get_contents($url);
        $data = json_decode($json);

        if ($data->fan_count) {
            return $this->likes = $data->fan_count;
        } else if ($data->error &&
            array_key_exists($data->error->code, $this->error_codes)
        ) {
            $this->error = $this->error_codes[$data->error->code];
        } else {
            $this->error = $this->error_codes['default'];
        }

        return false;
    }

    /**
     * @return bool
     */
    function getError()
    {
        return $this->error;
    }
}