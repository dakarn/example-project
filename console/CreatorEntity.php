<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 25.04.2018
 * Time: 11:35
 */

class CreatorEntity
{
    const COMMAND = [
        '-f',
        '-vars'
    ];

    const EXT = '.php';

    private $argv = [];

    private $file;

    private $content = '';

    private $stackSetter = [];

    private $stackGetter = [];

    public function __construct()
    {
        $this->argv = $_SERVER['argv'];

        if ($this->argv[1] === self::COMMAND[0]) {
            $this->createModelFile();
        }

        $this->argv = array_slice($this->argv, 3);

        if ($this->argv[0] === self::COMMAND[1]) {
            $this->addProperties();
        }

        $this->addMethods();
    }

    private function createModelFile()
    {
        $this->file = fopen($this->argv[2] . self::EXT, 'w+');

        $this->addHeaderFile();
        $this->addClassName();

        fwrite($this->file, $this->content);
        $this->content = '';
    }

    private function addMethods()
    {
        foreach ($this->stackGetter as $stackIndex => $stack) {
            $returnType = empty($stack) ? '' : ': ' . $stack;
            $this->content .= '    public function get' . ucfirst($stackIndex) . '()' . $returnType . PHP_EOL . '    {' . PHP_EOL . '        return $this->' . $stackIndex . '; ' . PHP_EOL . '    }';
            $this->content .= PHP_EOL . PHP_EOL;
        }

        foreach ($this->stackGetter as $stackIndex => $stack) {
            $returnType = ': self';
            $inputType  =  empty($stack) ? '' : $stack . ' ';
            $this->content .= '    public function set' . ucfirst($stackIndex) . '(' . $inputType . '$' . $stackIndex . ')' . $returnType . PHP_EOL . '    {' . PHP_EOL . '        $this->' . $stackIndex . ' = $' . $stackIndex . '; ' . PHP_EOL . '        return $this;' . PHP_EOL . '    }';
            $this->content .= PHP_EOL . PHP_EOL;
        }
    }

    private function addProperties()
    {
        array_shift($this->argv);

        foreach ($this->argv as $propertyValue) {

            $split        = strpos($propertyValue, ':');
            $property     = $propertyValue;
            $type         = '';
            $defaultValue = '';

            if ($split !== false) {
                $property = substr($property, 0, $split);
                $type     = substr($propertyValue, $split + 1);
            }

            switch ($type) {
                case 'array':
                    $defaultValue = ' = []';
                    break;
                case 'string':
                    $defaultValue = " = ''";
                    break;
            }

            $this->stackGetter[$property] = $type;
            $this->stackSetter[$property] = $type;

            $this->content .= '    private $' . $property . $defaultValue . ';';
            $this->content .= PHP_EOL . PHP_EOL;
        }
    }

    private function addHeaderFile()
    {
        $this->content = '<?php ' . PHP_EOL . PHP_EOL . 'namespace Test;' . PHP_EOL . PHP_EOL;
    }

    private function addClassName()
    {
        $this->content .= 'class ' . $this->argv[2] . '' . PHP_EOL . '{' . PHP_EOL;
    }

    public function __destruct()
    {
        $this->content .= PHP_EOL . '}';
        fwrite($this->file, $this->content);
        fclose($this->file);
    }
}

new CreatorEntity();