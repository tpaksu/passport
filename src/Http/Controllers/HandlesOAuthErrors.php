<?php

namespace Laravel\Passport\Http\Controllers;

use Exception;
use Throwable;
use Illuminate\Http\Response;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Zend\Diactoros\Response as Psr7Response;
use Illuminate\Contracts\Debug\ExceptionHandler;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

trait HandlesOAuthErrors
{
    use ConvertsPsrResponses;

    /**
     * Perform the given callback with exception handling.
     *
     * @param  \Closure  $callback
     * @return \Illuminate\Http\Response
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (OAuthServerException $e) {
            $this->exceptionHandler()->report($e);

            return $this->translateResponse($this->convertResponse(
                $e->generateHttpResponse(new Psr7Response)
            ));
        } catch (Exception $e) {
            $this->exceptionHandler()->report($e);

            return new Response($this->configuration()->get('app.debug') ? $e->getMessage() : 'Error.', 500);
        } catch (Throwable $e) {
            $this->exceptionHandler()->report(new FatalThrowableError($e));

            return new Response($this->configuration()->get('app.debug') ? $e->getMessage() : 'Error.', 500);
        }
    }

    /**
     * Translates the response content
     *
     * @return \Illuminate\Http\Response
     */
    protected function translateResponse(Response $httpResponse)
    {
        // get response content as object
        $content = json_decode($httpResponse->content(), false);

        // backup the old message and hint properties
        $message_backup = property_exists($content->message) ? $content->message : false;
        $message_hint_backup = property_exists($content,"hint") ? $content->hint : false;

        // translate strings
        $content->message = trans("passport::messages.".$content->error);
        $content->hint = trans("passport::messages.".$content->error."_hint");

        // if the translation file doesn't contain the key, restore from backup, and if property didn't exist before, delete the keys from the message
        if($content->message == "passport:messages.".$content->error) $content->message = $message_backup;
        if($content->message == false) unset($content->message);
        if($content->hint == "passport:messages.".$content->error."_hint") $content->hint = $message_hint_backup;
        if($content->hint == false) unset($content->hint);

        return $httpResponse->setContent(json_encode($content));
    }

    /**
     * Get the configuration repository instance.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    protected function configuration()
    {
        return Container::getInstance()->make(Repository::class);
    }

    /**
     * Get the exception handler instance.
     *
     * @return \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected function exceptionHandler()
    {
        return Container::getInstance()->make(ExceptionHandler::class);
    }
}
