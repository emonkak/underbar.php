SOURCES=$(shell find src tests -name '*.php')

all: src/Underdash/Enumerable.php

src/Underdash/Enumerable.php: scripts/generate-enumerable-trait.php src/Underdash/Strict.php
	php $< > $@

report/index.html: $(SOURCES)
	@phpunit --coverage-html report .

clean:
	rm -f src/Underdash/Enumerable.php

report: report/index.html

test:
	@phpunit .

.PHONY: all clean report test
