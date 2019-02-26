<?php 

class Response {

    const ERROR_MISSING_FILE          = 'Error: No file uploaded';
    const ERROR_FILE_TOO_BIG          = 'Error: File too big';
    const ERROR_FILE_ALREADY_EXISTS   = 'Error: A file with this name already exists';
    const ERROR_FILE_DOES_NOT_EXISTS  = 'Error: File does not exist';
    const ERROR_EXTENSION_NOT_ALLOWED = 'Error: A file with this extension is not allowed';
    const ERROR_UPLOAD_FAIL           = 'Error: A problem occurred during file upload! Please try again';
    const ERROR_DELETE_FAIL           = 'Error: A problem occurred while deleteting the file. Please try again';
    const HTTP_RESPONSE_CODE          = 200;
    const SUCCESS_FILE_DELETED        = 'Sucess: File has been deleted';
    const SUCCESS_FILE_UPLOADED       = 'Sucess: File has been uploaded';
    

    public $responseCode;
    public $messages;
    public $data;

    public function __construct($responseCode, $data = [], $messages = []) {
        $this->responseCode = $responseCode;
        if(!empty($data)) {
            $this->data = $data;
        }
        if(!empty($messages)) {
            $this->messages = $messages;
        }
    }

    public function setErrorMessage($message) {
        $this->messages[] = $message;
        return $this;
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

}

?>