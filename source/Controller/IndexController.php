<?php

declare(strict_types=1);

namespace GoogleNlp\Controller;

use DOMDocument;
use DOMXPath;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use voku\helper\HtmlDomParser;


final class IndexController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response):ResponseInterface {
        include __DIR__ . '/../../vendor/simple_html_dom/simple_html_dom.php';
        require_once __DIR__ . '/../../vendor/simple_html_dom/simple_html_dom.php';


        $view = Twig::fromRequest($request);

        return $view->render(
            $response,
            'dashboard.twig',
            [
            ]
        );
    }

    public function urlToContent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $text = $request->getParsedBody()['text'];
        $dom = HtmlDomParser::file_get_html($text);
        $content = $dom->find('.article__paragraph p')->plaintext;
        $content2 = implode(' ', $content);

        $view = Twig::fromRequest($request);
        return $view->render(
            $response,
            'dashboard.twig',
            [
                'LText' => $text,
                'content' => $content2
            ]
        );
    }
}