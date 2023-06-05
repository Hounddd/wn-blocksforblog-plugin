# Blocks For Blog Plugin

Adds blocks for the Winter.Blocks plugin.
Replaces the post editor (Winter.Blog plugin) with blocks according to settings.

## Requirements

This plugin use the [HTML To Markdown for PHP](https://github.com/thephpleague/html-to-markdown) library.
You must adjust your root winter composer.json file to include this plugin's composer.json file in your dependencies.

```json
"extra": {
    "merge-plugin": {
        "include": [
            "plugins/hounddd/blocksforblog/composer.json"
        ],
        "recurse": true,
        "replace": false,
        "merge-dev": false
    }
},
```
