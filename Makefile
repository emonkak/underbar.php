all: src/Understrike/Enumerable.php

src/Understrike/Enumerable.php: scripts/generate-enumerable-trait.php
	php $< > $@

clean:
	rm -f src/Understrike/Enumerable.php

.PHONY: all clean
