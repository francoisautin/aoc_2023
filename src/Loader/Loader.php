<?php

declare(strict_types=1);

namespace App\Loader;

final class Loader
{
    /**
     * @throws InputLoadException
     */
    public static function loadFileContents(string $fileName): string
    {
        $path = "input/$fileName";
        if (!file_exists($path)) {
            throw new InputLoadException("File $path does not exist.");
        }

        $fileHandle = fopen($path, 'r');
        if (!$fileHandle) {
            throw new InputLoadException("Failed to open file $fileName.");
        }

        $contents = fread($fileHandle, filesize($path));
        fclose($fileHandle);
        return $contents;
    }
}
