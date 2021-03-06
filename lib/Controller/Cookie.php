<?php
namespace xepan\base;
/** Enhances authentication procedure by adding ability to set cookie on login. Manually logging out will clear cookie. */
class Controller_Cookie extends \AbstractController{
    public $show_checkbox=true;
    function init(){
        parent::init();
        //var_dump($_COOKIE);
        $this->api->requires('atk','4.2');

        if(!$this->owner instanceof \Auth_Basic){
            throw $this->exception('Must be added into $api->auth');
        }

        $this->owner->addHook(array('tryLogin','loggedIn','logout','updateForm'),$this);
    }
    function tryLogin($auth){

        // Don't check the cookie if already logged in
        if($this->owner->isLoggedIn()){
            return;
        }

        if(isset($_COOKIE[$auth->name."_username"]) && isset($_COOKIE[$auth->name."_password"])){
            // TODO -- MUST BE SOME ENCRYPTED HASH ONLY 
            $id=$auth->verifyCredentials( $_COOKIE[$auth->name."_username"], $_COOKIE[$auth->name."_password"]);
            if($id){
                // Successfully validated user
                $this->app->memorize('user_loggedin', $auth->model);
                $this->breakHook($id);
            }
        }
    }
    function loggedIn($auth,$user=null,$pass=null){
        if(!$pass)return;
        if($this->show_checkbox && !$auth->form->get('memorize'))return;
        setcookie($auth->name."_username",$user,time()+60*60*24*30*6);
        setcookie($auth->name."_password",$pass,time()+60*60*24*30*6);
    }
    function logout($auth){
		setcookie($auth->name."_username",null);
		setcookie($auth->name."_password",null);
    }
    function updateForm($auth){
        if ($this->show_checkbox) {
            $auth->form->addField('Checkbox','memorize','Remember me');
        }
    }
}
