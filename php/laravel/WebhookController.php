<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Exception;  
use Symfony\Component\Process\Process;

class WebhookController
{
    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Validate an incoming github webhook
     *
     * @param string $known_token Our known token that we've defined
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     * @return void
     */
    protected function validateGithubWebhook($known_token, Request $request)
    {
        if (($signature = $request->headers->get('X-Hub-Signature')) == null) {
            throw new \Exception('Header not set');
        }

        $signature_parts = explode('=', $signature);

        if (count($signature_parts) != 2) {
            throw new \Exception('signature has invalid format');
        }

        $known_signature = hash_hmac('sha1', $request->getContent(), $known_token);

        if (! hash_equals($known_signature, $signature_parts[1])) {
            throw new \Exception('secret token is not match');
        }
    }

    /**
     * Validate an incoming gitlab webhook
     *
     * @param string $known_token Our known token that we've defined
     * @param \Illuminate\Http\Request $request
     *
     * @throws  \Exception 
     * @return void
     */
    protected function validateGitlabWebhook($known_token, Request $request)
    {
        if (($signature = $request->headers->get('X-Gitlab-Token')) == null) {
            throw new  \Exception('Header not set');
        }

        if (strcmp($known_token, $signature) != 0) {
            throw new \Exception('secret token is not match');
        }
    }


    /**
     * Entry point to our webhook handler
     * comment unused validation! 
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    { 
        /** uncomment one of these:  */ 
        // if u are using github:
        // $this->validateGithubWebhook(config('app.webhook_secret'), $request); 
        // if u are using gitlab
        // $this->validateGitlabWebhook(config('app.webhook_secret'), $request); 

        $this->logger->info('Hello World. The Git webhook is validated!');
        $this->logger->info($request->getContent());

        $root_path = base_path(); 
        $process = new Process(['cd', $root_path, '&&', './webhook.sh']);
        $process->run(function ($type, $buffer) {
            $this->logger->info("[webhook]: ".$type.", ".$buffer); 
        });

        $this->logger->info("finished!");
    }
}
