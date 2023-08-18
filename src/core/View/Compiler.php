<?php
namespace Ore\View;

use Ore\Core;
use Ore\Trait\Cache;
use Exception;

class Compiler extends Core
{

	CONST REP_DOLLAR = "__DOLLAR_MARK_REPLACE__";

	private $_baseText = "";
	private $_distText = "";

	public function __construct($baseText)
	{
		parent::__construct();
		$this->_baseText = $baseText;
	}

	public function compile()
	{
		$lineData = $this->lineBase();
		$lineData = $this->markEcho($lineData);
		$lineData = $this->makeBlock($lineData);
		$this->_distText = $this->package($lineData);
		$this->_distText = str_replace(self::REP_DOLLAR, "\$", $this->_distText);
		return $this->_distText;
	}

	protected function package($d)
	{
		return implode("\r\n", $d);
	}

	protected function markEcho($d)
	{
		$doll = self::REP_DOLLAR;
		foreach($d as &$v)
		{
			$v = preg_replace("#@@({$doll}[0-9a-zA-Z\[\]\"]+)#is", "<?=\\1?>", $v);
			$v = preg_replace("#@({$doll}[0-9a-zA-Z\[\]\"]+)#is", "<?=htmlspecialchars(\\1)?>", $v);
		}
		return $d;
	}

	protected function makeBlock($d)
	{
		$doll = self::REP_DOLLAR;
		foreach($d as &$v)
		{
			$v = preg_replace("#{(.*?)}#", "<?php \\1 ?>", $v);
		}
		return $d;
	}

	protected function lineBase()
	{
		$text = str_replace(["\r\n", "\r"], "\n", $this->_baseText);
		$r = [];
		foreach(explode("\n", $text) as $d)
		{
			$r[] = str_replace(["\n", "\$"], ["", self::REP_DOLLAR], $d);
		}
		return $r;
	}

}
