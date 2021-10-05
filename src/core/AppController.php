<?php

declare(strict_types=1);

namespace Budget\Core;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;

class AppController
{
    private $request;
    private $response;
    private $model;
    private $renderer;

    public function __construct(
        ResponseInterface $response,
        Renderer $renderer,
        AppModel $model
    ) {
        $this->request = ServerRequest::fromGlobals();
        $this->response = $response;
        $this->response_body = $response->getBody();
        $this->renderer = $renderer;
        $this->model = $model;
        $this->setAppHeaders();
    }

    public function checkLogin()
    {
        $isLoggedIn = false;
        if (isset($GLOBALS['isLoggedIn']) && $GLOBALS['isLoggedIn']) {
            $isLoggedIn = true;
        }
        return $isLoggedIn;
    }

    private function setAppHeaders()
    {
        return $GLOBALS['config']['app_headers'];
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Provides an instance of the
     * Response Handler class
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Provides an instance of the class used 
     * to render the UI
     * @var \Dreamax\Core\Classes\Renderer $renderer
     * @return \Dreamax\Core\Classes\Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Provides a Stream instance that is 
     * used in the creation of a http response
     * to render the UI
     * @var \Dreamax\Core\Classes\Renderer $renderer
     * @return StreamInterface
     */
    public function getResponseBody()
    {
        return $this->response_body;
    }

    public function setErrorResponse(
        string $template = '',
        array $template_data = [],
        string $status = '400',
        string $mime = 'text/html'
    ) {

        return $this->setResponse($template, $template_data, $status, $mime);
    }

    /**
     * Builds a custom response
     * @param string $template
     * @param mixed $response_data
     * @param string $content_type
     * @param string $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setResponse(
        string $template = '',
        array $template_data = [],
        string $status = '200',
        string $mime = 'text/html'
    ): ResponseInterface {

        /**
         * If returning data to a REST API the 
         * encode the data to JSON
         */
        if ($mime == 'application/json') {

            $this->response_body->write(json_encode($template_data));
        } else {

            $response_data = $this->getRenderer()->render($template, $template_data);
            $this->response_body->write($response_data);
        }

        return $this->getResponse()
            ->withBody($this->response_body)
            ->withHeader('Content-Type', $mime)
            ->withStatus($status);
    }

    /**
     * Builds a redirect
     * @param string $url
     * @param string $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setRedirect(
        string $url,
        string $status = '302'
    ): ResponseInterface {
        $base = $GLOBALS['config']['base_path'];
        $response = $this->getResponse()
            ->withBody($this->response_body)
            ->withHeader('Content-Type', 'text/plain')
            ->withAddedHeader('Location', $base . $url)
            ->withStatus($status);

        return $response;
    }


    /**
     * Get POST data as array
     */
    public function getPostData()
    {
        $post_data = $this->getRequest()->getParsedBody();

        if (!empty($post_data)) {
            return $post_data;
        } else {
            $post_data = $this->getRequest()->getBody()->getContents();

            return json_decode($post_data, true);
        }
    }

    /**
     * Validate image and Save file locally
     */
    public function saveUploadedFiles($module, $system = false)
    {
        // TODO ... sanitization
        $post_files = $_FILES;

        foreach ($post_files as $key => $file) {

            if ($file['size'] !== 0) {

                $filename = sprintf(
                    '%s.%s',
                    $this->create_uuid(),
                    pathinfo($file['name'], PATHINFO_EXTENSION)
                );

                $path = __DIR__ . '/../../../uploads/' . $GLOBALS['sess']['user_id'] . '/' . $module . '/';
                if ($system === true) {
                    $path = __DIR__ . '/../../../uploads/' . $module . '/';
                }

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                // user_id / module
                $file_location =  $path . $filename;
                move_uploaded_file($_FILES["file"]["tmp_name"], $file_location);
                return $filename;
            }
        }
        return false;
    }

    /**
     * function to generate a uuid
     */
    public function create_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
