SOURCES=$(shell find src -name '*.php')

all: src/Underbar/Enumerable.php

src/Underbar/Enumerable.php: scripts/generate-enumerable-trait.php src/Underbar/ArrayImpl.php
	php $< > $@

clean:
	rm -f src/Underbar/Enumerable.php

.PHONY: all clean
