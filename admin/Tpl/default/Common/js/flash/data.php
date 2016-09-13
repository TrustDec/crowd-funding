<?php

include './php-ofc-library/open-flash-chart.php';

$year = array(); 
$price = array(); 
$year[] = '1983'; $price[] = 36.7; 
$year[] = '1984'; $price[] = 38.7; 
$year[] = '1985'; $price[] = 42.8; 
$year[] = '1986'; $price[] = 38.2; 
$year[] = '1987'; $price[] = 37.8; 
$year[] = '1988'; $price[] = 34.7; 
$year[] = '1989'; $price[] = 38.4; 
$year[] = '1990'; $price[] = 40.2; 
$year[] = '1991'; $price[] = 39.5; 
$year[] = '1992'; $price[] = 40.3; 
$year[] = '1993'; $price[] = 45.9; 
$year[] = '1994'; $price[] = 48.9; 
$year[] = '1995'; $price[] = 50.9; 
$year[] = '1996'; $price[] = 52.9; 
$year[] = '1997'; $price[] = 57.9; 
$year[] = '1998'; $price[] = 60.9; 
$year[] = '1999'; $price[] = 61.9; 
$year[] = '2000'; $price[] = 76.9; 
$year[] = '2001'; $price[] = 77.9; 
$year[] = '2002'; $price[] = 69.9; 
$year[] = '2003'; $price[] = 77.9; 
$year[] = '2004'; $price[] = 77.9; 
$year[] = '2005'; $price[] = 79.9; 
$year[] = '2006'; $price[] = 88.9; 
$year[] = '2007'; $price[] = 87.9; 
$year[] = '2008'; $price[] = 103.9; 
 
 
$chart = new open_flash_chart(); 
 
$title = new title( 'UK Petrol price (pence) per Litre' ); 
$title->set_style( "{font-size: 20px; color: #A2ACBA; text-align: center;}" ); 
$chart->set_title( $title ); 
 
$area = new area(); 
$area->set_colour( '#8f8fbd' ); 
$area->set_values( $price ); 
$area->set_key( 'Price', 12 ); 
$chart->add_element( $area ); 
 
$x_labels = new x_axis_labels(); 
$x_labels->set_steps( 2 ); 
$x_labels->set_vertical(); 
$x_labels->set_colour( '#A2ACBA' ); 
$x_labels->set_labels( $year ); 
 // ²åÈëÊý¾Ý
$x = new x_axis(); 
$x->set_colour( '#dadada' ); 
$x->set_grid_colour( '#D7E4A3' ); 
$x->set_offset( false ); 
$x->set_steps(4); 
// Add the X Axis Labels to the X Axis 
$x->set_labels( $x_labels ); 
 
$chart->set_x_axis( $x ); 
 
// 
// LOOK: 
// 
$x_legend = new x_legend( '1983 to 2008' ); 
$x_legend->set_style( '{font-size: 20px; color: #778877}' ); 
$chart->set_x_legend( $x_legend ); 
 
// 
// remove this when the Y Axis is smarter 
// 
$y = new y_axis(); 
$y->set_range( 0, 150, 30 ); 
$chart->add_y_axis( $y ); 
 
echo $chart->toPrettyString(); 