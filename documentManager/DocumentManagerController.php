<?php 

Class DocumentManagerController {

   const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'txt', 'dat', 'mov', 'avi', 'flv', 'swf'];
   const MAX_FILE_SIZE = 5*MB;

   /* For the purpose of this exercise, the uploaded files are being saved on a directory within the same web server's document root.
    However, it's not ideal for real life scenarios. We can either save the files on a CDN or a static content server incapable of executing dynamic content
    This is because it's not 100% possible to validate the uploaded document. So a server serving only static content will prevent if any malicious code within the document
    I am however performing basic validation with whitelisted file extensions

    This function is used to upload a document to the server. 
    @return    $response JSON
    */
    public function upload() {
      
      // Let's valdate some things!
      // 1. Empty upload
      if(!$this->isFileUploaded()) {
         $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::ERROR_MISSING_FILE]);
         return json_encode($response);
      }
       
      // 2. file is within size limits 
      if(!$this->isFileSizeValid()) {
         $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::ERROR_FILE_TOO_BIG]);
         return json_encode($response);
      }

      // 3. file extension is valid
      if(!$this->isValidExtension()) {
         $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::ERROR_EXTENSION_NOT_ALLOWED]);
         return json_encode($response);
      } 

      // 4. File already exists 
      if($this->isFileExists()) {
         $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::ERROR_FILE_ALREADY_EXISTS]);
         return json_encode($response);  
      }
         
      // all's good, lets save the file
      if (move_uploaded_file($_FILES["inputFile"]["tmp_name"], $this->getFilePath() )) {
         $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::SUCCESS_FILE_UPLOADED]);
         return json_encode($response);     
      } 
      $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::ERROR_UPLOAD_FAIL]);
      return json_encode($response);  
    }

    /**
     * Delete a file by its name & return true if the file was successfully deleted
     * 
     * @return  $response JSON
     */
   public function delete($fileName) {
      // Check is file to delete exists
      if( empty($fileName) || !$this->isFileExists($fileName)) {
         $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::ERROR_FILE_DOES_NOT_EXISTS]);
         return json_encode($response);  
      }
      // delete the file
      $fileToDelete = $this->getFilePath($fileName);
      if(!unlink($fileToDelete)) {
         $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::ERROR_DELETE_FAIL]);
         return json_encode($response);  
      }
      $response = new Response(Response::HTTP_RESPONSE_CODE, [], [Response::SUCCESS_FILE_DELETED]);
      return json_encode($response);  
   }

   /**
    * Get list of all saved files
    * 
    * @return  []    List of file Urls
    */
   public function getAll() {
      $data = [];
      $allFiles = scandir($this->getUploadDirectory());
      $files = array_diff($allFiles, ['.', '..']);
      foreach($files as $index => $fileName) {
            $fileUrl = BASE_URL . UPLOAD_DIRECTORY . $fileName;
            $data[] = ["name"=>$fileName, "url"=>$fileUrl]; 
      }   
      $response = new Response(Response::HTTP_RESPONSE_CODE, $data, []);
      return json_encode($response);   
   }

   /**
     * Check to see that a file has actually been uploaded
     * 
     * @return    boolean  
     */
    private function isFileUploaded() {
      if(!empty($_FILES["inputFile"]) && ($_FILES["inputFile"]["error"] == 0) ) {
            return true;
      }
      return false;
   }

   /** 
    * Check the file size is within allowed limits
    *  
    * @return  boolean
    */
   private function isFileSizeValid() {
      if($_FILES["inputFile"]["size"] <= self::MAX_FILE_SIZE) {
         return true;
      } 
      return false;
   }

    /** 
    * @return    boolean
    */ 
    private function isValidExtension() {
      $extension = pathinfo($_FILES["inputFile"]["name"], PATHINFO_EXTENSION);
      if(in_array($extension, self::ALLOWED_EXTENSIONS)) {
         return true;
      }
      return false;
   }

   /**
    * Check if a file with the same name already exists
    *
    * @return  boolean
    */
   private function isFileExists($fileName='') {
      $filePath = $this->getFilePath($fileName);
      if (file_exists($filePath)) {
         return true;
      }
      return false;
   }

   /**
    * This function is used to get the full path to the file. If fileName is empty, it read the fileName from the $_FILES global
    * 
    * @param   string   $fileName  
    * @return  string   directory path to the file
    */
   private function getFilePath($fileName='') {
      if(empty($fileName) ) {
         $fileName = basename($_FILES["inputFile"]["name"]); 
      } 
      return $this->getUploadDirectory() . $fileName;
   }

   /**
    * @return  string   directory to upload the documents
    */
    private function getUploadDirectory() {
       return BASE_DIRECTORY . UPLOAD_DIRECTORY ;
    }
}
?>