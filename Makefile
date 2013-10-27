# Makefile for web developing

HTMLS=$(patsubst jade/%.jade,%.html,$(wildcard jade/*.jade))
STYLESHEETS=$(patsubst sass/%.sass,css/%.css,$(wildcard sass/[^_]*.sass))
JAVASCRIPTS=$(patsubst coffee/%.coffee,js/%.js,$(wildcard coffee/*.coffee))

JADE_INCLUDES=$(shell find jade/ja_JP)

all: $(HTMLS) $(STYLESHEETS) $(JAVASCRIPTS)

clean:
	rm -f $(HTMLS) $(STYLESHEETS) $(JAVASCRIPTS)

%.html: jade/%.jade $(JADE_INCLUDES)
	jade --obj "{filename: \"$<\"}" --pretty < $< \
	| sed -e 's/<code class="lang-/<code class="language-/' > $@

css/%.css: sass/%.sass config.rb $(wildcard sass/_*.sass)
	compass compile $<

js/%.js: coffee/%.coffee
	coffee -o $(dir $@) $<

.PHONY: all clean
