<?php
/**
 * Created by PhpStorm.
 * User: maks
 * Date: 31.10.16
 * Time: 12:33
 */

namespace Generator\Services;

use FPDI;

/**
 * Class PdfGenerator
 * @package Generator\Services
 */
class PdfGenerator
{

    /**
     * @var FPDI
     */
    private $generator;

    /**
     * @var string
     */
    private $seo_data = '';

    /**
     * @var string
     */
    private $facebook_likes = '';

    /**
     * @var
     */
    private $post;

    /**
     * @var array
     */
    private $google_data = array(
        'resp1' => ['0', '0'],
        'resp2' => ['0', '0'],
        'resp3' => ['0', '0'],
        'resp4' => ['0', '0'],
        'resp5' => ['0', '0'],
        'summ_1' => '0',
        'summ_2' => '0'
    );

    /**
     * @param $path
     * @param $post
     * @return mixed
     */
    public function generate($path, $post)
    {
        $this->prepare($path);
        $this->post = $post;

        $this->drawTitle();

        $this->generator->SetFontSize(11);

        if (isset($this->google_data['kw_1']['keyword'])) {
            $y = $this->getY($this->google_data['kw_1']['keyword'], 49, 103.2, 100.8);
            $this->draw(26, $y, $this->google_data['kw_1']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_1']['total_de'], 30, 103.2, 100.8);
            $this->draw(81, $y, $this->google_data['kw_1']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_1']['total_reg'], 30, 102.7, 100.8);
            $this->draw(140, $y, $this->google_data['kw_1']['total_reg'], 52, 4);
        }
        if (isset($this->google_data['kw_2']['keyword'])) {
            $y = $this->getY($this->google_data['kw_2']['keyword'], 49, 111.1, 109);
            $this->draw(26, $y, $this->google_data['kw_2']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_2']['total_de'], 30, 111.1, 109);
            $this->draw(81, $y, $this->google_data['kw_2']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_2']['total_reg'], 30, 110.6, 109);
            $this->draw(140, $y, $this->google_data['kw_2']['total_reg'], 52, 4);
        }
        if (isset($this->google_data['kw_3']['keyword'])) {
            $y = $this->getY($this->google_data['kw_3']['keyword'], 49, 119.4, 117);
            $this->draw(26, $y, $this->google_data['kw_3']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_3']['total_de'], 30, 119.4, 117);
            $this->draw(81, $y, $this->google_data['kw_3']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_3']['total_reg'], 30, 118.9, 117);
            $this->draw(140, $y, $this->google_data['kw_3']['total_reg'], 52, 4);
        }
        if (isset($this->google_data['kw_4']['keyword'])) {
            $y = $this->getY($this->google_data['kw_4']['keyword'], 49, 127.5, 125.2);
            $this->draw(26, $y, $this->google_data['kw_4']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_4']['total_de'], 30, 127.2, 125.2);
            $this->draw(81, $y, $this->google_data['kw_4']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_4']['total_reg'], 30, 127.2, 125.2);
            $this->draw(140, $y, $this->google_data['kw_4']['total_reg'], 52, 4);
        }
        if (isset($this->google_data['kw_5']['keyword'])) {
            $y = $this->getY($this->google_data['kw_5']['keyword'], 49, 135.5, 133.5);
            $this->draw(26, $y, $this->google_data['kw_5']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_5']['total_de'], 30, 135.5, 133.5);
            $this->draw(81, $y, $this->google_data['kw_5']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_5']['total_reg'], 30, 135.5, 133.5);
            $this->draw(140, $y, $this->google_data['kw_5']['total_reg'], 52, 4);
        }
        if (isset($this->google_data['kw_6']['keyword'])) {
            $y = $this->getY($this->google_data['kw_6']['keyword'], 49, 143.5, 142.2);
            $this->draw(26, $y, $this->google_data['kw_6']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_6']['total_de'], 30, 143.5, 142.2);
            $this->draw(81, $y, $this->google_data['kw_6']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_6']['total_reg'], 30, 143.5, 142.2);
            $this->draw(140, $y, $this->google_data['kw_6']['total_reg'], 52, 4);
        }
        if (isset($this->google_data['kw_7']['keyword'])) {
            $y = $this->getY($this->google_data['kw_7']['keyword'], 49, 151.7, 150.9);
            $this->draw(26, $y, $this->google_data['kw_7']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_7']['total_de'], 30, 151.7, 150.9);
            $this->draw(81, $y, $this->google_data['kw_7']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_7']['total_reg'], 30, 151.7, 150.9);
            $this->draw(140, $y, $this->google_data['kw_7']['total_reg'], 52, 4);
        }
        if (isset($this->google_data['kw_8']['keyword'])) {
            $y = $this->getY($this->google_data['kw_8']['keyword'], 49, 159.8, 159.5);
            $this->draw(26, $y, $this->google_data['kw_8']['keyword'], 52, 4, false);
            $y = $this->getY($this->google_data['kw_8']['total_de'], 30, 159.8, 159.5);
            $this->draw(81, $y, $this->google_data['kw_8']['total_de'], 52, 4);
            $y = $this->getY($this->google_data['kw_8']['total_reg'], 30, 159.8, 159.5);
            $this->draw(140, $y, $this->google_data['kw_8']['total_reg'], 52, 4);
        }

        if (
            isset($this->google_data['kw_1']['keyword']) || isset($this->google_data['kw_2']['keyword']) ||
            isset($this->google_data['kw_3']['keyword']) || isset($this->google_data['kw_4']['keyword']) ||
            isset($this->google_data['kw_5']['keyword'])
        ) {
            $y = $this->getY($this->google_data['summ_1'], 30, 168, 167);
            $this->draw(81, $y, $this->google_data['summ_1'], 52, 4);
            $y = $this->getY($this->google_data['summ_2'], 30, 168, 167);
            $this->draw(140, $y, $this->google_data['summ_2'], 52, 4);
        }


        $this->generator->SetFontSize(16);
        $this->draw(88, 184.5, $this->seo_data, 15, 5);

        if (isset($post['activiren']) && $post['activiren'] == 'on') {
            $this->generator->SetFontSize(10);
            $this->draw(55.5, 241.3, $this->facebook_likes, 19, 4);

            $this->generator->SetFontSize(60);
            $this->drawDesign($post['design']);
//          Mobile Website / Responsive
            $this->drawRadios($post['responsive'], 107.1, 218.5, 167.1);
//          Google MyBusiness Eintrag
            $this->drawRadios($post['eintrag'], 107.3, 226.8, 167.1);
//          Facebook-Seite
            $this->drawRadios($post['facebook'], 107.1, 235, 167);
//          Facebook Verlinkung
            $this->drawRadios($post['verlinkung'], 107.1, 243.1, 167.1);
        }

        $this->generator->setPdfVersion('1.3');
        return $this->generator->Output("{$post['company_name']}-Analysis.pdf", "F", true);
    }

    /**
     *
     */
    private function drawTitle()
    {
        $this->generator->SetFontSize(18);
        $y = $this->getY($this->post['company_name'], 39, 58.5, 55.5);
        $this->draw(78, $y, $this->post['company_name'], 42, 4);
    }

    /**
     * @param $text
     * @param $max_length
     * @param $than
     * @param $else
     * @return mixed
     */
    private function getY($text, $max_length, $than, $else)
    {
        return $this->generator->GetStringWidth($text) <= $max_length ? $than : $else;
    }

    /**
     * @param $path
     */
    private function prepare($path)
    {
        $this->generator = new FPDI();
        $this->generator->AddFont('MyriadPro-Regular', '', 'MyriadPro-Regular.ttf', true);
        $this->generator->setSourceFile($path);
        $tplIdx = $this->generator->importPage(1);
        $this->generator->SetMargins(0, 0, 0);
        $this->generator->SetAutoPageBreak(0, 0);
        $this->generator->addPage();
        $this->generator->useTemplate($tplIdx, null, null, 0, 0, true);
        $this->generator->SetFont('MyriadPro-Regular');
        $this->generator->SetTextColor(0, 0, 0);
        $this->generator->SetFontSize(12);
    }

    /**
     * @param $x
     * @param $y
     * @param $text
     * @param $w
     * @param $h
     * @param string $align
     */
    private function draw($x, $y, $text, $w, $h, $align = 'C')
    {
        $this->generator->SetXY($x, $y);
        $this->generator->MultiCell($w, $h, $text, 0, $align);
    }

    /**
     * @param $param
     */
    private function drawDesign($param)
    {
        switch ($param) {
            case 1:
                $this->draw(94.7, 203, '.', 5, 7);
                break;
            case 2:
                $this->draw(133.5, 203, '.', 5, 7);
                break;
            case 3:
                $this->draw(172.3, 203, '.', 5, 7);
                break;
            default:
                break;

        }
    }

    /**
     * @param $param
     * @param $yes_x
     * @param $y
     * @param $no_x
     */
    private function drawRadios($param, $yes_x, $y, $no_x)
    {
        switch ($param) {
            case 1:
                $this->draw($yes_x, $y, '.', 5, 7);
                break;
            case 2:
                $this->draw($no_x, $y, '.', 5, 7);
                break;
            default:
                break;

        }
    }

    /**
     * @param $data
     * @return $this
     */
    public function setSeoData($data)
    {
        $this->seo_data = $data;
        return $this;
    }

    /**
     * @param $likes
     * @return $this
     */
    public function setFacebookLiles($likes)
    {
        $this->facebook_likes = $likes;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setGoogleData($data)
    {
        $this->google_data = $data;
        $this->calcSum();
        return $this;
    }

    /**
     * Calculate sum
     */
    private function calcSum()
    {
        $first_col = 0;
        $second_col = 0;

        foreach ($this->google_data as $k => $value) {
            if (is_array($value)) {
                $col1 = array_shift($value['total_de']);
                $col2 = array_shift($value['total_reg']);
                $this->google_data[$k]['total_de'] = $col1;
                $this->google_data[$k]['total_reg'] = $col2;
                $first_col += $col1;
                $second_col += $col2;
            }
        }

        $this->google_data['summ_1'] = $first_col;
        $this->google_data['summ_2'] = $second_col;
    }
}