<?php
/*
 * Preferred solution for Grid Mapping, PHP Puzzles # 8 - December 2020.
 * Author: Sherri Wheeler (Twitter @SyntaxSeed) for PHP Architect Magazine.
 */

function displayGrid(array $grid) : string {
    $char[0] = '░'; // Light
    $char[1] = '▓'; // Dark

    $joined = implode("\n", array_map('implode', $grid));
    $joined = str_replace(0, $char[0], $joined);
    $joined = str_replace(1, $char[1], $joined);
    return $joined;
}


echo displayGrid([
    [0,1,0,0,0],
    [0,1,0,1,0],
    [0,1,0,1,0],
    [0,0,0,1,0]
    ]);
