<?php

namespace Snowair\Dotenv;

use Snowair\Dotenv\Parser;

class Loader extends \josegonzalez\Dotenv\Loader
{

    public function parse()
    {
        $contents = false;
        $filepaths = $this->filepaths();

        foreach ($filepaths as $i => $filepath) {
            $isLast = count($filepaths) - 1 === $i;
            if (!file_exists($filepath) && $isLast) {
                return $this->raise(
                    'InvalidArgumentException',
                    sprintf("Environment file '%s' is not found", $filepath)
                );
            }

            if (is_dir($filepath) && $isLast) {
                return $this->raise(
                    'InvalidArgumentException',
                    sprintf("Environment file '%s' is a directory. Should be a file", $filepath)
                );
            }

            if ((!is_readable($filepath) || ($contents = file_get_contents($filepath)) === false) && $isLast) {
                return $this->raise(
                    'InvalidArgumentException',
                    sprintf("Environment file '%s' is not readable", $filepath)
                );
            }

            if ($contents !== false) {
                break;
            }
        }

        $parser = new Parser;
        $this->environment = $parser->parse($contents);

        return $this;
    }

}
