<?php
/**
 * Created by PhpStorm.
 * User: maks
 * Date: 31.10.16
 * Time: 19:06
 */

namespace Generator\Services;

/**
 * Class SeoChecker
 * @package Generator\Services
 */
class SeoChecker
{
    /**
     * @param $domain
     * @return float|string
     */
    public function process($domain)
    {
        $url = 'https://www.seobility.net/en/seocheck/';
        $html = file_get_contents($url . $domain);
        return $this->extract($html);
    }

    /**
     * @param $html
     * @return float|string
     */
    private function extract($html)
    {
        if (preg_match('/[[][{].+/', $html, $match) && !empty($match))
            $data = json_decode(str_replace(';', '', $match[0]));
        else
            return '';

        return round($data[0]->data);
    }
}