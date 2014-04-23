<?php
class hooking{
	private $hooks;
	function __construct()
	{
		$this->hooks=array();
	}
	function AddEvent($where,$callback,$priority=50)
	{
		if(!isset($this->hooks[$where]))
		{
			$this->hooks[$where]=array();
		}
		$this->hooks[$where][$callback]=$priority;
	}
	function remove_action($where,$callback)
	{
		if(isset($this->hooks[$where][$callback]))
			unset($this->hooks[$where][$callback]);
	}
	function execute($where,$args=array())
	{
		if(isset($this->hooks[$where]) && is_array($this->hooks[$where]))
		{
			arsort($this->hooks[$where]);
			foreach($this->hooks[$where] as $callback=>$priority)
			{
				call_user_func_array($callback,$args);
			}
		}
	}
}

$hooking_daemon=new hooking;

function AddEvent($where,$callback,$priority=50)
{
	global $hooking_daemon;
	if(isset($hooking_daemon))
		$hooking_daemon->AddEvent($where,$callback,$priority = 1);
}
function remove_action($where,$callback)
{
	global $hooking_daemon;
	if(isset($hooking_daemon))
	$hooking_daemon->remove_action($where,$callback);
}
function execute_action($where,$args=array())
{
	global $hooking_daemon;
	if(isset($hooking_daemon))
	$hooking_daemon->execute($where,$args);
}

//_init    - before OpenStats basic function (can be used to handle POST or GET variables)
//_start   - before HTML
//_head    - before close head tag
//_content - content after main menu
//_footer  - footer
//_after_footer - content after OS footer

function OPENSTEAM_INIT() { 
  execute_action("OPENSTEAM_INIT");
}

function OPENSTEAM_START() {
  execute_action("OPENSTEAM_START");
}

function OPENSTEAM_HEAD() {
  execute_action("OPENSTEAM_HEAD");
}

function OPENSTEAM_MENU() {
  execute_action("OPENSTEAM_MENU");
}

function OPENSTEAM_BEFORE_CONTENT() {
  execute_action("OPENSTEAM_BEFORE_CONTENT");
}

function OPENSTEAM_CONTENT() {
  execute_action("OPENSTEAM_CONTENT");
}

function OPENSTEAM_AFTER_CONTENT() {
  execute_action("OPENSTEAM_AFTER_CONTENT");
}

function OPENSTEAM_POST() {
  execute_action("OPENSTEAM_POST");
}

function OPENSTEAM_FOOTER() {
  execute_action("OPENSTEAM_FOOTER");
}

//more hooks
function OPENSTEAM_CUSTOM() {
  execute_action("OPENSTEAM_CUSTOM");
}

function OPENSTEAM_SUBSCRIBE() {
  execute_action("OPENSTEAM_SUBSCRIBE");
}

function OPENSTEAM_META() {
  execute_action("OPENSTEAM_META");
}

function OPENSTEAM_AFTER_FOOTER() {
  execute_action("OPENSTEAM_AFTER_FOOTER");
}
?>