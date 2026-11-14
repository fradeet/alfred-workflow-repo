# alfred-apple-music-recommandation
A Alfred workflow, get Apple Music recommandation by keyboard.

## Development Setup

```bash
git clone --recurse-submodules https://github.com/fradeet/alfred-apple-music-recommandation
cd alfred-apple-music-recommandation
composer install
```

The project requires PHP 8.5 or later with the cURL and JSON extensions.
Run `composer lint` to check the PHP source files.
Run `composer phpstan` to perform static analysis.

## Roadmap
- [] Cache images and use grid view
- [] Use shortcut key to control directly play or add to queue
