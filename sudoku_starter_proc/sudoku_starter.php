<?php

/**
 * Charge un fichier en fournissant son chemin
 * @param string $filepath Chemin du fichier
 * @return array|null Un tableau si le fichier existe et est valide, null sinon
 */
function loadFromFile(string $filepath): ?array
{
    $content = file_get_contents($filepath);
    $data = json_decode($content, true);
    return $data;
}

/**
 * Retourne la valeur d'une cellule
 * @param array $grid
 * @param int $rowIndex Index de ligne
 * @param int $columnIndex Index de colonne
 * @return int Valeur
 */
function get(array $grid, int $rowIndex, int $columnIndex): int
{

    return $grid[$rowIndex][$columnIndex];
}

/**
 * Affecte une valeur dans une cellule
 * @param array $grid
 * @param int $rowIndex Index de ligne
 * @param int $columnIndex Index de colonne
 * @param int $value Valeur
 */
function set(array &$grid, int $rowIndex, int $columnIndex, int $value): void
{

    $grid[$rowIndex][$columnIndex] = $value;
}

/**
 * Retourne les données d'une ligne à partir de son index
 * @param array $grid
 * @param int $rowIndex Index de ligne (entre 0 et 8)
 * @return array Chiffres de la ligne demandée
 */
function row(array $grid, int $rowIndex): array
{
    return $grid[$rowIndex];
}

/**
 * Retourne les données d'une colonne à partir de son index
 * @param array $grid
 * @param int $columnIndex Index de colonne (entre 0 et 8)
 * @return array Chiffres de la colonne demandée
 */
function column(array $grid, int $columnIndex): array
{
    $column = [];
    for($i = 0 ; $i <= 8; $i++)
    {
        array_push($column, $grid[$i][$columnIndex]);
    }
    return $column;
}

/**
 * Retourne les données d'un bloc à partir de son index
 * L'indexation des blocs est faite de gauche à droite puis de haut en bas
 * @param array $grid
 * @param int $squareIndex Index de bloc (entre 0 et 8)
 * @return array Chiffres du bloc demandé
 */
function square(array $grid, int $squareIndex): array
{
    $rowIndex = intdiv($squareIndex, 3) * 3;
    $columnIndex = ($squareIndex % 3) * 3;
    // permet d'obtenir :
    // 0 : 0;0
    // 1 : 0;3
    // 2 : 0;6
    // 3 : 3;0
    // 4 : 3;3
    // 5 : 3;6
    // 6 : 6;0
    // 7 : 6;3
    // 8 : 6;6
    $square = [];
    for($i = $rowIndex; $i < $rowIndex + 3; $i++)
    {
        for($j = $columnIndex; $j < $columnIndex + 3; $j++)
        {
            array_push($square, $grid[$i][$j]);
        }
    }
    return $square;
}

function getIndexSquare(int $rowIndex, int $columnIndex): int
{
    for($i = 0; $i < 9; $i = $i + 3)
    {
        if (in_array($rowIndex, range($i, $i + 2))) // determine square's line
        {
            for($j = 0; $j < 9; $j = $j + 3)
            {
                if (in_array($columnIndex, range($j, $j + 2))) // determine square's column
                {
                    return $i + intdiv($j, 3);
                }
            }
        }
    }
    return -1;
}

/**
 * Génère l'affichage de la grille
 * @param array $grid
 * @return void
 */
function display(array $grid): void
{
    for ($line = 0; $line < 25; $line++)
    {
        for ($column = 0; $column < 25; $column++)
        {
            if ($line == 0)
            {
                if ($column == 0)
                {
                    echo("╔");
                }
                else if ($column == 24)
                {
                    echo("╗");
                }
                else if ($column == 8 || $column == 16)
                {
                    echo("╦");
                }
                else
                {
                    echo("═");
                }
            }
            else if ($line == 8 || $line == 16)
            {
                if ($column == 0)
                {
                    echo("╠");
                }
                else if ($column == 24)
                {
                    echo("╣");
                }
                else if ($column == 8 || $column == 16)
                {
                    echo("╬");
                }
                else
                {
                    echo("═");
                }
            }
            else if ($line == 24)
            {
                if ($column == 0)
                {
                    echo("╚");
                }
                else if ($column == 24)
                {
                    echo("╝");
                }
                else if ($column == 8 || $column == 16)
                {
                    echo("╩");
                }
                else
                {
                    echo("═");
                }
            }
            else
            {
                if ($column == 0 || $column == 8 || $column == 16 || $column == 24)
                {
                    echo("║");
                }
                else
                {
                    if ($column % 2 == 0 && $line % 2 == 0)
                    {
                        $l = Adjuster($line);
                        $c = Adjuster($column);
                        $s = $grid[$l][$c];
                        if($s == "0")
                            $s = ".";
                        echo($s);
                    }
                    else
                    {
                        echo(" ");
                    }
                }
            }
        }
        echo(PHP_EOL);
    }
}

function Adjuster(int $ori): int
{
    if ($ori == 2)
        return 0;
    else if ($ori == 4)
        return 1;
    else if ($ori == 6)
        return 2;
    else if ($ori == 10)
        return 3;
    else if ($ori == 12)
        return 4;
    else if ($ori == 14)
        return 5;
    else if ($ori == 18)
        return 6;
    else if ($ori == 20)
        return 7;
    else if ($ori == 22)
        return 8;
    else
        return -1;
}

/**
 * Teste si la valeur peut être ajoutée aux coordonnées demandées
 * @param array $grid
 * @param int $rowIndex Index de ligne
 * @param int $columnIndex Index de colonne
 * @param int $value Valeur
 * @return bool Résultat du test
 */
function isValueValidForPosition(array $grid, int $rowIndex, int $columnIndex, int $value): bool
{
    if(in_array($value, column($grid, $columnIndex)))
        return  false;
    if(in_array($value, row($grid,  $rowIndex)))
        return  false;
    if(in_array($value, square($grid, getIndexSquare($rowIndex, $columnIndex))))
        return  false;
    return true;
}

/**
 * Retourne les coordonnées de la prochaine cellule à partir des coordonnées actuelles
 * (Le parcours est fait de gauche à droite puis de haut en bas)
 * @param array $grid
 * @param int $rowIndex Index de ligne
 * @param int $columnIndex Index de colonne
 * @return array Coordonnées suivantes au format [indexLigne, indexColonne]
 */
function getNextRowColumn(array $grid, int $rowIndex, int $columnIndex): array
{
    if($rowIndex === sizeof($grid[0]) - 1)
    {
        $rowIndex = 0;
        if ($columnIndex === sizeof($grid) - 1)
        {
            $columnIndex = 0;
        }
        else
        {
            $columnIndex = $columnIndex + 1;
        }
    }
    else
    {
        $rowIndex = $rowIndex + 1;
    }
    return [$rowIndex, $columnIndex];
}

/**
 * Teste si la grille est valide
 * @param array $grid
 * @return bool
 */
function isValid(array $grid): bool
{
    $indexRow = 0;
    $indexColumn = 0;
    $isValid = true;
    $gridTemp=$grid;
    do
    {
        $val = get($gridTemp, $indexRow, $indexColumn);
        set($gridTemp, $indexRow, $indexColumn, 0);
        $isValid = isValueValidForPosition($gridTemp, $indexRow, $indexColumn, $val);
        set($gridTemp, $indexRow, $indexColumn, $val);
        $tab = getNextRowColumn($gridTemp, $indexRow, $indexColumn);
        $indexRow = $tab[0];
        $indexColumn = $tab[1];
    }
    while(($indexRow != 0 || $indexColumn != 0) && $isValid);
    return $isValid;
}

function solve(array $grid): ?array
{
    for($row = 0; $row < sizeof($grid); $row++)
    {
        for($column = 0; $column < sizeof($grid[$row]); $column++)
        {
            if($grid[$row][$column] === 0)
            {
                // determine toutes les possibilites
                //$chiffres = array_diff(range(1, 9), row($grid, $rowIndex), column($grid, $columnIndex), square($grid, getIndexSquare($rowIndex, $columnIndex)));
                for($number = 1; $number <= 9; $number++)
                {
                    //echo $number . " ? ";
                    //var_dump(isValueValidForPosition($grid, $row, $column, $number));
                    //echo PHP_EOL;
                    if(isValueValidForPosition($grid, $row, $column, $number))
                    {
                        $new_grid = $grid;
                        set($new_grid, $row, $column, $number);
                        //display($new_grid);
                        return solve($new_grid);
                    }
                }
            }
        }
    }

    display($grid);
    if(isValid($grid))
        return $grid;
    else
        return null;
}

function main(bool $mode=true): void
{
    if ($mode)
    {
        $dir = __DIR__ . '/grids';
        $files = array_values(array_filter(scandir($dir), function($f){ return $f != '.' && $f != '..'; }));

        foreach($files as $file)
        {
            $filepath = realpath($dir . '/' . $file);
            echo("Chargement du fichier $file" . PHP_EOL);
            $grid = loadFromFile($filepath);
            echo(display($grid) . PHP_EOL);
            $startTime = microtime(true);
            echo("Début de la recherche de solution" . PHP_EOL);
            $solvedGrid = solve($grid);
            if ($solvedGrid === null) {
                echo("Echec, grille insolvable" . PHP_EOL);
            } else {
                echo("Reussite :" . PHP_EOL);
                echo(display($solvedGrid) . PHP_EOL);
            }

            $duration = round((microtime(true) - $startTime) * 1000);
            echo("Durée totale : $duration ms" . PHP_EOL);
            echo("--------------------------------------------------" . PHP_EOL);
        }
    }
    else
    {
        generateGrid();
    }

}

main();