SHELL = /bin/sh
ROOT_DIR := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
VERSION = $(shell cat VERSION)
ZIP_FILE="lfischer-router-${VERSION}.zip"

source:
	cd $(ROOT_DIR)
	if [ -f ${ZIP_FILE} ]; then rm ${ZIP_FILE}; fi
	mkdir -p /tmp/lfischer_router/src/classes/modules/lfischer_router/
	cp -R * /tmp/lfischer_router/src/classes/modules/lfischer_router/
	mv /tmp/lfischer_router/src/classes/modules/lfischer_router/package.json /tmp/lfischer_router/
	rm /tmp/lfischer_router/src/classes/modules/lfischer_router/Makefile
	if [ -f /tmp/lfischer_router/src/classes/modules/lfischer_router/build/* ]; then rm /tmp/lfischer_router/src/classes/modules/lfischer_router/build/*; fi
	if [ -f /tmp/lfischer_router/src/classes/modules/lfischer_router/*.zip ]; then rm /tmp/lfischer_router/src/classes/modules/lfischer_router/*.zip; fi
	cd /tmp/lfischer_router/ && zip -rqy ${ZIP_FILE} * && cp -f /tmp/lfischer_router/${ZIP_FILE} $(ROOT_DIR)
	rm -R /tmp/lfischer_router
