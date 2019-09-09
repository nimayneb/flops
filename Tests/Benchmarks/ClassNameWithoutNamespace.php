<?php namespace JayBeeR\Flops\Tests {

    use ReflectionClass;
    use ReflectionException;

    class test
    {
        public function test1()
        {
            substr(strrchr(static::class, "\\"), 1);
        }

        public function test2()
        {
            substr(strrchr(__CLASS__, "\\"), 1);
        }

        /**
         * @throws ReflectionException
         */
        public function test3()
        {
            (new ReflectionClass($this))->getShortName();
        }
    }

    $start = microtime(true);
    for ($i = 0; $i < 10000; $i++) (new Test)->test1();
    $stop = microtime(true);
    echo number_format($stop - $start, 10, '.', ',');
    echo "\n";

    $start = microtime(true);
    for ($i = 0; $i < 10000; $i++) (new Test)->test2();
    $stop = microtime(true);
    echo number_format($stop - $start, 10, '.', ',');
    echo "\n";

    $start = microtime(true);
    for ($i = 0; $i < 10000; $i++) (new Test)->test3();
    $stop = microtime(true);
    echo number_format($stop - $start, 10, '.', ',');
    echo "\n";
}