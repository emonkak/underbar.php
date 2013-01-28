all: src/Underdash/Enumerable.php

src/Underdash/Enumerable.php: scripts/generate-enumerable-trait.php src/Underdash/Strict.php
	php $< > $@

test:
	@phpunit .

clean:
	rm -f src/Underdash/Enumerable.php

.PHONY: all clean test
