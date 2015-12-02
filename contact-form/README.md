# Contact Form Plugin for PHPSF2

## Installation
1. Copy the `contact-form` directory to `app/plugins`.
2. Add Gump to your projects' composer dependencies: `composer require wixel/gump`.
3. Add theme support for Contact Form in your `theme.json` file. (Add `contact-form`).
4. Set options in `app/plugins/contact-form/config.php`.

### Template
The plugin will generate a response, this can be accessed _only_ from the page you set as your form handler in the `config.php`. To access the response, copy `{{ contact_response|raw }}` in to your template.
