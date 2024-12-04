# KTranslate

`KTranslate` — это Laravel-трейт для автоматического перевода полей моделей через морфную связь. Пакет поддерживает автоматическое создание необходимых файлов и миграций.

---

## Установка

### 1. Установка через Composer
Добавьте пакет в ваш проект Laravel:

```bash
composer require vendor/ktranslate
```

### 2. Запуск миграций
После установки выполните команду:

```bash
php artisan migrate
```

Миграция создаст таблицу `localisations`, необходимую для хранения данных о переводах.

---

## Использование

### 1. Добавление трейта в модель
Добавьте трейт `KTranslateTrait` в модель, где вы хотите использовать перевод полей:

```php
use Vendor\KTranslate\KTranslateTrait;

class Post extends Model
{
    use KTranslateTrait;

    protected $fillable = ['title', 'content'];
}
```

### 2. Работа с переводами
Теперь вы можете использовать метод `KTranslate` для получения перевода полей:

```php
$post = Post::find(1);

// Получение перевода поля "title" для текущей локали
echo $post->KTranslate('title');
```

### 3. Автоматическое создание модели `Localization`
Если модель `Localization` отсутствует, она будет автоматически создана в папке `app/Models` с базовой реализацией:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Localization extends Model
{
    protected $fillable = ['localizable_type', 'localizable_id', 'language', 'field', 'value'];

    public function localizable(): MorphTo
    {
        return $this->morphTo();
    }
}
```

---

## Конфигурация

Если вы хотите настроить использование другой модели для локализаций, создайте файл конфигурации `ktranslate.php`:

```php
php artisan vendor:publish --tag=config
```

В конфигурационном файле вы можете переопределить класс модели:

```php
<?php

return [
    'localization_model' => \App\Models\CustomLocalization::class,
];
```

---

## Таблица `localisations`

Миграция создаёт таблицу `localisations` со следующей структурой:

| Поле               | Тип          | Описание                          |
|--------------------|--------------|-----------------------------------|
| `id`               | `bigint`     | Уникальный идентификатор записи. |
| `localizable_type` | `string`     | Тип модели, связанной с переводом. |
| `localizable_id`   | `bigint`     | Идентификатор записи модели.     |
| `language`         | `string`     | Код языка (например, `en`, `ru`). |
| `field`            | `string`     | Название переводимого поля.      |
| `value`            | `text`       | Значение перевода.               |
| `created_at`       | `timestamp`  | Дата создания записи.            |
| `updated_at`       | `timestamp`  | Дата последнего обновления.      |

---

## Пример использования

### Добавление локализации
Вы можете добавлять переводы для моделей через морфную связь:

```php
$post = Post::find(1);

$post->localization()->create([
    'language' => 'ru',
    'field' => 'title',
    'value' => 'Заголовок',
]);

$post->localization()->create([
    'language' => 'en',
    'field' => 'title',
    'value' => 'Title',
]);
```

### Получение перевода
Для текущей локали перевод будет получен автоматически:

```php
App::setLocale('ru');
echo $post->KTranslate('title'); // "Заголовок"

App::setLocale('en');
echo $post->KTranslate('title'); // "Title"
```

Если перевод отсутствует, возвращается значение оригинального поля.

---

## Лицензия

Этот пакет распространяется под лицензией MIT.
```

---

### Что добавлено в новый `README.md`:
1. **Автоматическое создание модели `Localization`:** Описание процесса, как она генерируется при необходимости.
2. **Конфигурация:** Как заменить модель `Localization`, если это требуется.
3. **Пример использования:** Пошаговые примеры добавления и получения переводов.
4. **Таблица `localisations`:** Структура базы данных и описание полей. 

Теперь README предоставляет полный обзор и документацию по пакету.
