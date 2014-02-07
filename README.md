# PhileDisqus

Phile plugin for embedding Disqus in your website

## Usage

Add your Disqus ID to `config.php`:

```php
$config['disqus_id'] = 'MYID'; 
```

and activate the plugin:

```php
$config['plugins'] = array(
    // [...]
    'phileDisqus' => array('active' => true),
);
```

Now in your theme add `{{ disqus_comments }}` wherever you want to display the comments section.
