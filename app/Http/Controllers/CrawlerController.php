<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpClient\HttpClient;

class CrawlerController extends Controller
{
    public function index()
    {
        $sessao = '9f1ko3a6u5uqd1bktk110jtbg4';

        $client = new Client(HttpClient::create(array(
            'headers' => array(
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding' => 'gzip, deflate',
                'Accept-Language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'Cache-Control' => 'max-age=0',
                'Connection' => 'keep-alive',
                'Host' => 'applicant-test.us-east-1.elasticbeanstalk.com',
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36 OPR/94.0.0.0'
            ),
        )));
        $cookie = new Cookie("PHPSESSID", $sessao, null, "/", "http://applicant-test.us-east-1.elasticbeanstalk.com", true, true);
        $client->getCookieJar()->set($cookie);
        $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36');
        $client->followRedirects(true);
        $crawler = $client->request('GET', 'http://applicant-test.us-east-1.elasticbeanstalk.com');
        //dd($crawler);
        $token = $crawler->filter('#token')->extract(['value', 'name']);
        $form = $crawler->selectButton('Descobrir resposta')->form();
        $resposta = $client->submit($form, ['token' => $token[0][0]]);

        $resposta->filter('#answer')->each(function ($node) {
            echo $node->text()."\n";
        });

    }
}
