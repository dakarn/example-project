<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 25.04.2018
 * Time: 11:35
 */

class CreatorModel
{
	/**
	 * @var array
	 */
	const COMMANDS = [
        '-f',
        '-vars',
		'-path'
    ];

	/**
	 * @var array
	 */
	const EXT = '.php';

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
	private $content = '';

	/**
	 * @var string
	 */
	private $filename = '';

	/**
	 * @var array
	 */
	private $stackSetter = [];

	/**
	 * @var array
	 */
	private $stackGetter = [];

	/**
	 * @var array
	 */
	private $tpl = [];

	/**
	 * CreatorModel constructor.
	 */
	public function __construct()
    {
    	include_once '../bootstrap-cli.php';
        $this->argv = $_SERVER['argv'];

        if ($this->argv[1] != self::COMMANDS[0]) {
			throw new \InvalidArgumentException('Argument "' . self::COMMANDS[0]. '" with name of model not found!');
        }

	    $this->loadTemplate();
	    $this->createModelFile();
	    $this->addHeaderFile();

	    $this->argv = array_slice($this->argv, 3);

	    if ($this->argv[0] != self::COMMANDS[1]) {
	    	throw new InvalidArgumentException('Argument "' . self::COMMANDS[1]. '" for create property not found!');
	    }

	    $this->addProperties();
	    $this->addConstruct();
        $this->addMethods();
    }

	/**
	 * @return void
	 */
	private function loadTemplate(): void
    {
    	$this->tpl['title']       = file_get_contents(TEMPLATE . '/creator-model/title.txt');
    	$this->tpl['footer']      = file_get_contents(TEMPLATE . '/creator-model/footer.txt');
    	$this->tpl['property']    = file_get_contents(TEMPLATE . '/creator-model/property.txt');
    	$this->tpl['setter']      = file_get_contents(TEMPLATE . '/creator-model/setter.txt');
    	$this->tpl['getter']      = file_get_contents(TEMPLATE . '/creator-model/getter.txt');
    	$this->tpl['construct']   = file_get_contents(TEMPLATE . '/creator-model/constructor.txt');
    }

	/**
	 * @return void
	 */
	private function createModelFile(): void
    {
    	$this->filename = $this->argv[2];
        $this->file     = fopen($this->argv[2] . self::EXT, 'w+');
    }

    /**
	 * @return void
	 */
	private function addConstruct(): void
    {
	    $this->content .= sprintf($this->tpl['construct'], $this->filename);
    }

	/**
	 * @return void
	 */
	private function addMethods(): void
    {
        foreach ($this->stackGetter as $stackIndex => $stackValue) {
            $returnType = empty($stackValue) ? '' : ': ' . $stackValue;
            $this->content .= sprintf($this->tpl['getter'], substr($returnType, 2), ucfirst($stackIndex), $returnType, $stackIndex);
        }

        foreach ($this->stackSetter as $stackIndex => $stackValue) {
            $inputType  =  empty($stackValue) ? '' : $stackValue . ' ';
	        $this->content .= sprintf($this->tpl['setter'], $inputType, $stackIndex, $this->filename, ucfirst($stackIndex), $inputType, $stackIndex, $stackIndex, $stackIndex);
        }
    }

	/**
	 * @return void
	 */
	private function addProperties(): void
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

            $this->content .= sprintf($this->tpl['property'], $type, $property, $defaultValue);
        }
    }

	/**
	 * @return void
	 */
	private function addHeaderFile(): void
    {
        $this->content = sprintf($this->tpl['title'], $this->argv[2]);
    }

	/**
	 *
	 */
	public function __destruct()
    {
        $this->content .= $this->tpl['footer'];
        fwrite($this->file, $this->content);
        fclose($this->file);
    }
}

new CreatorModel();