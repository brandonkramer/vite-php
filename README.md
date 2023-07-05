<div align="center">
  <a href="https://vitejs.dev/">
    <img width="200" height="200" hspace="10" src="https://vitejs.dev/logo.svg" alt="vite logo" />
  </a>
  <h1>Vite PHP</h1>
  <p>
A library of back-end/PHP utilities for WordPress development with ViteJS.

You can read more about ViteJS on [vitejs.dev](https://vitejs.dev)
</p>
</div>

## Usages

Install dependency into your project.
```
composer require wp-strap/vite
```

This exposes some classes which are responsible for generating asset URLs from Vite's manifest.json that you can register or enqueue. It's also responsible for enabling HMR when the ViteJS dev server is running.

This package is made for:
- https://github.com/wp-strap/wp-vite-starter 
- https://github.com/wp-strap/vite

But can be used with any ViteJS setups.

The classes follow PSR practices with interfaces, so it can be included trough OOP with dependency injection and IoC containers. It also provides a Facade class that allows you to use static methods instead to call the methods everywhere you like.



Example with using the facade:
```php
use WPStrap\Vite\Assets;

// Resolves instance and registers project configurations
Assets::register([
    'dir' => plugin_dir_path(__FILE__), // or get_stylesheet_directory() for themes
    'url' => plugins_url(\basename(__DIR__)) // or get_stylesheet_directory_uri() for themes
    'version' => '1.0.2', // Set a global version (optional)
    'deps' => [ 'scripts' => [], 'styles' => [] ]  // Set global dependencies (optional)
]);

// Listens to ViteJS dev server and makes adjustment to make HMR work
Assets::devServer()->start();

// returns: https://your-site.com/wp-content/plugins/your-plugin/build/js/main.oi4h32d.js
Assets::get('js/main.js') 

// Alternatively you can use these as well which will be more targeted to specific folders
// and for some of the methods you don't need to write the file extension
Assets::js('main') 
Assets::css('main') 
Assets::image('bird-on-black.jpg') 
Assets::svg('instagram') 
Assets::font('SourceSerif4Variable-Italic.ttf.woff2')

// Example of enqueuing the scripts
add_action('wp_enqueue_scripts', function () {

    // You can enqueue & register the tradtional way using global data
    wp_enqueue_script('my-handle', Assets::js('main'), Assets::deps('scripts'), Assets::version());
    wp_enqueue_style('my-handle', Assets::css('main'), Assets::deps('styles'), Assets::version());
    
    // Or use a more simple method that includes the global deps & version
    Assets::enqueueStyle('my-handle', 'main');
    
    // Which also comes with some handy chained methods
    Assets::enqueueScript('my-handle', 'main', ['another-dep'])
        ->useAsync()
        ->useAttribute('key', 'value')
        ->localize('object_name', ['data' => 'data'])
        ->appendInline('<script>console.log("hello");</script>');
});
```

Example with using instances
```php
use WPStrap\Vite\Assets;
use WPStrap\Vite\AssetsService;
use WPStrap\Vite\DevServer;

// Instantiates the Asset service and registers project configurations
$assets = new AssetsService();
$assets->register([
    'dir' => plugin_dir_path(__FILE__), // or get_stylesheet_directory() for themes
    'url' => plugins_url(\basename(__DIR__)) // or get_stylesheet_directory_uri() for themes
]);

// Listens to ViteJS dev server and makes adjustment to make HMR work
(new DevServer($assets))->start();

$assets->get('js/main.js'); 
$assets->js('main') 
$assets->css('main') 
$assets->image('bird-on-black.jpg') 
$assets->svg('instagram') 
$assets->font('SourceSerif4Variable-Italic.ttf.woff2')

// Traditional 
wp_enqueue_script('my-handle', $this->assets->js('main'), $this->assets->deps('scripts'), $this->assets->version());
wp_enqueue_style('my-handle', $this->assets->css('main'), $this->assets->deps('styles'), $this->assets->version());

// Custom methods
$this->assets->enqueueStyle('my-handle', 'main');
$this->assets->enqueueScript('my-handle', 'main', ['another-dep'])
    ->useAsync()
    ->useAttribute('key', 'value')
    ->localize('object_name', ['data' => 'data'])
    ->appendInline('<script>console.log("hello");</script>');

// You can also use the facade based on this instance.
Assets::setFacade($assets);
Assets::get('css/main.css');
```

Example with using instances wih functions
```php
use WPStrap\Vite\AssetsInterface;
use WPStrap\Vite\AssetsService;
use WPStrap\Vite\DevServer;

function assets(): AssetsInterface {
     static $assets;
     
     if(!isset($assets)) {
        $assets = (new AssetsService())->register([
            'dir' => plugin_dir_path(__FILE__), 
            'url' => plugins_url(\basename(__DIR__)),
            'version' => '1.0.0'
        ]);
     }
     
     return $assets;
}

(new DevServer(assets()))->start();


add_action('wp_enqueue_scripts', function () {

    // Traditional
    wp_enqueue_script('my-handle', assets()->js('main'), assets()->deps('scripts'), assets()->version());
    wp_enqueue_style('my-handle', assets()->css('main'), assets()->deps('styles'), assets()->version());
    
    // Using custom methods
    assets()->enqueueStyle('my-handle', 'main');
    assets()->enqueueScript('my-handle', ['Main', 'main'], ['another-dep'])
        ->useAsync()
        ->useAttribute('key', 'value')
        ->localize('object_name', ['data' => 'data'])
        ->appendInline('<script>console.log("hello");</script>');
});
```

Example with using the League Container
```php
use League\Container\Container;
use WPStrap\Vite\Assets;
use WPStrap\Vite\AssetsInterface;
use WPStrap\Vite\AssetsService;
use WPStrap\Vite\DevServer;
use WPStrap\Vite\DevServerInterface;

$container = new Container();
$container->add(AssetsInterface::class)->setConcrete(AssetsService::class)->addMethodCall('register', [
    'dir' => plugin_dir_path(__FILE__), 
    'url' => plugins_url(\basename(__DIR__)) 
]);
$container->add(DevServerInterface::class)->setConcrete(DevServer::class)->addArgument(AssetsInterface::class);

$assets = $container->get(AssetsInterface::class);
$devServer = $container->get(DevServerInterface::class);

$devServer->start();

$assets->get('main/main.css');

// You can also set a PSR container as a facade accessor
Assets::setFacadeAccessor($container);
Assets::get('main/main.css')
```

### DevServer

`Assets::devServer()->start(3000');` OR `(new DevServer($assets))->start('3000');`

The dev server class is responsible for listening to the ViteJS dev server using CURL, checking if it's running locally on port 3000 which you can adjust using the optional param from the start() method as seen above.

If it can validate the dev server is running, it will inject viteJS scripts provided from the dev server, filter all asset urls and load source files instead (from the assets::get(), assets:css(), assets::js() etc. methods),
and alter the script tags to make sure the source files can be loaded as modules for HMR.

**This should only be run on local/dev environments.** As it's using CURL on each request, so you don't want to run this on production.

### Project Example

You can find more info and a project example here: https://github.com/wp-strap/wp-vite-starter