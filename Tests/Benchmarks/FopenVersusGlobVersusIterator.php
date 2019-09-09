<?php namespace JayBeeR\Flops\Tests {

    require_once '../../vendor/autoload.php';

    use FilesystemIterator;
    use JayBeeR\Flops\LocalFileSystem;
    use RecursiveDirectoryIterator;
    use RecursiveIteratorIterator;

    $rootPath = __DIR__ . '/../../Classes';
    $iterates = 1000;

    /**
     * FOPEN
     */
    {
        echo "fopen:\n";
        $max = 0;
        $start = microtime(true);

        for ($i = 0; $i < $iterates; $i++) {
            $files = (function ($from = '.') {
                if (!is_dir($from))
                    return false;

                $files = [];
                $directories = [$from];

                while (null !== ($directory = array_pop($directories))) {
                    if ($resource = opendir($directory)) {
                        while (false !== ($file = readdir($resource))) {
                            if ($file == '.' || $file == '..')
                                continue;
                            $path = $directory . '/' . $file;
                            if (is_dir($path))
                                $directories[] = $path;
                            else
                                $files[] = $path;
                        }
                        closedir($resource);
                    }
                }
                return $files;
            })($rootPath);
            $max = max($max, count($files));
        }
        echo '   Count: ' . $max . "\n";
        $stop = microtime(true);
        echo '    Time: ' . number_format($stop - $start, 10, '.', ',');
        echo "\n\n";
    }

    /**
     * DIRECTORY ITERATOR
     */
    {
        echo "DirectoryIterator:\n";
        $max = 0;
        $start = microtime(true);
        for ($i = 0; $i < $iterates; $i++) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME));
            $max = max($max, iterator_count($files));
        }
        echo '   Count: ' . $max . "\n";
        $stop = microtime(true);
        echo '    Time: ' . number_format($stop - $start, 10, '.', ',');
        echo "\n\n";
    }

    /**
     * GLOB
     */
    {
        echo "glob:\n";
        $max = 0;
        $start = microtime(true);
        for ($i = 0; $i < $iterates; $i++) {
            $files = array_filter(glob($rootPath . '/{**/*,*}', GLOB_BRACE), 'is_file');
            $max = max($max, count($files));
        }
        echo '   Count: ' . $max . "\n";
        $stop = microtime(true);
        echo '    Time: ' . number_format($stop - $start, 10, '.', ',');
        echo "\n\n";
    }

    /**
     * FLOPS
     */
    {
        echo "FLOPS:\n";
        $max = 0;
        $start = microtime(true);
        $rootFolder = LocalFileSystem::getFolder($rootPath);
        for ($i = 0; $i < $iterates; $i++) {
            $files = $rootFolder->findAllFiles(null, true);
            $max = max($max, iterator_count($files));
        }
        echo '   Count: ' . $max . "\n";
        $stop = microtime(true);
        echo '    Time: ' . number_format($stop - $start, 10, '.', ',');
        echo "\n";
    }
}