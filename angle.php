<?php
/*
  For vectors p1 and p2
  Dot product
  (p1^p2)/(|p1|*|p2|) = cos(theta)，0<=theta<=180
  
  Cross product
  p1 * p2 = x1y2  - x2 y1 = -p2 * p1
  if p1 * p2 is positive, then p1 is clockwise from p2 with respect to the origin (0, 0);
  if this cross product is negative, then p1 is counterclockwise from p2.

  Use (lng lat) as poit (x y)
*/


function getRotateAngle($lat1, $lng1, $lat2, $lng2)
{
   $epsilon = 1.0e-6;
    $nyPI = acos(-1.0);

    //vector p1 ($lat2, $lng2)--->($lat1, $lng1) 
    $x1 = $lng1-$lng2;
    $y1 = $lat1-$lat2;

    //vector p2 (0.0,0.0)---->(0.0,1.0) stands North
    $x2 =0.0;
    $y2 =1.0;
 
    // normalize
    $dist1 = sqrt( $x1 * $x1 + $y1 * $y1 );
    $x1 /= $dist1;
    $y1 /= $dist1;
    $dist2 = sqrt( $x2 * $x2 + $y2 * $y2 );
    $x2 /= $dist2;
    $y2 /= $dist2;
    // dot product
    $dot = $x1 * $x2 + $y1 * $y2;
    if (abs($dot-1.0) <= $epsilon )
        $angle = 0.0;
    else if ( abs($dot+1.0) <= $epsilon )
        $angle = $nyPI;
    else {
        
        $angle = acos($dot);
        //cross product
        $cross = $x1 * $y2 - $x2 * $y1;
        // vector p2 is clockwise from vector p1
        // with respect to the origin (0.0)
        if ($cross < 0 ) {
            $angle = 2 * $nyPI - $angle;
        }    
    }
    $degree = $angle *  180.0 / $nyPI;
    return $degree;
}


var_dump(getRotateAngle(43.8006949,-79.4455814 ,43.800668646930951, -79.445804597379308));
?>
