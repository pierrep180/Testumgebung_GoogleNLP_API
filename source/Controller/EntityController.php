<?php

declare(strict_types=1);

namespace GoogleNlp\Controller;

use Google\Cloud\Language\LanguageClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use voku\helper\HtmlDomParser;

final class EntityController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $view = Twig::fromRequest($request);

        return $view->render(
            $response,
            'entity.twig',
            [
            ]
        );
    }

    public function entityUrlToContent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $text = $request->getParsedBody()['text'];
        $dom = HtmlDomParser::file_get_html($text);
        $content = $dom->find('.article__paragraph p')->plaintext;
        $content2 = implode(' ', $content);

        $view = Twig::fromRequest($request);
        return $view->render(
            $response,
            'entity.twig',
            [
                'LText' => $text,
                'content' => $content2
            ]
        );
    }

    public function analyzeEntities(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $text = $request->getParsedBody()['text'];
        $entities = [];
        try {
            $language = new LanguageClient([
                'keyFilePath' => getcwd() . '/../pierre-pinisch-ded291dbc73b.json',
            ]);

            $annotation = $language->analyzeEntities($text);

            foreach ($annotation->entities() as $entity) {
                $entities [] = [
                    'type' => $entity['type'] ?? 0,
                    'name' => $entity['name'] ?? 0,
                    'salience' => $entity['salience'] ?? 0,
                    'metadata' => $metadata = [
                        'mid' => $entity['metadata']['mid'] ?? 0,
                        'wikipedia_url' => $entity['metadata']['wikipedia_url'] ?? 0
                    ],
                ];
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $view = Twig::fromRequest($request);
        return $view->render(
            $response,
            'entity.twig',
            [
                'AText' => $text,
                'entities' => $entities,
                'entityCount' => count($entities)
            ]
        );
    }

}