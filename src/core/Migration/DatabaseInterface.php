<?php
namespace Ore\Migration;

interface DatabaseInterface
{
	public function migrate();
	public function alter();
}
