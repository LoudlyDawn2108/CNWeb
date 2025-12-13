<?php
$path = 'inp.txt';
if (!is_readable($path)) {
    fwrite(STDERR, "Unable to open file!\n");
    exit(1);
}

// read whole file into a string
$contents = file_get_contents($path);
if ($contents === false) {
    fwrite(STDERR, "Failed to read `inp.txt`\n");
    exit(1);
}

$lines = explode(PHP_EOL, $contents);
$password = 0;
$curr = 50;

function dialLeft($start, $steps): int
{
    global $password;
    $curr = $start;
    for ($i = 0; $i < $steps; $i++) {
        $curr--;
        if ($curr < 0) {
            $password++;
            $curr = 99;
        }
    }
    if ($curr === 0) $password++;
    if ($start === 0) $password--;
    return ($curr + 100) % 100;
}

function dialRight($start, $steps): int
{
    global $password;
    $total = $start + $steps;
    $wraps = intdiv($total, 100);
    $password += $wraps;
    return $total % 100;
}


foreach ($lines as $line) {
    $dir = $line[0];
    $steps = (int)substr($line, 1);
    echo "Dialed: " . $line . " From: " . $curr;
    $curr = match ($dir) {
        'L' => dialLeft($curr, $steps),
        'R' => dialRight($curr, $steps),
        default => throw new InvalidArgumentException("The direction can only be left or right at the start of line"),
    };
    echo " To: " . $curr . " password: " . $password . PHP_EOL;
}

echo $password;

