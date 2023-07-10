<?php
session_start();
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
ini_set("display_errors", 0); //enabled errors, even in production also


$tag_id = isset($_GET['tag_id'])?$_GET['tag_id']:'start';
//$_GET['flow_id'] = 2;
date_default_timezone_set("Asia/Calcutta");
require_once( str_replace('//', '/', dirname(__FILE__) . '/') . 'klogger.php');
require_once( str_replace('//', '/', dirname(__FILE__) . '/') . 'WAresponseChat.php');

$logger = new klogger();
$trackId = time();
$logger->setTrackId($trackId);

$WAResponse = new ResponseChat();

$json1 = file_get_contents('php://input');
$data_arr = json_decode($json1,1);
$logger->info("GET parameters :  ".json_encode($_GET));
$logger->info("post json  ".$json1);
$_GET['called_number'] = $data_arr['caller']['phone_number'];

$bot_state = isset($data_arr['bot_state'])?$data_arr['bot_state']:"None";

$response = $data_arr["data"];
$body = $response["body"];
$type = $response["type"];

$logger->info(" type $type  body: ".json_encode($body));
//$WAResponse->sendText($json1);

$resp_id = "__NONE__";

if($type == "text"){
        //      $WAResponse->sendText($body["data"]);
        $resp_id = $body["data"];
}else if($type == "image"){
        //              $WAResponse->sendImage($body["link"], " "," echo : ". $body["caption"]);
}else if($type == "video"){
        //              $WAResponse->sendVideo($body["link"], " "," echo : ". $body["caption"]);
}else if($type == "document"){
        //              $WAResponse->sendDocument($body["link"], " "," echo : ". $body["caption"],$body["filaname"]);
}else if($type == "audio"){
        //              $WAResponse->sendAudio($body["link"], " "," audio echo : ". $body["caption"]);
}else if($type == "location"){
        //              $WAResponse->sendLocation($body["lotitude"],$body["logitude"]," address ");
}else if($type == "reply"){
        //      $WAResponse->sendText("received reply obect : ".json_encode($body));
        $resp_id = $body["id"];
}
else{
        $WAResponse->sendText("unknow request");
        $WAResponse->send();
        exit;
}
$WAResponse->sendText(" *BOT* received as below");
$WAResponse->sendText("```".$json1."```");


$op = new ResponseChat();


$jpeg = "jpeg";
$stateMainMenu = "MainMenu";
$stateMainMenuChoiceJPEG = "jpeg";


$menuChoices = array( );
$menuChoices["text"] = array("id"=>"text","title"=>"sending Text","description"=>"it shares sample json object and sample image ","data"=>"Welecome to the *Ozonetel* _Cloud_ _COMMUNICATION API_","caption"=>"Sample Image");

$menuChoices["image"] = array("id"=>"image","title"=>"sending Image","description"=>"it shares sample json object and sample image ","link"=>"https://kookoo.in/customers/whatsapp/WAmedia.php?id=631668545593398","caption"=>"Sample Image");

$menuChoices["video"] = array("id"=>"video","title"=>"sending Video","description"=>"it shares sample json object and sample video ","link"=>"https://kookoo.in/customers/whatsapp/WAmedia.php?id=653789536630710");

$menuChoices["document"] = array("id"=>"document","title"=>"sending document","description"=>"it shares sample json doc Object","link"=>"https://kookoo.in/customers/whatsapp/WAmedia.php?id=993094048778175","filename"=>"Ozonetel Cloud communication document");
$menuChoices["location"] = array("id"=>"location","title"=>"sending location","description"=>"it shares sample json location Object","link"=>"https://kookoo.in/customers/whatsapp/WAmedia.php?id=993094048778175","filename"=>"Ozonetel Cloud communication document");
$menuChoices["buttons"] = array("id"=>"buttons","title"=>"Interactive buttons","description"=>"get json Interactive buttons Object","link"=>"https://kookoo.in/customers/whatsapp/WAmedia.php?id=993094048778175","filename"=>"Ozonetel Cloud communication document");
$menuChoices["list"] = array("id"=>"list","title"=>"list example","description"=>"Interactive List","link"=>"https://kookoo.in/customers/whatsapp/WAmedia.php?id=993094048778175","filename"=>"Ozonetel Cloud communication document");


if($bot_state == $stateMainMenu ){
        $welcome= "yes";
        if(isset($menuChoices[$resp_id])){
                if($resp_id == "text"){
                        $WAResponse->sendText($menuChoices[$resp_id]["data"]);
                        $op->sendText($menuChoices[$resp_id]["data"]);
                }else if($resp_id == "image"){
                        $WAResponse->sendImage($menuChoices[$resp_id]["link"],"",$menuChoices[$resp_id]["caption"]);
                        $op->sendImage($menuChoices[$resp_id]["link"],"",$menuChoices[$resp_id]["caption"]);
                }elseif($resp_id == "video"){
                        $WAResponse->sendVideo($menuChoices[$resp_id]["link"],$menuChoices[$resp_id]["caption"]);
                        $op->sendVideo($menuChoices[$resp_id]["link"],$menuChoices[$resp_id]["caption"]);
                }elseif($resp_id == "document"){
                        $WAResponse->sendDocument($menuChoices[$resp_id]["link"],$menuChoices[$resp_id]["filename"]);
                        $op->sendDocument($menuChoices[$resp_id]["link"],$menuChoices[$resp_id]["filename"]);
                }elseif($resp_id == "location"){
                        $WAResponse->sendLocation("17.4333111","78.3872307","Ozonetel Communication Pvt. Ltd","Sanali Spazio, 3rd Floor, Adjacent:, Inorbit Mall Rd, Software Units Layout, Madhapur, Telangana 500081");
                        $op->sendLocation("17.4333111","78.3872307","Ozonetel Communication Pvt. Ltd","Sanali Spazio, 3rd Floor, Adjacent:, Inorbit Mall Rd, Software Units Layout, Madhapur, Telangana 500081");
                }elseif($resp_id == "buttons"){
                        $interactive = $WAResponse->initInteractive(" ");
                        $buttons = $interactive->Button("Please share your feed back on this document..*Note:* Buttons supports _max_ *3* options");
                        $buttons->addButton("3","Excellent");
                        $buttons->addButton("2","Good");
                        $buttons->addButton("1","Poor");
                        $WAResponse->addInteractiveObject($buttons->buildObject());
                        $WAResponse->setBotState("Buttons_Test");

                        $op1 = $op->initInteractive(" ");
                        $opb = $op1->Button("Please share your feed back on this document..*Note:* Buttons supports _max_ *3* options");
                        $opb->addButton("3","Excellent");
                        $opb->addButton("2","Good");
                        $opb->addButton("1","Poor");
                        $op->addInteractiveObject($opb->buildObject());
                        $op->setBotState("Buttons_Test");
                        $welcome = "no";
                }elseif ($resp_id == "list"){
                        $list =  new listObject("Welcome to the *Ozonetel Cloud Communication API*  application, Here you can get exmaple of json response required for the kookoo action, To Know each action type example json, _choose the options to know_");
                        $section =  $list->Section("Sending Examples");
                        foreach($menuChoices as $key =>$choice){
                                $section->addChoice($choice["id"],$choice["title"],$choice['description']);
                        }
                        $list->addSection($section->buildObject());
                        $op->addInteractiveObject($list->buildObject());
                        $op->setBotState($stateMainMenu);
                }

                $WAResponse->sendText("json object of _*$resp_id*_");
                $WAResponse->sendText("```".str_replace('\\', '',json_encode($op->getResponse()))."```");
        }else{
                $WAResponse->sendText(" not received as expected ");
        }

        if ($welcome == "yes") Welcome();
}else if($bot_state == "Buttons_Test" ){
        if($resp_id == "3" || $resp_id == "2" || $resp_id == "1" ){
                $WAResponse->sendText("Thank you for feedback, Rated as $resp_id  ");
        }
        Welcome();

}else{
        Welcome();
}

function Welcome(){
        global $WAResponse;
        global $menuChoices;
        global $stateMainMenu;
        $list =  new listObject("Welcome to the *Ozonetel Cloud Communication API*  application, Here you can get exmaple of json response required for the kookoo action, To Know each action type example json, _choose the options to know_");
        $section =  $list->Section("Sending Image");
        foreach($menuChoices as $key =>$choice){
                $section->addChoice($choice["id"],$choice["title"],$choice['description']);
        }
        $list->addSection($section->buildObject());
        $WAResponse->addInteractiveObject($list->buildObject());
        $WAResponse->setBotState($stateMainMenu);
}
$WAResponse->send();
exit;