<?php
/**
 * Created by PhpStorm.
 * User: nataliandre
 * Date: 01.05.16
 * Time: 18:52
 */

    // Kramar Libs , only 2X2 version
    /**
     * @param $L
     * @return mixed
     */
    function setLineK($L){
        $result['A'] = $L['end']['y'] - $L['start']['y'];
        $result['B'] = $L['start']['x'] - $L['end']['x'];
        $result['C'] = -($L['start']['y']*$L['end']['x'] - $L['start']['x']*$L['end']['y']);
        return $result;
    }

    /**
     * @param $A11
     * @param $A12
     * @param $A21
     * @param $A22
     * @param $B1
     * @param $B2
     * @return mixed
     */
    function setKramarMatrix2X2($A11,$A12,$A21,$A22,$B1,$B2){
        $result[1][1] = $A11;
        $result[1][2] = $A12;
        $result[2][1] = $A21;
        $result[2][2] = $A22;
        $result[1]['B'] = $B1;
        $result[2]['B'] = $B2;
        return $result;
    }

    /**
     * @param $matrix
     * @return bool
     */
    function getKramarRoot2X2($matrix){
        $d = $matrix[1][1]*$matrix[2][2] - $matrix[2][1]*$matrix[1][2];
        $d1 = $matrix[1]['B']*$matrix[2][2] - $matrix[2]['B']*$matrix[1][2];
        $d2 = $matrix[2]['B']*$matrix[1][1] - $matrix[1]['B']*$matrix[2][1];
        if(abs($d)<0.1){return false;}
        $result['x'] = $d1/$d;
        $result['y'] = $d2/$d;
        return $result;
    }

    //other controll functions
    /**
     * @param $coord
     * @return int
     */
    function getSignOfCoordiats($coord){
        return ($coord>=0) ? 1 : 0;
    }

    /**
     * @param $vector
     * @return bool
     */
    function isNullVector($vector){
        if($vector['x'] == 0 && $vector['y']==0){
            return true;
        }
    }

    /**
     * @param $p
     * @param $w
     * @return bool
     */
    function  isOnLineVectorMethod($p,$w){
        $vectorA = array(
            'x' => $w['start']['x'] - $p['x'],
            'y' => $w['start']['y'] - $p['y']
        );
        $vectorB = array(
            'x' => $w['end']['x'] - $p['x'],
            'y' => $w['end']['y'] - $p['y']
        );
        if(isNullVector($vectorA) || isNullVector($vectorB)){ return true;}
        if(
        !(
            getSignOfCoordiats($vectorA['x']) == getSignOfCoordiats($vectorB['x'])
            &&
            getSignOfCoordiats($vectorA['y']) == getSignOfCoordiats($vectorB['y'])
        )
        ){return true;}
        return false;
    }

    /**
     * @param $p
     * @param $w
     * @return bool
     */
    function isOnSlantLineVectorMethod($p,$w){
            $vector['x'] = $w['start']['x'] - $w['end']['x'];
            $vector['y'] = $w['start']['y'] - $w['end']['y'];
            $left = abs(($p['x'] - $w['start']['x'])*$vector['y']);
            $right = abs(($p['y'] - $w['start']['y'])*$vector['x']);
            $vectorA = array(
                'x' => $w['start']['x'] - $p['x'],
                'y' => $w['start']['y'] - $p['y']
            );
            $vectorB = array(
                'x' => $w['end']['x'] - $p['x'],
                'y' => $w['end']['y'] - $p['y']
            );
            $epsilon = 0.001;
            $condition  = abs($left-$right) < $epsilon;

            if($condition && (isNullVector($vectorA) || isNullVector($vectorB))){
                return true;
            }
            if( $condition
                &&
                !(
                    getSignOfCoordiats($vectorA['x']) == getSignOfCoordiats($vectorB['x'])
                    &&
                    getSignOfCoordiats($vectorA['y']) == getSignOfCoordiats($vectorB['y'])
                )
            ){
                return true;
            }
            return false;
    }

/**
 * @param $array
 * @param $point
 * @return bool
 */
function checkPointInArray($array,$point){
    foreach ($array as $c){
        if($c['x'] == $point['x'] && $c['y'] == $point['y']){
            return true;
        }
    }
    return false;
}
