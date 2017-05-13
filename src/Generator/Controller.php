<?php
/**
 * Created by PhpStorm.
 * User: maks
 * Date: 25.10.16
 * Time: 14:07
 */

namespace Generator;

use Interop\Container\ContainerInterface;
use Slim\Views\Twig;

/**
 * Class Controller
 * @package Generator
 */
abstract class Controller
{
    /**
     * @var ContainerInterface
     */
    protected $ci;

    /**
     * @var Twig
     */
    protected $view;

    /**
     * Controller constructor.
     * @param ContainerInterface $ci
     */
    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
        $this->view = $ci->get('view');
    }
}