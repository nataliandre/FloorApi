<?php
			/*
			* @author Andrii Moroz
			* @return is ajax request {boolean}
			*/
		 	$ajax = false;
		 	if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
		 				&& !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
		 				&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		 	{
		 				$ajax = true;
			}
