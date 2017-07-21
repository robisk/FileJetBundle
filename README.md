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

# Usage

See example directory.

### Image mutations

Use

```{{ file_url(file, '<MUTATION>') }}``` in view, where *file* is an implementation of *Everlution\FileJetBundle\Entity\File* and second argument is the file mutation.

#### Mutation examples
**Resize:** sz_1000x1000_100_100 => size = 1000x1000, offset = 100x100

**Crop:** c_1000x1000_100_100

**Relative crop:** c_0.4x0.89_0.1_0.1 => same as crop, but size and offset is in %.

**Fill the image:** fill_200x200 => you can have smaller image and want to fill it to certain size

**Position:** pos_center => used with **fill** - position of smaller image within larger filled image (center + cardinal directions eg. southwest etc)

**Background:** bg_white => used with **fill** - background color of fill (transparent to be implemented)

**Rotate:** rot_90 => rotate 90, 180, 270 degrees

Basically, string before "\_" represents operation and string after "_" represents arguments. Arguments are like ImageMagick's [geometry](http://www.imagemagick.org/script/command-line-processing.php#geometry).

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

#### Construct advanced crop mutation with variable aspec ratio
Allows to crop image with a custom aspec ratio. Result will be padded with an empty space to match a required aspect ratio (2.5).

```ts

// Create cropper: https://fengyuanchen.github.io/cropperjs/
const cropper = new Cropper(element, {
    checkCrossOrigin: false,
    zoomable: false,
    responsive: true,
    viewMode: 2
  });
  
// Construct mutation
const mutation = crop(2.5);
   
function crop(requiredRatio: number): string {
  const cropData = cropper.getData(true);
  const canvasData = cropper.getCanvasData();

  const mutations = [
    toCropMutation(cropData, canvasData),
    ...toFillMutation(cropData, requiredRatio)
  ];

  return mutations.join(',');
}

function toCropMutation(cropData, canvasData): string {
  const width = (cropData.width / canvasData.naturalWidth).toFixed(4);
  const height = (cropData.height / canvasData.naturalHeight).toFixed(4);
  const x = (cropData.x / canvasData.naturalWidth).toFixed(4);
  const y = (cropData.y / canvasData.naturalHeight).toFixed(4);

  return 'c_' + width + 'x' + height + '_' + x + '_' + y;
}

function *toFillMutation(cropData, requiredRatio: number): IterableIterator<string> {
  const currentRatio = cropData.width / cropData.height;

  if (requiredRatio !== currentRatio) {
    yield 'pos_center';
  }

  if (requiredRatio > currentRatio) {
    // Adds left and right padding
    yield 'fill_' + (cropData.height * requiredRatio / cropData.width).toFixed(4) + 'x1.0';
  } else if (requiredRatio < currentRatio) {
    // Adds top and bottom padding
    yield 'fill_1.0x' + (cropData.width / requiredRatio / cropData.height).toFixed(4);
  }
}
```
