<?php
/*
*
*  Tile Methods
*  1) Straight
*  2) StraightIndentTop
*  3) StraightIndentLeft;
*  4) AnglewiseCube
*  5) AnglewiseRectangle
*/


/**
* Class App
* Main class to contoll the system
*/
class App{
	    //$walls array of JSON cordinats
		private $walls;
  		// $params  {array}  request uri
		public function __construct($params){
		//@ see ajax.php class Ajax
		global $ajax;
		//@ get Rooms from file/POSTmessage in functions.php
		$data = false;
		if(isset($param['dataJSON'])){
			echo 'dataLoaded';
			$data = $param['dataJSON'];
		}
		$rooms = Functions::getRooms('../FloorPlan.json',$data);
    	//@ get Walls from $rooms in functions.php
		$walls = Functions::getWalls($rooms);
		//@ set param Walls to class App
		$this->walls = $walls;
		// $ajax see ajax.php
		if(!$ajax){
                $walls =  Functions::getWalls($rooms,4);
			    //@ get Area of room from functions.php
		        $params = Functions::getUsedArea($walls);
				//@ get WallsVector (horizontal/vertical) from functions.php
				$walls = Functions::getWallsVector($walls);
				//@ function that lay tile in {params} from function.php
				$tile = new Tile(30,30,$walls,$params,7);
				//@ function that lay Tile in defined params
				$tile->layTile('StraightWall');
        		//prod version
			 	//echo '<h2>Permission denied</h2>';
				//dev version uncoment
				print_r($tile->getTile());
		}else{
			if(isset($params['roomDraw'])){
					$count = count($rooms) - 1;
					$walls = array();
					for($num=1; $num<$count;$num++){
							array_push($walls,Functions::getWalls($rooms,$num));
					}
					$return = json_encode($walls);
				    echo $return;
			}
			if(isset($params['layType'])){
					$roNo = 0;
					if(isset($params['roomNum'])){
						    $roNo = $params['roomNum']+1;
					}
				    $walls =  Functions::getWalls($rooms,$roNo);
				    $type = $params['layType'];
					$w = 40;
					if(isset($params['tileW'])){
							$w = $params['tileW'];
					}
					$l = 40;
					if(isset($params['tileL'])){
							$l = $params['tileL'];
					}
					$ajax = false;
					
					$param = Functions::getUsedArea($walls);
					$walls = Functions::getWallsVector($walls);
                    $start = $params['startWall'];
                    $tile = new Tile($w,$l,$walls,$param,$start);
				    $tile->layTile($type);
					echo $tile->getTileJSON();
			}
			if(isset($params['saveJson'])){
				$data = $params['jsonData'];
				$time = time();
				file_put_contents('tileResult'.$time.'.json',$data);
				echo 'tileResult'.$time.'.json';
			}
			// if $_REQUEST['getRooms'] returns rooms count
			if(isset($params['getRooms'])){
				  $count = count($rooms) - 1;
				  echo $count;
			}
			//return the room walls
			if(isset($params['roomNum']) && isset($params['roomWalls'])){
						$openings = Functions::getNumWallWithDoor($rooms[$params['roomNum']]);
						$walls_numders = Functions::getNumWall($rooms[$params['roomNum']]);
            $walls_numders[$openings]['opening'] = true;
					  echo json_encode($walls_numders);
			}
		}
	}
}
