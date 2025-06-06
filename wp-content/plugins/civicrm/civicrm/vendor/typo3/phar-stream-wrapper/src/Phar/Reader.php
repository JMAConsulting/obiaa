<?php
declare(strict_types=1);
namespace TYPO3\PharStreamWrapper\Phar;

/*
 * This file is part of the TYPO3 project.
 *
 * It is free software; you can redistribute it and/or modify it under the terms
 * of the MIT License (MIT). For the full copyright and license information,
 * please read the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

class Reader
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * Mime-type in order to use zlib, bzip2 or no compression.
     * In case ext-fileinfo is not present only the relevant types
     * 'application/x-gzip' and 'application/x-bzip2' are assigned
     * to this class property.
     *
     * @var string
     */
    private $fileType;

    public function __construct(string $fileName)
    {
        if (strpos($fileName, '://') !== false) {
            throw new ReaderException(
                'File name must not contain stream prefix',
                1539623708
            );
        }

        $this->fileName = $fileName;
        $this->fileType = $this->determineFileType();
    }

    public function resolveContainer(): Container
    {
        $data = $this->extractData($this->resolveStream() . $this->fileName);

        if ($data['stubContent'] === null) {
            throw new ReaderException(
                'Cannot resolve stub',
                1547807881
            );
        }
        if ($data['manifestContent'] === null || $data['manifestLength'] === null) {
            throw new ReaderException(
                'Cannot resolve manifest',
                1547807882
            );
        }
        if (strlen($data['manifestContent']) < $data['manifestLength']) {
            throw new ReaderException(
                sprintf(
                    'Exected manifest length %d, got %d',
                    strlen($data['manifestContent']),
                    $data['manifestLength']
                ),
                1547807883
            );
        }

        return new Container(
            Stub::fromContent($data['stubContent']),
            Manifest::fromContent($data['manifestContent'])
        );
    }

    /**
     * @param string $fileName e.g. '/path/file.phar' or 'compress.zlib:///path/file.phar'
     */
    private function extractData(string $fileName): array
    {
        $stubContent = null;
        $manifestContent = null;
        $manifestLength = null;

        $resource = fopen($fileName, 'r');
        if (!is_resource($resource)) {
            throw new ReaderException(
                sprintf('Resource %s could not be opened', $fileName),
                1547902055
            );
        }

        while (!feof($resource)) {
            $line = fgets($resource);
            // stop processing in case the system fails to read from a stream
            if ($line === false) {
                break;
            }
            // stop reading file when manifest can be extracted
            if ($manifestLength !== null && $manifestContent !== null && strlen($manifestContent) >= $manifestLength) {
                break;
            }

            $manifestPosition = strpos($line, '__HALT_COMPILER();');

            // first line contains start of manifest
            if ($stubContent === null && $manifestContent === null && $manifestPosition !== false) {
                $stubContent = substr($line, 0, $manifestPosition - 1);
                $manifestContent = preg_replace('#^.*__HALT_COMPILER\(\);(?>[ \n]\?>(?>\r\n|\n)?)?#', '', $line);
                $manifestLength = $this->resolveManifestLength($manifestContent);
            // line contains start of stub
            } elseif ($stubContent === null) {
                $stubContent = $line;
            // line contains start of manifest
            } elseif ($manifestContent === null && $manifestPosition !== false) {
                $manifestContent = preg_replace('#^.*__HALT_COMPILER\(\);(?>[ \n]\?>(?>\r\n|\n)?)?#', '', $line);
                $manifestLength = $this->resolveManifestLength($manifestContent);
            // manifest has been started (thus is cannot be stub anymore), add content
            } elseif ($manifestContent !== null) {
                $manifestContent .= $line;
                $manifestLength = $this->resolveManifestLength($manifestContent);
            // stub has been started (thus cannot be manifest here, yet), add content
            } elseif ($stubContent !== null) {
                $stubContent .= $line;
            }
        }
        fclose($resource);

        return [
            'stubContent' => $stubContent,
            'manifestContent' => $manifestContent,
            'manifestLength' => $manifestLength,
        ];
    }

    /**
     * Resolves the stream to handle compressed Phar archives.
     */
    private function resolveStream(): string
    {
        if ($this->fileType === 'application/x-gzip' || $this->fileType === 'application/gzip') {
            return 'compress.zlib://';
        }
        if ($this->fileType === 'application/x-bzip2') {
            return 'compress.bzip2://';
        }
        return '';
    }

    private function determineFileType(): string
    {
        if (class_exists('\\finfo')) {
            $fileInfo = new \finfo();
            return (string)$fileInfo->file($this->fileName, FILEINFO_MIME_TYPE);
        }
        return $this->determineFileTypeByHeader();
    }

    /**
     * In case ext-fileinfo is not present only the relevant types
     * 'application/x-gzip' and 'application/x-bzip2' are resolved.
     */
    private function determineFileTypeByHeader(): string
    {
        $resource = fopen($this->fileName, 'r');
        if (!is_resource($resource)) {
            throw new ReaderException(
                sprintf('Resource %s could not be opened', $this->fileName),
                1557753055
            );
        }
        $header = fgets($resource, 4);
        fclose($resource);
        if (strpos($header, "\x42\x5a\x68") === 0) {
            return 'application/x-bzip2';
        }
        if (strpos($header, "\x1f\x8b") === 0) {
            return 'application/x-gzip';
        }
        return '';
    }

    private function resolveManifestLength(string $content): ?int
    {
        if (strlen($content) < 4) {
            return null;
        }
        return static::resolveFourByteLittleEndian($content, 0);
    }

    public static function resolveFourByteLittleEndian(string $content, int $start): int
    {
        $payload = substr($content, $start, 4);
        if (!is_string($payload)) {
            throw new ReaderException(
                sprintf('Cannot resolve value at offset %d', $start),
                1539614260
            );
        }

        $value = unpack('V', $payload);
        if (!isset($value[1])) {
            throw new ReaderException(
                sprintf('Cannot resolve value at offset %d', $start),
                1539614261
            );
        }
        return $value[1];
    }

    public static function resolveTwoByteBigEndian(string $content, int $start): int
    {
        $payload = substr($content, $start, 2);
        if (!is_string($payload)) {
            throw new ReaderException(
                sprintf('Cannot resolve value at offset %d', $start),
                1539614263
            );
        }

        $value = unpack('n', $payload);
        if (!isset($value[1])) {
            throw new ReaderException(
                sprintf('Cannot resolve value at offset %d', $start),
                1539614264
            );
        }
        return $value[1];
    }
}
