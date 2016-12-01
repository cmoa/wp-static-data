# WP STATIC DATA v1.0.0

## About

WP Static Data provides a way to manage commonly occurring pieces of static data. Useful for keeping track of information like hours of operation, price lists, and locations/addresses.

Data files are stored in JSON format and can be accompanied by a view template of the same filename. View templates are used by the provided shortcode while the JSON data itself can be returned by [WP REST API](http://v2.wp-api.org) endpoints.

## Usage

Add your JSON files to a folder within your theme called `static`. A subfolder called `views` can be added to use templates with the `static_data` shortcode.

Example directory structure:

```
wordpress/
└── wp-content/
    └── themes/
        └── your-theme/     # → Your theme directory
            └── static/     # → JSON data files
                └── views/  # → Mustache templates
```

Add the plugin folder to your plugins directory and activate via WordPress. WP Static Data enables the shortcode `[static_data global="filename"]`. Substitute "filename" for the name of a JSON file in your `static` folder (e.g. `[static_data global="hours"]`)

The shortcode will load the corresponding template in your `views` folder. You can pass an optional `views` parameter to the shortcode to specify a particular view to render. For instance, you might have multiple locations with different hours, but you'd like to render them all the same way (e.g. `[static_data global="store_hours" view="hours"]`).

If you also make use of the [WP REST API](http://v2.wp-api.org) plugin, WP Static Data provides API endpoints for your static data as well. For instance, to access the hours JSON data, your endpoint would look like `http://example.com/wp-json/data/v1/hours`

## Examples

> static/hours.json

```json
[
  {
    "day": "Monday",
    "opens": "10 am",
    "closes": "5 pm"
  },
  {
    "day": "Tuesday",
    "opens": "8 am",
    "closes": "4 pm"
  }
]
```

> static/views/hours.html

```html
<table>
  {{#hours}}
    <tr>
      <td class="hours-day">{{day}}</td>
      <td class="hours-time">
        {{#opens}}
          <time>{{opens}}</time>—<time>{{closes}}</time>
        {{/opens}}
        {{^opens}}
          Closed
        {{/opens}}
      </td>
    </tr>
  {{/hours}}
</table>
```

## Dependencies

Mustache/Mustache ~2.5 (included)
