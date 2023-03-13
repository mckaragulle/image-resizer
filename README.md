# Image Resizer - Fotoğraf Boyutlandırma

With this package, you can compress and resize your photos in the storage areas to the desired dimensions and extension via the image manipulation package.

Bu paket ile image intervention paketi üzerinden storage klasöründe bulunan fotoğraflarınızı istediğiniz boyutta ve uzanttı da sıkıştırıp yeniden boyutlandırabilirsiniz.

## Requirements - Gereklilikler

- PHP >= 8.1.15
- Fileinfo Extension

## Supported Image Libraries - Desteklenen imaj kütüphaneleri

- GD Library (>=2.0)
- Imagick PHP extension (>=6.5.7)

## Getting Started - Kurulum

- Terminal de aşağıdaki kodu çalıştırabilirsiniz
```php
> composer require karagulle/image-resizer
```

- Yada composer.json dosyasınıza aşağıdaki satırı ekleyin.
```json
"karagulle/image-resizer": "^0.1.13"
```

- Ardından bağımlıkları yükleyin.
```php
> composer install
```

- config/app.php dosyasını açıp aşağıdaki satırı providers içerisine ekleyin.
```php
 Karagulle\ImageResizer\ImageResizerProvider::class,
```

- config/app.php dosyasını açıp aşağıdaki satırı alias içerisine facade olarak ekleyin.
```php
'ImageResizer' => Karagulle\ImageResizer\Facades\ImageResizerFacade::class
```

- Paket kurulumu tamamlandı. Paketin Storage dizinine erişebilmesi için symbolik link oluşturmalısınız. Daha önce bu komutu çalıştırmışsanız bu adımı atlayabilirsiniz.
```php
php artisan symbolik:link
```

## Code Examples - Kod Örneği

1. Usage Example - Örnek:
```php
<img src="{{ ImageResizer::open('mountain.jpeg')}}" width="200" height="200" alt="">
```

2. Another Usage Example - Farklı Örnek:
```php
<img src="{{ ImageResizer::open('mountain.jpeg', 300, 400)}}" width="200" height="200" alt="">
```

3. Another Usage Example - Farklı Örnek:
```php
<img src="{{ ImageResizer::open('mountain.jpeg', 300, 400, 'webp')}}" width="200" height="200" alt="">
```