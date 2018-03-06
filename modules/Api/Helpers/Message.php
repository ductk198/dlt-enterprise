<?php

namespace Modules\Api\Helpers;

class Message{

    /**
     * Tra ve thong bao
     */
    public function returnMessage($status, $code, $message = '', $data = array()){
        $message = [
            'message' => $message
        ];
        if($data) {
            $message['data'] = $data;
        }
        $response = response()->json($message, $code, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $response;
    }
}
?>