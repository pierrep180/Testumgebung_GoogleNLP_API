<?php

declare(strict_types=1);

namespace GoogleNlp\Controller;


use Exception;
use Google\Cloud\Language\LanguageClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use voku\helper\HtmlDomParser;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/simple_html_dom/simple_html_dom.php';


final class SentimentController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render(
            $response,
            'sentiment.twig',
            [
            ]
        );
    }

    public function sentimentUrlToContent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $text = $request->getParsedBody()['text'];
        $dom = HtmlDomParser::file_get_html($text);
        $content = $dom->find('.article__paragraph p')->plaintext;
        $content2 = implode(' ', $content);
        $view = Twig::fromRequest($request);
        return $view->render(
            $response,
            'sentiment.twig',
            [
                'LText' => $text,
                'content' => $content2
            ]
        );
    }

    public function analyzeSentiment(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $text = $request->getParsedBody()['text'];
        $sentences = [];
        try {
            $language = new LanguageClient([
                'keyFilePath' => getcwd() . '/../pierre-pinisch-ded291dbc73b.json',
            ]);

            $annotation = $language->analyzeSentiment($text);
            foreach ($annotation->sentences() as $sentence) {
                $sentiment = [
                    'magnitude' => $sentence['sentiment']['magnitude'] ?? 0,
                    'score' => $sentence['sentiment']['score'] ?? 0
                ];
                $sentences[] = [
                    'text' => $sentence['text']['content'] ?? '',
                    'sentiment' => $sentiment
                ];
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $view = Twig::fromRequest($request);
        return $view->render(
            $response,
            'sentiment.twig',
            [
                'AText' => $text,
                'sentiment_score' => $annotation->sentiment()['score'],
                'magnitude_score' => $annotation->sentiment()['magnitude'],
                'sentences' => $sentences
            ]
        );

    }
}

