<?php
/**
 * Maze Class - Accumulated solutions from current and previous Grid and Maze related puzzles.
 * Each month we will add more to this class.
 * Version 1.8 - Dec 2020
 * Provides grid-based maze generation and traversal functions. For command-line use.
 * Author: Sherri Wheeler (Twitter @SyntaxSeed) for PHP Architect Magazine.
 */

class Maze{
    public int $width;
    public int $height;
    public array $grid;

    const LIGHT = '░';
    const DARK = '▓';

    /**
     * Initialize the grid with a given width and height and an intial fill value.
     * Pass zero for height and width to get a random size.
     */
    public function __construct(int $width=0, int $height=0, string $initialFill=self::DARK){
        $this->width = ($width == 0) ? random_int(10, 50) : $width;
        $this->height = ($height == 0) ? random_int(10, 50) : $height;
        $this->fillUniform($initialFill);
    }

    /**
     * Fill the grid with a single initial value.
     */
    public function fillUniform(string $fill=self::DARK) : void {
        $this->grid = array_fill(0, $this->height, array_fill(0, $this->width, $fill));
    }

    /**
     * Fill the grid with a supplied 2-dimensional array.
     */
    public function fillFromArray(array $gridContents) : void {
        $this->width = count($gridContents[0]);
        $this->height = count($gridContents);
        $this->grid = $gridContents;
    }

    /**
     * Fill the grid with random dark or light shade.
     */
    public function fillRandom() : void {
        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                $this->grid[$i][$j] = random_int(0, 1);
            }
        }
    }

    /**
     * PHP Puzzles 8 - Dec 2020.
     * Return the contents of the grid for display on the command line.
     */
    public function displayGrid() : string {
        $joined = implode("\n", array_map('implode', $this->grid));
        $joined = str_replace(0, self::LIGHT, $joined);
        $joined = str_replace(1, self::DARK, $joined);
        return $joined;
    }
}

/*
$maze = new Maze();
$maze->fillRandom();
echo($maze->displayGrid());
echo("\n\n");


$maze->fillFromArray([
    [0,1,0,0,0],
    [0,1,0,1,0],
    [0,1,0,1,0],
    [0,0,0,1,0]
    ]);
echo($maze->displayGrid());
*/
