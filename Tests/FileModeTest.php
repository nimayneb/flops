<?php namespace JayBeeR\Flops\Tests {

    use JayBeeR\Flops\Modes\FileMode;
    use PHPUnit\Framework\TestCase;

    class FileModeTest extends TestCase
    {
        /**
         *  truncating(+beginning, +writing) => w
         *                                   => writing() => w
         *                                                => reading => w+
         *                                   => reading() => w+
         *                                                => writing() => w+
         *
         *  creating(+writing, +beginning) => x
         *                                 => reading(+writing) => x+
         *                                                      => writing() => x+
         *                                 => writing() => x
         *                                              => reading() => x+
         *
         *  appending(+writing) => a
         *                      => reading(+writing) => a+
         *                                           => writing() => a+
         *                      => writing() => a
         *                                   => reading() => a+
         *
         *  reading() => r
         *            => writing() => r+
         *                         => truncating(+beginning) => w+
         *                         => appending() => a+
         *                         => creating(+beginning) => x+
         *            => truncating(+beginning, +writing) => w+
         *                                                => writing() => w+
         *            => appending(+writing) => a+
         *                                   => writing() => a+
         *            => creating(+writing, +beginning) => x+
         *                                              => writing() => x+
         *
         *  writing() => w
         *            => reading() => w+
         *                         => truncating() => w+
         *                         => appending() => a+
         *                         => creating() => x+
         *            => truncating(+beginning) => w
         *                                      => reading() => w+
         *            => appending() => a
         *                           => reading() => a+
         *            => creating(+beginning) => x
         *                                    => reading() => x+
         * @test
         */
        public function allVariantsReturnsFileMode()
        {
            // 57x variants to set 8 file modes
            $this->assertEquals('w', FileMode::truncating());
            $this->assertEquals('w', FileMode::truncating()->writing());
            $this->assertEquals('w+', FileMode::truncating()->writing()->reading());
            $this->assertEquals('w+', FileMode::truncating()->reading());
            $this->assertEquals('w+', FileMode::truncating()->reading()->writing());

            $this->assertEquals('a', FileMode::appending());
            $this->assertEquals('a+', FileMode::appending()->reading());
            $this->assertEquals('a+', FileMode::appending()->reading()->writing());
            $this->assertEquals('a', FileMode::appending()->writing());
            $this->assertEquals('a+', FileMode::appending()->writing()->reading());

            $this->assertEquals('x', FileMode::mustNotExists());
            $this->assertEquals('x+', FileMode::mustNotExists()->reading());
            $this->assertEquals('x+', FileMode::mustNotExists()->reading()->writing());
            $this->assertEquals('x', FileMode::mustNotExists()->writing());
            $this->assertEquals('x+', FileMode::mustNotExists()->writing()->reading());

            $this->assertEquals('r', FileMode::mustExists());
            $this->assertEquals('r', FileMode::mustExists()->reading());
            $this->assertEquals('r+', FileMode::mustExists()->reading()->writing());

            $this->assertEquals('r', FileMode::reading());
            $this->assertEquals('r+', FileMode::reading()->writing());
            $this->assertEquals('x+', FileMode::reading()->writing()->mustNotExists());
            $this->assertEquals('r+', FileMode::reading()->writing()->mustExists());
            $this->assertEquals('a+', FileMode::reading()->writing()->appending());
            $this->assertEquals('w+', FileMode::reading()->writing()->truncating());
            $this->assertEquals('x+', FileMode::reading()->mustNotExists());
            $this->assertEquals('x+', FileMode::reading()->mustNotExists()->writing());
            $this->assertEquals('r', FileMode::reading()->mustExists());
            $this->assertEquals('r+', FileMode::reading()->mustExists()->writing());
            $this->assertEquals('a+', FileMode::reading()->appending());
            $this->assertEquals('a+', FileMode::reading()->appending()->writing());
            $this->assertEquals('w+', FileMode::reading()->truncating());
            $this->assertEquals('w+', FileMode::reading()->truncating()->writing());

            $this->assertEquals('w', FileMode::writing());
            $this->assertEquals('w+', FileMode::writing()->reading());
            $this->assertEquals('x+', FileMode::writing()->reading()->mustNotExists());
            $this->assertEquals('r+', FileMode::writing()->reading()->mustExists());
            $this->assertEquals('a+', FileMode::writing()->reading()->appending());
            $this->assertEquals('w+', FileMode::writing()->reading()->truncating());
            $this->assertEquals('x', FileMode::writing()->mustNotExists());
            $this->assertEquals('x+', FileMode::writing()->mustNotExists()->reading());
            $this->assertEquals('r+', FileMode::writing()->mustExists());
            $this->assertEquals('r+', FileMode::writing()->mustExists()->reading());
            $this->assertEquals('a', FileMode::writing()->appending());
            $this->assertEquals('a+', FileMode::writing()->appending()->reading());
            $this->assertEquals('w', FileMode::writing()->truncating());
            $this->assertEquals('w+', FileMode::writing()->truncating()->reading());
        }
    }
} 