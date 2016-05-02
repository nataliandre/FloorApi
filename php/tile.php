<?php



/**
* Class Tile  - model of tiled floor
*@author Andrii Moroz
*@
*/




class Tile{
	public $a; //tile length
	public $b; //tile width
	public $walls; // object of room walls
	public $walls_polarize;
	public $tile; // object of tile coordinats & sizes
	public $room;
	public $inWall;
    public $start;
	public $iteratorDelay = 4;

	/*
	*@author Andrii Moroz
	*@return
	*@params -none
	*@constructor
	* test passed---
	*/
	public function __construct($x,$y,$walls,$room,$start = false){
		$this->a = $x;
		$this->b = $y;
		$this->walls = $walls;
		$this->room = $room;
        $this->start = $start;
	}

	/*
	*@author Andrii Moroz
	*@return
	*@params -none
	*test passed ---
	*/
	public function layTile($param = 'StraightIndentTop'){
			if(empty($this->walls_polarize)){
				foreach($this->walls as $wall){
					$this->walls_polarize[] = $this->polirizeWall($wall);
				}
			}
			$this->walls = $this->walls_polarize;
			$this->$param();
			$this->validateTile();
	}

	/**
	 *
	 */
	public function validateTile(){
		$iterator = 0;
		foreach ($this->tile as $tile){
			if(count($tile) < 2){
				unset($this->tile[$iterator]);
				$iterator++;
				continue;
			}
		}
		sort($this->tile);
	}

	/**
	 * @param $tile
	 * @return mixed
	 */
	public function replaceInTurn($tile,$type=false){
		if(count($tile) == 4 && $type != 'Angel') {
				$p = $this->getExdtremePoints($tile);
				$tileModification[0]['x'] = $p['left'];
				$tileModification[0]['y'] = $p['top'];
				$tileModification[1]['x'] = $p['right'];
				$tileModification[1]['y'] = $p['top'];
				$tileModification[2]['x'] = $p['right'];
				$tileModification[2]['y'] = $p['bottom'];
				$tileModification[3]['x'] = $p['left'];
				$tileModification[3]['y'] = $p['bottom'];
				$tile = $tileModification;
		}
		return $tile;
	}

	/**
	 * @param $coords
	 * @return mixed
	 */
	public function getExdtremePoints($coords){
			$point['left'] = 10000;
			$point['right'] = 0;
			$point['top'] = 10000;
			$point['bottom'] = 0;
			foreach ($coords as $coord){
				if($coord['x']<$point['left']){$point['left'] = $coord['x'];}
				if($coord['x']>$point['right']){$point['right'] = $coord['x'];}
				if($coord['y']<$point['top']){$point['top'] = $coord['y'];}
				if($coord['y']>$point['bottom']){$point['bottom'] = $coord['y'];}
			}
			return $point;
	}

	/**
	 * @param $a
	 * @param $b
	 * @return mixed
	 * test passed ---
	 */
    public function checkRoomIndent($a,$b){
        if($this->start!='center'){
            $wall  = $this->walls[$this->start];
            if($wall['start']['x'] == $wall['end']['x']){
                $x = $wall['start']['x'];
                $full__width = abs($x - $this->room['left']);
                $indent['x'] = $full__width%$a;
                $indent['y'] = 0;
            }
            if($wall['start']['y'] == $wall['end']['y']){
                $y = $wall['start']['y'];
                $full__height = abs($y - $this->room['top']);
                $indent['y'] = $full__height%$b;
                $indent['x'] = 0;
            }
        }else{
			$x = abs($this->room['right'] - $this->room['left'])/2;
			$y = abs($this->room['top'] - $this->room['bottom'])/2;
			$full__height = abs($y - $this->room['top']);
			$indent['y'] = $full__height%$b;
			$full__width = abs($x - $this->room['right']);
			$indent['x'] = $full__width%$a;
        }
        if(!isset($indent)){
           $indent['x'] = 0;
           $indent['y'] = 0;
        }
        return $indent;
    }


	/**
	 *
	 */
	public function StraightWall(){

		$full__width = abs($this->room['right'] - $this->room['left']);
		$full__height = abs($this->room['top'] - $this->room['bottom']);
		$top = $this->room['top'];
		$left = $this->room['left'];

		$ci = ceil($full__width/$this->b);
		$cj = ceil($full__height/$this->a);
		//change to find tiles on border
        $ci+=$this->iteratorDelay;
        $cj+=$this->iteratorDelay;

		$b = $this->b;
		$a = $this->a;

        $indentRoom['x'] = 0;
        $indentRoom['y'] = 0;
        $indentRoom = $this->checkRoomIndent($a,$b);

		for($i=-$this->iteratorDelay;$i<$ci;$i++){

			for($j=-$this->iteratorDelay;$j<$cj;$j++){
				$c[0]['y'] = $top+$j*$a+$indentRoom['y'];
				$c[0]['x'] = $left+$i*$b+$indentRoom['x'];
				$c[1]['y'] = $top+$j*$a+$indentRoom['y'];
				$c[1]['x'] = $left+($i+1)*$b+$indentRoom['x'];
				$c[2]['y'] = $top+($j+1)*$a+$indentRoom['y'];
				$c[2]['x'] = $left+($i+1)*$b+$indentRoom['x'];
				$c[3]['y'] = $top+($j+1)*$a+$indentRoom['y'];
				$c[3]['x'] = $left+$i*$b+$indentRoom['x'];
				$this->setTile($c);
			}
		}
	}

	/**
	 *
	 */
	public function StraightIndentTop(){
				$indent = ceil($this->b/2);
				$full__width = abs($this->room['right'] - $this->room['left']);
				$full__height = abs($this->room['top'] - $this->room['bottom']);
				$top = $this->room['top'];
				$left = $this->room['left'];
				$ci = ceil($full__width/$this->b);
				$cj = ceil($full__height/$this->a);
				//change to find tiles on border
				$ci+=$this->iteratorDelay;
				$cj+=$this->iteratorDelay;
				$b = $this->b;
				$a = $this->a;
                $indentRoom['x'] = 0;
                $indentRoom['y'] = 0;
                $indentRoom = $this->checkRoomIndent($a,$b);
				$indentStep = false;
				for($i=-$this->iteratorDelay;$i<$ci;$i++){
						for($j=-$this->iteratorDelay;$j<$cj;$j++){
							$ind = ($indentStep) ? $indent : 0;
							$c[0]['y'] = $top+$j*$a - $ind+$indentRoom['y'];
							$c[0]['x'] = $left+$i*$b - $indentRoom['x'];
							$c[1]['y'] = $top+$j*$a - $ind +$indentRoom['y'];
							$c[1]['x'] = $left+($i+1)*$b +$indentRoom['x'];
							$c[2]['y'] = $top+($j+1)*$a-$ind +$indentRoom['y'];
							$c[2]['x'] = $left+($i+1)*$b+$indentRoom['x'];
							$c[3]['y'] = $top+($j+1)*$a - $ind +$indentRoom['y'];
							$c[3]['x'] = $left+$i*$b +$indentRoom['x'];
							$this->setTile($c);
						}
						$indentStep = !$indentStep;
				}
	}

	/**
	 *
	 */
	public function StraightIndentLeft(){
				$indent = ceil($this->a/2);
				$full__width = abs($this->room['right'] - $this->room['left']);
				$full__height = abs($this->room['top'] - $this->room['bottom']);
				$top = $this->room['top'];
				$left = $this->room['left'];
				$ci = ceil($full__width/$this->b);
				$cj = ceil($full__height/$this->a);
				$ci+=$this->iteratorDelay;
				$cj+=$this->iteratorDelay;
				$b = $this->b;
				$a = $this->a;
                $indentRoom['x'] = 0;
                $indentRoom['y'] = 0;
                $indentRoom = $this->checkRoomIndent($a,$b);
				$indentStep = false;
				for($i=-$this->iteratorDelay;$i<$cj;$i++){
						for($j=-$this->iteratorDelay;$j<$ci;$j++){
							$ind = ($indentStep) ? $indent : 0;
							$c[0]['y'] = $top+$i*$a +$indentRoom['x'];
							$c[0]['x'] = $left+$j*$b - $ind +$indentRoom['y'];;
							$c[1]['y'] = $top+$i*$a +$indentRoom['x'];
							$c[1]['x'] = $left+($j+1)*$b - $ind +$indentRoom['y'];
							$c[2]['y'] = $top+($i+1)*$a +$indentRoom['x'];
							$c[2]['x'] = $left+($j+1)*$b - $ind +$indentRoom['y'];
							$c[3]['y'] = $top+($i+1)*$a +$indentRoom['x'];
							$c[3]['x'] = $left+$j*$b - $ind +$indentRoom['y'];
							$this->setTile($c,'Angel');
						}
						$indentStep = !$indentStep;
				}
	}

	/**
	 *
	 */
	public function  AnglewiseCube(){
		$full__width = abs($this->room['right'] - $this->room['left']);
		$full__height = abs($this->room['top'] - $this->room['bottom']);
		$top = $this->room['top'];
		$left = $this->room['left'];
		$b = $this->b;
		$a = $this->a;
		$r = ceil(sqrt($a*$a + $b*$b)/2);
		$indentRoom['x'] = 0;
        $indentRoom['y'] = 0;
        $indentRoom = $this->checkRoomIndent($r,$r);
		$ci = ceil($full__width/$r);
		$cj = ceil($full__height/$r);
		$ci+=$this->iteratorDelay;
		$cj+=$this->iteratorDelay;
		for($i=-$this->iteratorDelay;$i<$ci;$i++){
				for($j=-$this->iteratorDelay;$j<$cj;$j++){
					 if(($i%2==0 && $j%2==0) || ($i%2!=0 && $j%2!=0)){
						 $c[0]['y'] = $top+($j-1)*$r + $indentRoom['y'];
						 $c[0]['x'] = $left+$i*$r + $indentRoom['x'];
						 $c[1]['y'] = $top+$j*$r + $indentRoom['y'];
						 $c[1]['x'] = $left+($i+1)*$r + $indentRoom['x'];
						 $c[2]['y'] = $top+($j+1)*$r + $indentRoom['y'];
						 $c[2]['x'] = $left+$i*$r + $indentRoom['x'];
						 $c[3]['y'] = $top+$j*$r + $indentRoom['y'];
						 $c[3]['x'] = $left+($i-1)*$r + $indentRoom['x'];
						 $this->setTile($c,'Angel');
					 }
				}
		}
	}

	/**
	 *
	 */
	public function AnglewiseRectangle(){
		$full__width = abs($this->room['right'] - $this->room['left']);
		$full__height = abs($this->room['top'] - $this->room['bottom']);
		$top = $this->room['top'];
		$left = $this->room['left'];
		$b = $this->b;
		$a = $this->a;
		$r = ceil(sqrt($a*$a + $b*$b)/2);
        $indentRoom['x'] = 0;
        $indentRoom['y'] = 0;
        $indentRoom = $this->checkRoomIndent($r,$r);
		$ci = ceil($full__width/$r);
		$cj = ceil($full__height/$r);
		$ci+=$this->iteratorDelay;
		$cj+=$this->iteratorDelay;
		for($i=-$this->iteratorDelay;$i<$ci;$i++){
			for($j=-$this->iteratorDelay;$j<$cj;$j++){
				if(($i%2==0 && $j%2==0) || ($i%2!=0 && $j%2!=0)){
					$c[0]['y'] = $top+($j-1)*$r+ $indentRoom['y'] ;
					$c[0]['x'] = $left+$i*$r + $indentRoom['x'];
					$c[1]['y'] = $top+$j*$r  + $indentRoom['y'];
					$c[1]['x'] = $left+($i+1)*$r + $indentRoom['x'];
                    $c[2]['y'] = $top+($j+0.5)*$r + $indentRoom['y'];
					$c[2]['x'] = $left+($i+0.5)*$r + $indentRoom['x'];
                    $c[3]['y'] = $top+($j-0.5)*$r + $indentRoom['y'];
					$c[3]['x'] = $left+($i-0.5)*$r + $indentRoom['x'];
                    $d[0]['y'] = $top+($j-0.5)*$r + $indentRoom['y'];
					$d[0]['x'] = $left+($i-0.5)*$r + $indentRoom['x'];
                    $d[1]['y'] = $top+($j+0.5)*$r + $indentRoom['y'];
					$d[1]['x'] = $left+($i+0.5)*$r + $indentRoom['x'];
					$d[2]['y'] = $top+($j+1)*$r + $indentRoom['y'];
					$d[2]['x'] = $left+$i*$r + $indentRoom['x'];
					$d[3]['y'] = $top+$j*$r + $indentRoom['y'];
					$d[3]['x'] = $left+($i-1)*$r + $indentRoom['x'];
					$this->setTile($c,'Angel');
					$this->setTile($d,'Angel');
				}
			}
		}
	}

	/**
	 * @param $coordinats
	 * @param null $type
	 */
	public function setTile($coordinats,$type = null){
		$tile = $this->cutTile($coordinats,$type);
		if($tile){
			$this->tile[] = $tile;
		}
	}

	/*
	*@author Andrii Moroz
	*@return tile acoss array
	*@params -none
	*/
	public function getTile(){
		return $this->tile;
	}
	/*
	*@author Andrii Moroz
	*@return $x {int} json format of room tile
	*@params -none
	*/
	public function getTileJSON(){
		return json_encode($this->tile);
	}

	/*
	*@ by Andrii Moroz moroz97andre@mail.ua
	*@params $coord {array of int} coordinats of one tile
	*
	*/
	public function cutTile($coord,$type = null){
		$borders = $this->getWallsFromCoords($coord,$type);
        $noPointInRoom = 0;
		for($i = 0; $i < count($coord);  $i++){
				if($this->pointFixedToRoomPlane($coord[$i])){
				   continue;
				}else{
                   $noPointInRoom++; 
                }
        }

        if($noPointInRoom > 0 && $noPointInRoom <= 3){
            $tile = $this->cutTileControll($coord,$borders,$noPointInRoom);
            if(empty($tile) || $tile == null){ return false;}
			$tile = $this->replaceInTurn($tile,$type);
            return $tile;
        }
        elseif($noPointInRoom == 0){
	       return $coord;
        }else{
            return false;
        }
        
    }


	/**
	 * @param $coords
	 * @param $borders
	 * @param $pointsInBorder
	 * @return array
	 */
    public function cutTileControll($coords,$borders,$pointsInBorder){
        $iterator = 0;
        $acrossed = false;
        $return  = array();
        foreach($borders as $border){
             $tmp = $this->getAcrossedWall($border,$iterator);
             if($tmp){
				 foreach($tmp as $t){
					 if(!$acrossed){ $acrossed[0]=$t; continue;}
					 $c = count($acrossed);
					 $acrossed[$c] = $t;
					 unset($c);
				 }
             }
            $iterator++;
        }
        
		$iterator = 0;
		for($i =0; $i<count($coords); $i++){
			if($this->pointFixedToRoomPlane($coords[$i])){
				$return[$iterator] =  $coords[$i];
				$return[$iterator]['iteration'] = $i;
				$iterator++;
			}
			if($acrossed) {
				for ($j = 0; $j < count($acrossed); $j++) {
					if ($acrossed[$j]['point']['iter'] == $i) {
						$mes = $this->pointInTile($acrossed[$j]['wall'],$return,$borders);
						if($mes){
							if($this->noPointsWithSuchIteration($return,$i)){
								$return[$iterator] = $mes;
								$return[$iterator]['iteration'] = $i;
								$iterator++;
								$mes = false;
							}
						}
						$return[$iterator] = $acrossed[$j]['point']['coords'];
						$return[$iterator]['iteration'] = $i;
						$iterator++;
						if($mes){
							$return[$iterator] = $mes;
							$return[$iterator]['iteration'] = $i;
							$iterator++;
							$mes = false;
						}
						unset($acrossed[$j]);
						sort($acrossed);
					}
				}
			}
		}
        return $return;
    }




	/**
	 * @param $wall
	 * @param $tile
	 * @param $iter
	 * @param $coords
	 * @return bool
	 */
    public function pointInTile($wall,$coord,$tile)
	{
		foreach ($wall as $key=>$value){
			if ($this->pointFixedToPlane($value, $tile)) {if(!checkPointInArray($coord,$value)){return $value;}}
		}
		return false;
	}

	public function noPointsWithSuchIteration($coords,$iteration){
		foreach ($coords as $coord){
			if($coord['iteration'] == $iteration){ return false; }
		}
		return true;

	}

	/**
	 * @param $wall_test
	 * @param $iterator
	 * @return bool
	 */
	public function getAcrossedWall($wall_test,$iterator){
			$across = false;
			$across_count = 0;
			foreach($this->walls as $wall){
				$acrossed = $this->isAcrossLines($wall_test,$wall);
				if($acrossed){
					$across[$across_count]['point']['coords'] = $acrossed;
					$across[$across_count]['point']['iter'] = $iterator;
					$across[$across_count]['wall'] = $wall;
					$across_count++;
				}
				unset($acrossed);
			}
			return $across;
	}




	/**
	 * @param $coords
	 * @param $type
	 * @return array
	 */
	public  function getWallsFromCoords($coords,$type){
		for($i=0;$i<count($coords);$i++) {
			    $step  = $i;
				$dstep = $step + 1;

				if($dstep == count($coords)){
					$dstep = 0;
				}
				$sx = $coords[$step]['x'];
				$sy = $coords[$step]['y'];
				$ex = $coords[$dstep]['x'];
				$ey = $coords[$dstep]['y'];


				$currentWall = [
					'start' => [
						'x' => $sx,
						'y' => $sy
					],
					'end' =>
					[
						'x' => $ex,
						'y' => $ey
					]
				];
				$currentWall = $this->polirizeWall($currentWall,$type);
				$walls[] = $currentWall;
		}

		return $walls;
	}

	/*
	*@author Andrii Moroz
	*@params $array{array $coords} array  of coordinats; $pos {int} start position of  change
	*@returns $w wall with the point
	*/
	public function findPointWall($x,$y){
		$p['x'] = $x;
		$p['y'] = $y;
		foreach($this->walls as $wall){
			$isOnLine = $this->isOnLine($p,$wall);
			if($isOnLine){return $isOnLine;}
		}
		return false;


	}




	/*
	*@author Andrii Moroz
	*@params $array{array $coords} array  of coordinats; $pos {int} start position of  change
	*@returns $w wall with the point
	*test passed ---
	*/
	public function pointFixedToRoomPlane($point)
	{
		return $this->pointFixedToPlane($point, $this->walls);
	}

	/*
	*@author Andrii Moroz
	*@params $array{array $coords} array  of coordinats; $pos {int} start position of  change
	*@returns $w wall with the point
	*/
	public function pointFixedToPlane($point,$plane){
		$borders = $plane;
	    if($this->isOnBorder($point, $plane)){return true;}
		$ray['start']['x'] = $point['x'];
		$ray['start']['y'] = $point['y'];
		$ray['end']['x'] = 10000;
		$ray['end']['y'] = 10000;
		$acrosses = 0;
		$acrossesArray = array();
		foreach($borders as $border){
			$isAcrossLines = $this->isAcrossLines($ray,$border);
			if($isAcrossLines){
				$acrosses++;
			}
		}
	    return ($acrosses%2!=0) ? true : false;
	}


	/*
	*@author Andrii Moroz
	*@params $inv {array $coords} the line wich asroced by other line ; $acros {array $c}
	*/
	public function isOnAcrossBorders($point,$plane){
		    $onAngleBorders = $this->isOnBorder($point,$plane);
		    if(count($onAngleBorders)==2){
				return true;
			}
			return false;
	}

	/*FINAL*/
	/*
	*@author Andrii Moroz
	*@params $inv {array $coords} the line wich asroced by other line ; $acros {array $c}
	*/
	public function isAcrossLines($inv,$acros){
		$iABC = setLineK($inv);
		$aABC = setLineK($acros);
		$matrix = setKramarMatrix2X2($iABC['A'],$iABC['B'],$aABC['A'],$aABC['B'],$iABC['C'],$aABC['C']);
		$result = getKramarRoot2X2($matrix);
		if($result){
			if(isOnLineVectorMethod($result,$inv) && isOnLineVectorMethod($result,$acros)){
				return $result;
			}else{return false;}
		}else{return false;}
	}

	/**
	 * @param $inv
	 * @param $acros
	 * @return bool
	 * test passed ---
	 */
	public function checkVerticalNHorizontal($v,$h){
		if(
			(
			   $v['start']['x'] <= $h['end']['x']
			   && $v['start']['x'] >= $h['start']['x']
			   && $v['start']['y'] <= $h['start']['y']
			   && $v['end']['y'] >= $h['start']['y']
			)
			||
			(
				$v['start']['x'] >= $h['end']['x']
				&& $v['start']['x'] <= $h['start']['x']
				&& $v['start']['y'] >= $h['start']['y']
				&& $v['end']['y'] <= $h['start']['y']
			)
			||
			(
				$v['start']['x'] >= $h['end']['x']
				&& $v['start']['x'] <= $h['start']['x']
				&& $v['start']['y'] <= $h['start']['y']
				&& $v['end']['y'] >= $h['start']['y']
			)
			||
			(
				$v['start']['x'] <= $h['end']['x']
				&& $v['start']['x'] >= $h['start']['x']
				&& $v['start']['y'] >= $h['start']['y']
				&& $v['end']['y'] <= $h['start']['y']
			)
		){

			$point['x'] = $v['start']['x'];
			$point['y'] = $h['start']['y'];
			return $point;

		}
		else{
			return false;
		}
	}


	/*
	*@author Andrii Moroz
	*@params $wall - {array of $coodrd} start - end
	*@return {boolean} if $wall is vertical
	* test passed ---
	*/
	public function isVertical($wall){
       if(gettype($wall['start']) == "object"){
			if($wall['start']->{'x'} == $wall['end']->{'x'}){return true;}
			else{return false;}
		}else{
			if($wall['start']['x'] == $wall['end']['x']){return true;}
			else{return false;}
		}
	}

	/*
	*@author Andrii Moroz
	*@params $wall - {array of $coodrd} start - end
	*@return {boolean} if $wall is horizontal
	*test passed ---
	*/
	public function isHorizontal($wall){
    	if(gettype($wall['start']) == "object"){
			if($wall['start']->{'y'} == $wall['end']->{'y'}){return true;}
			else{return false;}
		}else{
			if($wall['start']['y'] == $wall['end']['y']){return true;}
			else{return false;}
		}
	}
	
	/**
	 * @param $point
	 * @return array|bool
	 * test -- passed
	 */
	public function isOnRoomBorder($point){
		$walls = $this->walls;
		return $this->isOnBorder($point,$walls);
	}

	/**
	 * @param $point
	 * @param $walls
	 * tests -- passed
	 * @return array|bool
	 */
	public function isOnBorder($point,$walls){
			$return = false;
			foreach ($walls as $wall) {
            	$isOnLine = $this->isOnLine($point,$wall);
				if($isOnLine){ $return[]=$isOnLine;}
				unset($isOnLine);
			}
			return $return;
	}

	/**
	 * @param $p
	 * @param $wall
	 * @return bool
	 * tests -- passed
	 */
	public function isOnLine($p,$wall){
		if(isOnSlantLineVectorMethod($p,$wall)){return $wall;}
		return false;
	}

	
	/*
	*@author Andrii Moroz
	*@params $wall {array $coords} the wall wich will polirize
	*@returns $return{array $coords} polirized wall
	* test passed ---
	*/
	public function polirizeWall($wall,$angel = null){
		if(gettype($wall['start']) == "object"){
				if($wall['start']->{'x'} == $wall['end']->{'x'}){
					$ys = $this->min($wall['start']->{'y'},$wall['end']->{'y'});
					$ye = $this->max($wall['start']->{'y'},$wall['end']->{'y'});
					$xs = $wall['start']->{'x'};
					$xe = $xs;
				}else{
					$xs = $this->min($wall['start']->{'x'},$wall['end']->{'x'});
					$xe = $this->max($wall['start']->{'x'},$wall['end']->{'x'});
					$ys = $wall['start']->{'y'};
					$ye = $ys;
				}
				$return['start']['x'] = $xs;
				$return['start']['y'] = $ys;
				$return['end']['x'] = $xe;
				$return['end']['y'] = $ye;
				return $return;
		}else{
			if($wall['start']['x'] == $wall['end']['x']){
				$ys = $this->min($wall['start']['y'],$wall['end']['y']);
				$ye = $this->max($wall['start']['y'],$wall['end']['y']);
				$xs = $wall['start']['x'];
				$xe = $xs;
			}elseif($angel == null && $wall['start']['y'] == $wall['end']['y']){
				$xs = $this->min($wall['start']['x'],$wall['end']['x']);
				$xe = $this->max($wall['start']['x'],$wall['end']['x']);
				$ys = $wall['start']['y'];
				$ye = $ys;
			}else{
                $xs = $wall['start']['x'];
                $ys = $wall['start']['y'];
                $xe = $wall['end']['x'];
                $ye = $wall['end']['y'];
            }
			$return['start']['x'] = $xs;
			$return['start']['y'] = $ys;
			$return['end']['x'] = $xe;
			$return['end']['y'] = $ye;
			return $return;
		}
	}

	/**
	 * @param $a
	 * @param $b
	 * @return mixed
	 */
    public function min($a,$b){
        if($a > $b){return $b;}
		else{return $a;}
    }

	/**
	 * @param $a
	 * @param $b
	 * @return mixed
	 */
    public function max($a,$b){
        if($a < $b){return $b;}
		else{return $a;}
    }
}
?>
