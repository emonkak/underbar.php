SOURCES=$(shell find src tests -name '*.php')

all: src/Underbar/Enumerable.php

src/Underbar/Enumerable.php: scripts/generate-enumerable-trait.php src/Underbar/ArrayImpl.php
	php $< > $@

report/index.html: $(SOURCES)
	vendor/phpunit/phpunit/composer/bin/phpunit --coverage-html report tests

clean:
	rm -f src/Underbar/Enumerable.php

report: report/index.html

test:
	vendor/phpunit/phpunit/composer/bin/phpunit tests

.PHONY: all clean report test
