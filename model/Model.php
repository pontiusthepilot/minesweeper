<?php

declare(strict_types=1);

namespace model;

class Model
{
    public function __construct()
    {
        if (!is_dir('./state')) {
            mkdir('./state');
        }

        if (file_exists('./state/state.json')) {
            //retrieve the current game state
            $this->retrieveGame();
        }
    }

    public function createGame($rows = 10, $columns = 10, $mines = 10)
    {
        if (file_exists('./state/state.json')) {
            unlink('./state/state.json');
        }
        //Create a new game
        $this->maxRowIndex = $rows - 1;
        $this->maxColumnIndex = $columns - 1;
        $this->maxMines = $mines;

        $this->grid = array();

        $this->populateGrid();

        $state = array();

        $state['maxRowIndex'] = $this->maxRowIndex;
        $state['maxColumnIndex'] = $this->maxColumnIndex;
        $state['maxMines'] = $this->maxMines;

        $state['grid'] = $this->grid;

        $staterow = json_encode($state);

        file_put_contents('./state/state.json', $staterow);
    }

    public function destroyGame() {
        if (file_exists('./state/state.json')) {
            unlink('./state/state.json');
        }
    }

    private function retrieveGame()
    {
        $staterow = file_get_contents('./state/state.json', true);

        $state = json_decode($staterow, true);

        $this->maxRowIndex = $state['maxRowIndex'];
        $this->maxColumnIndex = $state['maxColumnIndex'];
        $this->maxMines = $state['maxMines'];

        $this->grid = $state['grid'];
    }

    private function populateGrid()
    {
        for ($row = 0; $row <= $this->maxRowIndex; $row++) {
            for ($col = 0; $col <= $this->maxColumnIndex; $col++) {
                $this->grid[$row][$col] = '';
            }
        }

        $this->sowMines();

        for ($row = 0; $row <= $this->maxRowIndex; $row++) {
            for ($column = 0; $column <= $this->maxColumnIndex; $column++) {
                $this->countMines($row, $column);
            }
        }

        return;
    }

    private function sowMines()
    {
        for ($mine = 0; $mine < $this->maxMines; $mine++) {
            $this->layMine();
        }

        return;
    }

    private function layMine()
    {
        $laid = false;

        do {
            $mineRow = rand(0, $this->maxRowIndex);
            $mineCol = rand(0, $this->maxColumnIndex);

            if ($this->grid[$mineRow][$mineCol] != 'X') {
                $this->grid[$mineRow][$mineCol] = 'X';

                $laid = true;
            }
        } while (!$laid);

        return;
    }

    private function countMines($row, $column)
    {
        $minecount = 0;
        $startRow = 0;
        $startColumn = 0;

        //Do not count adjacent mines if the current cell identified by the passed row and column contains a mine.
        if ($this->grid[$row][$column] == 'X') {
            return;
        }

        $startRow = $this->calculateStartRow($row);

        $endRow = $this->calculateEndRow($row);

        $startColumn = $this->calculateStartColumn($column);

        $endColumn = $this->calculateEndColumn($column);

        for ($rowi = $startRow; $rowi <= $endRow; $rowi++) {
            for ($columnj = $startColumn; $columnj <= $endColumn; $columnj++) {
                //Don't count the current cell.
                if ($rowi == $row && $columnj == $column) {
                    continue;
                }

                if ($this->grid[$rowi][$columnj] == 'X') {
                    $minecount++;
                }
            }
        }

        if ($minecount > 0) {
            $this->grid[$row][$column] = $minecount;
        }

        return;
    }

    public function checkForMine($row, $column)
    {
        $result = array();

        if (!file_exists('./state/state.json')) {
            $result['message'] = 'Please press New Game to start.';
            $result['status'] = 2;

            return $result;
        }

        if ($this->grid[$row][$column] == 'X') {
            $result['message'] = 'Game Over';
            $result['status'] = 1;

            $this->destroyGame();
        } else {
            $result['message'] = 'Game on';
            $result['status'] = 0;
        }

        $result['row'] = $row;
        $result['column'] = $column;
        $result['content'] = $this->grid[$row][$column];

        return $result;
    }

    public function calculateStartRow($row)
    {
        if ($row > 0) {
            $startRow = $row - 1;
        } else {
            $startRow = 0;
        }

        return $startRow;
    }

    public function calculateEndRow($row)
    {
        if ($row + 1 < $this->maxRowIndex) {
            $endRow = $row + 1;
        } else {
            $endRow = $this->maxRowIndex;
        }

        return $endRow;
    }

    public function calculateStartColumn($column)
    {
        if ($column > 0) {
            $startColumn = $column - 1;
        } else {
            $startColumn = 0;
        }

        return $startColumn;
    }

    public function calculateEndColumn($column)
    {
        if ($column + 1 < $this->maxColumnIndex) {
            $endColumn = $column + 1;
        } else {
            $endColumn = $this->maxColumnIndex;
        }

        return $endColumn;
    }
}