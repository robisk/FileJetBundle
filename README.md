# FileJet Bundle

# Installation

```sh
composer require everlutionsk/file-jet-bundle
```

### Enable the bundle

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Everlution\FileJetBundle\EverlutionFileJetBundle()
    );
}
```

### Configure the bundle

Following configuration snippet describes how to configure the bundle.<br>

```yml
# app/config/config.yml
everlution_file_jet:
  storages:
    - id: <STORAGE_ID>
      api_key: <API_KEY>
      name: <CUSTOM_LOCAL_NAME>
```

# Usage in PHP

See example directory.

### Image mutations

Use

```{{ file_url(file, '<MUTATION>') }}``` in view, where *file* is an implementation of *Everlution\FileJetBundle\Entity\File* and second argument is the file mutation.

#### Mutation examples
**Resize:** sz_1000x1000_100_100 => size = 1000x1000, offset = 100x100

**Crop:** c_1000x1000_100_100

**Relative crop:** c_0.4x0.89_0.1_0.1 => same as crop, but size and offset is in %.

Mutations can be chained like "sz_1000x1000,c_100x100".

# How to
#### Construct crop mutation

```js
// Create cropper: https://fengyuanchen.github.io/cropperjs/
var cropper = new Cropper(element, {
      checkCrossOrigin: false,
      zoomable: false,
      aspectRatio: 1,
      responsive: true,
      viewMode: 2
    });

// Construct mutation
var cropData = cropper.getData(true);
var canvasData = cropper.getCanvasData();

var width = (cropData.width / canvasData.naturalWidth).toFixed(4);
var height = (cropData.height / canvasData.naturalHeight).toFixed(4);
var x = (cropData.x / canvasData.naturalWidth).toFixed(4);
var y = (cropData.y / canvasData.naturalHeight).toFixed(4);

var mutation = 'c_' + width + 'x' + height + '_' + x + '_' + y;
```
