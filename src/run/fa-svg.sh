#!/usr/bin/env bash

# copy sgs
#cp -r ./node_modules/@fortawesome/fontawesome-free/svgs/* ./fonts/svg/

SVGINPATH=./node_modules/@fortawesome/fontawesome-free/svgs
SVGOUTPATH=./fonts/svg

for SVGPATH in ${SVGINPATH}/*; do
	REALM=$( basename $SVGPATH );
	mkdir -p ${SVGOUTPATH}/${REALM}
	for SVG in ${SVGPATH}/*.svg; do
		SVGFILE=$( basename $SVG );
		sed -E 's/<!--.*-->//g' $SVG > ${SVGOUTPATH}/${REALM}/${SVGFILE}
	done
done

#
