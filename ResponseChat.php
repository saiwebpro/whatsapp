<?php

class ResponseChat {

        protected $data = array();
        private $btns ;
        private $logger;

        public function __construct($loggerFileName = null)
        {
                global $logger; 
                $this->logger = $logger;//new Logger($loggerFileName);
                $this->data = array("bot_state"=> time() );
        }
        public function setBotState($state=""){
                $this->data['bot_state'] = $state;
        }

        public function sendText($msg=""){
                $this->logger->debug(print_r(get_defined_vars(), true));

                $this->data['data'][] = array("type" => "text", "body"=>array("data" => $msg) );

        }
        public function sendDocument($link="", $filename="",  $mimeType="", $caption="" ){
                $this->logger->debug(print_r(get_defined_vars(), true));

                $this->data['data'][] = array("type" => "document", "body" => array( "link"=>$link,  "filename" => $filename,"mime_type" => $mimeType,"caption" => $caption));
                //$this->send();
        }
        public function sendImage($link="",$mimeType="", $caption="" ){
                $this->data['data'][] = array("type" => "image", "body" => array( "link"=>$link, "mime_type" => $mimeType,"caption" => $caption ) );
        }
        public function sendAudio($link="",$mimeType="", $caption="" ){
                $this->data['data'][] = array("type" => "audio", "body" => array( "link"=>$link, "mime_type" => $mimeType,"caption" => $caption ) );
        }
        public function sendLocation($latitude="",$longitude="", $name="", $address= "" ){
                $this->data['data'][] = array("type" => "location", "body" => array( "latitude"=>$latitude, "longitude" => $longitude,"name" => $name, "address" => $address ) );
        }
        public function sendVideo($link="",$mimeType="", $caption="" ){
                $this->data['data'][] = array("type" => "video", "body" => array( "link"=>$link, "mime_type" => $mimeType,"caption" => $caption ) );
        }
        public function addContact($contatObject ){
                $this->data['data'][] = array("type" => "Contact", "Contact"=> $contatObject);
        }
		public function sendAgentTransfer($skillName="", $uui="" ){
			$this->logger->debug(print_r(get_defined_vars(), true));

			$this->data['data'][] = array("type" => "CCTransfer", "skillName"=>$skillName, "uui" => $uui);
	}

	public function initInteractive($name) {
			$list = new listObject($name);
			return $list;
	}

	public function addInteractiveObject( $listObject  ){
			$this->logger->debug(print_r(get_defined_vars(), true));
			$this->data['data'][] = array("type" => "interactive", "body" => $listObject );
	}

	public function addButtons( $listObject  ){
			$this->logger->debug(print_r(get_defined_vars(), true));
			$this->data['data'][] = array("type" => "interactive", "body" => $listObject );
	}

	public function getData(){
			return  json_encode($this->data);
	}


	public function getResponse(){
			return  $this->data;
	}
	public function putTestData($data){
			$this->data = $data;
	}
	// Parse the XML.and Deconstruct
	public function getXML() {
			return $this->doc->saveXML();
	}
	public function printData() {
			//          header("Content-Type: application/json");
			return json_encode($this->data);
	}
	public function send(){
			header("Content-Type: application/json");
			$this->logger->info("whatsapp post data:::".json_encode($this->data,JSON_PRETTY_PRINT));
			echo $this->getData();   //  json_encodei($this->data);
			//exit;

	}

}
class Contact {
	private $type; //interactive
	protected $contact;
	protected $text;


	public function __construct($link=''){
			$this->text = "Please share below datails";
			$this->link = $link;
			$this->contact = []; //default empty
	}

	public function addText($key){
			$this->text=$key;
	}

	public function addName($name){
			$Object = array("key"=>"name","value"=>$name);
			array_push($this->contact,$Object);
	}
	public function addPhone($phone){

			$Object = array("key"=>"phone","value"=>$phone);
			array_push($this->contact,$Object);


	}
	public function addEmail($email){
			$Object = array("key"=>"email","value"=>$email);
			array_push($this->contact,$Object);
	}


	public function addAddress($address){
			$Object = array("key"=>"address","value"=>$address);
			array_push($this->contact,$Object);
	}
	public function addCustomField($key,$value){
			$Object = array("key"=>$key,"value"=>$value);
			array_push($this->contact,$Object);
	}

	public function returnContact(){
			$payload = [
					"text" => $this->text,
					"fields"=>  $this->contact,
			];
			return $payload;
	}

}

class Menu {


	private $type; //interactive
	private $title;
	protected $buttons;

	public function __construct($type,$title,$link){
			$this->type = $type;
			$this->title = $title;
			$this->link = $link;
			$this->buttons = []; //default empty
	}

	public function addChoice($id,$title){
			$buttonObject = array("id"=>$id,"title"=>$title);
			array_push($this->buttons,$buttonObject);

	}
	public function returnMenuPayload(){

			$payload = [
					"type" => $this->type,
					"title"=>  $this->title,
					"link"=>$this->link,
					"choices"=> $this->buttons
			];
			return $payload;
	}

	public function returnButtonPayloadImage($image){

			$payload = [
					"type" => $this->type,
					$this->type => [
							"type"=> "button",
					"header"=>[
							"type"=>"image",
					"image"=>[
							"link"=>$image
					]
					],
					"body"=> [
							"text" => $this->title,
					],
					"action"=> [
							"buttons"=>$this->buttons
					]

					]
			];
			$this->buttons = []; //clear buttons
			return $payload;


	}

}
class buttonObject {
	private $header;
	private $body;
	private $footer;
	private $choices;

	public function __construct($title){
			$this->body = $title;
			$this->choices = array();
	}

	public function addButton($id, $title){
			$this->choices[] = array("id"=> $id, "title"=>$title);
	}

	public function buildObject(){
			$payload = [
					"type" => "button",
					"title"=>  $this->body,
					"choices"=> $this->choices
			];
			return $payload;
	}
}



#*************** List Object ***********************

class listObject {
	private $header;
	private $body;
	private $footer;
	private $sections;

	public function __construct($title){
			$this->body = $title;
			$this->sections = array();
	}

	public function Section($name) {
			$section = new listObjectSection($name);
			return $section;
	}

	public function Button($name) {
			$section = new buttonObject($name);
			return $section;
	}

	public function addSection($section){
			$this->sections[] = $section;
	}


	public function buildObject(){
			$payload = [
					"type" => "list",
					"title"=>  $this->body,
					"sections"=> $this->sections
			];
			return $payload;
	}
}
class listObjectSection {
	private $title;
	private $choices = [];

	public function __construct($title) {
			$this->title = $title;
	}

	public function addChoice($id, $title, $description="") {
			// $choice = new Choice($name, $value);
			$this->choices[] = array("id"=>$id,"title"=>$title,"description"=>$description);
			return $this;
	}

	public function buildObject() {
			return array("title" => $this->title, "choices" => $this->choices);
	}

}
