all: src/Understrike/Enumerable.php

src/Understrike/Enumerable.php: scripts/generate-enumerable-trait.php src/Understrike/Strict.php
	php $< > $@

test:
	@phpunit .

clean:
	rm -f src/Understrike/Enumerable.php

.PHONY: all test clean
