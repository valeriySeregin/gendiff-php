# gendiff-php
[![Main workflow](https://github.com/valeriySeregin/gendiff-php/workflows/PHP%20Gendiff/badge.svg)](https://github.com/valeriySeregin/gendiff-php/actions)
[![Project Status: Inactive – The project has reached a stable, usable state but is no longer being actively developed; support/maintenance will be provided as time allows.](https://www.repostatus.org/badges/latest/inactive.svg)](https://www.repostatus.org/#inactive)
[![Maintainability](https://api.codeclimate.com/v1/badges/7302eaa208e0a0ff9567/maintainability)](https://codeclimate.com/github/valeriySeregin/gendiff-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7302eaa208e0a0ff9567/test_coverage)](https://codeclimate.com/github/valeriySeregin/gendiff-php/test_coverage)

A util calculating a difference between two files. This util supports two file input formats: json and yaml. Output might be formatted in json, plain and pretty.

# Setup
`git clone https://github.com/valeriySeregin/gendiff-php.git`

`make install`

# Composer
`composer global require valeraseregin/gendiff-php`

`echo export PATH="$PATH:$HOME/.config/composer/vendor/bin" >> ~/.bashrc`

# Getting help
`gendiff --help`

# Testing
`make test`

# Linting
`make lint`

# Usage example
[![asciicast](https://asciinema.org/a/KqOoQLKz4g4SEouqsYTwgdHPK.svg)](https://asciinema.org/a/KqOoQLKz4g4SEouqsYTwgdHPK)