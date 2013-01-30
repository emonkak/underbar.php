SOURCES=$(shell find src tests -name '*.php')

all: src/Underbar/Enumerable.php

src/Underbar/Enumerable.php: scripts/generate-enumerable-trait.php src/Underbar/Strict.php
	php $< > $@

report/index.html: $(SOURCES)
	@phpunit --coverage-html report .

clean:
	rm -f src/Underbar/Enumerable.php

report: report/index.html

test:
	@phpunit .

.PHONY: all clean report test
