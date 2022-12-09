<?php

namespace App\Http\Helpers;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse {

    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;
    protected $token = '';

    /**
     * @return mixed
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode, $httpCode = null) {
        $httpCode = $httpCode ?? $statusCode;
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $statusCode
     * @param null $httpCode
     * @return $this
     */
    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data) {
        $response = response()->json($data, $this->getStatusCode());
        if ($this->token) {
            $response->headers->set('Authorization', 'Bearer ' . $this->token);
        }
        return $response;
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @param array $header
     * @return mixed
     */
    public function status($status, array $data, $code = null) {
        if ($code) {
            $this->setStatusCode($code);
        }
        $status = [
            'status' => $status,
            'code' => $this->statusCode
        ];

        $data = array_merge($status, $data);
        return $this->respond($data);
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     * @return mixed
     */
    /*
     * 格式
     * data:
     *  code:422
     *  message:xxx
     *  status:'error'
     */
    public function error($message = 'error', $code = FoundationResponse::HTTP_BAD_REQUEST, $status = 'error') {
        return $this->setStatusCode($code)->message($message, $status);
    }

    /**
     * @param $message
     * @param string $status
     * @return mixed
     */
    public function message($message, $status = "success") {
        // if(!is_array($message)) {
        //     $message = [$message];
        // }
        return $this->status($status, [
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!") {
        return $this->error($message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created") {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);
    }

    /**
     * @param $data
     * @param string $status
     * @param array $header
     * @return mixed
     */
    public function success($data = [], $status = "success") {
        return $this->status($status, compact('data'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = 'Not Fond!') {
        return $this->error($message, Foundationresponse::HTTP_NOT_FOUND);
    }

}

