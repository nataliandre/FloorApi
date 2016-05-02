<?php
/**
* Class Function With Static methods
*/
class Functions{


	/*
	*@author AndriiMoroz
	*@param $source путь к json {string}
	*@return $walls {array} array of rooms coordinats
	*/
	public static function getRooms($source,$data){
				$entity = file_get_contents($source);
				$entity = json_decode($entity);
				if($data){
					$entity = json_decode($data);
				}
				foreach($entity->{'floors'} as $room){
					foreach($room->{'rooms'} as $wall){
						$walls[] = 	$wall->{'walls'};
					}
				}
				return $walls;
	}

	/*
	 *@author AndriiMoroz
	 *@param $array {array} array of wall coordinats; $num {int} num of the room
	 *@returns $walls {array} coordinats of room walls
	 */
	public static function getWalls($array,$num = 0){
		    $i=0;
		    foreach($array[$num] as $room){
			  $room->{'inner'} = self::checkTheSlantOfRoomWalls($room->{'inner'});

		      $walls[$i]['start'] = $room->{'inner'}->{'start'};
		      $walls[$i]['end'] = $room->{'inner'}->{'end'};
		      $i++;
		    }
			return $walls;
	}

	/**
	 * @param $w
	 * @return mixed
	 */
	public static function checkTheSlantOfRoomWalls($w){
		$start = $w->{'start'};
		$end = $w->{'end'};
		if($start->{'x'} != $end->{'x'} && $start->{'y'} != $end->{'y'}){
			$dx = abs($start->{'x'} - $end->{'x'});
			$dy = abs($start->{'y'} - $end->{'y'});
			if($dx > $dy){
				$w->{'end'}->{'y'} = $w->{'start'}->{'y'};
			}else{
				$w->{'end'}->{'x'} = $w->{'start'}->{'x'};
			}
		}
		return $w;
	}

	/*
	 *@author AndriiMoroz
	 *@params $walls {array of Objects} walls coordinates
	 *@returns $angle{array} - coordinats of wall angles
	*/
	public static function getWallsVector($walls){
			$horizontal = false;
			$vertical = false;
			$iterator = 0;
			foreach($walls as $wall){
							$horizontal = ($wall['end']->{'y'} == $wall['start']->{'y'});
							$vertical   = ($wall['end']->{'x'} == $wall['start']->{'x'});
							$walls[$iterator]['horizontal'] = $horizontal;
							$walls[$iterator]['vertical'] = $vertical;
							$horizontal = false;
							$vertical = false;
							$iterator ++;
			 }
			 return $walls;
	}

	  /*
	   *@author AndriiMoroz
	   *@params $wall {array of Objects} walls coordinates // prepeared in getRooms walls with room number
	   *@returns $num{int} - number of with door wall
	  */
	  public static function getNumWallWithDoor($wall){
				$iter = 0;
				foreach ($wall as $val) {
					if(!empty($val->{'openings'})){
						foreach ($val->{'openings'} as $openings) {
							if($openings->{'type'} == "floorDoor"){
								return $iter;
							}
						}
					}
					$iter++;
				}
				return false;
	  }

  /*
   *@author AndriiMoroz
   *@params $wall {array of Objects} walls coordinates  // prepeared in getRooms walls with room number
   *@returns $walls{array} - number of with door wall
  */
  public static function getNumWall($wall){
	  		$iter = 0;
            foreach ($wall as $val) {
                $walls[$iter]['num'] = $iter;
                $iter++;
            }
            return $walls;
  }

	/*
	 *@author AndriiMoroz
	 *@params $walls {array of Objects} walls coordinates
	 *@returns $angle{array} - coordinats of wall angles
	*/
	public static function getUsedArea($walls){
		$angle['left'] = 10000;
 		$angle['bottom'] = 0;
 		$angle['top'] = 10000;
		$angle['right'] = 0;
 		foreach($walls as $wall){
        	foreach($wall as $c){
						if($c->{'x'} < $angle['left']){
							$angle['left'] = $c->{'x'};
						}


						if($c->{'y'} < $angle['top']){
							$angle['top'] = $c->{'y'};
						}


						if($c->{'y'} > $angle['bottom']){
							$angle['bottom'] = $c->{'y'};
						}

						if($c->{'x'} > $angle['right']){
							$angle['right'] = $c->{'x'};
						}
        	}
		}
		return $angle;
	}









}
