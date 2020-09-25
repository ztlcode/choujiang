<?php

class utility_net
{
	public static function getPostData()
	{
		return file_get_contents("php://input");
	}
}
