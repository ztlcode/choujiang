<?php
class utility_session
{
	static public function &get()
	{
		if(session_status()!=PHP_SESSION_ACTIVE)session_start();
		return $_SESSION;
	}
}

