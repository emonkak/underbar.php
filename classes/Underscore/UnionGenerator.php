<?php

namespace Underscore;

class UnionGenerator
{
	public function union(array $arrays)
	{
		foreach ($arrays as $array) {
			foreach ($array as $index => $value)
				yield $value;
		}
	}
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
