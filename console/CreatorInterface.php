<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.05.2018
 * Time: 23:12
 */

class CreatorInterface
{
    /**
     * @var array
     */
    const COMMANDS = [
        '-f',
    ];

    /**
     * @var string
     */
    const PREFIX = 'Interface';

    /**
     * @var string
     */
    const EXT  = '.php';

    /**
     * @var array
     */
    const FINDING_TEXT = [
        'public function',
        'public static function',
        'namespace',
        'use',
    ];

    /**
     * @var array
     */
    private $argv = [];

    /**
     * @var
     */
    private $file;

    /**
     * @var string
     */
    private $filename = '';

    /**
     * @var array
     */
    private $content = [];

    /**
     * @var array
     */
    private $methods = [];

    /**
     * @var string
     */
    private $namespace = '';

    /**
     * @var array
     */
    private $use = [];

    /**
     * CreatorInterface constructor.
     */
    public function __construct()
    {
        include_once '../bootstrap-cli.php';
        $this->argv = $_SERVER['argv'];

        if ($this->argv[1] != self::COMMANDS[0]) {
            throw new \InvalidArgumentException('Argument "' . self::COMMANDS[0]. '" with path of file not found!' . PHP_EOL);
        }

        $this->loadPHPFile();
        $this->findMethodsAndHeaders();
        $this->createInterface();
    }

    /**
     * @return void
     */
    private function loadPHPFile(): void
    {
        $this->filename = $this->argv[2];

        if (!is_file($this->filename . self::EXT)) {
            throw new \InvalidArgumentException('File not found!' . PHP_EOL);
        }

        $this->content  = file($this->filename . self::EXT);
    }

    /**
     * @return void
     */
    private function findMethodsAndHeaders(): void
    {
        foreach ($this->content as $index => $item) {
            if (substr($item, 0, strlen(self::FINDING_TEXT[2])) === self::FINDING_TEXT[2]) {
                $this->namespace = $item . PHP_EOL;
            }

            if (substr($item, 0, strlen(self::FINDING_TEXT[3])) === self::FINDING_TEXT[3]) {
                $this->use[] = $item;
            }

            if (strpos($item, self::FINDING_TEXT[0]) !== false || strpos($item, self::FINDING_TEXT[1]) !== false) {
                $this->methods[] = $this->parsePHPDoc($this->content, $index) . str_replace(PHP_EOL, '', $item) . ';';
            }
        }
    }

    /**
     * @param array $content
     * @param string $index
     * @return string
     */
    private function parsePHPDoc(array $content, string $index): string
    {
        $answer = [];

        for ($i = $index - 1; $i > $index - 15; $i--) {
            if (strpos($content[$i], '*') === false) {
               break;
            }
            $answer[] = $content[$i];
        }

        return implode('', array_reverse($answer));
    }

    /**
     * @return void
     */
    private function createInterface(): void
    {
        $this->file = fopen($this->filename . self::PREFIX . self::EXT,'w+');
        $useString = implode('', $this->use);

        if (!empty($useString)) {
            $useString .= PHP_EOL;
        }

        fwrite($this->file, '<?php' . PHP_EOL . PHP_EOL . $this->namespace . $useString .  'interface ' . basename($this->filename) . self::PREFIX . PHP_EOL . '{' . PHP_EOL);

        foreach ($this->methods as $index => $method) {
            if (empty($method)) {
                continue;
            }
            fwrite($this->file, $method . PHP_EOL . ($index + 1 == count($this->methods) ? '' : PHP_EOL));
        }

        fwrite($this->file, '}');
        fclose($this->file);
    }
}

new CreatorInterface();