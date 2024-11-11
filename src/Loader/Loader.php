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
            throw new InputLoadException("File $path does not exist");
        }

        $fileHandle = fopen($path, 'r');
        if (!$fileHandle) {
            throw new InputLoadException("Failed to open file $path");
        }

        $fileSize = filesize($path);
        if (false === $fileSize || 0 === $fileSize) {
            throw new InputLoadException("Failed to assess size of file $path. Is the file empty?");
        }

        $contents = fread($fileHandle, $fileSize);
        if (false === $contents) {
            throw new InputLoadException("Failed to load file $path");
        }

        fclose($fileHandle);
        return $contents;
    }
}
