#!/bin/bash
ls /code/cache/container* 2> /dev/null | while read file; do
    if [ -f "${file}" ]; then
        rm "$file"
    fi
done

