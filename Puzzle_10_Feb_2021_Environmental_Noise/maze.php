<?php

/**
 * Maze Class - Accumulated solutions from current and previous Grid and Maze related puzzles.
 * Each month we will add more to this class.
 * Version 1.10 - Feb 2021
 * Provides grid-based maze generation and traversal functions. For command-line use.
 * Author: Sherri Wheeler (Twitter @SyntaxSeed) for PHP Architect Magazine.
 */

class Maze
{
    public int $width;
    public int $height;
    public array $grid;

    const LIGHT = '░';
    const DARK = '▓';

    /**
     * Initialize the grid with a given width and height and an intial fill value.
     * Pass zero for height and width to get a random size.
     */
    public function __construct(int $width = 0, int $height = 0, int $initialFill = 1)
    {
        $this->width = ($width == 0) ? random_int(10, 50) : $width;
        $this->height = ($height == 0) ? random_int(10, 50) : $height;
        $this->fillUniform($initialFill);
    }

    /**
     * Fill the grid with a single initial integer value.
     */
    public function fillUniform(int $fill = 1): void
    {
        $this->grid = array_fill(0, $this->height, array_fill(0, $this->width, $fill));
    }

    /**
     * Fill the grid with a supplied 2-dimensional array.
     */
    public function fillFromArray(array $gridContents): void
    {
        $this->width = count($gridContents[0]);
        $this->height = count($gridContents);
        $this->grid = $gridContents;
    }

    /**
     * Fill the grid with random dark or light shade.
     */
    public function fillRandom(): void
    {
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
    public function displayGrid(): string
    {
        $joined = implode("\n", array_map('implode', $this->grid));
        $joined = str_replace(0, self::LIGHT, $joined);
        $joined = str_replace(1, self::DARK, $joined);
        return $joined;
    }

    /**
     * Add random rectangles to the grid with a given density (number of caverns per 100 squares of grid-area).
     * A density of 3.0 will draw 3 caverns in a 10 by 10 grid.
     * Higher density results in smaller caverns.
     */
    public function addNoise(float $density = 3.0): void
    {
        $gridArea = $this->width * $this->height;
        $numCaverns = round(($gridArea / 100) * $density);

        for ($i = 1; $i <= $numCaverns; $i++) {
            $start = [
                'x' => random_int(0, $this->width - 1),
                'y' => random_int(0, $this->height - 1)
            ];

            $maxWidth = (floor($this->width / $density) >= 2) ? floor($this->width / $density) : 2;
            $width = random_int(2, $maxWidth);

            $maxHeight = (floor($this->height / $density) >= 2) ? floor($this->height / $density) : 2;
            $height = random_int(2, $maxHeight);

            $this->drawCavern($start, $width, $height);
        }
    }


    /**
     * Draw a rectangle with a starting point in the upper-left corner and a given width and height.
     */
    public function drawCavern(array $start = ['x' => 0, 'y' => 0], int $width, int $height, int $fill = 0): void
    {
        // Draw one line for each unit of cavern height.
        for ($i = 1; $i <= $height; $i++) {
            if ($start['y'] > ($this->height - 1)) {
                // Out of bounds of the grid. Stop drawing lines.
                return;
            }
            $this->drawHorizontal($start, $width, $fill);
            $start['y']++; // Shift our line drawing down.
        }
    }

    /**
     * Fill a horizontal line to the right from the starting point, for the given length.
     * Return the new position.
     */
    public function drawHorizontal(array $start = ['x' => 0, 'y' => 0], int $length, int $fill = 0): array
    {
        $new = $start;
        $new['x'] = $start['x'] + $length;
        // Make sure we aren't moving beyond the grid size.
        if ($new['x'] >= $this->width) {
            $new['x'] = $this->width - 1;
        }
        for ($x = $start['x']; $x <= $new['x']; $x++) {
            $this->grid[$start['y']][$x] = $fill;
        }
        return $new;
    }

    /**
     * Fill a vertical line down from the starting point, for the given length.
     * Return the new position.
     */
    public function drawVertical(array $start = ['x' => 0, 'y' => 0], int $length, int $fill = 0): array
    {
        $new = $start;
        $new['y'] = $start['y'] + $length;
        // Make sure we aren't moving beyond the grid size.
        if ($new['y'] >= $this->height) {
            $new['y'] = $this->height - 1;
        }
        for ($y = $start['y']; $y <= $new['y']; $y++) {
            $this->grid[$y][$new['x']] = $fill;
        }
        return $new;
    }

    /**
     * PHP Puzzles 9 - Jan 2021.
     * Fill a path from the 'entrance' at top-left to the exit at bottom-right.
     */
    public function drawPath(array $position = ['x' => 0, 'y' => 0], int $fill = 0): void
    {
        $exit['x'] = $this->width - 1;   // Set the exit to the bottom-right corner.
        $exit['y'] = $this->height - 1;

        // Base case - we are at the exit.
        if ($position['x'] == $exit['x'] && $position['y'] == $exit['y']) {
            $this->grid[$position['y']][$position['x']] = $fill;
            return;
        }

        // Move right.
        $position = $this->drawHorizontal($position, random_int(0, round($this->width / 2)));

        // Move down.
        $position = $this->drawVertical($position, random_int(0, round($this->height / 2)));

        // Recursive call (next staircase step).
        $this->drawPath($position, $fill);
    }
}

/*
$maze = new Maze(30, 15);
$maze->drawPath();
$maze->addNoise();
echo($maze->displayGrid());
echo("\n\n");
*/
