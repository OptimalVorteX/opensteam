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

function OPENBANS_INIT() { 
  execute_action("OPENBANS_INIT");
}

function OPENBANS_START() {
  execute_action("OPENBANS_START");
}

function OPENBANS_HEAD() {
  execute_action("OPENBANS_HEAD");
}

function OPENBANS_MENU() {
  execute_action("OPENBANS_MENU");
}

function OPENBANS_BEFORE_CONTENT() {
  execute_action("OPENBANS_BEFORE_CONTENT");
}

function OPENBANS_CONTENT() {
  execute_action("OPENBANS_CONTENT");
}

function OPENBANS_AFTER_CONTENT() {
  execute_action("OPENBANS_AFTER_CONTENT");
}

function OPENBANS_POST() {
  execute_action("OPENBANS_POST");
}

function OPENBANS_FOOTER() {
  execute_action("OPENBANS_FOOTER");
}

//more hooks
function OPENBANS_CUSTOM() {
  execute_action("OPENBANS_CUSTOM");
}

function OPENBANS_SUBSCRIBE() {
  execute_action("OPENBANS_SUBSCRIBE");
}

function OPENBANS_META() {
  execute_action("os_add_meta");
}
?>