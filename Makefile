#!/usr/bin/make -f
#
#

# ------------------------------------------------------------------------
#
# General and combined targets
#
.PHONY:  update clean help

# target: all - Default target, run tests and build
all: test build


# target: test - Do all tests
test: jscs jshint less-lint html-lint



# target: build - Do all build
build: less-compile less-minify js-minify



# target: update - Update the codebase.
update:
	git pull
	composer update



# target: clean - Removes generated files and directories.
clean:
	@echo "Target clean not implemented."
	#rm -f $(CSS_MINIFIED) $(JS_MINIFIED)



# target: help - Displays help.
help:
	@echo "make [target] ..."
	@echo "target:"
	@egrep "^# target:" Makefile | sed 's/# target: / /g'



# target: cache - Create the cache directory.
.PHONY: cache
cache:
	mkdir cache
	chmod 777 cache
