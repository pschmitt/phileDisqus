# PhileDisqus

Phile plugin for embedding Disqus in your website

## Installation

Clone this repo to `plugins/pschmitt/disqus`:

```bash
mkdir -p ~http/plugins/pschmitt
git clone https://github.com/pschmitt/phileDisqus.git /srv/http/plugins/pschmitt/disqus
# You may consider using a submodule for this
git submodule add http://github.com/pschmitt/phileDisqus.git /srv/http/plugins/pschmitt/disqus
```

## Usage

Add your Disqus ID to `config.php`:

```php
$config['disqus_id'] = 'MYID';
```

and activate the plugin:

```php
$config['plugins'] = array(
    // [...]
    'pschmitt\\disqus' => array('active' => true),
);
```

## Settings

By default, the comments section isn't displayed right ahead, but a link to launch it on demand. (**Requires jquery**). To change this you can adjust your the plugin's settings:

```php
$config['disqus_load_on_click'] = false;
```

Now in your theme add `{{ disqus_comments }}` wherever you want to display the comments section.
