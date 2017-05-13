<?php
/**
 * Created by PhpStorm.
 * User: maks
 * Date: 25.10.16
 * Time: 14:12
 */

namespace Generator\Controllers;

use Generator\Controller;
use Generator\Services\AdWords;
use Generator\Services\FacebookAPI;
use Generator\Services\PdfGenerator;
use Generator\Services\SeoChecker;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class MainController
 * @package Generator\Controllers
 */
class MainController extends Controller
{
    /**
     * @var array
     */
    private $errors = array();

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response|static
     */
    public function __invoke(Request $request, Response $response, $args)
	{
		$body = false;

		if ($request->getMethod() === 'POST') {
		    $post = $request->getParsedBody();
            $body = $post;
            $post = $this->executePost($post);
        }

		if (!empty($this->errors) || $request->getMethod() === 'GET') {
			$this->errors[100] = $this->errors[0];
			unset($this->errors[0]);
			$this->view->render($response, 'main.twig', array(
				'errors' => @array_flip($this->errors),
				'csrf' => null,
				'post' => $body ? $body : false
			));
		} else {
			$file = PUB_DIR.'/'.$post['company_name'].'-Analysis.pdf';

			$response = $response
				->withHeader('Content-Description', 'File Transfer')
				->withHeader('Content-Type', 'application/octet-stream')
				->withHeader('Content-Disposition', 'attachment;filename="'.$post['company_name'].'-Analysis.pdf'.'"')
				->withHeader('Expires', '0')
				->withHeader('Cache-Control', 'must-revalidate')
				->withHeader('Pragma', 'public')
				->withHeader('Content-Length', filesize($file));

			readfile($file);
			unlink($file);

			return $response;
		}
	}

    /**
     * @param $url
     * @return bool
     */
    private function getCompanyFromUrl($url)
	{
		return preg_match('/facebook.com\/([\p{L}-_+\s{L}]+)/u', $url, $matches) ? $matches[1] : false;
	}

    /**
     * @param $domain
     * @return mixed
     */
    private function prepareDomain($domain)
	{
		return str_replace(['http://', 'https://', 'www.'], '', $domain);
	}

    /**
     * @param $post
     * @param $google
     * @return mixed
     */
    private function execute($post, $google)
	{
		$pdf = new PdfGenerator();
		$seo = new SeoChecker();

		$fb_sett = $this->ci->get('settings')['facebook'];
		$fb = new FacebookAPI($fb_sett['app_id'], $fb_sett['app_secret']);

		$file = $this->resolveFile($post);

		if (isset($post['verlinkung']) && $post['verlinkung'] != 2)
			$stream = $pdf->setSeoData($seo->process($post['domain']))
			    ->setFacebookLiles($fb->get($post['fb_company']))
			    ->setGoogleData($google)
			    ->generate($file, $post);
		else
            $stream = $pdf->setSeoData($seo->process($post['domain']))
			    ->setGoogleData($google)
			    ->generate($file, $post);

		if ($fb->getError())
			$this->errors[] = $fb->getError();

		return $stream;
	}

    /**
     * @param $post
     * @return string
     */
    private function resolveFile($post)
    {
        if (isset($post['activiren']) && $post['activiren'] === 'on')
            return PUB_DIR . '/pdf/ACTIVE.pdf';

        return PUB_DIR . '/pdf/DISABLED.pdf';
	}

    /**
     * @param $post
     * @return bool
     */
    private function prepareData($post)
	{
		$default = '/^[\d\p{L}-. ]+[\d\p{L}-. ]*$/u';
		$domain = '/^(.*?)\.?(([^.]*)(?:(?:\..{2,4})|(?:(?:\..{2,3})(?:\..{2}))))$/im';
		$required = array(
			'city' => '/^[\p{L}-.\s\']+$/u',
			'street' => '',
			'company_name' => ''
		);

		if ($post['domain'] != '' && !preg_match($domain, $post['domain']))
			$this->errors[] = 'domain';
		else
			$post['domain'] = $this->prepareDomain($post['domain']);

		foreach ($required as $key => $value) {
			if (false == $post[$key])
				$this->errors[] = $key;

			if ('' != $required[$key] && !preg_match($required[$key], $post[$key]))
				$this->errors[] = $key;
			else if (!preg_match($default, $post[$key]))
				$this->errors[] = $key;
		}

		return empty($this->errors) ? $post : false;
	}

    /**
     * @param $post
     * @return mixed
     */
    private function getAdWordsResults($post)
	{
		$location = $post['city'];

		$keywords = [
			'kw_1' => isset($post['kw_1']) ? $post['kw_1'] : false,
			'kw_2' => isset($post['kw_2']) ? $post['kw_2'] : false,
			'kw_3' => isset($post['kw_3']) ? $post['kw_3'] : false,
			'kw_4' => isset($post['kw_4']) ? $post['kw_4'] : false,
			'kw_5' => isset($post['kw_5']) ? $post['kw_5'] : false,
			'kw_6' => isset($post['kw_6']) ? $post['kw_6'] : false,
			'kw_7' => isset($post['kw_7']) ? $post['kw_7'] : false,
			'kw_8' => isset($post['kw_8']) ? $post['kw_8'] : false
        ];

		return (new AdWords())->get($keywords, $location);
	}

    /**
     * @param $post
     * @return bool
     */
    private function executePost($post)
    {
        if ($post['fb_company'] != '' && $post['verlinkung'] != 2) {
            $company = $this->getCompanyFromUrl($post['fb_company']);

            if ($company)
                $post['fb_company'] = $company;
            else
                $this->errors[] = 'fb_company';
        } else
            $post['verlinkung'] = 2;

        if ($post = $this->prepareData($post)) {
            $res = $this->getAdWordsResults($post);
            $this->execute($post, $res);
        }

        return $post;
	}
}