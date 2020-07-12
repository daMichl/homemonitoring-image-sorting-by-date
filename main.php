<?php
require_once 'vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Carbon\Carbon;

date_default_timezone_set('Europe/Berlin');

$finder = new Finder();
$filesystem = new Filesystem();

$finder->files()->in(__DIR__ . '/../1');

$amountOfFiles = $finder->count();
$actualFileCount = 0;

foreach ($finder as $file) {
    $actualFileCount++;

    $absoluteFilePath = (string) $file->getRealPath();
    $fileNameWithExtension = (string) $file->getRelativePathname();
    $fileNameWithoutExtension = $file->getFilenameWithoutExtension();

    echo floor(100 / $amountOfFiles * $actualFileCount) . " % | $actualFileCount / $amountOfFiles > $fileNameWithExtension" . PHP_EOL;

    $currentTime = Carbon::createFromTimestamp($fileNameWithoutExtension);
    $currentDate = $currentTime->toDateString();

    $currentDestinationDir = __DIR__ . "/../sorted/$currentDate";

    if (!$filesystem->exists($currentDestinationDir)) {
        $filesystem->mkdir($currentDestinationDir);
    }

    if (!empty($absoluteFilePath) && !empty($fileNameWithExtension)) {
        @$filesystem->rename($absoluteFilePath, "$currentDestinationDir/" .$fileNameWithExtension, true);
    }
    
}

echo 'DONE!!!'. PHP_EOL;